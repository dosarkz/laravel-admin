<?php
namespace Dosarkz\LaravelAdmin\Providers;

use Dosarkz\LaravelAdmin\Admin;
use Dosarkz\LaravelAdmin\Commands\InstallCommand;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->publishes([
            __DIR__ . '/config/laravel-admin.php' => config_path('laravel-admin.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__.'/../Install/Migrations');
        $this->loadRoutesFrom(__DIR__.'/../Install/Routes/routes.php');


        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }

        $router->aliasMiddleware('adminAuth', 'Dosarkz\LaravelAdmin\Middleware\AdminAuth');
        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'admin');
        $this->publishes([__DIR__.'/../resources/assets' => public_path('vendor/laravel-admin')], 'laravel-admin-assets');


    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

//        $this->app->register('Intervention\Image\ImageServiceProvider');
//        $this->app->bind('Image', 'Intervention\Image\Facades\Image');


        $this->app->singleton('laravel-admin', function ($app) {
            return new Admin();
        });
    }




}
