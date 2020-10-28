<?php

namespace Dedemao\Payjs;

use Dedemao\Payjs\Console\Commands\CreateMenu;
use Dedemao\Payjs\Console\Commands\DeleteMenu;
use Dedemao\Payjs\Console\Commands\Install;
use Dedemao\Payjs\Console\Commands\UnInstall;
use Illuminate\Support\ServiceProvider;

class PayjsServiceProvider extends ServiceProvider
{

    protected $commands = [
        CreateMenu::class,
        DeleteMenu::class,
        Install::class,
        UnInstall::class,
    ];

    public function register()
    {
        $this->commands($this->commands);
    }
    /**
     * {@inheritdoc}
     */
    public function boot(Payjs $extension)
    {
        if (! Payjs::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'payjs');
        }

        if (file_exists($routes = base_path('routes/payjs_admin.php'))) {
            $this->loadRoutesFrom($routes);
        }

        if (file_exists($routes = base_path('routes/payjs_front.php'))) {
            $this->loadRoutesFrom($routes);
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [
                    __DIR__.'/../database' => database_path(),
                    __DIR__.'/../routes' => base_path('routes'),
                    $assets => public_path('vendor/dedemao/laravel-admin-payjs')
                ],
                'laravel-admin-payjs'
            );

        }
    }
}
