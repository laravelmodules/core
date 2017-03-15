<?php

namespace Amamarul\Modules\Commands\Crud;

use Illuminate\Support\Str;
use Amamarul\Modules\Support\Stub;
use Amamarul\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

use Amamarul\Modules\Commands\GeneratorCommand;

class DasboardMenuCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'ruta';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:admin-menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new admin-menu for the specified module.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('ruta', InputArgument::REQUIRED, 'The name of the form request class.'),
            array('module', InputArgument::OPTIONAL, 'The name of module will be used.'),
        );
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/stubs/admin_menu.stub', [
            'NAMESPACE'         => $this->getClassNamespace($module),
            'MODULENAME'        => $module->getStudlyName(),
            'CLASS_NAMESPACE'   => $this->getClassNamespace($module),
            'FILLABLE'          => $this->getFillable(),
            'CLASS'             => $this->getClass(),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'NAME'              => Str::studly($this->argument('name')),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $seederPath = $this->laravel['modules']->config('paths.generator.controller');

        return $path . $seederPath . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name')).'Datatable';
    }

    /**
     * @return string
     */
    private function getFillable()
    {
        $table = $this->argument('tabla');

        if (!is_null($table)) {
            $columns = \DB::getSchemaBuilder()->getColumnListing($this->argument('tabla'));
            $fields = '';
                foreach ($columns as $value) {
                    if ($value === 'id' || $value === 'created_at' || $value === 'updated_at' || $value === 'name' || $value === 'nombre') {
                        $fields .= "                    '" .$value."',\n";
                    } else {
                        $fields .= "                    // '" .$value."',\n";
                    }
                }
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
}
