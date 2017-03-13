<?php

namespace Amamarul\ModulesMaru\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class GenerateGitkeepCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:gitkeep';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate GitKeep on empty folders for the specified module or for all modules.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if ($module = $this->argument('module')) {
            $this->info("Generating GitKeeps on <comment>{$this->argument('module')}</comment> Module.");
            $this->generateGitKeep($module);
        } else {
            $this->info('Generating GitKeeps on all modules.');
            foreach ($this->laravel['modules']->all() as $module) {
                $this->generateGitKeep($module->getStudlyName());
            }
        }
    }

    public function generateGitKeep($module)
    {
        $module = $this->laravel['modules']->findOrFail($module);

        $this->line("<comment>Running for module</comment>: {$module}");

        $list = $this->getFolders();
        foreach ($list as $key => $value) {
            $path = $module->getPath().'/'.$value;
            if (count(glob($path.'/*')) === 0 ) {
                $this->putGitKeep($path);
                $this->line("<comment>{$value}</comment>: .gitkeep Generated!");
            } else {
            }
        }
    }

    /**
     * Generate git keep to the specified path.
     *
     * @param string $path
     */
    public function putGitKeep($path)
    {
        \File::put($path . '/.gitkeep', '');
    }

    /**
     * Get the list of module folders.
     *
     * @return array
     */
    public function getFolders()
    {
        return array_values(config('modules.paths.generator'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('module', InputArgument::OPTIONAL, 'Module name.'),
        );
    }
}
