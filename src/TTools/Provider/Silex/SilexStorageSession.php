<?php

namespace TTools\Provider\Silex;

use TTools\Provider\StorageProvider;

class SilexStorageSession implements StorageProvider {

    private $session;

    function __construct($session)
    {
        $this->session = $session;
    }

	function storeRequestSecret($request_token, $request_secret)
	{
        $this->session->set('last_token', $request_token);
        $this->session->set('last_secret', $request_secret);
	}

    function getRequestSecret()
    {
        return $this->session->get('last_secret');
    }

    function storeLoggedUser($logged_user)
    {
        $this->session->set('logged_user', serialize($logged_user));
    }

    function getLoggedUser()
    {
        return unserialize($this->session->get('logged_user'));
    }

}