Getting Started
===============

TTools is a clean and straightforward library for dealing with the Twitter API.

Requirements
------------
TTools only requires the php5-curl extension.

Installing
----------

Installation can be easily made through `Composer <https://getcomposer.org/>`_. Add `ttools/ttools <https://packagist.org/packages/ttools/ttools>`_ to your composer.json::

    {
        "require": {
            "ttools/ttools": "2.1.*"
        }
    }

After running `composer install/update` you will be able to use TTools in your application.


Creating your first application
-------------------------------

The easiest way to play around with the Twitter API is creating a single-user application. This means you will not need to authenticate users, as you will be working with fixed user API keys.

The code below shows the authenticated user (the owner of the provided API keys) timeline::

    <?php
    require( __DIR__ . '/../vendor/autoload.php');

    $config = array(
        'consumer_key'        => 'API_KEY',
        'consumer_secret'     => 'API_SECRET',
        'access_token'        => 'USER_TOKEN',
        'access_token_secret' => 'USER_TOKEN_SECRET',
    );

    $app = new \TTools\App($config);
    $timeline = $app->getTimeline();
    print_r($timeline);

For this example to work, you need to have the 4 keys necessary to authenticate requests.

To get your keys, you need to first register your application at http://dev.twitter.com . On the application details page, there's an option to generate your access tokens;
use them along with the application tokens (`API Key` and `API Secret`) in the config array (as shown in the example above) and you will have a ready-to-request Twitter App.

If you never created a Twitter App before, check this `step-by-step guide <app_creation.html>`_.