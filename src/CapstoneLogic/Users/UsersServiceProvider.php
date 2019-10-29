<?php

namespace CapstoneLogic\Users;

use Blade;
use Request;
use Artisan;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class UsersServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfig();
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->registerBladeDirectives();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/users.php', 'users'
        );

        Route::prefix('api/users')
            ->middleware('api')
            ->namespace('CapstoneLogic\Users')
            ->group(__DIR__ . '/../../routes/api.php');

        $this->commands([
            \CapstoneLogic\Users\Console\InitCommand::class,
        ]);
    }

    /**
     * Publish the config file to the application config directory
     */
    public function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/../../config/users.php' => config_path('capstonelogic/users.php'),
        ], 'config');
    }

    public function registerBladeDirectives()
    {
        // role
        Blade::directive('role', function ($expression) {
            return "<?php if (Auth::check() && Auth::user()->hasRole({$expression})): ?>";
        });

        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        // permission
        Blade::directive('permission', function ($expression) {
            return "<?php if (Auth::check() && Auth::user()->hasPermission({$expression})): ?>";
        });

        Blade::directive('endpermission', function () {
            return "<?php endif; ?>";
        });
    }
}
