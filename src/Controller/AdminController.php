<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Classe;
use App\Entity\Module;
use App\Entity\Filiere;
use App\Entity\TeachingAssignment;
use App\Entity\SchoolYear;
use App\Entity\Grade;
use App\Repository\UserRepository;
use App\Repository\ClasseRepository;
use App\Repository\ModuleRepository;
use App\Repository\FiliereRepository;
use App\Repository\TeachingAssignmentRepository;
use App\Repository\SchoolYearRepository;
use App\Repository\GradeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin')]
class AdminController extends AbstractController
{
    // Dashboard
    #[Route('/dashboard', name: 'app_admin_dashboard')]
    public function dashboard(
        UserRepository $userRepo,
        ClasseRepository $classeRepo,
        ModuleRepository $moduleRepo,
        SchoolYearRepository $syRepo
    ): Response
    {
        $currentYear = $syRepo->findOneBy(['isCurrent' => true]);
        
        // Helper to count by role using LIKE because roles are JSON
        $countByRole = function($role) use ($userRepo) {
            return $userRepo->createQueryBuilder('u')
                ->select('count(u.id)')
                ->where('u.roles LIKE :role')
                ->setParameter('role', '%"'.$role.'"%')
                ->getQuery()
                ->getSingleScalarResult();
        };

        $stats = [
            'users_total' => $userRepo->count([]),
            'students' => $countByRole('ROLE_STUDENT'),
            'teachers' => $countByRole('ROLE_TEACHER'),
            'admins' => $countByRole('ROLE_ADMIN'),
            'classes_total' => $currentYear ? $classeRepo->count(['schoolYear' => $currentYear]) : 0,
            'modules_total' => $moduleRepo->count([]),
            'current_year' => $currentYear,
        ];

        return $this->render('admin/index.html.twig', [
            'stats' => $stats,
        ]);
    }

    // Users Management
    #[Route('/users', name: 'app_admin_users')]
    public function listUsers(Request $request, UserRepository $repo, ClasseRepository $classeRepo): Response
    {
        $role = $request->query->get('role', 'all');
        $classeId = $request->query->get('classe');
        $specialty = $request->query->get('specialty');
        $page = (int)$request->query->get('page', 1);
        $search = $request->query->get('search');
        $filiereId = $request->query->get('filiere');
        
        $limit = 20;

        $qb = $repo->createQueryBuilder('u');

        if ($role === 'student') {
            $qb->andWhere('u.roles LIKE :role')->setParameter('role', '%"ROLE_STUDENT"%');
            if ($filiereId) {
                $qb->leftJoin('u.classe', 'c')
                   ->andWhere('c.filiere = :filiere')
                   ->setParameter('filiere', $filiereId);
            }
            if ($classeId) {
                $qb->andWhere('u.classe = :classe')->setParameter('classe', $classeId);
            }
        } elseif ($role === 'teacher') {
             $qb->andWhere('u.roles LIKE :role')->setParameter('role', '%"ROLE_TEACHER"%');
             if ($specialty) {
                 $qb->andWhere('u.specialty LIKE :spec')->setParameter('spec', "%$specialty%");
             }
        } elseif ($role === 'admin') {
             $qb->andWhere('u.roles LIKE :role')->setParameter('role', '%"ROLE_ADMIN"%');
        }

        if ($search) {
            $qb->andWhere('u.firstName LIKE :search OR u.lastName LIKE :search OR u.email LIKE :search')
               ->setParameter('search', "%$search%");
        }

        $totalUsers = count($qb->getQuery()->getResult());
        
        $qb->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit);
           
        $users = $qb->getQuery()->getResult();

        $filieres = $repo->getEntityManager()->getRepository(Filiere::class)->findAll();
        
        if ($filiereId) {
            $classes = $classeRepo->findBy(['filiere' => $filiereId]);
        } else {
            $classes = $classeRepo->findAll();
        }

