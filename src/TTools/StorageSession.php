<?php

namespace TTools;

use \TTools\TTools;

class StorageSession implements StorageProvider {


	function storeRequestSecret($request_token, $request_secret)
	{
        $_SESSION['last_token'] = $request_token;
        $_SESSION['last_secret'] = $request_secret;
	}

    function getRequestSecret()
    {
        if (isset($_SESSION['last_secret']))
            return $_SESSION['last_secret'];
    }

    function storeLoggedUser($logged_user)
    {
        $_SESSION['logged_user'] = serialize($logged_user);
    }

    function getLoggedUser()
    {
        if (isset($_SESSION['logged_user']))
            return unserialize($_SESSION['logged_user']);
    }

}