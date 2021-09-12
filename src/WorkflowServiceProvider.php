<?php

namespace Pitangent\Workflow;

use Illuminate\Support\ServiceProvider;

class WorkflowServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'ows');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'ows');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        $this->commands([
            Commands\TraitMakeCommand::class,
            Commands\ControllerMakeCommand::class,
            Commands\ModelMakeCommand::class,
            Commands\AuthMakeCommand::class,
            Commands\NotificationMakeCommand::class
        ]);

        $this->app['router']->aliasMiddleware('jwt', Http\Middlewares\JWTMiddleware::class);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/workflow.php', 'workflow');

        // Register the service the package provides.
        $this->app->singleton('workflow', function ($app) {
            return new Workflow;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['workflow'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/workflow.php' => config_path('workflow.php'),
        ], 'workflow.config');
    }
}
