<?php

namespace TTools;

class RequestProvider {

    public function get($var)
    {
        if (!empty($_REQUEST[$var]))
            return $_REQUEST[$var];
    } 

}