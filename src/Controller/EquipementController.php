<?php

namespace App\Controller;

use App\Entity\Equipement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Ajoutez cette ligne pour importer FileType
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\EquipementType;

use App\Repository\EquipementRepository;



class EquipementController extends AbstractController
{
    #[Route('/index', name: 'app_equipement')]
    public function index(): Response
    {
        return $this->render('equipement/index.html.twig', [
            'controller_name' => 'EquipementController',
        ]);
    }


    #[Route('/afficher_eq', name: 'app_afficher_eq')]
    public function affiche(EquipementRepository $equipementRepository, Request $request): Response
    {
        $equipements=$this->getDoctrine()->getRepository(Equipement::class)->findAll();
        return $this->render('equipement/affiche_equipement.html.twig',
            ['equipements'=>$equipements]);
    }
    

    #[Route('/ajouter_eq', name: 'app_ajouter_eq')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $equipement = new Equipement();

        $form = $this->createForm(EquipementType::class, $equipement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($equipement);
            $entityManager->flush();

            return $this->redirectToRoute('app_afficher_eq');
        }

        return $this->render('equipement/ajouter_equipement.html.twig', [
            'equipement' => $equipement,
            'form' => $form->createView(),
        ]);
    }



    #[Route('/equipement/modifier/{libelle}', name: 'app_modifier_eq')]
    public function modifier(Request $request, string $libelle, EntityManagerInterface $entityManager): Response
    {
        $equipement = $entityManager->getRepository(Equipement::class)->findOneBy(['libelle' => $libelle]);

        if (!$equipement) {
            throw $this->createNotFoundException('Equipement non trouvé');
        }

        $form = $this->createForm(EquipementType::class, $equipement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // You can handle photo logic here if necessary
            $entityManager->flush();

            return $this->redirectToRoute('app_afficher_eq');
        }

        return $this->render('equipement/modifier_equipement.html.twig', [
            'form' => $form->createView(),
            'equipement' => $equipement,
        ]);
    }






    /*#[Route('/ajoute', name: 'app_ajoute')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entretien = new Entretien();

        $form = $this->createForm(EntretienType::class, $entretien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entretien);
            $entityManager->flush();

            return $this->redirectToRoute('app_affiche', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entretien/ajoute.html.twig', ['entretien' => $entretien,'form' => $form,]);
    }
*/

    #[Route('/admin/equipement/supprimer/{id_equipement}', name: 'app_equipement_supprimer')]
    public function delete($id_equipement, EntityManagerInterface $entityManager, EquipementRepository $equipementRepository): Response
    {



        $equipement = $equipementRepository->find($id_equipement);
        $entityManager->remove($equipement);
        $entityManager->flush();
        return $this->redirectToRoute('app_afficher_eq');
    }



}
