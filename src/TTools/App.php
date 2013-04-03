<?php

namespace TTools;

use \TTools\TTools;

class App extends TToolsApp {


	protected function storeRequestSecret($request_token, $request_secret)
	{
        switch ($this->default_storage) {
            case self::TT_STORAGE_DB:
                break;

            case self::TT_STORAGE_FILE:
                break;

            case self::TT_STORAGE_SESSION:
            default:
                /* SESSION storage is default */
                $_SESSION['last_token'] = $request_token;
                $_SESSION['last_secret'] = $request_secret; 

        }
	}

    protected function getRequestSecret()
    {
        switch ($this->default_storage) {
            case self::TT_STORAGE_DB:
                break;

            case self::TT_STORAGE_FILE:
                break;

            case self::TT_STORAGE_SESSION:
            default:
                /* SESSION storage is default */
                return $_SESSION['last_secret'];

        }
    }

}