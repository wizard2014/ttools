<?php

namespace TTools\Provider\Silex;

use TTools\Provider\RequestProviderInterface;

class SilexRequestProvider implements RequestProviderInterface
{

    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get($var)
    {
        return $this->request->get($var);
    }

}