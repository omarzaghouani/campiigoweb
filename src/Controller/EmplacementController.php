<?php

namespace App\Controller;
use App\Entity\Emplacement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\EmplacementType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EmplacementRepository;




class EmplacementController extends AbstractController
{
    
    #[Route('/ajouteremp', name: 'app_ajouteremp')]
    public function new(Request $request): Response
    {
        $emplacement = new Emplacement();
        $form = $this->createForm(EmplacementType::class, $emplacement);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the $emplacement object to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($emplacement);
            $entityManager->flush();
    
            // Redirect to a route that shows the newly added emplacement
            return $this->redirectToRoute('app_afficheremp', ['id' => $emplacement->getIdemplacement()]);
        }
    
        // Render the form template with the form object
        return $this->render('emplacement/ajouter_emp.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/afficheremp', name: 'app_afficheremp')]
    public function show(Request $request, EmplacementRepository $emplacementRepository): Response
    {
        $searchTerm = $request->query->get('search');
        $sortBy = $request->query->get('sort_by', 'disponibilite'); // Default sort by disponibilite
        $emplacements = $emplacementRepository->findBySearchTermAndSort($searchTerm, $sortBy);
        
        return $this->render('emplacement/affiche_emp.html.twig', [
            'emplacement' => $emplacements,
            'searchTerm' => $searchTerm,
        ]);
    }

    
    #[Route('/Emplacement/Supprimer/{id_emplacement}', name: 'app_emplacement_supprimer')]
public function delete($id_emplacement, EmplacementRepository $emplacementRepository, EntityManagerInterface $entityManager): Response
{

    $emplacement = $emplacementRepository->find($id_emplacement);
        $entityManager->remove($emplacement);
        $entityManager->flush();


    return $this->redirectToRoute('app_afficheremp');
}
 
#[Route('/{idemplacement}/edit_emp', name: 'app_emp_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Emplacement $emplacement, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(EmplacementType::class, $emplacement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_afficheremp', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('emplacement/modifier_emp.html.twig', [
        'emplacement' => $emplacement,
        'form' => $form,
    ]);
}


}    
