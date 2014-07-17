TTools with Silex
=================

This is a very basic example usage for authorizing users from a Silex Application.
TTools has Silex ServiceProvider to take care of the object initialization using the correct Session and Request instances.

Note: If you want to create a new Twitter application using Silex, have a look at the `Twilex Project <http://twilex.readthedocs.org/>`_.

Example App
-----------

The example below is a one-page app that will authenticate the user and show its timeline::

    <?php
    require( __DIR__ . '/../vendor/autoload.php');

    $app = new Silex\Application();
    $app['debug'] = true;

    $app->register(new Silex\Provider\SessionServiceProvider());
    $app->register(new TTools\Provider\Silex\TToolsServiceProvider(), array(
        'ttools.consumer_key'        => 'API_KEY',
        'ttools.consumer_secret'     => 'API_SECRET'
    ));

    $app->get('/', function() use($app) {

        if ($app['ttools']->isLogged()) {
            $user = $app['ttools']->getCurrentUser();

            echo "<h3>Logged in as @". $user['screen_name'] . "</h3>";
            echo '<p>[ <a href=".?logout=1">Logout</a> ]';

            $tl = $app['ttools']->getTimeline();

            echo "<h3>Your Timeline</h3><pre>";
            print_r($tl);
            echo "</pre>";

            echo "Last Req Info:<br/>";
            print_r($app['ttools']->getLastReqInfo());
        } else {
            $login_url = $app['ttools']->getLoginUrl();
            echo 'Please log in: <a href="'. $login_url . '">' . $login_url . '</a>';
        }

        return 'Hello !!!';

    });

    $app->run();

