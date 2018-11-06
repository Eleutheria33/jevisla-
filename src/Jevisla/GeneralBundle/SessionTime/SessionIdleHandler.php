<?php

// AppBundle/Handler/SessionIdleHandler.php

namespace Jevisla\GeneralBundle\SessionTime;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SessionIdleHandler
{
    protected $session;
    protected $tokenStorage;
    protected $router;
    protected $maxIdleTime;

    public function __construct(SessionInterface $session, TokenStorageInterface $tokenStorage, RouterInterface $router, $maxIdleTime = 0)
    {
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
        $this->maxIdleTime = $maxIdleTime;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        // On teste si la requête est bien la requête principale (et non une sous-requête)
        if (!$event->isMasterRequest()) {
            return;
        }

        // variable session_max_idle_time : 600 dans app/config/parameters.yml
        if ($this->maxIdleTime > 0) {
            $this->session->start();
            $lapse = time() - $this->session->getMetadataBag()->getLastUsed();
            if ($lapse > $this->maxIdleTime && null !== $this->tokenStorage->getToken()) {
                $this->tokenStorage->setToken(null);
                // Change the route if you are not using FOSUserBundle.
                $event->setResponse(new RedirectResponse($this->router->generate('fos_user_security_login')));
            }
        }
    }
}
