TTools with Symfony
===================

This is a very basic example usage for authorizing users from a Symfony Application.
TTools comes with Symfony **Session** and **Request** support.

Authorizing users
-----------------

In order to use TTools on Symfony, you need to specify the Symfony providers when creating the TTools Application object.
A good practice is defining credentials as parameters in your application::

            #parameters.yml
            ttools.credentials:
                consumer_key: 'APP_CONSUMER_KEY'
                consumer_secret: 'APP_CONSUMER_SECRET'
                access_token: 'USER_ACCESS_TOKEN'
                access_token_secret: 'USER_ACCESS_TOKEN_SECRET'

then, instantiate the TTools object using the Symfony providers (the method below should go in a controller)::

    use TTools\Provider\Symfony\SymfonyRequestProvider;
    use TTools\Provider\Symfony\SymfonyStorageSession;

    (...)

    public function twitterAction(Request $request)
    {
        $sp = new SymfonyStorageSession($this->get('session'));
        $rp = new SymfonyRequestProvider($this->get('request'));
        $config = $this->container->getParameter('ttools.credentials');
        $twitter = new \TTools\App($config, $sp, $rp);

        if ($twitter->isLogged()) {
            $user = $twitter->getCurrentUser();

            return new Response("Logged in as: " . $user['screen_name']);

        } else {
            $login_url = $twitter->getLoginUrl();

            return new RedirectResponse($login_url);
        }
    }

