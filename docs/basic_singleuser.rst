Single User Application
=======================

The most basic usage of TTools is for a single user application, like a Twitter Bot. As no authorization is required, you don't need to worry about Request and Session Storage.

For this you will need to have all 4 keys needed to authenticate a request:

- **consumer_key**: The application API Key (also known as *consumer key*)
- **consumer_secret**: The application API Secret (also known as *consumer secret*)
- **access_token**: The User Access Token
- **access_token_secret**: The User Access Token Secret

You can obtain these keys after registering your application on Twitter: https://dev.twitter.com. On the application details page,
there's an option to generate the access tokens for your user.

If you never created a Twitter App before, check this `step-by-step guide <app_creation.html>`_.

Example
-------

The code to create a single user application is very simple, you don't need to deal with Request or Session storage.
Below is an example::

    $config = array(
        'consumer_key'        => 'APP_CONSUMER_KEY',
        'consumer_secret'     => 'APP_CONSUMER_SECRET',
        'access_token'        => 'USER_TOKEN',
        'access_token_secret' => 'USER_TOKEN_SECRET',
    );

    $app = new \TTools\App($config);

    echo "static user credentials:<br/><pre>";
    print_r($app->getCredentials());

    echo "</pre><br>last req info:<br>";
    print_r($app->getLastReqInfo());


For a more real-life example, check the Great Zoltar application (a Twitter auto-reply bot):
https://github.com/erikaheidi/greatzoltar
