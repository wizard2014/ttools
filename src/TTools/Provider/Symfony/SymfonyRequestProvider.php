<?php

namespace TTools\Provider\Symfony;

use TTools\Provider\RequestProvider;

class SymfonyRequestProvider extends RequestProvider{

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