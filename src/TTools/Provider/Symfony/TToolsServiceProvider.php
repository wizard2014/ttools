<?php
/**
 * Helper Class to share a TTools Service on the application container
 */

namespace TTools\Provider\Symfony;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use TTools\Provider\Symfony\SymfonyRequestProvider;
use TTools\Provider\Symfony\SymfonyStorageSession;
use TTools\App;

class TToolsServiceProvider {

    private $ttools;

    /**
     * @param array $config
     * @param SessionInterface $session
     * @param RequestStack $requestStack
     *
     */
    public function __construct(array $config = [], SessionInterface $session, RequestStack $requestStack)
    {
        $sp = new SymfonyStorageSession($session);
        $request = $requestStack->getCurrentRequest();

        $rp = $request ? new SymfonyRequestProvider($request) : null;

        $this->ttools = new App($config, $sp, $rp);
    }

    /**
     * @return \TTools\App
     */
    public function getManager()
    {
        return $this->ttools;
    }

    public function isLogged()
    {
        return $this->ttools->isLogged();
    }

    public function getUser()
    {
        return $this->ttools->getCurrentUser();
    }
} 