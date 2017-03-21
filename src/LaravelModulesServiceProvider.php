<?php

namespace Amamarul\Modules;

use Illuminate\Support\ServiceProvider;
use Amamarul\Modules\Providers\BootstrapServiceProvider;
use Amamarul\Modules\Providers\ConsoleServiceProvider;
use Amamarul\Modules\Providers\ContractsServiceProvider;
use Amamarul\Modules\Support\Stub;
use Amamarul\Modules\Support\Helper;

class LaravelModulesServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Booting the package.
     */
    public function boot()
    {
        $this->registerNamespaces();

        $this->registerModules();
    }

    /**
     * Register all modules.
     */
    protected function registerModules()
    {
        $this->app->register(BootstrapServiceProvider::class);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerServices();
        $this->setupStubPath();
        $this->registerProviders();
        $this->loadHelpers();
        $this->packages();
    }

    /**
     * Load helpers.
     */
    public function loadHelpers()
    {
        Helper::loadModuleHelpers(__DIR__);
    }

    /**
     * Setup stub path.
     */
    public function setupStubPath()
    {
        $this->app->booted(function ($app) {
            Stub::setBasePath(__DIR__ . '/Commands/stubs');

            if ($app['modules']->config('stubs.enabled') === true) {
                Stub::setBasePath($app['modules']->config('stubs.path'));
            }
        });
    }

    /**
     * Register package's namespaces.
     */
    protected function registerNamespaces()
    {
        $configPath = __DIR__ . '/../config/config.php';
        $this->mergeConfigFrom($configPath, 'modules');
        $this->publishes([
            $configPath => config_path('modules.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     */
    protected function registerServices()
    {
        $this->app->singleton('modules', function ($app) {
            $path = $app['config']->get('modules.paths.modules');

            return new Repository($app, $path);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['modules'];
    }

    /**
     * Register providers.
     */
    protected function registerProviders()
    {
        $this->app->register(ConsoleServiceProvider::class);
        $this->app->register(ContractsServiceProvider::class);
    }

    public function packages()
    {
        /*
         * Package Service Providers...
         */
        $this->app->register(\Arcanedev\LogViewer\LogViewerServiceProvider::class);
        $this->app->register(\Arcanedev\NoCaptcha\NoCaptchaServiceProvider::class);
        $this->app->register(\Collective\Html\HtmlServiceProvider::class);
        $this->app->register(\Creativeorange\Gravatar\GravatarServiceProvider::class);
        $this->app->register(\DaveJamesMiller\Breadcrumbs\ServiceProvider::class);
        $this->app->register(\HieuLe\Active\ActiveServiceProvider::class);
        $this->app->register(\Laravel\Socialite\SocialiteServiceProvider::class);
        $this->app->register(\Laravel\Tinker\TinkerServiceProvider::class);
        $this->app->register(\Yajra\Datatables\DatatablesServiceProvider::class);
        $this->app->register(\Yajra\Datatables\ButtonsServiceProvider::class);

        /*
         * Third Party Aliases
         */
         $loader = \Illuminate\Foundation\AliasLoader::getInstance();
         $loader->alias('Active', \HieuLe\Active\Facades\Active::class);
         $loader->alias('Breadcrumbs', \DaveJamesMiller\Breadcrumbs\Facade::class);
         $loader->alias('Captcha', \Arcanedev\NoCaptcha\Facades\NoCaptcha::class);
         $loader->alias('Form', \Collective\Html\FormFacade::class);
         $loader->alias('Gravatar', \Creativeorange\Gravatar\Facades\Gravatar::class);
         $loader->alias('Html', \Collective\Html\HtmlFacade::class);
         $loader->alias('Socialite', \Laravel\Socialite\Facades\Socialite::class);
         
         /*
          * Sets third party service providers that are only needed on local/testing environments
          */
         if ($this->app->environment() != 'production') {
             /**
              * Loader for registering facades.
              */
             $loader = \Illuminate\Foundation\AliasLoader::getInstance();

             /*
              * Load third party local providers
              */
             $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);

             /*
              * Load third party local aliases
              */
             $loader->alias('Debugbar', \Barryvdh\Debugbar\Facade::class);
         }
    }
}
