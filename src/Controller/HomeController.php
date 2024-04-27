<?php
 
 namespace App\Controller;
 use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface; // Correct namespace

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;

class HomeController extends AbstractController
{
    private $security;
    private $session;
    private $passwordEncoder;

    public function __construct(Security $security, SessionInterface $session, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->security = $security;
        $this->session = $session;
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route('/login', name: 'login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // Récupérez l'utilisateur connecté
        $user = $this->security->getUser();

        // Récupérer l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Dernier nom d'utilisateur entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // Vérifier si le formulaire de connexion a été soumis
        if ($request->isMethod('POST')) {
            // Récupérer les données soumises par le formulaire
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            // Expression régulière pour valider le format de l'e-mail
            $emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

            // Validation du format de l'e-mail
            if (!preg_match($emailPattern, $email)) {
                // Adresse email invalide
                $this->addFlash('error', 'Adresse e-mail invalide.');
                return $this->redirectToRoute('login');
            }

            // Validation de la longueur minimale du mot de passe
            if (strlen($password) < 8) {
                // Mot de passe trop court
                $this->addFlash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
                return $this->redirectToRoute('login');
            }

            // Vérification si l'e-mail existe dans la base de données
            $userRepository = $this->getDoctrine()->getRepository(Utilisateur::class);
            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                // L'e-mail n'existe pas dans la base de données
                $this->addFlash('error', 'Aucun utilisateur trouvé avec cet e-mail. Veuillez créer un compte.');
                return $this->redirectToRoute('user_new'); // Remplacez 'new_user' par le nom de votre route pour la page de création de compte
            }
                 // Store the user in the session
                 $this->get('session')->set('user', $user);


            // Logique de validation du mot de passe
            // Vous pouvez ajouter des contrôles supplémentaires sur le mot de passe ici
            // Après la connexion réussie, enregistrez les informations de l'utilisateur dans la session
            $this->session->set('user_id', $user->getId());
            $this->session->set('user_email', $user->getEmail());

            // Si les contrôles sont réussis, vous pouvez rediriger l'utilisateur vers la page d'accueil ou une autre page appropriée
            return $this->redirectToRoute('home_client');
        }
        return $this->render('home/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
    #[Route('/homeClient', name: 'home_client')]
    public function homeClient(): Response
    {
        
        // Logique de votre page homeClient.html.twig
        return $this->render('home/homeClient.html.twig');
    }
    #[Route('/base-front', name: 'base_front')]
    public function baseFront(): Response
    {
        // Render your basefront.html.twig template
        return $this->render('basefront.html.twig');
    }

    #[Route('/logout', name: 'logout')]
    public function logout()
    {
        // The logic here is empty. Symfony's security component will intercept this route and handle the logout.
    }

}
