<?php

namespace TTools\Provider\Basic;

use TTools\Provider\RequestProviderInterface;

class RequestProvider implements RequestProviderInterface {

    public function get($var)
    {
        if (!empty($_REQUEST[$var]))
            return $_REQUEST[$var];
    }

}