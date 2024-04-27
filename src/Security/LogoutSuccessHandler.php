<?php

namespace App\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutSuccessHandler implements LogoutSuccessHandlerInterface, EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onLogoutSuccess(Request $request)
    {
        $this->logger->info('User logged out.');
        return new RedirectResponse($request->getBasePath() . '/login');
    }

    public static function getSubscribedEvents()
    {
        return [
            LogoutEvent::class => 'onLogoutSuccess',
        ];
    }
}
