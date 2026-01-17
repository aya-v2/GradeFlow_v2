<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Entity\Module;
use App\Entity\User;
use App\Repository\GradeRepository;
use App\Repository\TeachingAssignmentRepository;
use App\Service\GradeCalculationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_STUDENT')]
class StudentController extends AbstractController
{
    #[Route('/student/dashboard', name: 'app_student_dashboard')]
    public function index(GradeRepository $gradeRepo, TeachingAssignmentRepository $taRepo, GradeCalculationService $gradCalc): Response
    {
        $user = $this->getUser();

        // If $user is not the User entity, avoid querying with it and treat as no class assigned
        if ($user instanceof User) {
            // 1. Fetch only THIS student's grades
            $grades = $gradeRepo->findBy(['student' => $user]);
            $classeName = $user->getClasse() ? $user->getClasse()->getName() : 'Non assigné';
        } else {
            $grades = [];
            $classeName = 'Non assigné';
        }

        // 2. Calculate Averages (Optional but impressive)
        $reportCard = [];
        $totalScore = 0;
        $moduleCount = 0;

        foreach ($grades as $grade) {
            $cc = $grade->getCcGrade();
            $exam = $grade->getExamGrade();

            // Final grade is only visible when finalized
            $final = null;
            if ($grade->getIsPublished() === Grade::STATE_FINALIZED) {
                $final = $gradCalc->calculateFinal($cc, $exam);
            }

            $status = $gradCalc->determineResult($final);

            $reportCard[] = [
                'module' => $grade->getModule()->getName(),
                'cc' => $cc,
                'exam' => $exam,
                'final' => $final,
                'status' => $status
            ];

            if ($final !== null) {
                $totalScore += $final;
                $moduleCount++;
            }
        }

        $generalAverage = $moduleCount > 0 ? $totalScore / $moduleCount : 0;

        // Recent activity: last 2 grades with type (CC or Exam)
        $recent = $gradeRepo->createQueryBuilder('g')
            ->andWhere('g.student = :student')
            ->andWhere('g.isPublished > 0')
            ->setParameter('student', $user)
            ->orderBy('g.id', 'DESC')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult();

        $recentActivities = [];
        foreach ($recent as $r) {
            // Prioritize Exam grade, fall back to CC if exam is null
            if ($r->getExamGrade() !== null) {
                $type = 'exam';
                $grade = $r->getExamGrade();
            } else {
                $type = 'cc';
                $grade = $r->getCcGrade();
            }
            
            $recentActivities[] = [
                'module' => $r->getModule()->getName(),
                'grade' => $grade,
                'type' => $type,
            ];
        }

        // Current semester modules for the student's class
        $currentAssignments = [];
        if ($user instanceof User && $user->getClasse()) {
            $tas = $taRepo->findBy(['associatedClass' => $user->getClasse()]);
            foreach ($tas as $t) {
                $currentAssignments[] = [
                    'module' => $t->getModule()->getName(),
                    'teacher' => $t->getTeacher()->getFirstName() . ' ' . $t->getTeacher()->getLastName(),
                ];
            }
        }

        return $this->render('student/index.html.twig', [
            'reportCard' => $reportCard,
            'generalAverage' => $generalAverage,
            'classe' => $classeName,
            'recentActivities' => $recentActivities,
            'currentAssignments' => $currentAssignments,
        ]);
    }

    #[Route('/student/grades', name: 'app_student_grades')]
    public function gradesPage(): Response
    {
        $user = $this->getUser();
        $years = [];
        if ($user instanceof User) {
            foreach ($user->getEnrollments() as $enr) {
                $class = $enr->getAssociatedClass();
                $sy = $class && $class->getSchoolYear() ? $class->getSchoolYear()->getName() : null;
                if ($sy && !in_array($sy, $years)) $years[] = $sy;
            }
        }

        // default to latest (last) if present
        $defaultYear = empty($years) ? null : end($years);

        return $this->render('student/grades.html.twig', [
            'years' => $years,
            'defaultYear' => $defaultYear,
        ]);
    }

