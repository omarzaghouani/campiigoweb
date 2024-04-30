<?php

namespace App\Controller;

use App\Entity\Location;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\LocationType;
use App\Repository\LocationRepository ;

class LocationController extends AbstractController
{
    #[Route('/location', name: 'app_location')]
    public function index(): Response
    {
        return $this->render('location/index.html.twig', [
            'controller_name' => 'LocationController',
        ]);
    }

    #[Route('/location/afficher', name: 'app_location_afficher')]
    public function afficherLocations(): Response
    {
        $locations = $this->getDoctrine()->getRepository(Location::class)->findAll();
        return $this->render('location/afficherLocations.html.twig', ['locations' => $locations]);
    }

    #[Route('/location/ajouter', name: 'app_location_ajouter')]
    public function ajouterLocation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($location);
            $entityManager->flush();

            return $this->redirectToRoute('app_location_afficher');
        }

        return $this->render('location/ajouterLocation.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/location/modifier/{id}', name: 'app_location_modifier')]
    public function modifierLocation(Request $request, Location $location, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_location_afficher');
        }

        return $this->render('location/modifierLocation.html.twig', [
            'form' => $form->createView(),
            'location' => $location,
        ]);
    }

    #[Route('/location/supprimer/{id}', name: 'app_location_supprimer')]
    public function supprimerLocation(Location $location, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($location);
        $entityManager->flush();

        return $this->redirectToRoute('app_location_afficher');
    }
}
