<?php

// src/Controller/HomeController.php

namespace App\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Centre;


class HomeController extends AbstractController
{
    
    /*#[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Get the error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
    
        return $this->render('home/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }*/
    
#[Route('/homeClient', name: 'home_client')]
public function homeClient(): Response
{
    
    // Logique de votre page homeClient.html.twig
    return $this->render('home/home.html.twig');
}

#[Route('/afficherreservation', name: 'app_afficherreservation')]
public function show(): Response
{
    $centre = $this->getDoctrine()->getRepository(Centre::class)->findAll();
    return $this->render('emplacement/affiche_emp.html.twig', ['emplacement' => $centre]);
}
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        // Logique de votre page d'accueil
        return $this->render('home/index.html.twig');
    }
    #[Route('/reset-password', name: 'app_forgot_password_request')]
public function request(Request $request, MailerInterface $mailer): Response
{
    // Logique pour traiter le formulaire de demande de réinitialisation du mot de passe
    // Générer un token et envoyer l'e-mail avec ce token
}

#[Route('/reset-password/reset/{token}', name: 'app_reset_password')]
public function reset(string $token, Request $request): Response
{
    // Logique pour réinitialiser le mot de passe avec le token fourni
}
    
    #[Route('/base-front', name: 'base_front')]
    public function baseFront(): Response
    {
        // Render your basefront.html.twig template
        return $this->render('basefront.html.twig');
    }
/*
    #[Route('/logout', name: 'logout')]
    public function logout()
    {
        // The logic here is empty. Symfony's security component will intercept this route and handle the logout.
    }*/
}
