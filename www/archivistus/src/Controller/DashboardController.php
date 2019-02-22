<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index()
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    /**
    * @Route("/", name="home")
    */
    public function accueil()
    {
        return $this->render('dashboard/home.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
