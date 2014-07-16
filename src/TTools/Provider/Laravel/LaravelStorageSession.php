<?php

namespace TTools\Provider\Laravel;

use TTools\Provider\StorageProviderInterface;

class LaravelStorageSession implements StorageProviderInterface
{
    private $session;

    const KEY_TOKEN  = 'ttools_last_token';
    const KEY_SECRET = 'ttools_last_secret';
    const KEY_USER   = 'ttools_logged_user';

    public function __construct($session)
    {
        $this->session = $session;
    }

    public function storeRequestSecret($request_token, $request_secret)
    {
        $this->session->set(self::KEY_TOKEN, $request_token);
        $this->session->set(self::KEY_SECRET, $request_secret);
    }

    public function getRequestSecret()
    {
        return $this->session->get(self::KEY_SECRET);
    }

    public function storeLoggedUser($logged_user)
    {
        $this->session->set(self::KEY_USER, serialize($logged_user));
    }

    public function getLoggedUser()
    {
        return unserialize($this->session->get(self::KEY_USER));
    }

    public function logout()
    {
        $this->session->set(self::KEY_USER, null);
        $this->session->set(self::KEY_TOKEN, null);
        $this->session->set(self::KEY_SECRET, null);
    }
}