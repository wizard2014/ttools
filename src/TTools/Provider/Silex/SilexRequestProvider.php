<?php

namespace TTools\Provider\Silex;

use TTools\Provider\RequestProvider;

class SilexRequestProvider extends RequestProvider{

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