        $allTeachers = $repo->createQueryBuilder('t')
            ->where('t.roles LIKE :role')
            ->setParameter('role', '%"ROLE_TEACHER"%')
            ->getQuery()
            ->getResult();
        $specialties = array_unique(array_filter(array_map(fn($t) => $t->getSpecialty(), $allTeachers)));

        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
            'all_count' => $totalUsers,
            'classes' => $classes,
            'filieres' => $filieres,
            'specialties' => $specialties,
            'current_filters' => [
                'role' => $role,
                'classe' => $classeId,
                'filiere' => $filiereId,
                'specialty' => $specialty,
                'search' => $search,
                'page' => $page,
                'pages_count' => ceil($totalUsers / $limit)
            ]
        ]);
    }

    #[Route('/users/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function editUser(
        User $user, 
        Request $request, 
        EntityManagerInterface $em, 
        UserPasswordHasherInterface $hasher,
        ClasseRepository $classeRepo,
        FiliereRepository $filiereRepo
    ): Response
    {
        if ($request->isMethod('POST')) {
            $user->setFirstName($request->request->get('firstName'));
            $user->setLastName($request->request->get('lastName'));
            $user->setEmail($request->request->get('email'));
            
            $password = $request->request->get('password');
            if (!empty($password)) {
                $hashed = $hasher->hashPassword($user, $password);
                $user->setPassword($hashed);
            }

            if (in_array('ROLE_TEACHER', $user->getRoles())) {
                $user->setSpecialty($request->request->get('specialty'));
            }

            if (in_array('ROLE_STUDENT', $user->getRoles())) {
                $classId = $request->request->get('classe');
                if ($classId) {
                    $classe = $classeRepo->find($classId);
                    if ($classe) $user->setClasse($classe);
                } else {
                     $user->setClasse(null);
                }
            }

            $em->flush();

            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/users/form.html.twig', [
            'user' => $user,
            'edit' => true,
            'classes' => $classeRepo->findAll(),
            'filieres' => $filiereRepo->findAll()
        ]);
    }

    #[Route('/users/new', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    public function newUser(
        Request $request, 
        EntityManagerInterface $em, 
        UserPasswordHasherInterface $hasher,
        ClasseRepository $classeRepo,
        FiliereRepository $filiereRepo,
        SchoolYearRepository $syRepo
    ): Response
    {
        $user = new User();
        
        if ($request->isMethod('POST')) {
            $user->setFirstName($request->request->get('firstName'));
            $user->setLastName($request->request->get('lastName'));
            $user->setEmail($request->request->get('email'));
            $user->setType('user'); 
            
            $password = $request->request->get('password');
            if ($password) {
                $hashed = $hasher->hashPassword($user, $password);
                $user->setPassword($hashed);
            }

            $role = $request->request->get('role');
            if ($role) {
                $user->setRoles([$role]);
                if ($role === 'ROLE_STUDENT') $user->setType('student');
                elseif ($role === 'ROLE_TEACHER') $user->setType('teacher');
                elseif ($role === 'ROLE_ADMIN') $user->setType('admin');
            }

            if ($role === 'ROLE_TEACHER') {
                $user->setSpecialty($request->request->get('specialty'));
            }

            if ($role === 'ROLE_STUDENT') {
                $classId = $request->request->get('classe');
                if ($classId) {
                    $classe = $classeRepo->find($classId);
                    if ($classe) $user->setClasse($classe);
                }
            }
            
            if (!$user->getCin()) $user->setCin('CIN-' . substr(uniqid(), 0, 10));
            if (!$user->getType()) $user->setType('user');

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_admin_users');
        }

        // Restrict classes to Level 1 and Current Year for new students
        $currentYear = $syRepo->findOneBy(['isCurrent' => true]);
        $classes = [];
        if ($currentYear) {
            $classes = $classeRepo->findBy(['level' => 1, 'schoolYear' => $currentYear]);
        }

        return $this->render('admin/users/form.html.twig', [
            'user' => null,
            'edit' => false,
            'classes' => $classes,
            'filieres' => $filiereRepo->findAll()
        ]);
    }

    #[Route('/users/{id}/delete', name: 'app_admin_user_delete', methods: ['POST'])]
    public function deleteUser(User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();
        
        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/users/{id}', name: 'app_admin_user_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function showUser(User $user, GradeRepository $gradeRepo, TeachingAssignmentRepository $taRepo): Response
    {
        $gradesBySemester = [];
        $assignments = [];

        $semAverages = [];

        if (in_array('ROLE_STUDENT', $user->getRoles())) {
            $grades = $gradeRepo->findBy(['student' => $user]);
            foreach ($grades as $grade) {
                // Group by Semester via Module -> Semester
                $module = $grade->getModule();
                if ($module) {
                    $sem = $module->getSemester();
                    if (!isset($gradesBySemester[$sem])) {
                        $gradesBySemester[$sem] = [];
                    }
                    $gradesBySemester[$sem][] = $grade;
                }
            }
            // Sort semesters
            ksort($gradesBySemester);

            // Calculate Averages
            foreach ($gradesBySemester as $sem => $grades) {
                $totalPoints = 0;
                $totalCoeffs = 0;
                foreach ($grades as $grade) {
                    $module = $grade->getModule();
                    $coeff = $module->getCoefficient();
                    $cc = $grade->getCcGrade() ?? 0;
                    $exam = $grade->getExamGrade() ?? 0;
                    
                    // Moyenne Module = (CC + Exam * 2) / 3
                    $modAvg = ($cc + $exam * 2) / 3;
                    
                    $totalPoints += $modAvg * $coeff;
                    $totalCoeffs += $coeff;
                }
                $semAverages[$sem] = $totalCoeffs > 0 ? $totalPoints / $totalCoeffs : 0;
            }
        }

        if (in_array('ROLE_TEACHER', $user->getRoles())) {
            $assignments = $taRepo->findBy(['teacher' => $user]);
        }

        return $this->render('admin/users/show.html.twig', [
            'user' => $user,
            'gradesBySemester' => $gradesBySemester,
            'semAverages' => $semAverages,
            'assignments' => $assignments
        ]);
    }

    // Classes Management
    #[Route('/classes', name: 'app_admin_classes')]
    public function listClasses(Request $request, ClasseRepository $repo, FiliereRepository $filiereRepo, SchoolYearRepository $syRepo): Response
    {
        // Get current school year
        $currentYear = $syRepo->findOneBy(['isCurrent' => true]);
        
        $filiereId = $request->query->get('filiere');
        
        if ($filiereId) {
             $filiere = $filiereRepo->find($filiereId);
             $filieres = $filiere ? [$filiere] : [];
        } else {
            $filieres = $filiereRepo->findAll();
        }

        $classesByFiliere = [];
        
        foreach ($filieres as $filiere) {
            $classes = $repo->findBy(['filiere' => $filiere]);
            // Filter to show only current year classes
            $filteredClasses = array_filter(
                $classes,
                fn($c) => $c->getSchoolYear() && ($currentYear ? $c->getSchoolYear()->getId() === $currentYear->getId() : false)
            );
            $classesByFiliere[$filiere->getId()] = $filteredClasses;
        }

        return $this->render('admin/classes/index.html.twig', [
            'filieres' => $filieres, // Now filtered if requested
            'allFilieres' => $filiereRepo->findAll(), // For logic in dropdown
            'classesByFiliere' => $classesByFiliere,
            'currentYear' => $currentYear,
            'currentFiliereId' => $filiereId
        ]);
    }

    #[Route('/classes/archive', name: 'app_admin_classes_archive')]
    public function listClassesArchive(ClasseRepository $repo, FiliereRepository $filiereRepo, SchoolYearRepository $syRepo): Response
    {
        // Get all archived school years
        $allYears = $syRepo->findAll();
        $archivedYears = array_filter(
            $allYears,
            fn($y) => !$y->isCurrent()
        );
        
        $filieres = $filiereRepo->findAll();
        $classesByFiliere = [];
        
        foreach ($filieres as $filiere) {
            $classes = $repo->findBy(['filiere' => $filiere]);
            // Filter to show only archived year classes
            $filteredClasses = array_filter(
                $classes,
                fn($c) => $c->getSchoolYear() && !$c->getSchoolYear()->isCurrent()
            );
            $classesByFiliere[$filiere->getId()] = $filteredClasses;
        }

        return $this->render('admin/classes/archive.html.twig', [
            'filieres' => $filieres,
            'classesByFiliere' => $classesByFiliere,
            'archivedYears' => $archivedYears,
        ]);
    }

    #[Route('/classes/{id}/edit', name: 'app_admin_classe_edit', methods: ['GET', 'POST'])]
    public function editClasse(
        Classe $classe, 
        Request $request, 
        EntityManagerInterface $em,
        FiliereRepository $filiereRepo,
        SchoolYearRepository $syRepo
    ): Response
    {
        if ($request->isMethod('POST')) {
            $classe->setName($request->request->get('name'));
            $classe->setLevel((int)$request->request->get('level'));
            
            $filiereId = $request->request->get('filiere');
            if ($filiereId) {
                $filiere = $filiereRepo->find($filiereId);
                if ($filiere) $classe->setFiliere($filiere);
            }
            
            $syId = $request->request->get('schoolYear');
            if ($syId) {
                $sy = $syRepo->find($syId);
                if ($sy) $classe->setSchoolYear($sy);
            }
            
            $em->flush();
            return $this->redirectToRoute('app_admin_classes');
        }

        return $this->render('admin/classes/form.html.twig', [
            'classe' => $classe,
            'edit' => true,
            'filieres' => $filiereRepo->findAll(),
            'schoolYears' => $syRepo->findAll(),
        ]);
    }

    #[Route('/classes/new', name: 'app_admin_classe_new', methods: ['GET', 'POST'])]
    public function newClasse(
        Request $request,
        EntityManagerInterface $em,
        FiliereRepository $filiereRepo, 
        SchoolYearRepository $syRepo
    ): Response
    {
        if ($request->isMethod('POST')) {
            $classe = new Classe();
            $classe->setName($request->request->get('name'));
            $classe->setLevel((int)$request->request->get('level'));
            
            $filiereId = $request->request->get('filiere');
            if ($filiereId) {
                $filiere = $filiereRepo->find($filiereId);
                if ($filiere) $classe->setFiliere($filiere);
            }
            
            $syId = $request->request->get('schoolYear');
            if ($syId) {
                $sy = $syRepo->find($syId);
                if ($sy) $classe->setSchoolYear($sy);
            }
            
            $em->persist($classe);
            $em->flush();
            return $this->redirectToRoute('app_admin_classes');
        }

        return $this->render('admin/classes/form.html.twig', [
            'classe' => null,
            'edit' => false,
            'filieres' => $filiereRepo->findAll(),
            'schoolYears' => $syRepo->findAll(),
        ]);
    }

    #[Route('/classes/{id}', name: 'app_admin_classe_show', methods: ['GET'])]
    public function showClasse(Classe $classe): Response
    {
        return $this->render('admin/classes/show.html.twig', [
            'classe' => $classe,
            'students' => $classe->getStudents()
        ]);
    }

    #[Route('/classes/{id}/delete', name: 'app_admin_classe_delete', methods: ['POST'])]
    public function deleteClasse(Classe $classe, EntityManagerInterface $em): Response
    {
        $em->remove($classe);
        $em->flush();
        
        return $this->redirectToRoute('app_admin_classes');
    }

    // Modules Management
    #[Route('/modules', name: 'app_admin_modules')]
    #[Route('/modules', name: 'app_admin_modules')]
    public function listModules(ModuleRepository $repo, FiliereRepository $filiereRepo, Request $request): Response
    {
        $filiereId = $request->query->get('filiere');
        
        if ($filiereId) {
            $filiere = $filiereRepo->find($filiereId);
            $filieres = $filiere ? [$filiere] : [];
        } else {
            $filieres = $filiereRepo->findAll();
        }

        $modulesByFiliere = [];

        foreach ($filieres as $filiere) {
            $modules = $repo->findBy(['filiere' => $filiere], ['semester' => 'ASC', 'name' => 'ASC']);
            
            // Group by Semester
            $grouped = [];
            foreach ($modules as $module) {
                $sem = $module->getSemester();
                if (!isset($grouped[$sem])) {
                    $grouped[$sem] = [];
                }
                $grouped[$sem][] = $module;
            }
            ksort($grouped); // Sort semesters
            
            $modulesByFiliere[$filiere->getId()] = [
                'filiere' => $filiere,
                'semesters' => $grouped
            ];
        }

        return $this->render('admin/modules/index.html.twig', [
            'modulesByFiliere' => $modulesByFiliere,
            'allFilieres' => $filiereRepo->findAll(), // For dropdown
            'currentFiliereId' => $filiereId
        ]);
    }

    #[Route('/modules/{id}/edit', name: 'app_admin_module_edit', methods: ['GET', 'POST'])]
    public function editModule(
        Module $module, 
        Request $request,
        EntityManagerInterface $em,
        FiliereRepository $filiereRepo
    ): Response
    {
        if ($request->isMethod('POST')) {
            $module->setName($request->request->get('name'));
            $module->setSemester((int)$request->request->get('semester'));
            $module->setCoefficient((float)$request->request->get('coefficient'));
            
            $filiereId = $request->request->get('filiere');
            if ($filiereId) {
                $filiere = $filiereRepo->find($filiereId);
                if ($filiere) $module->setFiliere($filiere);
            }
            
            $em->flush();
            return $this->redirectToRoute('app_admin_modules');
        }

        return $this->render('admin/modules/form.html.twig', [
            'module' => $module,
            'edit' => true,
            'filieres' => $filiereRepo->findAll(),
        ]);
    }

    #[Route('/modules/new', name: 'app_admin_module_new', methods: ['GET', 'POST'])]
    public function newModule(
        Request $request,
        EntityManagerInterface $em,
        FiliereRepository $filiereRepo
    ): Response
    {
        if ($request->isMethod('POST')) {
            $module = new Module();
            $module->setName($request->request->get('name'));
            $module->setSemester((int)$request->request->get('semester'));
            $module->setCoefficient((float)$request->request->get('coefficient'));
            
            $filiereId = $request->request->get('filiere');
            if ($filiereId) {
                $filiere = $filiereRepo->find($filiereId);
                if ($filiere) $module->setFiliere($filiere);
            }
            
            $em->persist($module);
            $em->flush();
            return $this->redirectToRoute('app_admin_modules');
        }

        return $this->render('admin/modules/form.html.twig', [
            'module' => null,
            'edit' => false,
            'filieres' => $filiereRepo->findAll(),
        ]);
    }

    #[Route('/modules/{id}/delete', name: 'app_admin_module_delete', methods: ['POST'])]
    public function deleteModule(Module $module, EntityManagerInterface $em): Response
    {
        $em->remove($module);
        $em->flush();
        
        return $this->redirectToRoute('app_admin_modules');
    }

    // Filieres Management
    #[Route('/filieres', name: 'app_admin_filieres')]
    public function listFilieres(FiliereRepository $repo): Response
    {
        $filieres = $repo->findAll();

        return $this->render('admin/filieres/index.html.twig', [
            'filieres' => $filieres,
        ]);
    }

    #[Route('/filieres/{id}/edit', name: 'app_admin_filiere_edit', methods: ['GET', 'POST'])]
    public function editFiliere(Filiere $filiere, Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $filiere->setName($request->request->get('name'));
            $filiere->setCode($request->request->get('code'));
            $em->flush();
            return $this->redirectToRoute('app_admin_filieres');
        }

        return $this->render('admin/filieres/form.html.twig', [
            'filiere' => $filiere,
            'edit' => true,
        ]);
    }

    #[Route('/filieres/new', name: 'app_admin_filiere_new', methods: ['GET', 'POST'])]
    public function newFiliere(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $filiere = new Filiere();
            $filiere->setName($request->request->get('name'));
            $filiere->setCode($request->request->get('code'));
            $em->persist($filiere);
            $em->flush();
            return $this->redirectToRoute('app_admin_filieres');
        }

        return $this->render('admin/filieres/form.html.twig', [
            'filiere' => null,
            'edit' => false,
        ]);
    }

    #[Route('/filieres/{id}/delete', name: 'app_admin_filiere_delete', methods: ['POST'])]
    public function deleteFiliere(Filiere $filiere, EntityManagerInterface $em): Response
    {
        $em->remove($filiere);
        $em->flush();
        
        return $this->redirectToRoute('app_admin_filieres');
    }

    // Teacher Assignments
    #[Route('/assignments', name: 'app_admin_assignments')]
    public function listAssignments(
        TeachingAssignmentRepository $repo,
        SchoolYearRepository $syRepo,
        Request $request
    ): Response
    {
        $currentYear = $syRepo->findOneBy(['isCurrent' => true]);
        $assignmentsByTeacher = [];
        $search = $request->query->get('search');
        
        if ($currentYear) {
            $allAssignments = $repo->findAll();
            $filteredAssignments = array_filter($allAssignments, fn($a) => 
                $a->getAssociatedClass()->getSchoolYear()?->getId() === $currentYear->getId()
            );

            foreach ($filteredAssignments as $assignment) {
                $teacher = $assignment->getTeacher();
                
                // Filter by search term if provided
                if ($search) {
                    $fullName = $teacher->getFirstName() . ' ' . $teacher->getLastName();
                    if (stripos($fullName, $search) === false) {
                        continue;
                    }
                }

                $teacherId = $teacher->getId();
                if (!isset($assignmentsByTeacher[$teacherId])) {
                     $assignmentsByTeacher[$teacherId] = [
                         'teacher' => $teacher,
                         'assignments' => []
                     ];
                }
                $assignmentsByTeacher[$teacherId]['assignments'][] = $assignment;
            }
            
            // Sort by teacher name
            usort($assignmentsByTeacher, fn($a, $b) => strcmp($a['teacher']->getLastName(), $b['teacher']->getLastName()));
        }

        return $this->render('admin/assignments/index.html.twig', [
            'assignmentsByTeacher' => $assignmentsByTeacher,
            'currentYear' => $currentYear,
        ]);
    }

    #[Route('/assignments/new', name: 'app_admin_assignment_new', methods: ['GET', 'POST'])]
    public function newAssignment(
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepo,
        ClasseRepository $classeRepo,
        ModuleRepository $moduleRepo,
        SchoolYearRepository $syRepo
    ): Response
    {
        if ($request->isMethod('POST')) {
            $assignment = new TeachingAssignment();
            
            $teacherId = $request->request->get('teacher');
            if ($teacherId) {
                $teacher = $userRepo->find($teacherId);
                if ($teacher) $assignment->setTeacher($teacher);
            }
            
            $classId = $request->request->get('class');
            if ($classId) {
                $classe = $classeRepo->find($classId);
                if ($classe) $assignment->setAssociatedClass($classe);
            }
            
            $moduleId = $request->request->get('module');
            if ($moduleId) {
                $module = $moduleRepo->find($moduleId);
                if ($module) $assignment->setModule($module);
            }
            
            $em->persist($assignment);
            $em->flush();
            
            return $this->redirectToRoute('app_admin_assignments');
        }

        $teachers = array_filter(
            $userRepo->findAll(),
            fn($u) => in_array('ROLE_TEACHER', $u->getRoles())
        );
        $currentYear = $syRepo->findOneBy(['isCurrent' => true]);
        $classes = $currentYear ? $classeRepo->findBy(['schoolYear' => $currentYear]) : [];

        return $this->render('admin/assignments/form.html.twig', [
            'assignment' => null,
            'edit' => false,
            'teachers' => $teachers,
            'classes' => $classes,
            'modules' => $moduleRepo->findAll(),
        ]);
    }

    #[Route('/assignments/{id}/edit', name: 'app_admin_assignment_edit', methods: ['GET', 'POST'])]
    public function editAssignment(
        TeachingAssignment $assignment,
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepo,
        ClasseRepository $classeRepo,
        ModuleRepository $moduleRepo
    ): Response
    {
        if ($request->isMethod('POST')) {
            $teacherId = $request->request->get('teacher');
            if ($teacherId) {
                $teacher = $userRepo->find($teacherId);
                if ($teacher) $assignment->setTeacher($teacher);
            }
            
            $classId = $request->request->get('class');
            if ($classId) {
                $classe = $classeRepo->find($classId);
                if ($classe) $assignment->setAssociatedClass($classe);
            }
            
            $moduleId = $request->request->get('module');
            if ($moduleId) {
                $module = $moduleRepo->find($moduleId);
                if ($module) $assignment->setModule($module);
            }
            
            $em->flush();
            return $this->redirectToRoute('app_admin_assignments');
        }

        $teachers = array_filter(
            $userRepo->findAll(),
            fn($u) => in_array('ROLE_TEACHER', $u->getRoles())
        );

        return $this->render('admin/assignments/form.html.twig', [
            'assignment' => $assignment,
            'edit' => true,
            'teachers' => $teachers,
            'classes' => $classeRepo->findAll(),
            'modules' => $moduleRepo->findAll(),
        ]);
    }

    #[Route('/users/{id}/bulletin', name: 'app_admin_bulletin_export', methods: ['GET'])]
    public function exportBulletin(User $user, GradeRepository $gradeRepo): Response
    {
        // For now, render a print-friendly version of the profile
        // In a real app, this would generate a PDF (e.g., using DomPDF or Snappy)
        
        $gradesBySemester = [];
        if (in_array('ROLE_STUDENT', $user->getRoles())) {
            $grades = $gradeRepo->findBy(['student' => $user]);
            foreach ($grades as $grade) {
                // Group by Semester via Module -> Semester
                $sem = $grade->getModule()->getSemester();
                if (!isset($gradesBySemester[$sem])) {
                    $gradesBySemester[$sem] = [];
                }
                $gradesBySemester[$sem][] = $grade;
            }
            ksort($gradesBySemester);
        }

        return $this->render('admin/users/bulletin.html.twig', [
            'user' => $user,
            'gradesBySemester' => $gradesBySemester
        ]);
    }


    #[Route('/assignments/{id}/delete', name: 'app_admin_assignment_delete', methods: ['POST'])]
    public function deleteAssignment(TeachingAssignment $assignment, EntityManagerInterface $em): Response
    {
        $em->remove($assignment);
        $em->flush();
        
        return $this->redirectToRoute('app_admin_assignments');
    }

    // System Actions
    #[Route('/system', name: 'app_admin_system')]
    public function system(SchoolYearRepository $syRepo): Response
    {
        $schoolYears = $syRepo->findAll();
        $currentYear = $syRepo->findOneBy(['isCurrent' => true]);

        return $this->render('admin/system/index.html.twig', [
            'schoolYears' => $schoolYears,
            'currentYear' => $currentYear,
        ]);
    }

    #[Route('/system/new-year', name: 'app_admin_new_year', methods: ['POST'])]
    public function startNewYear(
        SchoolYearRepository $repo, 
        GradeRepository $gradeRepo,
        EntityManagerInterface $em
    ): Response
    {
        // BLOCKER: Check if all grades for current year are finalized
        $pendingGrades = $gradeRepo->createQueryBuilder('g')
            ->select('count(g.id)')
            ->join('g.student', 's')
            ->join('s.classe', 'c')
            ->join('c.schoolYear', 'sy')
            ->where('sy.isCurrent = :current')
            ->andWhere('g.isPublished != :finalState')
            ->setParameter('current', true)
            ->setParameter('finalState', Grade::STATE_FINALIZED)
            ->getQuery()
            ->getSingleScalarResult();

        if ($pendingGrades > 0) {
            $this->addFlash('error', "Impossible de démarrer une nouvelle année : il reste $pendingGrades notes non finalisées pour l'année en cours.");
            return $this->redirectToRoute('app_admin_system');
        }

        // Mark current year as not current
        $current = $repo->findOneBy(['isCurrent' => true]);
        if ($current) {
            $current->setIsCurrent(false);
        }

        // Create new school year
        $newYear = new SchoolYear();
        $newYear->setName('2026-2027');
        $newYear->setIsCurrent(true);
        
        $em->persist($newYear);
        $em->flush();

        $this->addFlash('success', 'Nouvelle année scolaire (2026-2027) démarrée avec succès.');
        return $this->redirectToRoute('app_admin_system');
    }
}
