<?php

namespace TTools;

use \TTools\TTools;

class App implements TToolsApp {

    private var $ttools;
    private var $default_storage = 1;
    private var $current_user;

    const TT_STORAGE_SESSION = 1;
    const TT_STORAGE_FILE    = 2;
    const TT_STORAGE_DB      = 3;

	public function __construct(array $config, int $storage = null) 
	{
        $this->ttools = new TTools($config);
        if ($storage !== null)
            $this->default_storage = $storage;

        $this->current_user = $this->getUser();
    }

    public function getUser()
    {
        if ($this->ttools->getState()) {
            return $ttools->makeRequest('/' . TTools::API_VERSION .'/users/show.json',array("screen_name"=>$this->current_user));
        } else {
                /* check if there is a user comming from auth page on twitter */
            $user = array();
            if (!empty($_REQUEST['oauth_token'])) {
            
                /* if so, is time to ask for the access tokens.
                 * use the request_secret we stored before
                 * to make the request 
                 * the method returns a user array with access tokens, id and screen name*/
                $secret = $this->getRequestSecret()
                $user = $this->ttools->getAccessTokens($_REQUEST['oauth_token'], $secret);
              
                if (!empty($user['access_token'])) {
                    /* congratulations, you have successfully logged in */
                    $this->current_user = $user;
                }
            }
        }
        return $user;
    }

	private function storeRequestSecret($user_id, $request_secret)
	{
        switch ($this->default_storage) {
            case self::TT_STORAGE_DB:
                break;

            case self::TT_STORAGE_FILE:
                break;

            case self::TT_STORAGE_SESSION:
            default:
                /* SESSION storage is default */
                $_SESSION['last_secret'] = $request_secret; 

        }
	}

    private function getRequestSecret()
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

    private function saveSession($data);
}