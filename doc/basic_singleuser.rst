Basic Usage - single user app
======

The most basic usage of TTools is for a single user application, like a Twitter Bot. For this you will need to have all 4 keys needed to authenticate a request:

** consumer_key
** consumer_secret
** access_token
** access_token_secret

1. Installation
=====

Add this requirement to your composer.json file:

<pre>
{
    "require": {
            "ttools/ttools": "dev-master"
    }
}

</pre>

Run ``composer install`` or ``composer update`` to install TTools.

2. Creating the application
=====

The code to create a single user application is very simple, you don't need to deal with Request or Session storage.
Bellow is an example:

<pre>
$config = array(
    'consumer_key'        => 'APP_CONSUMER_KEY',
    'consumer_secret'     => 'APP_CONSUMER_SECRET',
    'access_token'        => 'USER_TOKEN',
    'access_token_secret' => 'USER_TOKEN_SECRET',
);

$app = new \TTools\App($config);

echo "user debug:<br/><pre>";
print_r($app->getCurrentUser());

echo "</pre><br>last req info:<br>";
print_r($app->getLastReqInfo());
</pre>

For a more real-life example, check the Great Zoltar application (a Twitter auto-reply bot)
<a href="https://github.com/erikaheidi/greatzoltar">https://github.com/erikaheidi/greatzoltar</a>
