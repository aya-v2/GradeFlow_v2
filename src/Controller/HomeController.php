<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        if ($this->isGranted('ROLE_TEACHER')) {
            return $this->redirectToRoute('app_teacher_dashboard');
        }

        if ($this->isGranted('ROLE_STUDENT')) {
            return $this->redirectToRoute('app_student_dashboard');
        }

        return $this->redirectToRoute('app_login');
    }
}
