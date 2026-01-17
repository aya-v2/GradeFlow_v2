<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Entity\User;
use App\Repository\GradeRepository;
use App\Repository\SchoolYearRepository;
use App\Entity\TeachingAssignment;
use App\Repository\TeachingAssignmentRepository;
use App\Service\GradeCalculationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_TEACHER')]
class TeacherController extends AbstractController
{
    #[Route('/teacher/dashboard', name: 'app_teacher_dashboard')]
    public function index(TeachingAssignmentRepository $repo, SchoolYearRepository $syRepo): Response
    {
        // 1. Get the logged in teacher
        $teacher = $this->getUser();

        // 2. Find their classes & modules
        // Separate current and archive assignments
        $allAssignments = $repo->findBy(['teacher' => $teacher]);

        $currentAssignments = [];
        $archiveYears = [];
        $archiveAssignments = [];

        foreach ($allAssignments as $a) {
            $sy = $a->getAssociatedClass()->getSchoolYear();
            if ($sy && $sy->isCurrent()) {
                $currentAssignments[] = $a;
            } else {
                $syName = $sy ? $sy->getName() : 'Unknown';
                if (!isset($archiveAssignments[$syName])) {
                    $archiveAssignments[$syName] = [];
                    $archiveYears[] = $syName;
                }
                $archiveAssignments[$syName][] = $a;
            }
        }
        sort($archiveYears);

        return $this->render('teacher/index.html.twig', [
            'currentAssignments' => $currentAssignments,
            'archiveYears' => $archiveYears,
            'archiveAssignments' => $archiveAssignments,
        ]);
    }

    #[Route('/teacher/grade/{id}', name: 'app_teacher_grade')]
    public function grade(
        TeachingAssignment $assignment, 
        Request $request, 
        EntityManagerInterface $em,
        GradeRepository $gradeRepo,
        GradeCalculationService $gradCalc
    ): Response
    {
        // 1. Get the class and module from the assignment
        $classe = $assignment->getAssociatedClass();
        $module = $assignment->getModule();
        $schoolYear = $classe->getSchoolYear();
        $isCurrentYear = $schoolYear && $schoolYear->isCurrent();
        
        // 2. Get the students (THIS IS THE FIX)
        // Instead of the complex $em->getRepository... we just ask the class for its students.
        $students = $classe->getStudents(); 

        // 3. Fetch existing grades for this Module
        $existingGrades = $gradeRepo->findBy(['module' => $module]);
        $gradesByStudent = [];
        foreach ($existingGrades as $g) {
            $gradesByStudent[$g->getStudent()->getId()] = $g;
        }

        // 4. Handle Save (POST request) - support AJAX
        if ($request->isMethod('POST')) {
            // Only allow save if current year
            if (!$isCurrentYear) {
                return $this->json(['status' => 'error', 'message' => 'Cannot edit archived year.'], 403);
            }

            $ccGrades = $request->request->all('cc');
            $examGrades = $request->request->all('exam');

            foreach ($students as $student) {
                $sId = $student->getId();

                // Get existing grade OR create new one
                $grade = $gradesByStudent[$sId] ?? new Grade();

                if (!$grade->getId()) {
                    $grade->setStudent($student);
                    $grade->setModule($module);
                    $grade->setIsPublished(Grade::STATE_DRAFT);
                }

                // Update scores (do NOT change is_published on Save)
                if (isset($ccGrades[$sId]) && $ccGrades[$sId] !== '') {
                    $grade->setCcGrade((float)$ccGrades[$sId]);
                }
                if (isset($examGrades[$sId]) && $examGrades[$sId] !== '') {
                    $grade->setExamGrade((float)$examGrades[$sId]);
                }

                $em->persist($grade);
            }

            $em->flush();

            // If Ajax, return JSON with minimal info
            if ($request->isXmlHttpRequest()) {
                // Build simple response: per-student final if exam exists
                $resp = [];
                foreach ($students as $student) {
                    $sId = $student->getId();
                    $g = $gradesByStudent[$sId] ?? $gradeRepo->findOneBy(['student' => $student, 'module' => $module]);
                    $exam = $g ? $g->getExamGrade() : null;
                    $cc = $g ? $g->getCcGrade() : null;
                    $final = $gradCalc->calculateFinal($cc, $exam);
                    if ($final !== null) {
                        $final = round($final, 2);
                    }
                    $resp[$sId] = ['final' => $final];
                }

                return new JsonResponse(['status' => 'ok', 'data' => $resp]);
            }

            $this->addFlash('success', 'Notes enregistrées avec succès !');

            return $this->redirectToRoute('app_teacher_grade', ['id' => $assignment->getId()]);
        }

        // compute helper flags for template
        $allCcFilled = true;
        $allExamFilled = true;
        $allGradesAtLeastCcApproved = true;
        foreach ($students as $student) {
            $sId = $student->getId();
            $g = $gradesByStudent[$sId] ?? null;
            if (!$g || $g->getCcGrade() === null) {
                $allCcFilled = false;
            }
            if (!$g || $g->getExamGrade() === null) {
                $allExamFilled = false;
            }
            if (!$g || $g->getIsPublished() === null || $g->getIsPublished() < \App\Entity\Grade::STATE_CC_APPROVED) {
                $allGradesAtLeastCcApproved = false;
            }
        }

        return $this->render('teacher/grading.html.twig', [
            'assignment' => $assignment,
            'students' => $students,
            'grades' => $gradesByStudent,
            'allCcFilled' => $allCcFilled,
            'allExamFilled' => $allExamFilled,
            'allGradesAtLeastCcApproved' => $allGradesAtLeastCcApproved,
            'isCurrentYear' => $isCurrentYear,
        ]);
    }

