<?php

namespace Amamarul\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Amamarul\Modules\Contracts\RepositoryInterface;
use Amamarul\Modules\Repository;

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