    #[Route('/student/grades/data', name: 'app_student_grades_data')]
    public function gradesData(GradeRepository $gradeRepo, TeachingAssignmentRepository $taRepo, GradeCalculationService $gradCalc, \App\Repository\ModuleRepository $moduleRepository): Response
    {
        $user = $this->getUser();
        $year = (string) ($_GET['year'] ?? '');

        // Determine class for this year via enrollments
        $classe = null;
        if ($user instanceof User) {
            foreach ($user->getEnrollments() as $enr) {
                $class = $enr->getAssociatedClass();
                $sy = $class && $class->getSchoolYear() ? $class->getSchoolYear()->getName() : null;
                if ($sy === $year) { $classe = $class; break; }
            }
            // fallback to current classe
            if (!$classe) $classe = $user->getClasse();
        }

        $semesters = ['S1' => [], 'S2' => []];

        if ($classe) {
            // Try teaching assignments first (works for current year)
            $tas = $taRepo->findBy(['associatedClass' => $classe]);
            
            // If no teaching assignments, fall back to querying grades directly (for archived years)
            if (empty($tas)) {
                $filiere = $classe && method_exists($classe, 'getFiliere') ? $classe->getFiliere() : null;
                if ($filiere) {
                    // Determine class level and corresponding semester range
                    $classLevel = $classe && method_exists($classe, 'getLevel') && $classe->getLevel() ? (int)$classe->getLevel() : 1;
                    $semRangeStart = 5 + (($classLevel - 1) * 2);
                    $semRangeEnd = $semRangeStart + 1;
                    
                    // Query ALL modules in this filière matching the semester range (not just those with grades)
                    $sem1 = 'S' . $semRangeStart;
                    $sem2 = 'S' . $semRangeEnd;
                    $allModules = $moduleRepository->createQueryBuilder('m')
                        ->where('m.filiere = :filiere AND (m.semester = :sem1 OR m.semester = :sem2)')
                        ->setParameter('filiere', $filiere)
                        ->setParameter('sem1', $sem1)
                        ->setParameter('sem2', $sem2)
                        ->orderBy('m.semester', 'ASC')
                        ->getQuery()
                        ->getResult();
                    
                    // Query grades for this student in this filière
                    $studentGrades = $gradeRepo->createQueryBuilder('g')
                        ->join('g.module', 'm')
                        ->where('g.student = :student AND m.filiere = :filiere AND (m.semester = :sem1 OR m.semester = :sem2)')
                        ->setParameter('student', $user)
                        ->setParameter('filiere', $filiere)
                        ->setParameter('sem1', $sem1)
                        ->setParameter('sem2', $sem2)
                        ->orderBy('m.semester', 'ASC')
                        ->getQuery()
                        ->getResult();
                    
                    $gradesByModule = [];
                    foreach ($studentGrades as $g) {
                        $gradesByModule[$g->getModule()->getId()] = $g;
                    }
                    
                    // Convert all modules to display format
                    foreach ($allModules as $module) {
                        $modName = $module->getName();
                        $coef = $module->getCoefficient();
                        $semRaw = $module->getSemester() ?? '';
                        $semRaw = is_string($semRaw) ? strtoupper(trim($semRaw)) : $semRaw;

                        // Map module semester to display bucket (S1 / S2)
                        $baseMap = 5 + (($classLevel - 1) * 2);
                        $sem = 'S1';
                        if (is_string($semRaw) && preg_match('/(\d+)/', $semRaw, $numMatch)) {
                            $num = (int)$numMatch[1];
                            if ($num === $baseMap) {
                                $sem = 'S1';
                            } elseif ($num === $baseMap + 1) {
                                $sem = 'S2';
                            }
                        }

                        // Get grade if it exists
                        $grade = $gradesByModule[$module->getId()] ?? null;
                        $cc = $grade ? $grade->getCcGrade() : null;
                        $exam = $grade ? $grade->getExamGrade() : null;
                        $final = null;
                        if ($grade && $grade->getIsPublished() === Grade::STATE_FINALIZED) {
                            $final = $gradCalc->calculateFinal($cc, $exam);
                            if ($final !== null) {
                                $final = round($final, 2);
                            }
                        }
                        $result = $gradCalc->determineResult($final);

                        $semesters[$sem][] = [
                            'module' => $modName,
                            'coefficient' => $coef,
                            'cc' => $cc,
                            'exam' => $exam,
                            'final' => $final,
                            'result' => $result,
                        ];
                    }
                }
            } else {
                // Teaching assignments exist; use them (normal path for current year)
                foreach ($tas as $t) {
                    $module = $t->getModule();
                    $modName = $module->getName();
                    $coef = $module->getCoefficient();
                    $semRaw = $module->getSemester() ?? '';
                    $semRaw = is_string($semRaw) ? strtoupper(trim($semRaw)) : $semRaw;

                    // Map module semester numbers to the two display buckets (S1 / S2)
                    // Class levels map to real semester numbers as follows:
                    //  level 1 -> semesters 5 & 6
                    //  level 2 -> semesters 7 & 8
                    //  level 3 -> semesters 9 & 10
                    $classLevel = $classe && method_exists($classe, 'getLevel') && $classe->getLevel() ? (int)$classe->getLevel() : 1;
                    $baseMap = 5; // default base for level 1
                    if ($classLevel === 2) $baseMap = 7;
                    if ($classLevel === 3) $baseMap = 9;

                    $sem = 'S1';
                    // try to extract numeric portion from semester value (e.g., 'S9' or '9')
                    if (is_string($semRaw) && preg_match('/(\d+)/', $semRaw, $numMatch)) {
                        $num = (int)$numMatch[1];
                        if ($num === $baseMap) {
                            $sem = 'S1';
                        } elseif ($num === $baseMap + 1) {
                            $sem = 'S2';
                        } else {
                            // fallback: odd -> S1, even -> S2
                            $sem = ($num % 2 === 0) ? 'S2' : 'S1';
                        }
                    } else {
                        // if no numeric value, preserve simple S1/S2 if present
                        if (is_string($semRaw) && preg_match('/^S([12])$/i', $semRaw, $m)) {
                            $sem = 'S' . $m[1];
                        } else {
                            $sem = 'S1';
                        }
                    }

                    $grade = $gradeRepo->findOneBy(['student' => $user, 'module' => $module]);
                    $cc = $grade ? $grade->getCcGrade() : null;
                    $exam = $grade ? $grade->getExamGrade() : null;
                    $final = null;
                    if ($grade && $grade->getIsPublished() === Grade::STATE_FINALIZED) {
                        $final = $gradCalc->calculateFinal($cc, $exam);
                        if ($final !== null) {
                            $final = round($final, 2);
                        }
                    }
                    $result = $gradCalc->determineResult($final);

                    $semesters[$sem][] = [
                        'module' => $modName,
                        'coefficient' => $coef,
                        'cc' => $cc,
                        'exam' => $exam,
                        'final' => $final,
                        'result' => $result,
                    ];
                }
            }
        }

        // Build unified chart dataset: all modules with grades (not per-semester average)
        $allModulesForChart = [];
        $semesterAverages = ['S1' => null, 'S2' => null];
        
        // Calculate semester averages using weighted formula
        foreach (['S1', 'S2'] as $s) {
            $weightedSum = 0;
            $totalCoefficient = 0;
            $hasAnyFinal = false;
            
            foreach ($semesters[$s] as $m) {
                if ($m['final'] !== null) {
                    $hasAnyFinal = true;
                    $weightedSum += $m['final'] * $m['coefficient'];
                    $totalCoefficient += $m['coefficient'];
                    
                    $allModulesForChart[] = [
                        'name' => $m['module'],
                        'final' => $m['final'],
                    ];
                }
            }
            
            // Semester average = sum(module_grade * coefficient) / sum(coefficients)
            if ($hasAnyFinal && $totalCoefficient > 0) {
                $semesterAverages[$s] = round($weightedSum / $totalCoefficient, 2);
            }
        }

        // Determine semester names based on class level
        $classLevel = $classe && method_exists($classe, 'getLevel') && $classe->getLevel() ? (int)$classe->getLevel() : 1;
        $baseMap = 5 + (($classLevel - 1) * 2);
        $semesterNames = [
            'S1' => 'Semestre ' . $baseMap,
            'S2' => 'Semestre ' . ($baseMap + 1),
        ];

        // Debug info to help diagnose empty module lists
        $debug = [
            'requested_year' => $year,
            'determined_class' => $classe ? (method_exists($classe, 'getName') ? $classe->getName() : (string)$classe) : null,
            'class_level' => $classLevel,
            'teaching_assignments_count' => isset($tas) ? count($tas) : 0,
            's1_count' => isset($semesters['S1']) ? count($semesters['S1']) : 0,
            's2_count' => isset($semesters['S2']) ? count($semesters['S2']) : 0,
        ];

        return $this->json(['semesters' => $semesters, 'semesterNames' => $semesterNames, 'semesterAverages' => $semesterAverages, 'chart' => $allModulesForChart, 'debug' => $debug]);
    }

