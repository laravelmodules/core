<?php

namespace Amamarul\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Amamarul\Modules\Commands\CommandCommand;
use Amamarul\Modules\Commands\ControllerCommand;
use Amamarul\Modules\Commands\DisableCommand;
use Amamarul\Modules\Commands\DumpCommand;
use Amamarul\Modules\Commands\EnableCommand;
use Amamarul\Modules\Commands\GenerateEventCommand;
use Amamarul\Modules\Commands\GenerateJobCommand;
use Amamarul\Modules\Commands\GenerateListenerCommand;
use Amamarul\Modules\Commands\GenerateMailCommand;
use Amamarul\Modules\Commands\GenerateMiddlewareCommand;
use Amamarul\Modules\Commands\GenerateNotificationCommand;
use Amamarul\Modules\Commands\GenerateProviderCommand;
use Amamarul\Modules\Commands\GenerateRouteProviderCommand;
use Amamarul\Modules\Commands\InstallCommand;
use Amamarul\Modules\Commands\ListCommand;
use Amamarul\Modules\Commands\MakeCommand;
use Amamarul\Modules\Commands\MakeRequestCommand;
use Amamarul\Modules\Commands\MigrateCommand;
use Amamarul\Modules\Commands\MigrateRefreshCommand;
use Amamarul\Modules\Commands\MigrateResetCommand;
use Amamarul\Modules\Commands\MigrateRollbackCommand;
use Amamarul\Modules\Commands\MigrationCommand;
use Amamarul\Modules\Commands\ModelCommand;
use Amamarul\Modules\Commands\PublishCommand;
use Amamarul\Modules\Commands\PublishConfigurationCommand;
use Amamarul\Modules\Commands\PublishMigrationCommand;
use Amamarul\Modules\Commands\PublishTranslationCommand;
use Amamarul\Modules\Commands\SeedCommand;
use Amamarul\Modules\Commands\SeedMakeCommand;
use Amamarul\Modules\Commands\SetupCommand;
use Amamarul\Modules\Commands\UpdateCommand;
use Amamarul\Modules\Commands\UseCommand;

use Amamarul\Modules\Commands\GenerateSidebarProviderCommand as SidebarProvider;
use Amamarul\Modules\Commands\GenerateBreadcrumbsProviderCommand as BreadcrumbsProvider;
use Amamarul\Modules\Commands\Crud\ControllerCommand as CrudController;
use Amamarul\Modules\Commands\Crud\MakeDatatableCommand as CrudDatatable;
use Amamarul\Modules\Commands\Crud\ModelCommand as CrudModel;
use Amamarul\Modules\Commands\Crud\MakeRequestCommand as CrudRequest;
use Amamarul\Modules\Commands\Crud\CrudCommand as Crud;
use Amamarul\Modules\Commands\RouteListCommand as RouteList;
use Amamarul\Modules\Commands\GenerateGitkeepCommand as GenerateGitkeep;
use Amamarul\Modules\Commands\RemoveGitkeepCommand as RemoveGitkeep;
use Amamarul\Modules\Commands\Modules\ModuleNameCommand as ModuleName;
use Amamarul\Modules\Commands\Installation\Module\NewCommand as NewModule;
use Amamarul\Modules\Commands\Installation\Install as InstallCoreLaravelModules;
use Amamarul\Modules\Commands\MakeComposerCommand as MakeViewComposer;
use Amamarul\Modules\Commands\Modules\MergeModulesJsonLangFilesCommand as MergeJsonLangFiles;
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

        MakeViewComposer::class,
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
        MergeJsonLangFiles::class,
        NewModule::class,
        InstallCoreLaravelModules::class,
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
