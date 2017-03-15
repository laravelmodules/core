<?php

namespace Amamarul\Modules\Commands\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;

class ModuleNameCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:app:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the module namespace';

    /**
     * The Composer class instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Current root application namespace.
     *
     * @var string
     */
    protected $currentRoot;

    /**
     * Change Module name command.
     *
     * @param  \Illuminate\Support\Composer  $composer
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Composer $composer, Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->lowerName = strtolower($this->laravel->modules->get($this->argument('module'))->getName());
        $this->currentRoot = 'Modules\\'.$this->laravel->modules->get($this->argument('module'))->getName();
        $this->currentComposerRoot = 'Modules\\\\'.$this->laravel->modules->get($this->argument('module'))->getName();

        $this->setAppDirectoryNamespace();

        $this->info('Module namespace set!');

        $this->composer->dumpAutoloads();

        $this->call('clear-compiled');
    }

    /**
     * Set the namespace on the files in the app directory.
     *
     * @return void
     */
    protected function setAppDirectoryNamespace()
    {
        $files = Finder::create()
                            ->in($this->laravel->modules->getPath())
                            ->contains($this->currentRoot)
                            ->contains($this->currentComposerRoot)
                            ->contains('"amamarul/'.$this->lowerName.'"')
                            ->name('*.*');

        foreach ($files as $file) {

            $this->replaceNamespace($file->getRealPath());
        }
        $this->laravel->modules->get($this->argument('module'))
                        ->setAlias($this->argument('name'));
        $this->laravel->modules->get($this->argument('module'))
                        ->setName($this->argument('name'));
        \File::moveDirectory($this->laravel->modules->getPath().'/'.$this->argument('module'),$this->laravel->modules->getPath().'/'.$this->argument('name'));
    }

    /**
     * Replace the App namespace at the given path.
     *
     * @param  string  $path
     * @return void
     */
    protected function replaceNamespace($path)
    {
        $search = [
            'namespace '.$this->currentRoot.';',
            $this->currentRoot.'\\',
            'namespace '.$this->currentComposerRoot.';',
            $this->currentComposerRoot.'\\',
            '"amamarul/'.$this->lowerName.'"',
        ];

        $replace = [
            'namespace Modules\\'.$this->argument('name').';',
            'Modules\\'.$this->argument('name').'\\',
            'namespace Modules\\\\'.$this->argument('name').';',
            'Modules\\\\'.$this->argument('name').'\\',
            '"amamarul/'.strtolower($this->argument('name')).'"',
        ];

        $this->replaceIn($path, $search, $replace);
    }

    /**
     * Replace the given string in the given file.
     *
     * @param  string  $path
     * @param  string|array  $search
     * @param  string|array  $replace
     * @return void
     */
    protected function replaceIn($path, $search, $replace)
    {
        if ($this->files->exists($path)) {
            $this->files->put($path, str_replace($search, $replace, $this->files->get($path)));
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The desired namespace.'],
            ['module', InputArgument::REQUIRED, 'The desired namespace.'],
        ];
    }
}
