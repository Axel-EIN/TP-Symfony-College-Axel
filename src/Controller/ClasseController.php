<?php

namespace App\Controller;

use App\Entity\Prof;
use App\Entity\Classe;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClasseController extends AbstractController
{
    #[Route('/classes', name: 'classes')]
    public function afficherClasses(ClasseRepository $repository): Response
    {
        $classes = $repository->findAll();

        return $this->render('classe/index.html.twig', [
            'titre' => 'la liste des classes',
            'classes' => $classes
        ]);
    }

    /**
     * @Route("/classes/create", name="create_classe")
     */
    public function ajouterClasse(Request $requete, EntityManagerInterface $entityManager) {

        $classe = new Classe;

        $formulaire = $this->createFormBuilder($classe)
            ->add('nom', TextType::class)
            ->add('niveau', IntegerType::class)
            ->add('profPrincipal', EntityType::class, [
				'class' => Prof::class,
                'choice_label' => 'nom',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter'
            ])
            ->getForm();

        $formulaire->handleRequest($requete);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            $entityManager->persist($classe);
            $entityManager->flush();

            return $this->redirectToRoute('classes');
        } else {
            return $this->render('prof/form.html.twig', [
                'titre' => 'Ajouter une classe',
                'type' => 'Ajouter',
                'formView' => $formulaire->createView()
            ]);
        }
    }

    /**
     * @Route("/classes/{id}/delete", name="delete_classe")
     */
    public function supprimerClasse(Classe $classe, EntityManagerInterface $entityManager) {

        $entityManager->remove($classe);
        $entityManager->flush();

        return $this->redirectToRoute('classes');
    }
}
