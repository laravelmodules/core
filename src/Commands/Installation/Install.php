<?php

namespace Amamarul\Modules\Commands\Installation;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Amamarul\Modules\Json;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:core:install {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Laravel Modules scaffolding into the application';

    /**
     * Install LaravelModules command.
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
        new Process('clear', base_path());
        $this->comment('Installing Laravel Modules');

        $this->line('<info> - </info>Installing Modules Namespace');
        $this->installModulesNamespace();

        $this->line('<info> - </info>Installing Npm Package Config');
        $this->installNpmPackageConfig();

        $this->line('<info> - </info>Installing Webpack File');
        $this->installWebpackFile();

        $this->line('<info> - </info>Installing Translations');
        $this->installLangs();

        $this->line('<info> - </info>Installing Views');
        $this->installViews();

        $this->line('<info> - </info>Updating Auth Config');
        $this->updateAuthConfig();

        $this->line('<info> - </info>Installing JavaScript');
        $this->installJavaScript();

        $this->line('<info> - </info>Installing Sass');
        $this->installSass();

        $this->line('<info> - </info>Installing Laravel Modules Environment Variables');
        $this->installEnvironmentVariables();

        $this->line('<info> - </info>Application key set successfully');
        $this->call('key:generate');

        new Process('composer dumpautoload', base_path());

        $this->line('<info> - </info>Installing Default Modules');
        $this->installDefaultModules();

        $this->line('<info> - </info>Removing Standard Migrations');
        $this->removeStandardMigrations();
        $this->line('');

        $this->table(
            ['Task', 'Status'],
            [
                ['Laravel Modules Installed!', '<info>✔</info>'],
                ['Installing Modules Namespace', '<info>✔</info>'],
                ['Installing Npm Package Config', '<info>✔</info>'],
                ['Installing Webpack File', '<info>✔</info>'],
                ['Installing Translations', '<info>✔</info>'],
                ['Installing Views', '<info>✔</info>'],
                ['Updating Auth Config', '<info>✔</info>'],
                ['Installing JavaScript', '<info>✔</info>'],
                ['Installing Sass', '<info>✔</info>'],
                ['Installing Laravel Modules Environment Variables', '<info>✔</info>'],
                ['Application key set successfully', '<info>✔</info>'],
                ['Installing Default Modules', '<info>✔</info>'],
                ['Removing Standard Migrations', '<info>✔</info>']
            ]
        );

        if ($this->option('force') || $this->confirm('Would you like to run your database migrations?', 'yes')) {
            (new Process('php artisan module:migrate', base_path()))->setTimeout(null)->run();
        }

        if ($this->option('force') || $this->confirm('Would you like to install your NPM dependencies?', 'yes')) {
            (new Process('npm install', base_path()))->setTimeout(null)->run();
        }

        if ($this->option('force') || $this->confirm('Would you like to run Mix?', 'yes')) {
            $this->table(
                ['Running Mix Option', 'Description'],
                [
                    ['dev', 'Run all Mix tasks'],
                    ['production', 'Run all Mix tasks and minify output'],
                    ['watch', 'Run all Mix tasks and will continue running in your terminal and watch all relevant files for changes']
                ]
            );
            $mixOption = $this->anticipate('What option you prefer?[dev],[production],[watch]', [
                                                        'dev',
                                                        'production',
                                                        'watch'
                                                        ], 'production');
            (new Process('npm run '.$mixOption, base_path()))->setTimeout(null)->run();
        }
        new Process('composer dumpautoload', base_path());
        $this->call('optimize');
        $this->call('module:dump');

        $this->displayPostInstallationNotes();
    }

    /**
     * Update the "auth" configuration file.
     *
     * @return void
     */
    protected function updateAuthConfig()
    {
        $path = config_path().'/auth.php';
        $search = [
            "'model' => App\User::class"
        ];

        $replace = [
            "'model' => Modules\Users\Models\Access\User\User::class"
        ];

        $this->replaceIn($path, $search, $replace);

    }

    /**
    * Update the "composer.json" file for the project.
    *
    * @return void
    */
    protected function installModulesNamespace()
    {
        $file = 'composer.json';
        $file = new Json(base_path() . '/' . $file);
        $file_a = $file->toArray();
        $autoload = $file_a['autoload'];
        $psr_4 = $file_a['autoload']['psr-4'];
        $psr_4 = array_add($psr_4, 'Modules\\','Modules/');
        array_set($autoload,'psr-4', $psr_4);
        $file->set('autoload',$autoload);
        $file->save();

        return;
    }

    /**
     * Remove Standard Migrations.
     *
     * @return void
     */
    protected function removeStandardMigrations()
    {
        $this->files->cleanDirectory(base_path().'/database/migrations');
    }

    /**
     * Install the "package.json" file for the project.
     *
     * @return void
     */
    protected function installNpmPackageConfig()
    {
        copy(
            __DIR__.'/stubs/package.json',
            base_path('package.json')
        );
    }

    /**
     * Install the "webpack.mix.js" file for the project.
     *
     * @return void
     */
    protected function installWebpackFile()
    {
        copy(
            __DIR__.'/stubs/webpack.mix.js',
            base_path('webpack.mix.js')
        );
    }

