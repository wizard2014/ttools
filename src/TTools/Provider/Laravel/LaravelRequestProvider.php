<?php

namespace TTools\Provider\Laravel;

use TTools\Provider\RequestProviderInterface;

class LaravelRequestProvider implements RequestProviderInterface
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