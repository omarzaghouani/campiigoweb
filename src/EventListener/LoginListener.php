<?php

namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginListener implements EventSubscriberInterface
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [LoginSuccessEvent::class => 'onLoginSuccess'];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $token = $event->getAuthenticationToken();
        $user = $token->getUser();
        $token = $event->getAuthenticationToken();

        if ($user instanceof User) {
            // Check the role of the user
            $roles = $user->getRoles();

            // If the user is ROLE_ADMIN, redirect to user_index
            if (in_array('ROLE_ADMIN', $roles, true)) {
                $response = new RedirectResponse($this->urlGenerator->generate('user_index'));
            } 
            // If the user is ROLE_USER, redirect to home_client
            elseif (in_array('ROLE_USER', $roles, true)) {
                $response = new RedirectResponse($this->urlGenerator->generate('home_client'));
            } 
            // If the user has other roles or no roles, redirect to a default route (e.g., homepage)
            else {
                $response = new RedirectResponse($this->urlGenerator->generate('home_client'));
            }
        } 
        // Handle cases where $user is not an instance of User
        else {
            // Redirect to a default route (e.g., homepage)
            $response = new RedirectResponse($this->urlGenerator->generate('client'));
        }

        $event->setResponse($response);
    }
}
