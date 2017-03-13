<?php

namespace Amamarul\ModulesMaru\Providers;

use Illuminate\Support\ServiceProvider;
use Amamarul\ModulesMaru\Commands\CommandCommand;
use Amamarul\ModulesMaru\Commands\ControllerCommand;
use Amamarul\ModulesMaru\Commands\DisableCommand;
use Amamarul\ModulesMaru\Commands\DumpCommand;
use Amamarul\ModulesMaru\Commands\EnableCommand;
use Amamarul\ModulesMaru\Commands\GenerateEventCommand;
use Amamarul\ModulesMaru\Commands\GenerateJobCommand;
use Amamarul\ModulesMaru\Commands\GenerateListenerCommand;
use Amamarul\ModulesMaru\Commands\GenerateMailCommand;
use Amamarul\ModulesMaru\Commands\GenerateMiddlewareCommand;
use Amamarul\ModulesMaru\Commands\GenerateNotificationCommand;
use Amamarul\ModulesMaru\Commands\GenerateProviderCommand;
use Amamarul\ModulesMaru\Commands\GenerateRouteProviderCommand;
use Amamarul\ModulesMaru\Commands\InstallCommand;
use Amamarul\ModulesMaru\Commands\ListCommand;
use Amamarul\ModulesMaru\Commands\MakeCommand;
use Amamarul\ModulesMaru\Commands\MakeRequestCommand;
use Amamarul\ModulesMaru\Commands\MigrateCommand;
use Amamarul\ModulesMaru\Commands\MigrateRefreshCommand;
use Amamarul\ModulesMaru\Commands\MigrateResetCommand;
use Amamarul\ModulesMaru\Commands\MigrateRollbackCommand;
use Amamarul\ModulesMaru\Commands\MigrationCommand;
use Amamarul\ModulesMaru\Commands\ModelCommand;
use Amamarul\ModulesMaru\Commands\PublishCommand;
use Amamarul\ModulesMaru\Commands\PublishConfigurationCommand;
use Amamarul\ModulesMaru\Commands\PublishMigrationCommand;
use Amamarul\ModulesMaru\Commands\PublishTranslationCommand;
use Amamarul\ModulesMaru\Commands\SeedCommand;
use Amamarul\ModulesMaru\Commands\SeedMakeCommand;
use Amamarul\ModulesMaru\Commands\SetupCommand;
use Amamarul\ModulesMaru\Commands\UpdateCommand;
use Amamarul\ModulesMaru\Commands\UseCommand;

use Amamarul\ModulesMaru\Commands\GenerateSidebarProviderCommand as SidebarProvider;
use Amamarul\ModulesMaru\Commands\GenerateBreadcrumbsProviderCommand as BreadcrumbsProvider;
use Amamarul\ModulesMaru\Commands\Crud\ControllerCommand as CrudController;
use Amamarul\ModulesMaru\Commands\Crud\MakeDatatableCommand as CrudDatatable;
use Amamarul\ModulesMaru\Commands\Crud\ModelCommand as CrudModel;
use Amamarul\ModulesMaru\Commands\Crud\MakeRequestCommand as CrudRequest;
use Amamarul\ModulesMaru\Commands\Crud\CrudCommand as Crud;
use Amamarul\ModulesMaru\Commands\RouteListCommand as RouteList;
use Amamarul\ModulesMaru\Commands\GenerateGitkeepCommand as GenerateGitkeep;
use Amamarul\ModulesMaru\Commands\RemoveGitkeepCommand as RemoveGitkeep;
use Amamarul\ModulesMaru\Commands\Modules\ModuleNameCommand as ModuleName;

class ConsoleServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * The available commands
     *
     * @var array
     */
    protected $commands = [
        MakeCommand::class,
        CommandCommand::class,
        ControllerCommand::class,
        DisableCommand::class,
        EnableCommand::class,
        GenerateEventCommand::class,
        GenerateListenerCommand::class,
        GenerateMiddlewareCommand::class,
        GenerateProviderCommand::class,
        GenerateRouteProviderCommand::class,
        InstallCommand::class,
        ListCommand::class,
        MigrateCommand::class,
        MigrateRefreshCommand::class,
        MigrateResetCommand::class,
        MigrateRollbackCommand::class,
        MigrationCommand::class,
        ModelCommand::class,
        PublishCommand::class,
        PublishMigrationCommand::class,
        PublishTranslationCommand::class,
        SeedCommand::class,
        SeedMakeCommand::class,
        SetupCommand::class,
        UpdateCommand::class,
        UseCommand::class,
        DumpCommand::class,
        MakeRequestCommand::class,
        PublishConfigurationCommand::class,
        GenerateJobCommand::class,
        GenerateMailCommand::class,
        GenerateNotificationCommand::class,

        SidebarProvider::class,
        BreadcrumbsProvider::class,
        Crud::class,
        CrudController::class,
        CrudDatatable::class,
        CrudModel::class,
        CrudRequest::class,
        RouteList::class,
        GenerateGitkeep::class,
        RemoveGitkeep::class,
        ModuleName::class,
    ];

    /**
     * Register the commands.
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * @return array
     */
    public function provides()
    {
        $provides = $this->commands;

        return $provides;
    }
}
