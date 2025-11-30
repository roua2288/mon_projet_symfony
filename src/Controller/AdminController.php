<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    // #[IsGranted('ROLE_ADMIN')] // Temporairement désactivé pour les tests
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'page_title' => 'Tableau de bord Admin',
        ]);
    }

    #[Route('/admin/etudiants', name: 'admin_etudiants')]
    #[IsGranted('ROLE_ADMIN')]
    public function etudiants(): Response
    {
        return $this->render('admin/etudiants.html.twig', [
            'page_title' => 'Gestion des Étudiants',
        ]);
    }

    #[Route('/admin/enseignants', name: 'admin_enseignants')]
    #[IsGranted('ROLE_ADMIN')]
    public function enseignants(): Response
    {
        return $this->render('admin/enseignants.html.twig', [
            'page_title' => 'Gestion des Enseignants',
        ]);
    }

    #[Route('/admin/emplois', name: 'admin_emplois')]
    #[IsGranted('ROLE_ADMIN')]
    public function emplois(): Response
    {
        return $this->render('admin/emplois.html.twig', [
            'page_title' => 'Gestion des Emplois du temps',
        ]);
    }

    #[Route('/admin/classes', name: 'admin_classes')]
    #[IsGranted('ROLE_ADMIN')]
    public function classes(): Response
    {
        return $this->render('admin/classes.html.twig', [
            'page_title' => 'Gestion des Classes',
        ]);
    }

    #[Route('/admin/annonces', name: 'admin_annonces')]
    #[IsGranted('ROLE_ADMIN')]
    public function annonces(): Response
    {
        return $this->render('admin/annonces.html.twig', [
            'page_title' => 'Gestion des Annonces',
        ]);
    }

    #[Route('/admin/parametres', name: 'admin_parametres')]
    #[IsGranted('ROLE_ADMIN')]
    public function parametres(): Response
    {
        return $this->render('admin/parametres.html.twig', [
            'page_title' => 'Paramètres',
        ]);
    }

}

