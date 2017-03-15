<?php

namespace Amamarul\Modules\Commands\Crud;

use Amamarul\Modules\Support\Stub;
use Amamarul\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

// use Illuminate\Filesystem\Filesystem;
use Facades\Illuminate\Filesystem\Filesystem;
use Amamarul\Modules\Commands\GeneratorCommand;

class ControllerCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected $argumentName = 'controller';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:crud:make-controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new restful controller for the specified module.';

    /**
     * Get controller name.
     *
     * @return string
     */
    public function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $controllerPath = $this->laravel['modules']->config('paths.generator.controller');
        $this->publishRoutes();
        return $path . $controllerPath . '/' . $this->getControllerName() . '.php';
    }

    public function publishRoutes()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());
        $httpPath = $this->laravel['modules']->config('paths.generator.http');
        $ruta = "Route::resource('".camel_case($this->argument('controller'))."', '".$this->getControllerName()."');\n";
        Filesystem::append($path.$httpPath.'/routes.php',$ruta);
    }

    /**
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'MODULENAME'        => $module->getStudlyName(),
            'FILLABLE'          => $this->getFillable(),
            'CONTROLLERNAME'    => $this->getControllerName(),
            'NAMESPACE'         => $module->getStudlyName(),
            'CLASS_NAMESPACE'   => $this->getClassNamespace($module),
            'CLASS'             => $this->getControllerName(),
            'LOWER_CLASS'       => strtolower($this->argument('controller')),
            'LOWER_NAME'        => strtolower($module->getLowerName()),
            'MODULE'            => $this->getModuleName(),
            'LOWER_MODULE'      => strtolower($this->getModuleName()),
            'NAME'              => studly_case($this->argument('controller')),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
            'MODEL_NAMESPACE'  => $this->laravel['modules']->config('paths.generator.model'),
        ]))->render();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('controller', InputArgument::REQUIRED, 'The name of the controller class.'),
            array('module', InputArgument::OPTIONAL, 'The name of module will be used.'),
            array('tabla', InputArgument::OPTIONAL, 'The name of module will be used.'),
        );
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain controller', null],
        ];
    }

    /**
     * @return array|string
     */
    protected function getControllerName()
    {
        $controller = studly_case($this->argument('controller'));

        if (str_contains(strtolower($controller), 'controller') === false) {
            $controller .= 'Controller';
        }

        return $controller;
    }

    /**
     * @return string
     */
    private function getFillable()
    {
        $table = $this->argument('tabla');

        if (!is_null($table)) {
            $columns = \DB::getSchemaBuilder()->getColumnListing($this->argument('tabla'));
            $fields = "'".implode("','",$columns)."'";

            return $fields;
        }

        return '';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace()
    {
        return 'Http\Controllers';
    }

    /**
     * Get the stub file name based on the plain option
     * @return string
     */
    private function getStubName()
    {
        // if ($this->option('plain') === true) {
        //     return '/../Crud/stubs/controller-plain.stub';
        // }

        return '/../Crud/stubs/controller.stub';
    }
}
