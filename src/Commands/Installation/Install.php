<?php

namespace Amamarul\Modules\Commands\Installation;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

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
        $this->updateAuthConfig();
        $this->installModulesNamespace();
        $this->installNpmPackageConfig();
        $this->installWebpackFile();
        $this->installViews();
        $this->updateAuthConfig();
        $this->installJavaScript();
        $this->installSass();
        $this->installEnvironmentVariables();
        $this->installModulesNamespace();
        $this->call('key:generate');
        new Process('composer dumpautoload', base_path());



        $this->table(
            ['Task', 'Status'],
            [
                ['Installing Laravel Modules', '<info>✔</info>'],
            ]
        );

        if ($this->option('force') || $this->confirm('Would you like to run your database migrations?', 'yes')) {
            (new Process('php artisan module:migrate', base_path()))->setTimeout(null)->run();
        }

        if ($this->option('force') || $this->confirm('Would you like to install your NPM dependencies?', 'yes')) {
            (new Process('npm install', base_path()))->setTimeout(null)->run();
        }

        if ($this->option('force') || $this->confirm('Would you like to run Gulp?', 'yes')) {
            (new Process('npm run production', base_path()))->setTimeout(null)->run();
        }

        $this->displayPostInstallationNotes();
    }

    /**
     * Update the "auth" configuration file.
     *
     * @return void
     */
    protected function updateAuthConfig()
    {
        $path = config_path('auth.php');
        $search = [
            'model' => App\User::class,
        ];

        $replace = [
            'model' => Modules\Users\Models\Access\User\User::class,
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
        $path = base_path('composer.json');
        $search = [
            '"App\\": "app/"',
        ];

        $replace = [
            '"App\\": "app/",\n
            "Modules\\": "Modules/"\n',
        ];

        $this->replaceIn($path, $search, $replace);
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
     * Install the default views for the application.
     *
     * @return void
     */
    protected function installViews()
    {
        copy(
            __DIR__.'/stubs/resources/views/backend',
            base_path('resources/views/backend')
        );
        copy(
            __DIR__.'/stubs/resources/views/dashboard',
            base_path('resources/views/dashboard')
        );
        copy(
            __DIR__.'/stubs/resources/views/frontend',
            base_path('resources/views/frontend')
        );
        copy(
            __DIR__.'/stubs/resources/views/includes',
            base_path('resources/views/includes')
        );
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

        copy(
            __DIR__.'/stubs/resources/assets/js',
            base_path('resources/assets/js')
        );
    }

    /**
     * Install the default Sass file for the application.
     *
     * @return void
     */
    protected function installSass()
    {
        copy(
            __DIR__.'/stubs/resources/assets/sass',
            base_path('resources/assets/sass')
        );
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
            'APP_LOCALE=en \n
            APP_FALLBACK_LOCALE=en \n
            APP_LOCALE_PHP=en_US \n \n',
        ];

        $this->replaceIn($path, $search, $replace);

        $data = "
        \n
        # Security\n
        SESSION_TIMEOUT_STATUS=true\n
        SESSION_TIMEOUT=600\n
        \n
        # Access\n
        ENABLE_REGISTRATION=true\n
        \n
        # Get your credentials at: https://www.google.com/recaptcha/admin\n
        REGISTRATION_CAPTCHA_STATUS=false\n
        NOCAPTCHA_SITEKEY=\n
        NOCAPTCHA_SECRET=\n
        \n
        # Socialite Providers\n
        #FACEBOOK_CLIENT_ID=\n
        #FACEBOOK_CLIENT_SECRET=\n
        #FACEBOOK_REDIRECT=\n
        \n
        #BITBUCKET_CLIENT_ID=\n
        #BITBUCKET_CLIENT_SECRET=\n
        #BITBUCKET_REDIRECT=\n
        \n
        #GITHUB_CLIENT_ID=\n
        #GITHUB_CLIENT_SECRET=\n
        #GITHUB_REDIRECT=\n
        \n
        #GOOGLE_CLIENT_ID=\n
        #GOOGLE_CLIENT_SECRET=\n
        #GOOGLE_REDIRECT=\n
        \n
        #LINKEDIN_CLIENT_ID=\n
        #LINKEDIN_CLIENT_SECRET=\n
        #LINKEDIN_REDIRECT=\n
        \n
        #TWITTER_CLIENT_ID=\n
        #TWITTER_CLIENT_SECRET=\n
        #TWITTER_REDIRECT=\n";
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
     * Display the post-installation information to the user.
     *
     * @return void
     */
    protected function displayPostInstallationNotes()
    {
        $this->comment('Post Installation Notes:');

        $this->line(PHP_EOL.'     → Laravel Modules Installed!');
    }
}
