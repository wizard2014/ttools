Basic multiuser app
======

For Authenticating users, you just need your application keys (consumer_key and consumer_secret), but you need to store tokens and deal with the request response.
Luckily, TTools has a very simple workflow and comes with some default providers to deal with this.

You need:

- consumer_key: The application consumer key
- consumer_secret: The application consumer secret

You can obtain this keys after registering your application on Twitter: https://dev.twitter.com 

1. Installation
=====

Add ttools to your composer.json file:

``"ttools/ttools": "dev-master"``



Run ``composer install`` or ``composer update`` to install TTools.

2. Creating the application
=====

This example uses the basic providers that comes with TTools. It will use the default PHP session for storing the request keys and authenticate the user.
After the user has succesfully authenticated, the app will print the user's timeline::

    $config = array(
        'consumer_key'    => 'APP_CONSUMER_KEY',
        'consumer_secret' => 'APP_CONSUMER_SECRET'
    );

    $app = new \TTools\App($config);

    if ($app->isLogged()) {
        $user = $app->getCurrentUser();

        echo "<h3>Logged in as @". $user['screen_name'] . "</h3>";
        echo '<p>[ <a href=".?logout=1">Logout</a> ]';

        $tl = $app->getTimeline();

        echo "<h3>Your Timeline</h3><pre>";
        print_r($tl);
        echo "</pre>";

        echo "Last Req Info:<br/>";
        print_r($app->getLastReqInfo());
    } else {
        $login_url = $app->getLoginUrl();
        echo 'Please log in: <a href="'. $login_url . '">' . $login_url . '</a>';
    }



