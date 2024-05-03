<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurFormType;
use App\Form\UserEditType;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use App\Repository\UserRepository;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Dompdf\Dompdf;

//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class UserController extends AbstractController
{
    

#[Route('/users', name: 'user_index')]
public function index(Request $request, UserRepository $utilisateurRepository, EntityManagerInterface $em): Response
{
    // Fetch the list of users
    $users = $utilisateurRepository->findAll();
    $query = $em->getRepository(User::class)->createQueryBuilder('u')->orderBy('u.id', 'DESC')->getQuery();
    $users = new Paginator($query);
        $currentPage = $request->query->getInt('page', 1);
        $itemsPerPage = 5;
        $users
            ->getQuery()
            ->setFirstResult($itemsPerPage * ($currentPage - 1))
            ->setMaxResults($itemsPerPage);

            $totalItems = count($users);
            $pagesCount = ceil($totalItems / $itemsPerPage);
    // Render the template and pass the users data
    return $this->render('user/back.html.twig', [
        'users' => $users,
        'CurrentPage' => $currentPage,
        'pagesCount' => $pagesCount,
    ]);
}

#[Route('/export-users', name: 'export_users')]
public function generate(Request $request): Response
{
    // Fetch user data from the repository
    $users = $this->getDoctrine()->getRepository(User::class)->findAll();

    // Create a new instance of the Spreadsheet class
    $spreadsheet = new Spreadsheet();

    // Select the active sheet
    $sheet = $spreadsheet->getActiveSheet();

    // Load the logo from the server
    $logoPath = $this->getParameter('kernel.project_dir') . '/public/images/logoCampigo.png';
    $drawing = new Drawing();
    $drawing->setPath($logoPath);
    $drawing->setCoordinates('B1');
    $drawing->setWidth(400); 
    $drawing->setHeight(250); 
    $drawing->setWorksheet($sheet);

    // Adjust column widths
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(20);

    // Set row height for specific rows if necessary
    $sheet->getRowDimension(15)->setRowHeight(30);

    // Set column headers
    $sheet->setCellValue('B15', 'Nom Utilisateur')->getStyle('B15')->applyFromArray(['font' => ['bold' => true]]);
    $sheet->setCellValue('C15', 'Email')->getStyle('C15')->applyFromArray(['font' => ['bold' => true]]);
    $sheet->setCellValue('D15', 'Prenom')->getStyle('D15')->applyFromArray(['font' => ['bold' => true]]);
    $sheet->setCellValue('E15', 'Numéro de Téléphone')->getStyle('E15')->applyFromArray(['font' => ['bold' => true]]);
    // Add more headers if needed

    // Add user data to the spreadsheet
    $row = 15 + 2; // Start from row 17 (after logo and headers)
    foreach ($users as $user) {
        $sheet->setCellValue('B' . $row, $user->getNom());
        $sheet->setCellValue('C' . $row, $user->getEmail());
        $sheet->setCellValue('D' . $row, $user->getPrenom());
        $sheet->setCellValue('E' . $row, $user->getNumerodetelephone());
        // Add more cell values for additional user properties
        $row++;
    }

    // Specify the Excel file name
    $fileName = 'users.xlsx';

    // Save the Excel file to the export directory
    $exportPath = $this->getParameter('kernel.project_dir') . '/public/exports/' . $fileName;
    $writer = new Xlsx($spreadsheet);
    $writer->save($exportPath);

    // Respond to the request with a success message
    return new Response('Fichier Excel généré avec succès : <a href="/exports/' . $fileName . '">Télécharger</a>');
}


// Route for generating a PDF for a specific user
#[Route('/user/{id}/generate-pdf', name: 'user_generate_pdf')]
public function generatePdfForUser(User $user): Response
{
    // Create a new instance of the PDF generation library (e.g., Dompdf)
    $pdf = new Dompdf();

    // Retrieve user data and format it for inclusion in the PDF
    $userData = [
        'Email' => $user->getEmail(),
        'Nom' => $user->getNom(),
        'Prénom' => $user->getPrenom(),
        'Numéro de téléphone' => $user->getNumeroDeTelephone(),
        // Add more user data as needed
    ];

    // Generate HTML content for the PDF
    $html = '<div style="text-align: center;">';
    $html .= '<img src="' . $this->getParameter('kernel.project_dir') . '/public/images/logoCampigo.png" style="margin-bottom: 20px;">'; // Add image
    $html .= '<h1>User Information</h1>';
    $html .= '<ul>';
    foreach ($userData as $key => $value) {
        $html .= '<li><strong>' . $key . ':</strong> ' . $value . '</li>';
    }
    $html .= '</ul>';
    $html .= '</div>';

    // Load HTML content into the PDF generator
    $pdf->loadHtml($html);

    // Set paper size and orientation
    $pdf->setPaper('A4', 'portrait');

    // Render PDF content
    $pdf->render();

    // Set response headers
    $response = new Response($pdf->output());
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'inline; filename="user_info.pdf"');

    return $response;
}



