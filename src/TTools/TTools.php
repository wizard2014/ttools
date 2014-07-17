<?php
/**
 * TTools Class
 */
namespace TTools;

class TTools
{
    private $consumer_key;
    private $consumer_secret;
    private $access_token;
    private $access_token_secret;
    private $auth_method;

    private $last_req_info;

    const VERSION              = '2.0-dev';
    const API_VERSION          = '1.1';
    const REQ_BASE             = 'https://api.twitter.com';
    const REQUEST_PATH         = '/oauth/request_token';
    const ACCESS_PATH          = '/oauth/access_token';

    const AUTH_METHOD_AUTHORIZE        = '/oauth/authorize';
    const AUTH_METHOD_AUTHENTICATE     = '/oauth/authenticate';

    public function __construct(array $config)
    {
        $this->consumer_key        = $config['consumer_key'];
        $this->consumer_secret     = $config['consumer_secret'];
        $this->access_token        = null;
        $this->access_token_secret = null;
        $this->last_req_info       = array();

        $this->auth_method = isset($config['auth_method']) ? $config['auth_method'] : self::AUTH_METHOD_AUTHORIZE;

        if (isset($config['access_token']) && isset($config['access_token_secret'])) {
            $this->access_token        = $config['access_token'];
            $this->access_token_secret = $config['access_token_secret'];
        }

    }

    /**
     * Sets the current user tokens
     * @param string $at  Access Token
     * @param string $ats Access Token Secret
     */
    public function setUserTokens($at, $ats)
    {
        $this->access_token        = $at;
        $this->access_token_secret = $ats;
    }

    /**
     * Gets the current user tokens
     * @return array Returns an array where the first position is the Access Token and the second position is the Access Token Secret
     */
    public function getUserTokens()
    {
        return array($this->access_token, $this->access_token_secret);
    }

    /**
     * Gets the authorization url.
     *
     * @param array $params Custom parameters passed to the OAuth request
     * @return array If successful, returns an array with 'auth_url', 'secret' and 'token'; otherwise, returns array with error code and message.
     */
    public function getAuthorizeUrl(array $params = array())
    {
        $result = $this->OAuthRequest(self::REQUEST_PATH, $params);

        if ($result->getCode() == 200) {
            $tokens = $this->parseResponse($result->getResponse());

            return array(
                'auth_url' => self::REQ_BASE . $this->auth_method . '?oauth_token=' . $tokens['oauth_token'],
                'secret'   => $tokens['oauth_token_secret'],
                'token'    => $tokens['oauth_token']
            );

        }

        return $this->handleError($result);
    }

    /**
     * Makes a Request to get the user access tokens
     * @param string $request_token
     * @param string $request_secret
     * @param string $oauth_verifier
     *
     * @return array Returns an array with the user data and tokens, or an error array with code and message
     */
    public function getAccessTokens($request_token, $request_secret, $oauth_verifier)
    {
        $this->setUserTokens($request_token, $request_secret);

        $result = $this->OAuthRequest(self::ACCESS_PATH, array('oauth_verifier' => $oauth_verifier), 'POST');

        if ($result->getCode() == 200) {

            $tokens = $this->parseResponse($result->getResponse());
            $this->setUserTokens($tokens['oauth_token'], $tokens['oauth_token_secret']);

            return array(
                'access_token'        => $this->access_token,
                'access_token_secret' => $this->access_token_secret,
                'screen_name'         => $tokens['screen_name'],
                'user_id'             => $tokens['user_id'],
            );
        } else {
            return $this->handleError($result);
        }
    }

    private function parseResponse($string)
    {
        $r = array();
        foreach (explode('&', $string) as $param) {
            $pair = explode('=', $param, 2);
            if (count($pair) != 2)
                continue;
            $r[urldecode($pair[0])] = urldecode($pair[1]);
        }
        return $r;
    }

    /**
     * @param $url
     * @param array $params
     * @param string $method
     * @param null $callback
     * @param bool $multipart
     * @param array $overwrite_config
     * @return array|OAuthResponse
     */
    private function OAuthRequest($url, $params = array(), $method = 'GET', $callback = null, $multipart = false, $overwrite_config = array())
    {
        $oauth = new OAuthRequest($this->consumer_key, $this->consumer_secret, $this->access_token, $this->access_token_secret);
        $oauth->setUserAgent('ttools ' . self::VERSION . ' - erikaheidi.github.com/ttools');

        $response = $oauth->request($method, $url, $params, $multipart);

        if (!$response)
            return array('error' => "666");

        $this->last_req_info = array (
            'path'          => $url,
            'response_code' => $response->getCode(),
        );

        if ($callback !== null) {
            call_user_func($callback, $response->getCode(), $response->getResponse());
        }

        return $response;
    }

    /**
     * @param $url
     * @param array $params
     * @param string $method
     * @param bool $multipart
     * @param array $overwrite_config
     * @return array|mixed
     */
    public function makeRequest($url, $params = array(), $method = 'GET', $multipart = false, $overwrite_config = array())
    {
        $result = $this->OAuthRequest($url, $params, $method, null, $multipart, $overwrite_config);
        if ($result->getCode() == 200) {
            return json_decode($result->getResponse(), 1);
        }

        return $this->handleError($result);
    }

    /**
     * @param OAuthResponse $response
     * @return array
     */
    public function handleError(OAuthResponse $response)
    {

        return array(
            'error'         => $response->getCode(),
            'error_message' => $response->getError(),
            'raw_response'  => $response->getResponse()

        );
    }

    /**
     * @return array
     */
    public function getLastReqInfo()
    {
        return $this->last_req_info;
    }

}
