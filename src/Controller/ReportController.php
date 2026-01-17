<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\TeachingAssignment;
use App\Entity\SchoolYear;
use App\Repository\GradeRepository;
use App\Repository\SchoolYearRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/report')]
class ReportController extends AbstractController
{
    #[Route('/bulletin/{studentId}/{yearId}', name: 'app_report_bulletin')]
    #[IsGranted('ROLE_USER')] // Students can see their own, Admin can see all
    public function bulletin(
        int $studentId,
        UserRepository $userRepo,
        SchoolYearRepository $syRepo,
        GradeRepository $gradeRepo,
        ?int $yearId = null
    ): Response
    {
        $student = $userRepo->find($studentId);
        if (!$student) {
            throw $this->createNotFoundException('Student not found');
        }

        // Security check: Students see only their own, Teachers/Admins see others
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_TEACHER')) {
            if ($this->getUser()->getId() !== $student->getId()) {
                throw $this->createAccessDeniedException();
            }
        }

        $year = $yearId ? $syRepo->find($yearId) : $syRepo->findOneBy(['isCurrent' => true]);
        
        // Fetch grades for this student and year
        // We assume grades are linked to modules, and modules are semi-global or linked to year via class?
        // Actually Grade -> Module. Module -> Filiere. 
        // How do we scope by year? A User belongs to a Class which belongs to a SchoolYear.
        // So we should verify if the student was in that class during that year.
        // For simplicity: We fetch grades where grade.student = student. 
        // And we might need to filter by year if grades are historic.
        // But Grade doesn't have a date.
        // Assumption: Grades in the system are for the student's *current* enrollment context or we filter by checking simple logic.
        // Or we rely on `student.classe` being the current one.
        
        // If we want a bulletin for "2025-2026", we assume the student's grades are relevant to that year.
        // Let's just fetch all grades for the student for now, and maybe group them?
        // But the prompt implies "Current" bulletin.
        
        $grades = $gradeRepo->findBy(['student' => $student]);
        
        // Calculate average?
        // Group by semester? Module->getSemester().
        
        $modulesBySemester = [];
        $averages = [];
        
        foreach ($grades as $grade) {
            if ($grade->isFinalized()) {
                $sem = $grade->getModule()->getSemester();
                $modulesBySemester[$sem][] = $grade;
            }
        }
        
        ksort($modulesBySemester);

        return $this->render('report/bulletin.html.twig', [
            'student' => $student,
            'year' => $year,
            'modulesBySemester' => $modulesBySemester,
        ]);
    }

    #[Route('/class-grades/{assignmentId}', name: 'app_report_class_grades')]
    #[IsGranted('ROLE_TEACHER')]
    public function classGrades(
        int $assignmentId,
        TeachingAssignmentRepository $assignmentRepo,
        GradeRepository $gradeRepo
    ): Response
    {
        $assignment = $assignmentRepo->find($assignmentId);
        if (!$assignment) {
            throw $this->createNotFoundException('Assignment not found');
        }

        // Security: only assigned teacher or admin
        if (!$this->isGranted('ROLE_ADMIN') && $assignment->getTeacher()->getId() !== $this->getUser()->getId()) {
            throw $this->createAccessDeniedException();
        }

        $classe = $assignment->getAssociatedClass();
        $module = $assignment->getModule();
        
        // Get all students in the class
        $students = $classe->getStudents();
        
        // Get grades for this module and these students
        $grades = [];
        foreach ($students as $student) {
            $grade = $gradeRepo->findOneBy(['student' => $student, 'module' => $module]);
            if ($grade) {
                $grades[$student->getId()] = $grade;
            }
        }

        return $this->render('report/class_grades.html.twig', [
            'assignment' => $assignment,
            'students' => $students,
            'grades' => $grades,
        ]);
    }
}