    #[Route('/teacher/grade/{id}/approve-cc', name: 'app_teacher_approve_cc', methods: ['POST'])]
    public function approveCc(
        TeachingAssignment $assignment,
        Request $request,
        EntityManagerInterface $em,
        GradeRepository $gradeRepo
    ): Response
    {
        $teacher = $this->getUser();
        if ($assignment->getTeacher()->getId() !== ($teacher instanceof User ? $teacher->getId() : null)) {
            throw $this->createAccessDeniedException();
        }

        // Check if current year
        $schoolYear = $assignment->getAssociatedClass()->getSchoolYear();
        if (!$schoolYear || !$schoolYear->isCurrent()) {
            return $this->json(['status' => 'error', 'message' => 'Cannot approve archived year.'], 403);
        }

        $classe = $assignment->getAssociatedClass();
        $module = $assignment->getModule();
        $students = $classe->getStudents();

        $existing = $gradeRepo->findBy(['module' => $module]);
        $gradesByStudent = [];
        foreach ($existing as $g) {
            $gradesByStudent[$g->getStudent()->getId()] = $g;
        }

        // validate all CC present
        foreach ($students as $student) {
            $sId = $student->getId();
            $g = $gradesByStudent[$sId] ?? null;
            if (!$g || $g->getCcGrade() === null) {
                return $this->json(['status' => 'error', 'message' => 'Not all CC grades are filled.'], 400);
            }
        }

        // set state to CC_APPROVED
        foreach ($students as $student) {
            $sId = $student->getId();
            $g = $gradesByStudent[$sId] ?? null;
            if ($g) {
                $g->setIsPublished(\App\Entity\Grade::STATE_CC_APPROVED);
                $em->persist($g);
            }
        }
        $em->flush();

        return $this->json(['status' => 'ok']);
    }

    #[Route('/teacher/classes', name: 'app_teacher_classes')]
    public function classes(TeachingAssignmentRepository $repo, SchoolYearRepository $syRepo): Response
    {
        $teacher = $this->getUser();
        
        // Get current year assignments
        $currentAssignments = [];
        $allAssignments = $repo->findBy(['teacher' => $teacher]);
        
        foreach ($allAssignments as $a) {
            $sy = $a->getAssociatedClass()->getSchoolYear();
            if ($sy && $sy->isCurrent()) {
                $currentAssignments[] = $a;
            }
        }

        return $this->render('teacher/classes.html.twig', [
            'assignments' => $currentAssignments,
        ]);
    }

