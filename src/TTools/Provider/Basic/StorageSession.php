<?php

namespace TTools\Provider\Basic;

use TTools\Provider\StorageProviderInterface;

class StorageSession implements StorageProviderInterface {

    const KEY_TOKEN  = 'ttools_last_token';
    const KEY_SECRET = 'ttools_last_secret';
    const KEY_USER   = 'ttools_logged_user';

    function storeRequestSecret($request_token, $request_secret)
    {
        $_SESSION[self::KEY_TOKEN] = $request_token;
        $_SESSION[self::KEY_SECRET] = $request_secret;
    }

    function getRequestSecret()
    {
        if (isset($_SESSION[self::KEY_SECRET]))
            return $_SESSION[self::KEY_SECRET];
    }

    function storeLoggedUser($logged_user)
    {
        $_SESSION[self::KEY_USER] = serialize($logged_user);
    }

    function getLoggedUser()
    {
        if (isset($_SESSION[self::KEY_USER]))
            return unserialize($_SESSION[self::KEY_USER]);
    }

    function logout()
    {
        unset($_SESSION[self::KEY_USER]);
        unset($_SESSION[self::KEY_TOKEN]);
        unset($_SESSION[self::KEY_SECRET]);
    }
}