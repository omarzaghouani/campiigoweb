<?php

namespace App\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\HttpUtils;

class LoginFailureHandler implements AuthenticationFailureHandlerInterface
{
    private $router;
    private $httpUtils;

    public function __construct(RouterInterface $router, HttpUtils $httpUtils)
    {
        $this->router = $router;
        $this->httpUtils = $httpUtils;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // Handle the failure (e.g., redirecting to the login page with an error)
        // ...
    }
}
