<?php

namespace TTools;

interface StorageProvider {

    function storeRequestSecret($request_token, $request_secret);

    function getRequestSecret();

    function storeLoggedUser($logged_user);

    function getLoggedUser();
}