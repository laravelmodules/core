<?php

namespace Amamarul\Modules\Commands;

use Illuminate\Support\Str;
use Amamarul\Modules\Support\Stub;
use Amamarul\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GenerateBreadcrumbsProviderCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'module';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:breadcrumbs-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a breadcrumbs provider for the specified module.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        /**
         * Si no existe la carpeta craearla
         */
        // $this->makeBreadcrumbsFolder();

        return (new Stub('/breadcrumbs-provider.stub', [
            'NAMESPACE'         => $this->getClassNamespace($module),
            'CLASS'             => $this->getClass(),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'NAME'              => $this->getFileName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
            'PATH_VIEWS'        => $this->laravel['config']->get('modules.paths.generator.views'),
            'PATH_LANG'         => $this->laravel['config']->get('modules.paths.generator.lang'),
            'PATH_CONFIG'       => $this->laravel['config']->get('modules.paths.generator.config'),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function makeBreadcrumbsFolder()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $generatorPath = $this->laravel['modules']->config('paths.generator.breadcrumbs');
        $breadcrumbsPath = $path . $generatorPath;

        if (!$this->laravel['files']->isDirectory($breadcrumbsPath)) {
            $this->laravel['files']->makeDirectory($breadcrumbsPath, 0777, true);
        }
        if (!$this->laravel['files']->exists($breadcrumbsPath.'/admin.php')) {
            $this->laravel['files']->put($breadcrumbsPath.'/admin.php','<?php');
        }
        if (!$this->laravel['files']->exists($breadcrumbsPath.'/dashboard.php')) {
            $this->laravel['files']->put($breadcrumbsPath.'/dashboard.php','<?php');
        }
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $generatorPath = $this->laravel['modules']->config('paths.generator.provider');

        return $path . $generatorPath . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return 'BreadcrumbsServiceProvider';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace()
    {
        return 'Providers';
    }
}
