<?php

namespace TTools\Provider;

interface StorageProviderInterface {

    function storeRequestSecret($request_token, $request_secret);

    function getRequestSecret();

    function storeLoggedUser($logged_user);

    function getLoggedUser();

    function logout();
}