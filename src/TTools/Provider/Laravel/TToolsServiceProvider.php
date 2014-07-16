<?php

namespace TTools\Provider\Laravel;

use TTools\App;
use TTools\Provider\Laravel\LaravelStorageSession;
use TTools\Provider\Laravel\LaravelRequestProvider;
use Illuminate\Support\ServiceProvider;

/**
 * TTools Laravel Service Provider
 *
 * @author Aran Wilkinson <aran@aranw.net>
 * @package TTools\Provider\Laravel
 */
class TToolsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $app = $this->app;

        $app['ttools'] = $app->share(function ($app) {

            $storage_provider = null;
            $request_provider = null;

            $config = array(
                'consumer_key'        => $app['config']['ttools.consumer_key'],
                'consumer_secret'     => $app['config']['ttools.consumer_secret'],
                'access_token'        => isset($app['config']['ttools.access_token']) ? $app['config']['ttools.access_token'] : null,
                'access_token_secret' => isset($app['config']['ttools.access_token_secret']) ? $app['config']['ttools.access_token_secret'] : null,
                'auth_method'         => isset($app['config']['ttools.auth_method']) ? $app['config']['ttools.auth_method'] : null,
            );

            if (isset($app['session'])) {
                $storage_provider = new LaravelStorageSession($app['session']);
            }

            $request_provider = new LaravelRequestProvider($app['request']);

            return new App($config, $storage_provider, $request_provider);
        });

    }

    public function boot()
    {
    }
}