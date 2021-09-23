<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'titre' => 'Accueil',
        ]);
    }

    /**
     * @Route("/reglement-interieur", name="reglement")
     */
    public function afficherReglement()
    {
        return $this->render('home/reglement.html.twig', [
            'titre' => 'le Réglement intérieur du collège',
        ]);
    }
}
