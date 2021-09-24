<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Eleve;
use App\Entity\Matiere;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NoteController extends AbstractController
{
    #[Route('/notes', name: 'notes')]
    public function afficherNotes(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Note::class);

        $notes = $repository->findAll();

        return $this->render('note/index.html.twig', [
            'titre' => 'la liste des notes',
            'notes' => $notes
        ]);
    }

    /**
     * @Route("/notes/create", name="create_note")
     */
    public function ajouterNote(Request $requete, EntityManagerInterface $entityManager) {

        $note = new Note;

        $formulaire = $this->createFormBuilder($note)
            ->add('note', IntegerType::class)
            ->add('coefficient', IntegerType::class)
            ->add('date', DateType::class, [
            'widget' => 'single_text',
            'input_format' => 'd/m/Y',
            ])
            ->add('matiere', EntityType::class, [
				'class' => Matiere::class,
                'choice_label' => 'nom',
            ])
            ->add('eleve', EntityType::class, [
				'class' => Eleve::class,
                'choice_label' => 'nom',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter'
            ])
            ->getForm();

        $formulaire->handleRequest($requete);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            $entityManager->persist($note);
            $entityManager->flush();

            return $this->redirectToRoute('notes');
        } else {
            return $this->render('note/form.html.twig', [
                'titre' => 'Ajouter une note',
                'type' => 'Ajouter',
                'formView' => $formulaire->createView()
            ]);
        }
    }

    /**
     * @Route("/notes/create/{id}", name="create_note_eleve")
     */
    public function ajouterNoteEleve(Request $requete, EntityManagerInterface $entityManager) {

        $note = new Note;

        $formulaire = $this->createFormBuilder($note)
            ->add('note', IntegerType::class)
            ->add('coefficient', IntegerType::class)
            ->add('date', DateType::class, [
            'widget' => 'single_text',
            'input_format' => 'd/m/Y',
            ])
            ->add('matiere', EntityType::class, [
				'class' => Matiere::class,
                'choice_label' => 'nom',
            ])
            ->add('eleve', EntityType::class, [
				'class' => Eleve::class,
                'choice_label' => 'nom',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter'
            ])
            ->getForm();

        $formulaire->handleRequest($requete);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            $entityManager->persist($note);
            $entityManager->flush();

            return $this->redirectToRoute('one_eleve', ['id' => $id]);
        } else {
            return $this->render('note/form-eleve.html.twig', [
                'titre' => 'Ajouter une note à l\'élève',
                'type' => 'Ajouter',
                'formView' => $formulaire->createView()
            ]);
        }
    }
}
