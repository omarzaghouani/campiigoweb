<?php

namespace App\Controller;
use App\Entity\Activite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use Dompdf\Dompdf;




class ActiviteController extends AbstractController
{
    #[Route('/activite', name: 'app_activite')]
    public function index(): Response
    {
        return $this->render('activite/index.html.twig', [
            'controller_name' => 'ActiviteController',
        ]);
    }

    

    #[Route('/afficher_act', name: 'app_afficher_act')]
    public function affiche(Request $request, ActiviteRepository $activiteRepository): Response
    {
        // Récupérer le terme de recherche depuis la requête GET
        $searchTerm = $request->query->get('centre');

        // Si un terme de recherche est fourni, effectuer la recherche
        if ($searchTerm) {
            $activite = $activiteRepository->findBySearchTerm($searchTerm);
        } else {
            // Sinon, récupérer toutes les activités
            $activite = $activiteRepository->findAll();
        }

        return $this->render('activite/affiche_activite.html.twig', ['a' => $activite]);
    }





    #[Route('/afficher_act_front', name: 'app_afficher_front_act')]
    public function affiche_front(Request $request, ActiviteRepository $activiteRepository): Response
    {
        // Récupérer le terme de recherche depuis la requête GET
        $searchTerm = $request->query->get('centre');

        // Si un terme de recherche est fourni, effectuer la recherche
        if ($searchTerm) {
            $activite = $activiteRepository->findBySearchTerm($searchTerm);
        } else {
            // Sinon, récupérer toutes les activités
            $activite = $activiteRepository->findAll();
        }

        return $this->render('activite/afficher_activite_front.html.twig', ['a' => $activite]);
    }

    

    #[Route('/ajouter_act', name: 'app_ajouter_act')]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $activite = new Activite();

    $form = $this->createForm(ActiviteType::class, $activite);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
       
        $entityManager->persist($activite);
        $entityManager->flush();

        return $this->redirectToRoute('app_afficher_act');
    }

    return $this->render('activite/ajouter_activite.html.twig', [
        'a' => $activite,
        'form' => $form->createView(),
    ]);
}



#[Route('/act/modifier/{Nom_activite}', name: 'app_modifier_act')]
public function modifier(Request $request, string $Nom_activite, EntityManagerInterface $entityManager): Response
{
    $activite = $entityManager->getRepository(Activite::class)->findOneBy(['Nom_activite' => $Nom_activite]);

    if (!$activite) {
        throw $this->createNotFoundException('activite non trouvé');
    }

    $form = $this->createForm(ActiviteType::class, $activite);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
    
        $entityManager->flush();

        return $this->redirectToRoute('app_afficher_act');
    }

    return $this->render('activite/modifier_activite.html.twig', [
        'a' => $activite,
        'form' => $form->createView(),
        
    ]);
}


   
#[Route('/admin/activite/Supprimer/{id_activite}', name: 'app_act_supprimer')]
public function delete($id_activite, ActiviteRepository $activiteRepository, EntityManagerInterface $entityManager): Response
{

    $activite = $activiteRepository->find($id_activite);
        $entityManager->remove($activite);
        $entityManager->flush();


    return $this->redirectToRoute('app_afficher_act');
}


#[Route('/Activite/{Nom_activite}/pdf', name: 'app_act_pdf', methods: ['GET'])]
public function generatePdf(Activite $activite): Response
{
    // Load the PDF template
    $html = $this->renderView('activite/PDF_activite.html.twig', [
        'activite' => $activite,
    ]);

    // Generate PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $dompdf->set_option('isHtml5ParserEnabled', true);
    $dompdf->set_option('isPhpEnabled', true);
    $dompdf->set_option('isRemoteEnabled', true);
    // Output the generated PDF
    return new Response(
        $dompdf->output(),
        Response::HTTP_OK,
        ['Content-Type' => 'application/pdf']
    );
}

}

