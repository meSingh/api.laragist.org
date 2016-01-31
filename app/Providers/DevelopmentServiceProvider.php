<?php

namespace GistApi\Providers;

use Illuminate\Support\ServiceProvider;

class DevelopmentServiceProvider extends ServiceProvider
{

    /**
     * Development specific ServiceProviders list
     *
     * @var array
     */
    private $providers = [
        // \Barryvdh\Debugbar\ServiceProvider::class,
        \Clockwork\Support\Laravel\ClockworkServiceProvider::class,
    ];


    /**
     * Development specific Alias list
     *
     * @var array
     */
    private $aliases = [
        // 'Debugbar'  => \Barryvdh\Debugbar\Facade::class,
        'Clockwork' => \Clockwork\Support\Laravel\Facade::class,
    ];


    /**
     * Development specific Middlewares list
     *
     * @var array
     */
    private $middlewares = [
        \Clockwork\Support\Laravel\ClockworkMiddleware::class,
    ];



    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
        // Register only in development environment 
        if ($this->app->environment('development')) 
        {
            // Register ServiceProviders
            foreach ($this->providers as $provider) 
            {
                $this->app->register($provider);
            }

            // Register Alias
            foreach ($this->aliases as $key => $alias) 
            {
                $this->app['config']->push('app.aliases', [$key => $alias]);
            }


            // Register Middlewares
            foreach ($this->middlewares as $middleware) 
            {
                $kernel = $this->app->make('Illuminate\Contracts\Http\Kernel');
                $kernel->pushMiddleware($middleware);
            }
        } 

    }
}
