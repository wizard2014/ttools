Making Requests to the Twitter API
==================================

TTools has a couple helper methods to get you started, but you can make any request you want to the Twitter API.

For the examples below, we are going to consider that you already have an APP configured with authorization tokens.
Have a look at the `basic single-user <basic_singleuser.html>`_ and `basic multi-user <basic_multiuser.html>`_ examples to get started.

Request responses come as **arrays** representing the Twitter API objects (json decoded).

for all the snippets, consider this initialization code (using your keys)::

        $config = array(
        'consumer_key'        => 'APP_CONSUMER_KEY',
        'consumer_secret'     => 'APP_CONSUMER_SECRET',
        'access_token'        => 'USER_TOKEN',
        'access_token_secret' => 'USER_TOKEN_SECRET',
        );

        $app = new \TTools\App($config);

Getting User Timeline
---------------------

using the helper method getTimeline()::

    $timeline = $app->getTimeline();
    print_r($timeline);

making the request manually::

    $timeline = $app->get('/statuses/home_timeline.json',array("count"=> 10));
    print_r($timeline);

Getting User Mentions
---------------------
using the helper method getMentions()::

    $mentions = $app->getMentions();
    print_r($mentions);

making the request manually::

    $mentions = $app->get('/statuses/mentions_timeline.json',array("count"=> 10));
    print_r($mentions);

Posting a Tweet
---------------
using the helper method update()::

    $app->update('This is my awesome tweet update');

manually posting an update::

    $app->post('/statuses/update.json', array('status' => 'This is my awesome tweet update');

Posting an Image
----------------

using the helper method updateWithMedia()::

    $app->updateWithMedia('path_to_my_awesome_image', 'This is my awesome image');




Other
-----
Consult the Twitter API documentation to see what else you can do with the API.
You can use the manual requests with any endpoint using **$app->get** and **$app->post**.