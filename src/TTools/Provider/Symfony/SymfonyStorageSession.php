<?php

namespace TTools\Provider\Symfony;

use TTools\Provider\StorageProviderInterface;

class SymfonyStorageSession implements StorageProviderInterface
{
    private $session;

    const KEY_TOKEN  = 'ttools_last_token';
    const KEY_SECRET = 'ttools_last_secret';
    const KEY_USER   = 'ttools_logged_user';

    function __construct($session)
    {
        $this->session = $session;
    }

    function storeRequestSecret($request_token, $request_secret)
    {
        $this->session->set(self::KEY_TOKEN, $request_token);
        $this->session->set(self::KEY_SECRET, $request_secret);
    }

    function getRequestSecret()
    {
        return $this->session->get(self::KEY_SECRET);
    }

    function storeLoggedUser($logged_user)
    {
        $this->session->set(self::KEY_USER, serialize($logged_user));
    }

    function getLoggedUser()
    {
        return unserialize($this->session->get(self::KEY_USER));
    }

    function logout()
    {
        $this->session->set(self::KEY_USER, null);
        $this->session->set(self::KEY_TOKEN, null);
        $this->session->set(self::KEY_SECRET, null);
    }
}