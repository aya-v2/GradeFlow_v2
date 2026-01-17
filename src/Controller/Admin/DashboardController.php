<?php

namespace App\Controller\Admin;

use App\Entity\Classe;
use App\Entity\Filiere;
use App\Entity\Module;
use App\Entity\SchoolYear;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/dashboard', name: 'app_admin_dashboard')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('GradeFlow V2');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Structure');
        yield MenuItem::linkToCrud('School Years', 'fas fa-calendar', \App\Entity\SchoolYear::class);
        yield MenuItem::linkToCrud('Filières', 'fas fa-university', \App\Entity\Filiere::class);
        yield MenuItem::linkToCrud('Classes', 'fas fa-chalkboard-teacher', \App\Entity\Classe::class);
        yield MenuItem::linkToCrud('Modules', 'fas fa-book', \App\Entity\Module::class);

        yield MenuItem::section('Users');
        yield MenuItem::linkToCrud('Users', 'fas fa-users', \App\Entity\User::class);
        
        // These are technical tables, mostly for debugging, but useful to have
        yield MenuItem::section('Assignments');
        // You didn't make CRUDs for Enrollment/Assignment yet, so I commented them out
        // yield MenuItem::linkToCrud('Inscriptions', 'fas fa-list', \App\Entity\Enrollment::class);
    }
}
