<?php

namespace Amamarul\Modules\Commands\Crud;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CrudCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Module Crud.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->call('module:crud:make-model', [
            'model' => $this->argument('name'),
            'module' => $this->argument('module'),
            'tabla' => $this->argument('tabla'),
        ]);
        $this->line("Model Created");

        $this->call('module:crud:make-datatable', [
            'name' => $this->argument('name'),
            'module' => $this->argument('module'),
            'tabla' => $this->argument('tabla'),
        ]);
        // $this->line("Datatable Created");

        $this->call('module:crud:make-request', [
            'name' => $this->argument('name'),
            'module' => $this->argument('module'),
            'tabla' => $this->argument('tabla'),
        ]);
        // $this->line("FormRequest Created");

        $this->call('module:crud:make-controller', [
            'controller' => $this->argument('name'),
            'module' => $this->argument('module'),
            'tabla' => $this->argument('tabla'),
        ]);
        // $this->line("Controller Created");

        // $this->line("");
        // $this->line("Crud Created");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'An example argument.'],
            ['module', InputArgument::REQUIRED, 'An example argument.'],
            ['tabla', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            // ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
