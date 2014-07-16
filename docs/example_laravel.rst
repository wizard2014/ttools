TTools with Laravel
===================

This is a very basic example usage for setting TTools up with a Laravel Application.

In order to use TTools on Laravel, you need to add the TTools Laravel Service Provider to your Laravel config this'll instruct
Laravel on runtime to bind the TTools Application object into the Laravel IoC.

Configuration
-------------

You'll need to add your Twitter credentials into your applications config files.

**/app/config/ttools.php** ::

    <?php

    return array(
        // Required Options
        'consumer_key' => '',
        'consumer_secret' => '',
        // Optional
        'access_token' => '',
        'access_token_secret' => '',
        'auth_method' => ''
    );

**/app/config/app.php** ::

Add `'TTools\Provider\Laravel\TToolsServiceProvider'` to the providers array

Optional

Add `'TTools' => 'TTools\Provider\Laravel\TToolsFacade'` to the aliases array