    #[Route('/student/bulletin/export', name: 'app_student_bulletin_export')]
    public function exportBulletin(
        \App\Service\BulletinPdfService $pdfService,
        GradeRepository $gradeRepo,
        TeachingAssignmentRepository $taRepo,
        GradeCalculationService $gradCalc,
        \App\Repository\ModuleRepository $moduleRepository
    ): Response {
        $user = $this->getUser();
        $year = (string) ($_GET['year'] ?? '');
        $semester = (string) ($_GET['semester'] ?? '');

        if (!$user instanceof User || !$year || !$semester) {
            return $this->json(['error' => 'Missing parameters'], 400);
        }

        // Determine class for this year via enrollments
        $classe = null;
        foreach ($user->getEnrollments() as $enr) {
            $class = $enr->getAssociatedClass();
            $sy = $class && $class->getSchoolYear() ? $class->getSchoolYear()->getName() : null;
            if ($sy === $year) { $classe = $class; break; }
        }
        if (!$classe) $classe = $user->getClasse();

        if (!$classe) {
            return $this->json(['error' => 'No class found'], 400);
        }

        // Get class level and semester range
        $classLevel = $classe && method_exists($classe, 'getLevel') && $classe->getLevel() ? (int)$classe->getLevel() : 1;
        $semRangeStart = 5 + (($classLevel - 1) * 2);
        
        // Map semester (S1 or S2) to actual semester number
        $actualSemester = $semester === 'S1' ? 'S' . $semRangeStart : 'S' . ($semRangeStart + 1);
        
        $filiere = $classe && method_exists($classe, 'getFiliere') ? $classe->getFiliere() : null;
        if (!$filiere) {
            return $this->json(['error' => 'No filiere found'], 400);
        }

        // Get all modules for this semester
        $modules = $moduleRepository->createQueryBuilder('m')
            ->where('m.filiere = :filiere AND m.semester = :semester')
            ->setParameter('filiere', $filiere)
            ->setParameter('semester', $actualSemester)
            ->orderBy('m.name', 'ASC')
            ->getQuery()
            ->getResult();

        // Get student's grades for these modules
        $grades = $gradeRepo->createQueryBuilder('g')
            ->join('g.module', 'm')
            ->where('g.student = :student AND m.filiere = :filiere AND m.semester = :semester')
            ->setParameter('student', $user)
            ->setParameter('filiere', $filiere)
            ->setParameter('semester', $actualSemester)
            ->getQuery()
            ->getResult();

        $gradesByModule = [];
        foreach ($grades as $g) {
            $gradesByModule[$g->getModule()->getId()] = $g;
        }

        // Build module data
        $moduleData = [];
        $allFinalized = true;
        
        foreach ($modules as $module) {
            $grade = $gradesByModule[$module->getId()] ?? null;
            $cc = $grade ? $grade->getCcGrade() : null;
            $exam = $grade ? $grade->getExamGrade() : null;
            $final = null;
            
            if ($grade && $grade->getIsPublished() === Grade::STATE_FINALIZED) {
                $final = $gradCalc->calculateFinal($cc, $exam);
                if ($final !== null) {
                    $final = round($final, 2);
                }
            } else {
                $allFinalized = false;
            }

            $result = $gradCalc->determineResult($final);

            $moduleData[] = [
                'module' => $module->getName(),
                'coefficient' => $module->getCoefficient(),
                'cc' => $cc,
                'exam' => $exam,
                'final' => $final,
                'result' => $result,
            ];
        }

        // Only allow export if all grades are finalized
        if (!$allFinalized) {
            return $this->json(['error' => 'Not all grades are finalized for this semester'], 400);
        }

        // Generate PDF
        $semesterDisplayName = $semester === 'S1' ? 'Semestre ' . $semRangeStart : 'Semestre ' . ($semRangeStart + 1);
        $pdf = $pdfService->generateSemesterBulletin(
            $user->getFirstName() . ' ' . $user->getLastName(),
            (string) $user->getId(),
            $classe->getName(),
            $year,
            $semesterDisplayName,
            $moduleData
        );

        // Return PDF as download
        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Bulletin_' . $semester . '_' . $year . '.pdf"',
        ]);
    }
}
