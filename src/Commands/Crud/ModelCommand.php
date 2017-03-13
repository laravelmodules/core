<?php

namespace Amamarul\ModulesMaru\Commands\Crud;

use Illuminate\Support\Str;
use Amamarul\ModulesMaru\Support\Stub;
use Amamarul\ModulesMaru\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Amamarul\ModulesMaru\Commands\GeneratorCommand;

class ModelCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'model';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:crud:make-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new model for the specified module.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('model', InputArgument::REQUIRED, 'The name of model will be created.'),
            array('module', InputArgument::OPTIONAL, 'The name of module will be used.'),
            array('tabla', InputArgument::OPTIONAL, 'The name of table will be used.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('fillable', null, InputOption::VALUE_OPTIONAL, 'The fillable attributes.', null),
        );
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/../Crud/stubs/model.stub', [
            'NAME'              => $this->getModelName(),
            'FILLABLE'          => $this->getFillable(),
            'NAMESPACE'         => $this->getClassNamespace($module),
            'CLASS'             => $this->getClass(),
            'LOWER_CLASS'       => snake_case($this->getClass()),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'LOWER_MODULE'      => snake_case($this->getModuleName()),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'TABLE_NAME'       => $this->argument('tabla'),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $seederPath = $this->laravel['modules']->config('paths.generator.model');

        return $path . $seederPath . '/' . $this->getModelName() . '.php';
    }

    /**
     * @return mixed|string
     */
    private function getModelName()
    {
        return Str::studly($this->argument('model'));
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

            return '['.$fields.']';
        }

        return '[]';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace()
    {
        return $this->laravel['modules']->config('paths.generator.model');
    }
}
