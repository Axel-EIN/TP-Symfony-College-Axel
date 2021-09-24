<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Entity\Classe;
use App\Repository\EleveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EleveController extends AbstractController
{
    #[Route('/eleves', name: 'eleves')]
    public function afficherEleves(EleveRepository $repository): Response
    {
        $eleves = $repository->findAll();

        return $this->render('eleve/index.html.twig', [
            'titre' => 'la liste des élèves',
            'eleves' => $eleves
        ]);
    }

    /**
     * @Route("/eleves/create", name="create_eleve")
     */
    public function ajouterEleve(Request $requete, EntityManagerInterface $entityManager) {

        $eleve = new Eleve;

        $formulaire = $this->createFormBuilder($eleve)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('dateDeNaissance', DateType::class, [
            'widget' => 'single_text',
            'input_format' => 'd/m/Y',
            ])
            ->add('classe', EntityType::class, [
				'class' => Classe::class,
                'choice_label' => 'nom',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter'
            ])
            ->getForm();

        $formulaire->handleRequest($requete);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            $entityManager->persist($eleve);
            $entityManager->flush();

            return $this->redirectToRoute('eleves');
        } else {
            return $this->render('eleve/form.html.twig', [
                'titre' => 'Ajouter un eleve',
                'type' => 'Ajouter',
                'formView' => $formulaire->createView()
            ]);
        }
    }

    /**
     * @Route("/eleves/{id}/delete", name="delete_eleve")
     */
    public function supprimerEleve(Eleve $eleve, EntityManagerInterface $entityManager) {

        $entityManager->remove($eleve);
        $entityManager->flush();

        return $this->redirectToRoute('eleves');
    }

    /**
     * @Route("/eleves/{id}", name="one_eleve")
     */
    public function one($id, EleveRepository $repository) {

        $un_eleve = $repository->find($id);

        $notes = $un_eleve->getNotes();
        
        $total = 0;
        $nbr_notes = 0;
        $moyenne = 0;
        foreach ($notes as $note) {
            $total += $note->getNote() * $note->getCoefficient();
            $nbr_notes++;
        }

        $moyenne = $total / $nbr_notes;

        return $this->render('eleve/one.html.twig', [
            'titre' => 'détail élève',
            'un_eleve' => $un_eleve,
            'moyenne' => $moyenne
        ]);
    }
}