#[Route('/users/{id}/delete', name: 'user_delete', methods: ['POST'])]
public function deleteUser(User $user): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($user);
    $entityManager->flush();

    // Redirect back to the user list page
    return new RedirectResponse($this->generateUrl('user_index'));
}

    #[Route('/profile', name: 'profile')]
    public function profile(): Response
    {
        // Retrieve user information here if needed
        // For example:
        // $user = $this->getUser();

        return $this->render('user/profile.html.twig');
    }

    
#[Route('/ad/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
public function editUser(Request $request, User $user, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(UserEditType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $profilePicture = $form->get('photoD')->getData();
        if ($profilePicture) {
            // If a new profile picture is uploaded
            $newFilename = uniqid() . '.' . $profilePicture->guessExtension();
            $profilePicture->move(
                'uploads',
                $newFilename
            );
            $user->setPhotoD($newFilename);
        } else {
            // Keep the old profile picture
            $user->setPhotoD($user->getPhotoD());
        }

        $entityManager->persist($user);
        $entityManager->flush(); // Save changes to the user

        return new RedirectResponse($this->generateUrl('user_index')); // Redirect to the user list page in the admin backend
    }

    return $this->render('user/backedit.html.twig', [
        'form' => $form->createView(),
    ]);
}

    #[Route('/profile/edit/{id}', name: 'profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserRepository $userRepository, User $user, EntityManagerInterface $entityManager): Response
    {
        //$user = $this->getUser(); // Get the current user
    
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            
            if ($form->get('photoD')->getData()) {
                $profilePicture = $form->get('photoD')->getData();
                if ($profilePicture) {
                // If a new profile picture is uploaded
                $newFilename = uniqid() . '.' . $profilePicture->guessExtension();
                $profilePicture->move(
                    "uploads",
                    $utilisateur->setPhotoD($newFilename)
                );
                $user->setPhotoD($newFilename);
            }
            } else {
                // Keep the old profile picture
                $user->setPhotoD($user->getPhotoD());
            }
    
            //$userRepository->save($user, true);
            $entityManager->persist($user);
            $entityManager->flush(); // Save changes to the user
    
            return $this->redirectToRoute('profile'); // Redirect to the profile page
        }
    
        return $this->render('user/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    


    #[Route('/signin', name: 'signin', methods: ['GET', 'POST'])] 
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurFormType::class, $utilisateur);
        $form->handleRequest($request);
        
        //console.log("test");
        if ($form->isSubmitted() && $form->isValid()) {
            //console.log("test2");
            $photoFile = $form->get('photo_d')->getData();
            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();
                try {
                    $photoFile->move($this->getParameter('uploaded_directory'), $newFilename);
                    $utilisateur->setPhotoD($newFilename);
                } catch (FileException $e) {
                    // Handle the error appropriately
                }
            }
            //console.log("test3");
            // Encode the password and set the roles
            //$utilisateur->setPassword($this->passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword()));
            $utilisateur->setPassword(
                $userPasswordHasher->hashPassword(
                    $utilisateur,
                    $form->get('plainPassword')->getData()
                )
                );
            $utilisateur->setRoles(['ROLE_USER']);  // Set default role, modify as necessary
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/new.html.twig', [
           // 'user' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/users/{id}', name: 'user_show')]
    public function show($id, UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur = $utilisateurRepository->find($id);

        if (!$utilisateur) {
            throw new NotFoundHttpException('No user found for id ' . $id);
        }

        return $this->render('user/show.html.twig', ['user' => $utilisateur]);
    }

    #[Route('/modifier/{id}', name: 'modifier')]
    public function edittest(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo_d')->getData();
            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();
                try {
                    $photoFile->move($this->getParameter('uploaded_directory'), $newFilename);
                    $utilisateur->setPhotoD($newFilename);
                } catch (FileException $e) {
                    // Handle the error appropriately
                }
            }

            // Optionally re-encode the password if it was changed
            // Note: Add a condition to check if the password was updated

            $entityManager->flush();

            return $this->redirectToRoute('user_show', ['id' => $utilisateur->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/supprimer/{id}', name: 'delete')]
    public function delete($id, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $utilisateurRepository->find($id);
        if ($user) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