    #[Route('/teacher/classes/{id}/students', name: 'app_teacher_students')]
    public function studentsInClass(int $id, EntityManagerInterface $em): Response
    {
        $teacher = $this->getUser();
        
        // Fetch the class and verify teacher access
        $classe = $em->getRepository(\App\Entity\Classe::class)->find($id);
        if (!$classe) {
            throw $this->createNotFoundException('Classe not found');
        }

        // Verify this teacher teaches this class
        $assignments = $em->getRepository(\App\Entity\TeachingAssignment::class)
            ->findBy(['teacher' => $teacher, 'associatedClass' => $classe]);
        
        if (empty($assignments)) {
            throw $this->createAccessDeniedException('You do not teach this class');
        }

        $students = $classe->getStudents();

        return $this->render('teacher/students.html.twig', [
            'classe' => $classe,
            'students' => $students,
        ]);
    }

    #[Route('/teacher/grading', name: 'app_teacher_grading')]
    public function grading(TeachingAssignmentRepository $repo): Response
    {
        $teacher = $this->getUser();
        
        // Get current year assignments only
        $currentAssignments = [];
        $allAssignments = $repo->findBy(['teacher' => $teacher]);
        
        foreach ($allAssignments as $a) {
            $sy = $a->getAssociatedClass()->getSchoolYear();
            if ($sy && $sy->isCurrent()) {
                $currentAssignments[] = $a;
            }
        }

        return $this->render('teacher/grading_select.html.twig', [
            'assignments' => $currentAssignments,
        ]);
    }

    #[Route('/teacher/profile', name: 'app_teacher_profile')]
    public function profile(): Response
    {
        return $this->redirectToRoute('app_profile');
    }

    #[Route('/teacher/grade/{id}/approve-final', name: 'app_teacher_approve_final', methods: ['POST'])]
    public function approveFinal(
        TeachingAssignment $assignment,
        Request $request,
        EntityManagerInterface $em,
        GradeRepository $gradeRepo
    ): Response
    {
        $teacher = $this->getUser();
        if ($assignment->getTeacher()->getId() !== ($teacher instanceof User ? $teacher->getId() : null)) {
            throw $this->createAccessDeniedException();
        }

        // Check if current year
        $schoolYear = $assignment->getAssociatedClass()->getSchoolYear();
        if (!$schoolYear || !$schoolYear->isCurrent()) {
            return $this->json(['status' => 'error', 'message' => 'Cannot approve archived year.'], 403);
        }

        $classe = $assignment->getAssociatedClass();
        $module = $assignment->getModule();
        $students = $classe->getStudents();

        $existing = $gradeRepo->findBy(['module' => $module]);
        $gradesByStudent = [];
        foreach ($existing as $g) {
            $gradesByStudent[$g->getStudent()->getId()] = $g;
        }

        // validate preconditions: CC approved and exam filled
        foreach ($students as $student) {
            $sId = $student->getId();
            $g = $gradesByStudent[$sId] ?? null;
            if (!$g || $g->getExamGrade() === null) {
                return $this->json(['status' => 'error', 'message' => 'Not all exam grades are filled.'], 400);
            }
            if ($g->getIsPublished() < \App\Entity\Grade::STATE_CC_APPROVED) {
                return $this->json(['status' => 'error', 'message' => 'CC must be approved first.'], 400);
            }
        }

        // set state to FINALIZED
        foreach ($students as $student) {
            $sId = $student->getId();
            $g = $gradesByStudent[$sId] ?? null;
            if ($g) {
                $g->setIsPublished(\App\Entity\Grade::STATE_FINALIZED);
                $em->persist($g);
            }
        }
        $em->flush();

        return $this->json(['status' => 'ok']);
    }
}
