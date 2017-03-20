<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default module namespace.
    |
    */

    'namespace' => 'Modules',

    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    |
    | Default module stubs.
    |
    */

    'stubs' => [
        'enabled' => false,
        'path' => base_path() . '/vendor/laravelmodules/core/src/Commands/stubs',
        'files' => [
            'start' => 'start.php',
            'routes/web' => 'routes/web.php',
            'routes/api' => 'routes/api.php',
            'routes/backend' => 'routes/Backend/routes.php',
            'routes/dashboard' => 'routes/Dashboard/routes.php',
            'routes/frontend' => 'routes/Frontend/routes.php',
            'json' => 'module.json',
            'views/index' => 'Resources/views/index.blade.php',
            'views/datatable' => 'Resources/views/datatable.blade.php',
            'views/show' => 'Resources/views/show.blade.php',
            'views/create' => 'Resources/views/create.blade.php',
            'views/edit' => 'Resources/views/edit.blade.php',
            'views/master' => 'Resources/views/layouts/master.blade.php',
            'scaffold/config' => 'Config/config.php',
            'sidebar/backend' => 'Sidebar/backend.php',
            'sidebar/dashboard' => 'Sidebar/dashboard.php',
            'breadcrumbs/backend' => 'Breadcrumbs/backend.php',
            'breadcrumbs/dashboard' => 'Breadcrumbs/dashboard.php',
            'Helpers/helpers' => 'Helpers/helpers.php',
            'composer' => 'composer.json',
        ],
        'replacements' => [
            'start' => ['LOWER_NAME'],
            'routes/web' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
            'routes/api' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
            'routes/backend/routes' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
            'routes/dashboard/routes' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
            'routes/frontend/routes' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
            'json' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
            'views/index' => ['LOWER_NAME'],
            'views/master' => ['STUDLY_NAME'],
            'scaffold/config' => ['STUDLY_NAME'],
            'sidebar/backend' => ['STUDLY_NAME','LOWER_NAME'],
            'sidebar/dashboard' => ['STUDLY_NAME','LOWER_NAME'],
            'breadcrumbs/backend' => ['STUDLY_NAME','LOWER_NAME'],
            'breadcrumbs/dashboard' => ['STUDLY_NAME','LOWER_NAME'],
            'Helpers/helpers' => ['LOWER_NAME'],
            'composer' => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'MODULE_NAMESPACE',
            ],
        ],
    ],
    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        |
        | This path used for save the generated module. This path also will added
        | automatically to list of scanned folders.
        |
        */

        'modules' => base_path('Modules'),
        /*
        |--------------------------------------------------------------------------
        | Modules assets path
        |--------------------------------------------------------------------------
        |
        | Here you may update the modules assets path.
        |
        */

        'assets' => public_path('modules'),
        /*
        |--------------------------------------------------------------------------
        | The migrations path
        |--------------------------------------------------------------------------
        |
        | Where you run 'module:publish-migration' command, where do you publish the
        | the migration files?
        |
        */

        'migration' => base_path('database/migrations'),
        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        |
        | Here you may update the modules generator path.
        |
        */

        'generator' => [
            'assets' => 'Assets',
            'breadcrumbs' => 'Breadcrumbs',
            'config' => 'Config',
            'command' => 'Console',
            'database' => 'Database',
            'migration' => 'Database/Migrations',
            'seeder' => 'Database/Seeders',
            'emails' => 'Emails',
            'event' => 'Events',
            'listener' => 'Events/Handlers',
            'Helpers' => 'Helpers',
            'http' => 'Http',
            'controller' => 'Http/Controllers',
            'controller/frontend' => 'Http/Controllers/Frontend',
            'controller/dashboard' => 'Http/Controllers/Dashboard',
            'controller/backend' => 'Http/Controllers/Backend',
            'filter' => 'Http/Middleware',
            'request' => 'Http/Requests',
            'jobs' => 'Jobs',
            'model' => 'Models',
            'notifications' => 'Notifications',
            'provider' => 'Providers',
            'repository' => 'Repositories',
            'repository/frontend' => 'Repositories/Frontend',
            'repository/dashboard' => 'Repositories/Dashboard',
            'repository/backend' => 'Repositories/Backend',
            'lang' => 'Resources/lang',
            'views' => 'Resources/views',
            'routes' => 'routes',
            'routes/backend' => 'routes/Backend',
            'routes/dashboard' => 'routes/Dashboard',
            'routes/frontend' => 'routes/Frontend',
            'sidebar' => 'Sidebar',
            'test' => 'Tests',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    |
    | Here you define which folder will be scanned. By default will scan vendor
    | directory. This is useful if you host the package in packagist website.
    |
    */

    'scan' => [
        'enabled' => false,
        'paths' => [
            base_path('vendor/*/*'),
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Composer File Template
    |--------------------------------------------------------------------------
    |
    | Here is the config for composer.json file, generated by this package
    |
    */

    'composer' => [
        'vendor' => 'amamarul',
        'author' => [
            'name' => 'Maru Amallo',
            'email' => 'ama_marul@hotmail.com',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Here is the config for setting up caching feature.
    |
    */
    'cache' => [
        'enabled' => false,
        'key' => 'laravel-modules',
        'lifetime' => 60,
    ],
    /*
    |--------------------------------------------------------------------------
    | Choose what laravel-modules will register as custom namespaces.
    | Setting one to false will require to register that part
    | in your own Service Provider class.
    |--------------------------------------------------------------------------
    */
    'register' => [
        'translations' => true,
    ],
];
