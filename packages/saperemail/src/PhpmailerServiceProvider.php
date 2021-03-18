<?php

namespace Saperemarketing\Phpmailer;

use Illuminate\Support\ServiceProvider;

class PhpmailerServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('saperemail', 'Saperemarketing\Phpmailer\Mailer');
        $config = __DIR__ . '/../config/saperemail.php';
        $this->mergeConfigFrom($config, 'saperemail');
        $this->publishes([__DIR__ . '/../config/saperemail.php' => config_path('saperemail.php')], 'config');
    }
}
