<?php

namespace Amamarul\Modules\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class RemoveGitkeepCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:gitkeep:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove GitKeep files on folders for the specified module or for all modules.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {

        if ($module = $this->argument('module')) {
            $this->info("Removing GitKeeps on {$this->argument('module')} Module.");
            $this->generateGitKeep($module);
        } else {
            $this->info('Removing GitKeeps on all modules.');
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
            $this->line("<comment>{$value}/.gitkeep</comment>: Deleted!");
            \File::delete($module->getPath().'/'.$value.'/.gitkeep');
        }
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
