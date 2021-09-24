<?php

namespace App\Controller;

use App\Entity\Prof;
use App\Entity\Matiere;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfController extends AbstractController
{
    #[Route('/profs', name: 'profs')]
    public function afficherProfs(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Prof::class);

        $profs = $repository->findAll();

        return $this->render('prof/index.html.twig', [
            'titre' => 'la liste des professeurs',
            'profs' => $profs
        ]);
    }

    /**
     * @Route("/profs/create", name="create_prof")
     */
    public function ajouterProf(Request $requete, EntityManagerInterface $entityManager) {

        $prof = new Prof;

        $formulaire = $this->createFormBuilder($prof)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('dateDeNaissance', DateType::class, [
				'widget' => 'single_text',
				'input_format' => 'd/m/Y',
			])
            ->add('matiere', EntityType::class, [
				'class' => Matiere::class,
                'choice_label' => 'nom',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter'
            ])
            ->getForm();

        $formulaire->handleRequest($requete);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            $entityManager->persist($prof);
            $entityManager->flush();

            return $this->redirectToRoute('profs');
        } else {
            return $this->render('prof/form.html.twig', [
                'titre' => 'Ajouter un professeur',
                'type' => 'Ajouter',
                'formView' => $formulaire->createView()
            ]);
        }
    }

    /**
     * @Route("/profs/{id}/delete", name="delete_prof")
     */
    public function supprimerProf(Prof $prof, EntityManagerInterface $entityManager) {

        $entityManager->remove($prof);
        $entityManager->flush();

        return $this->redirectToRoute('profs');
    }
}
