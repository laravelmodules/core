<?php

namespace Amamarul\Modules\Commands\Installation\Module;

use ZipArchive;
use RuntimeException;
use GuzzleHttp\Client;
use Symfony\Component\Process\Process;
// use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
// use Symfony\Component\Process\Process;

class NewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:new:install
                                    {name}
                                    {github}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the LaravelModules New Module into the application.
    Args:
    "name" => Module Name (The namespace.),
    "github" => The GitHub repository (Ex. <user>/<repository>)';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The desired namespace.'],
            ['github', InputArgument::REQUIRED, 'The GitHub repository (Ex. <user>/<repository>).'],
        ];
    }

    /**
     * Add new Module command.
     *
     * @param  \Illuminate\Support\Composer  $composer
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->installModule();
    }

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return void
     */
    protected function installModule()
    {
        if (! class_exists('ZipArchive')) {
            throw new RuntimeException('The Zip PHP extension is not installed. Please install it and try again.');
        }

        // $this->verifyModuleDoesntExist(
        //     $directory = ($this->argument('name')) ? getcwd().'/Modules/'.$this->argument('name') : getcwd().'/Modules/'
        // );
        $this->verifyModuleDoesntExist(
            $directory = ($this->argument('name')) ? getcwd().'/Modules/Tmp/'.$this->argument('name') : getcwd().'/Modules/Tmp/'
        );

        $this->line('<info>Crafting module...</info>');

        $this->download($zipFile = $this->makeFilename())
             ->extract($zipFile, $directory)
             ->cleanUp($zipFile);

        $tempName = array_last(explode('/',$this->argument('github'))).'-master/';
        $tempFolder = $this->laravel->modules->getPath().'/Tmp/';
        $tempModuleFolder = $tempFolder.$this->argument('name').'/'.$tempName.'/'.$this->argument('name');

        $this->files->moveDirectory($tempModuleFolder,$this->laravel->modules->getPath().'/'.$this->argument('name'));
        // $this->files->cleanDirectory($tempFolder);
        $this->files->deleteDirectory($tempFolder);

        // $this->call('module:install '.strtolower($this->argument('name')));
        // $this->call('module:install '.$this->argument('name')));
        $this->call('module:install', [
            'name' => $this->argument('name'),
        ]);
        $this->call('module:dump', [
            'module' => $this->argument('name'),
        ]);

        $this->line('<comment>Application ready! Build something amazing.</comment>');
    }

    /**
     * Verify that the application does not already exist.
     *
     * @param  string  $directory
     * @return void
     */
    protected function verifyModuleDoesntExist($directory)
    {
        if ((is_dir($directory) || is_file($directory)) && $directory != getcwd()) {
            throw new RuntimeException('Module already exists!');
        }
    }

    /**
     * Generate a random temporary filename.
     *
     * @return string
     */
    protected function makeFilename()
    {
        return getcwd().'/laravelmodules_'.$this->argument('name').'_'.md5(time().uniqid()).'.zip';
    }

    /**
     * Download the temporary Zip to the given file.
     *
     * @param  string  $zipFile
     * @param  string  $version
     * @return $this
     */
    protected function download($zipFile)
    {
        $filename = $this->argument('github').'/archive/master.zip';

        $response = (new Client)->get('https://github.com/'.$filename);

        file_put_contents($zipFile, $response->getBody());

        return $this;
    }

    /**
     * Extract the Zip file into the given directory.
     *
     * @param  string  $zipFile
     * @param  string  $directory
     * @return $this
     */
    protected function extract($zipFile, $directory)
    {
        $archive = new ZipArchive;

        $archive->open($zipFile);

        $archive->extractTo($directory);

        $archive->close();

        return $this;
    }

    /**
     * Clean-up the Zip file.
     *
     * @param  string  $zipFile
     * @return $this
     */
    protected function cleanUp($zipFile)
    {
        @chmod($zipFile, 0777);

        @unlink($zipFile);

        return $this;
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd().'/composer.phar')) {
            return '"'.PHP_BINARY.'" composer.phar';
        }

        return 'composer';
    }
}
