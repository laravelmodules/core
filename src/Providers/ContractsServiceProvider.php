<?php

namespace Amamarul\ModulesMaru\Providers;

use Illuminate\Support\ServiceProvider;
use Amamarul\ModulesMaru\Contracts\RepositoryInterface;
use Amamarul\ModulesMaru\Repository;

class ContractsServiceProvider extends ServiceProvider
{
    /**
     * Register some binding.
     */
    public function register()
    {
        $this->app->bind(RepositoryInterface::class, Repository::class);
    }
}
