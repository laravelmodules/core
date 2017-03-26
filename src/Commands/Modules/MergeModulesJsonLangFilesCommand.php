<?php

namespace Amamarul\Modules\Commands\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;

class MergeModulesJsonLangFilesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:lang:merge-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile All Modules Json Lang Files. If you only want to compile an specific module all module name';

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
     * All Json Lang Files.
     */
    protected $LangFiles = [];

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
        $module = $this->argument('module') !== null ? $this->argument('module') : null;
        $this->getExistingFiles();
        $this->getModulesFiles($module);
        $this->generateJsonFiles();
    }

    public function getExistingFiles()
    {
        $files = Finder::create()
                        ->in(resource_path('lang'))
                        ->name('*.json');
        foreach ($files as $lang) {
            $this->addContent($lang);
        }
    }

    /**
     * Get Existing Modules Files.
     *
     * @return array
     */
    public function getModulesFiles($module = null)
    {
        if ($module !== null) {
            $langs = collect();
            $files = Finder::create()
            ->in($this->laravel->modules->get($this->argument('module'))->getPath().'/Resources/lang')
            ->name('*.json');
            foreach ($files as $lang) {
                $this->addContent($lang);
            }
            $this->line('');
            $this->line('        <comment>Module: </comment><info>'.$module.'</info> added!');
        } else {
            foreach ($this->laravel->modules->all() as $module) {
                if (count($this->files->allFiles($module->getPath().'/Resources/lang')) > 0) {
                    $langs = collect();
                    $files = Finder::create()
                    ->in($module->getPath().'/Resources/lang')
                    ->name('*.json');
                    $f = collect();
                    foreach ($files as $lang) {
                        $this->addContent($lang);
                    }
                }
                $this->line('');
                $this->line('        <comment>Module: </comment> <info>'.$module.'</info> added!');
            }
        }
    }

    public function addContent($lang)
    {
        $file = $lang->getFilename();
        $content = collect(json_decode($lang->getContents()))->toArray();
        $data = array_key_exists($file,$this->LangFiles) ? $this->LangFiles[$file] : null;
        if ($data === null) {
            $this->LangFiles[$file] = $content;
        } else {
            $this->LangFiles[$file] = array_merge($content,$data);
        }
    }

    public function generateJsonFiles()
    {
        $this->line('');
        $this->line('Generating Files');
        $this->line('');
        $files = array_keys($this->LangFiles);
        foreach ($files as $file) {
            $content = $this->toJsonPretty(array_unique($this->LangFiles[$file]));
            $this->files->put(resource_path('lang').'/'.$file,$content);
            $this->line('        <comment>'.$file.'</comment> <info>generated!</info>');
        }
    }

    /**
     * Convert the given array data to pretty json.
     *
     * @param array $data
     *
     * @return string
     */
    public function toJsonPretty(array $data = null)
    {
        return json_encode($data ?: $this->attributes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The desired namespace.'],
        ];
    }
}
