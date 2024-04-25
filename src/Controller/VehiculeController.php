<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeType;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Ajoutez cette ligne pour importer FileType
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter; // Import ParamConverter

#[Route('/vehicule')]
class VehiculeController extends AbstractController
{
    #[Route('/', name: 'app_vehicule_index', methods: ['GET'])]
    public function index(VehiculeRepository $vehiculeRepository): Response
    {
        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehiculeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_vehicule_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

       
    if ($form->isSubmitted() && $form->isValid()) {
        $photoFile = $form->get('photo')->getData();
        if ($photoFile) {
            $newFilename = uniqid().'.'.$photoFile->guessExtension();

            // Move the file to the desired directory
            try {
                $photoFile->move(
                    $this->getParameter('uploaded_directory'), // Directory where photos should be saved
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle file upload error here...
            }

            $vehicule->setPhoto($newFilename);
        }

        
            $entityManager->persist($vehicule);
            $entityManager->flush();
        
            return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->renderForm('vehicule/new.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }

    #[Route('/{num_v}/edit', name: 'app_vehicule_edit', methods: ['GET', 'POST'])]
    #[ParamConverter('vehicule', class: Vehicule::class, options: ['mapping' => ['num_v' => 'numV']])] // ParamConverter declaration
    public function edit(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $photoFileName = md5(uniqid()).'.'.$photoFile->guessExtension();
                $photoFile->move(
                    $this->getParameter('photos_directory'),
                    $photoFileName
                );
                $vehicule->setPhoto($photoFileName);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vehicule/edit.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }

    #[Route('/{num_v}', name: 'app_vehicule_show', methods: ['GET'])]
    #[ParamConverter('vehicule', class: Vehicule::class, options: ['mapping' => ['num_v' => 'numV']])]
    public function show(Vehicule $vehicule): Response
    {
        return $this->render('vehicule/show.html.twig', [
            'vehicule' => $vehicule,
        ]);
    }
    #[Route('/{num_v}', name: 'app_vehicule_delete', methods: ['POST'])]
    #[ParamConverter('vehicule', class: Vehicule::class, options: ['mapping' => ['num_v' => 'numV']])] // ParamConverter declaration
    public function delete(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicule->getNumV(), $request->request->get('_token'))) {
            $entityManager->remove($vehicule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
    }
}
