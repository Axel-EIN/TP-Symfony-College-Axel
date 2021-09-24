<?php

namespace App\Controller;

use App\Entity\Matiere;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MatiereController extends AbstractController
{
    #[Route('/matieres', name: 'matieres')]
    public function afficherMatieres(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Matiere::class);

        $matieres = $repository->findAll();

        return $this->render('matiere/index.html.twig', [
            'titre' => 'la liste des matières',
            'matieres' => $matieres
        ]);
    }

    /**
     * @Route("/matieres/create", name="create_matiere")
     */
    public function ajouterMatiere(Request $requete, EntityManagerInterface $entityManager) {

        $matiere = new Matiere;

        $formulaire = $this->createFormBuilder($matiere)
            ->add('nom', TextType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Créer'
            ])
            ->getForm();

        $formulaire->handleRequest($requete);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            $entityManager->persist($matiere);
            $entityManager->flush();

            return $this->redirectToRoute('matieres');
        } else {
            return $this->render('matiere/form.html.twig', [
                'titre' => 'Ajouter une matière',
                'type' => 'Ajouter',
                'formView' => $formulaire->createView()
            ]);
        }
    }
}