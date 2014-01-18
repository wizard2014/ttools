<?php
/**
 * TTools Twitter Application Class
 */
namespace TTools;

use TTools\TTools;
use TTools\Provider\StorageProviderInterface;
use TTools\Provider\RequestProviderInterface;
use TTools\Provider\Basic\StorageSession;
use TTools\Provider\Basic\RequestProvider;

class App {

    /** @var \TTools\Provider\StorageProviderInterface  */
    private $storage;

    /** @var \TTools\Provider\RequestProviderInterface  */
    private $request;

    /** @var \TTools\TTools  */
    private $ttools;

    /** @var array */
    private $current_user;

    /** @var bool Get complete user credentials by default */
    private $strip_credentials;

    /**
     * @param array $config
     * @param StorageProviderInterface $sp
     * @param RequestProviderInterface $rp
     */
    public function __construct(array $config, StorageProviderInterface $sp = null, RequestProviderInterface $rp = null)
    {
        $this->storage = $sp ?: new StorageSession();
        $this->request = $rp ?: new RequestProvider();

        if (!isset($config['access_token'])) {
            /* check if theres a logged user in session */
            $user = $this->storage->getLoggedUser();
            if (!empty($user['access_token'])) {
                $config['access_token']        = $user['access_token'];
                $config['access_token_secret'] = $user['access_token_secret'];
            }
        }

        $this->strip_credentials = isset($config['strip_credentials']) ? $config['strip_credentials'] : false;
        $this->ttools = new TTools($config);

        $this->current_user = $this->getUser();
    }

    /**
     * @return int
     */
    public function isLogged()
    {
        return $this->current_user !== null;
    }

    /**
     * @return mixed
     */
    public function getLoginUrl()
    {
        $result = $this->ttools->getAuthorizeUrl();
        $this->storage->storeRequestSecret($result['token'], $result['secret']);
       
        return $result['auth_url'];
    }

    /**
     * @return array|mixed
     */
    public function getCurrentUser()
    {
        return $this->current_user;
    }

    /**
     * @return array
     */
    public function getLastReqInfo()
    {
        return $this->ttools->getLastReqInfo();
    }

    /**
     * @return array|mixed
     */
    public function getUser()
    {
        if (! $this->ttools->getState()) {
            $oauth_verifier = $this->request->get('oauth_verifier');
            if ($oauth_verifier !== null) {

                $secret = $this->storage->getRequestSecret();
                $oauth_token = $this->request->get('oauth_token');
                $credentials = $this->ttools->getAccessTokens($oauth_token, $secret, $oauth_verifier);

                if (!empty($credentials['access_token'])) {
                    if (!$this->strip_credentials) {
                        $credentials = array_merge($credentials, $this->getCredentials());
                    }
                    $this->storage->storeLoggedUser($credentials);
                }
            }
        }

        return $this->getLoggedUser();
    }

    public function getLoggedUser()
    {
        $credentials = $this->storage->getLoggedUser();

        if (is_array($credentials)) {
            return new User($credentials);
        }

        return null;
    }

    /**
     * @return array
     */
    public function getUserTokens()
    {
        return $this->ttools->getUserTokens();
    }

    /**
     * Performs a GET request to the Twitter API
     * @param $path
     * @param array $params
     * @param array $config
     * @return array|mixed
     */
    public function get($path, $params = array(), $config = array())
    {
        return $this->ttools->makeRequest('/' . TTools::API_VERSION . $path, $params, 'GET', $config);
    }

    /**
     * Performs a POST request to the Twitter API
     * @param $path
     * @param $params
     * @param bool $multipart
     * @param array $config
     * @return array|mixed
     */
    public function post($path, $params, $multipart = false, $config = array())
    {
        return $this->ttools->makeRequest('/' . TTools::API_VERSION . $path, $params, 'POST', $multipart, $config);
    }

    /**
     * Gets a user profile. If you want to get another user's profile, you just need to provide an array with either
     * the 'user_id' or the 'screen_name' .
     *
     * Example:
     * <code>
     * $profile = $ttools->getProfile(array('screen_name' => 'erikaheidi));
     * </code>
     *
     * @param array $params The twitter user ID or screen_name(optional)
     * @return array|mixed
     *
     */
    public function getProfile(array $params = null)
    {
        if (count($params)) {
            return $this->get('/users/show.json', $params);
        }

        return $this->getCredentials();
    }

    /**
     * Get logged user profile
     * @return array|mixed
     */
    public function getCredentials()
    {
        return $this->get('/account/verify_credentials.json',
            array('include_entities' => 'false', 'skip_status' => 'true')
        );
    }

    /**
     * @return array|mixed
     */
    public function getRemainingCalls()
    {
        return $this->get('/account/rate_limit_status.json');
    }

    /**
     * @param int $limit
     * @return array|mixed
     */
    public function getTimeline($limit = 10)
    {
        return $this->get('/statuses/home_timeline.json',array("count"=>$limit));
    }

    /**
     * Gets a user timeline (tweets posted by a user). Defaults to the current user timeline
     * @param null $user_id      If specified, will try to get this user id tweets
     * @param null $screen_name  If specified, will try to get this user screen_name tweets
     * @param int $limit
     * @return array|mixed
     */
    public function getUserTimeline($user_id = null, $screen_name = null, $limit = 10)
    {
        return $this->get(
            '/statuses/user_timeline.json',
            array(
                "count"       => $limit,
                "user_id"     => $user_id,
                "screen_name" => $screen_name,
            )
        );
    }

    /**
     * Gets current user mentions
     * @param int $limit
     * @return array|mixed
     */
    public function getMentions($limit = 10)
    {
        return $this->get('/statuses/mentions_timeline.json',array("count"=>$limit));
    }

    /**
     * Gets current user favorites
     * @param int $limit
     * @return array|mixed
     */
    public function getFavorites($limit = 10)
    {
        return $this->get('/favorites/list.json',array("count"=>$limit));
    }

    /**
     * Gets a specific Tweet
     * @param string $tweet_id The tweet id
     * @return array|mixed
     */
    public function getTweet($tweet_id)
    {
        return $this->get('/statuses/show/' . $tweet_id . '.json');
    }

    /**
     * Post a tweet
     * @param string $message   The tweet message
     * @param null $in_reply_to A tweet id that this post is replying to (default null)
     * @return array|mixed
     */
    public function update($message, $in_reply_to = null)
    {
        $message = strip_tags($message);

        return $this->post('/statuses/update.json', array(
            'status'      => $message,
            'in_reply_to_status_id' => $in_reply_to
        ));
    }

    /**
     * Post a tweet with an image embedded
     * @param string $image     Path to the image file
     * @param string $message   Message to be posted with the image
     * @param null $in_reply_to A tweet id that this post is replying to (default null)
     * @return array|mixed
     */
    public function updateWithMedia($image, $message, $in_reply_to = null)
    {
        $meta = getimagesize($image);
        $message = strip_tags($message);

        return $this->post('/statuses/update_with_media.json', array(
            'status'  => $message,
            'media[]' => '@' . $image . ';type=' . $meta['mime']
        ), true);
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        return $this->storage->logout();
    }
}