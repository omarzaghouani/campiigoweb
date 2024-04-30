<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Centre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Ajoutez cette ligne pour importer FileType
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CentreType;
use App\Form\RechercheType;
use App\Repository\CentreRepository;



class CentreController extends AbstractController
{
    #[Route('/k', name: 'app_centre_back')]
    public function back(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'CentreController',
        ]);
    }



    #[Route('', name: 'app_centre')]
    public function index(): Response
    {
        return $this->render('basefront.html.twig', [
            'controller_name' => 'CentreController',
        ]);
    }



    

    #[Route('/ajouter', name: 'app_ajouter')]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $centre = new Centre();
    $form = $this->createForm(CentreType::class, $centre);
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

            $centre->setPhoto($newFilename);
        }

        $entityManager->persist($centre);
        $entityManager->flush();

        return $this->redirectToRoute('app_afficher');
    }

    return $this->render('centre/ajouter_centre.html.twig', [
        'c' => $centre,
        'form' => $form->createView(),
    ]);
}



#[Route('/centre/modifier/{nom_centre}', name: 'app_modifier')]
public function modifier(Request $request, string $nom_centre, EntityManagerInterface $entityManager): Response
{
    $centre = $entityManager->getRepository(Centre::class)->findOneBy(['nom_centre' => $nom_centre]);

    if (!$centre) {
        throw $this->createNotFoundException('Centre non trouvé');
    }

    $form = $this->createForm(CentreType::class, $centre);
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

            $centre->setPhoto($newFilename);
        }
        $entityManager->flush();

        return $this->redirectToRoute('app_afficher');
    }

    return $this->render('centre/modifier_centre.html.twig', [
        'c' => $centre,
        'form' => $form->createView(),
        
    ]);
}






   
#[Route('/admin/Centre/Supprimer/{id_centre}', name: 'app_centre_supprimer')]
public function delete($id_centre, CentreRepository $centreRepository, EntityManagerInterface $entityManager): Response
{

    $centre = $centreRepository->find($id_centre);
        $entityManager->remove($centre);
        $entityManager->flush();


    return $this->redirectToRoute('app_afficher');
}

#[Route('/afficher', name: 'app_afficher')]
public function recherche(Request $request, CentreRepository $centreRepository)
{
    // Récupérer les valeurs de la ville et du tri à partir du formulaire
    $ville = $request->query->get('ville');
    $tri = $request->query->get('tri');

    // Rechercher les centres dans cette ville
    if ($ville) {
        $resultats = $centreRepository->findBy(['ville' => $ville]);
    } else {
        // Si aucune ville spécifiée, retourner tous les centres
        $resultats = $centreRepository->findAll();
    }

    // Trier les résultats en fonction de l'option de tri
    if ($tri === 'id_asc') {
        usort($resultats, function($a, $b) {
            return strcmp($a->getId_centre(), $b->getId_centre());
        });
    } elseif ($tri === 'id_desc') {
        usort($resultats, function($a, $b) {
            return strcmp($b->getId_centre(), $a->getId_centre());
        });
    }
    if ($tri === 'nom_asc') {
        usort($resultats, function($a, $b) {
            return strcmp($a->getNomCentre(), $b->getNomCentre());
        });
    } elseif ($tri === 'nom_desc') {
        usort($resultats, function($a, $b) {
            return strcmp($b->getNomCentre(), $a->getNomCentre());
        });
    }
    if ($tri === 'mail_asc') {
        usort($resultats, function($a, $b) {
            return strcmp($a->getMail(), $b->getMail());
        });
    } elseif ($tri === 'mail_desc') {
        usort($resultats, function($a, $b) {
            return strcmp($b->getMail(), $a->getMail());
        });
    }

    if ($tri === 'ville_asc') {
        usort($resultats, function($a, $b) {
            return strcmp($a->getVille(), $b->getVille());
        });
    } elseif ($tri === 'ville_desc') {
        usort($resultats, function($a, $b) {
            return strcmp($b->getVille(), $a->getVille());
        });
    }

    if ($tri === 'num_tel_asc') {
        usort($resultats, function($a, $b) {
            return strcmp($a->getNumTel(), $b->getNumTel());
        });
    } elseif ($tri === 'num_tel_desc') {
        usort($resultats, function($a, $b) {
            return strcmp($b->getNumTel(), $a->getNumTel());
        });
    }


    if ($tri === 'nbr_act_asc') {
        usort($resultats, function($a, $b) {
            return strcmp($a->getNbreActivite(), $b->getNbreActivite());
        });
    } elseif ($tri === 'nbr_act_desc') {
        usort($resultats, function($a, $b) {
            return strcmp($b->getNbreActivite(), $a->getNbreActivite());
        });
    }
    // Rendre le template Twig avec le formulaire et les résultats de recherche
    return $this->render('centre/affiche_centre.html.twig', [
        'form' => $this->createForm(RechercheType::class)->createView(),
        'resultats' => $resultats,
    ]);
}

}