    /**
     * Install the default translations for the application.
     *
     * @return void
     */
    protected function installLangs()
    {
        $this->files->copyDirectory(__DIR__.'/stubs/lang',
        base_path('resources/lang'));
    }

    /**
     * Install the default views for the application.
     *
     * @return void
     */
    protected function installViews()
    {
        $this->files->copyDirectory(__DIR__.'/stubs/public',
        base_path('public'));
        $this->files->copyDirectory(__DIR__.'/stubs/resources/views/backend',
        base_path('resources/views/backend'));
        $this->files->copyDirectory(__DIR__.'/stubs/resources/views/dashboard',
        base_path('resources/views/dashboard'));
        $this->files->copyDirectory(__DIR__.'/stubs/resources/views/frontend',
        base_path('resources/views/frontend'));
        $this->files->copyDirectory(__DIR__.'/stubs/resources/views/includes',
        base_path('resources/views/includes'));
    }


    /**
     * Install the default JavaScript file for the application.
     *
     * @return void
     */
    protected function installJavaScript()
    {
        if (! is_dir('resources/assets/js')) {
            mkdir(base_path('resources/assets/js'));
        }
        $this->files->copyDirectory(__DIR__.'/stubs/resources/assets/js',
        base_path('resources/assets/js'));
    }

    /**
     * Install the default Sass file for the application.
     *
     * @return void
     */
    protected function installSass()
    {
        $this->files->copyDirectory(__DIR__.'/stubs/resources/assets/sass',
        base_path('resources/assets/sass'));
    }

    /**
     * Install the environment variables for the application.
     *
     * @return void
     */
    protected function installEnvironmentVariables()
    {
        if (! file_exists(base_path('.env'))) {
            copy(
                __DIR__.'/stubs/.env.example',
                base_path('.env')
            );
            return;
        }

        $env = file_get_contents(base_path('.env'));

        if (str_contains($env, 'SESSION_TIMEOUT=')) {
            return;
        }

        $path = base_path('.env');
        $search = [
            'APP_URL=http://localhost',
        ];

        $replace = [
'APP_LOG=daily
APP_URL=http://localhost
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_LOCALE_PHP=en_US',
        ];

        $this->replaceIn($path, $search, $replace);

        $data = "\n# Security\nSESSION_TIMEOUT_STATUS=true\nSESSION_TIMEOUT=600\n\n# Access\nENABLE_REGISTRATION=true\n\n# Get your credentials at: https://www.google.com/recaptcha/admin\nREGISTRATION_CAPTCHA_STATUS=false\nNOCAPTCHA_SITEKEY=\nNOCAPTCHA_SECRET=\n\n# Socialite Providers\n#FACEBOOK_CLIENT_ID=\n#FACEBOOK_CLIENT_SECRET=\n#FACEBOOK_REDIRECT=\n\n#BITBUCKET_CLIENT_ID=\n#BITBUCKET_CLIENT_SECRET=\n#BITBUCKET_REDIRECT=\n\n#GITHUB_CLIENT_ID=\n#GITHUB_CLIENT_SECRET=\n#GITHUB_REDIRECT=\n\n#GOOGLE_CLIENT_ID=\n#GOOGLE_CLIENT_SECRET=\n#GOOGLE_REDIRECT=\n\n#LINKEDIN_CLIENT_ID=\n#LINKEDIN_CLIENT_SECRET=\n#LINKEDIN_REDIRECT=\n\n#TWITTER_CLIENT_ID=\n#TWITTER_CLIENT_SECRET=\n#TWITTER_REDIRECT=\n";
        $this->files->append($path,$data);
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
     * Update the "auth" configuration file.
     *
     * @return void
     */
    protected function installDefaultModules()
    {
        // if (! is_dir(base_path().'/Modules/Base')) {
        //     $this->call('module:new:install', [
        //         'name' => 'Base',
        //         'github' => 'laravelmodules/base',
        //     ]);
        // }
        // if (! is_dir(base_path().'/Modules/History')) {
        //     $this->call('module:new:install', [
        //         'name' => 'History',
        //         'github' => 'laravelmodules/history',
        //     ]);
        // }
        // if (! is_dir(base_path().'/Modules/Menu')) {
        //     $this->call('module:new:install', [
        //         'name' => 'Menu',
        //         'github' => 'laravelmodules/menu',
        //     ]);
        // }
        // if (! is_dir(base_path().'/Modules/Users')) {
        //     $this->call('module:new:install', [
        //         'name' => 'Users',
        //         'github' => 'laravelmodules/users',
        //     ]);
        // }
        // if (! is_dir(base_path().'/Modules/Views')) {
        //     $this->call('module:new:install', [
        //         'name' => 'Views',
        //         'github' => 'laravelmodules/views',
        //     ]);
        // }
        if (! is_dir(base_path().'/Modules/Users')) {
            $this->call('module:install', [
                'name' => 'laravelmodules/users',
                'type' => 'github',
            ]);
        }
    }

    /**
     * Display the post-installation information to the user.
     *
     * @return void
     */
    protected function displayPostInstallationNotes()
    {
        $this->comment('Post Installation Notes:');

        $this->line(PHP_EOL.'     → Laravel Modules Installed!');
        $this->line('');

    }
}
