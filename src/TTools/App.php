<?php

namespace TTools;

use \TTools\TTools;

class App implements TToolsApp {

    public $ttools;
    private $default_storage;
    private $current_user;

    const TT_STORAGE_SESSION = 1;
    const TT_STORAGE_FILE    = 2;
    const TT_STORAGE_DB      = 3;

	public function __construct(array $config, int $storage = null) 
	{
        $this->ttools = new TTools($config);
        if ($storage !== null)
            $this->default_storage = $storage;
        else
            $this->default_storage = self::TT_STORAGE_SESSION;

        $this->current_user = $this->getUser();
    }

    public function isLogged()
    {
        return count($this->current_user);
    }

    public function getLoginUrl()
    {
        $result = $this->ttools->getAuthorizeUrl();
        /* we need to store the token secret for the next request, 
         * after a user has authorized your app         
         */
        $_SESSION['request_secret'] = $result['secret'];
       
        $auth_link = $result['auth_url'];
    }

    public function getCurrentUser()
    {
        return $this->current_user;
    }

    public function getLastReqInfo()
    {
        return $this->ttools->getLastReqInfo();
    }

    public function getUser()
    {
        if ($this->ttools->getState()) {
            return $this->ttools->makeRequest('/' . TTools::API_VERSION .'/account/verify_credentials.json');
        } else {
                /* check if there is a user comming from auth page on twitter */
            $user = array();
            if (!empty($_REQUEST['oauth_token'])) {

                $secret = $this->getRequestSecret();
                $user = $this->ttools->getAccessTokens($_REQUEST['oauth_token'], $secret);
              
                if (!empty($user['access_token'])) {
                    /* congratulations, you have successfully logged in */
                    $this->current_user = $user;
                }
            }
        }
        return $user;
    }

	function storeRequestSecret($request_token, $request_secret)
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

    function getRequestSecret()
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