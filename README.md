# LaravelModules

- [Upgrade Guide](#upgrade-guide)
- [Installation](#installation)
- [Configuration](#configuration)
- [Naming Convension](#naming-convension)
- [Folder Structure](#folder-structure)
- [Creating Module](#creating-a-module)
- [Artisan Commands](#artisan-commands)
- [Routes](#routes)
- [Helpers](#helpers)
- [Facades](#facades)
- [Entity](#entity)
- [Auto Scan Vendor Directory](#auto-scan-vendor-directory)
- [Publishing Modules](#publishing-modules)


This is a laravel project for large laravel apps using modules inspired in:
- `rappasoft/laravel-5-boilerplate`
- `nwidart/laravel-modules`
- `amamarul/boiler-plate-commands`

LaravelModules by default has a "Core Module" to manage Modules and the following Modules:
- Base Module
- Users Module
- Menu Module

<a name="upgrade-guide"></a>
## Upgrade Guide

<a name="installation"></a>
## Installation

### Quick

To install through composer, simply run the following command:

``` bash
composer require amamarul/laravel-modules-maru
```

#### Add Service Provider

Next add the following service provider in `config/app.php`.

```php
'providers' => [
  Amamarul\Modules\LaravelModulesServiceProvider::class,
],
```

Next, add the following aliases to `aliases` array in the same file.

```php
'aliases' => [
  'Module' => Amamarul\Modules\Facades\Module::class,
],
```

Next publish the package's configuration file by running :

```
php artisan vendor:publish --provider="Amamarul\Modules\LaravelModulesServiceProvider"
```

Next run in console:

```
php artisan module:core:install"
```

#### Autoloading

By default controllers, entities or repositories are not loaded automatically. You can autoload your modules using `psr-4`. For example :

```json
{
  "autoload": {
    "psr-4": {
      "App\\": "app/",
      "Modules\\": "Modules/"
    }
  }
}
```

<a name="configuration"></a>
## Configuration

- `modules` - Used for save the generated modules.
- `assets` - Used for save the modules's assets from each modules.
- `migration` - Used for save the modules's migrations if you publish the modules's migrations.
- `generator` - Used for generate modules folders.
- `scan` - Used for allow to scan other folders.
- `enabled` - If `true`, the package will scan other paths. By default the value is `false`
- `paths` - The list of path which can scanned automatically by the package.
- `composer`
- `vendor` - Composer vendor name.
- `author.name` - Composer author name.
- `author.email` - Composer author email.
- `cache`
- `enabled` - If `true`, the scanned modules (all modules) will cached automatically. By default the value is `false`
- `key` - The name of cache.
- `lifetime` - Lifetime of cache.

<a name="creating-a-module"></a>
## Creating A Module

To create a new module you can simply run :

```
php artisan module:make <module-name>
```

- `<module-name>` - Required. The name of module will be created.

**Create a new module**

```
php artisan module:make Blog
```

**Create multiple modules**

```
php artisan module:make Blog User Auth
```
**When the Module was created you can chose is you would like to generate ".gitkeep" files on empty folders**
- If you choose "yes", you can remove them later running `module:gitkeep:remove <MODULE NAME>`.
- If you choose "no", you can generate them later running `module:gitkeep <MODULE NAME>`.

By default if you create a new module, that will add some resources like controller, seed class or provider automatically. If you don't want these, you can add `--plain` flag, to generate a plain module.

```shell
php artisan module:make Blog --plain
#OR
php artisan module:make Blog -p
```

**Import a new module from GitHub**

Run in console
``` bash
php artisan module:new:install <ModuleName> <gitUser>/<repositoryName>
```

Example
``` bash
php artisan module:new:install Users laravelmodules/users
```

<a name="naming-convension"></a>
**Naming Convension**

Because we are autoloading the modules using `psr-4`, we strongly recommend using `StudlyCase` convension.

<a name="folder-structure"></a>
**Folder Structure**

```
laravel-app/
app/
bootstrap/
vendor/
Modules/
  ├── Blog/
      ├── Assets/
      ├── Breadcrumbs/
      ├── Config/
      ├── Console/
      ├── Database/
          ├── Migrations/
          ├── Seeders/
      ├── Emails/
      ├── Events/
      ├── Helpers/
          ├── helpers.php
      ├── Http/
          ├── Controllers/
              ├── Backend/
              ├── Dashboard/
              ├── Frontend/
          ├── Middleware/
          ├── Requests/
              ├── Backend/
              ├── Dashboard/
              ├── Frontend/
      ├── Jobs/
      ├── Models/
      ├── Notifications/
      ├── Providers/
          ├── BlogServiceProvider.php
          ├── BreadcrumbsServiceProvider.php
          ├── RouteServiceProvider.php
          ├── SidebarServiceProvider.php
      ├── Repositories/
          ├── Backend/
          ├── Dashboard/
          ├── Frontend/
      ├── routes/
          ├── Backend/
              ├── routes.php
          ├── Dashboard/
              ├── routes.php
          ├── Frontend/
              ├── routes.php
          ├── routes.php
      ├── Resources/
          ├── lang/
          ├── views/
      ├── Sidebar/
          ├── admin.php
          ├── dashboard.php
      ├── Tests/
      ├── composer.json
      ├── module.json
      ├── start.php
```

<a name="artisan-commands"></a>
## Artisan Commands

Create new module.

```
php artisan module:make blog
```

Use the specified module.

```php
php artisan module:use blog
```

Show all modules in command line.

```
php artisan module:list
```

Create new command for the specified module.

```
php artisan module:make-command CustomCommand blog

php artisan module:make-command CustomCommand --command=custom:command blog

php artisan module:make-command CustomCommand --namespace=Modules\Blog\Commands blog
```

Create new migration for the specified module.

```
php artisan module:make-migration create_users_table blog

php artisan module:make-migration create_users_table --fields="username:string, password:string" blog

php artisan module:make-migration add_email_to_users_table --fields="email:string:unique" blog

php artisan module:make-migration remove_email_from_users_table --fields="email:string:unique" blog

php artisan module:make-migration drop_users_table blog
```

Rollback, Reset and Refresh The Modules Migrations.

```
php artisan module:migrate-rollback

php artisan module:migrate-reset

php artisan module:migrate-refresh
```

Rollback, Reset and Refresh The Migrations for the specified module.

```
php artisan module:migrate-rollback blog

php artisan module:migrate-reset blog

php artisan module:migrate-refresh blog
```

Create new seed for the specified module.

```
php artisan module:make-seed users blog
```

Migrate from the specified module.

```
php artisan module:migrate blog
```

Migrate from all modules.

```
php artisan module:migrate
```

Seed from the specified module.

```
php artisan module:seed blog
```

Seed from all modules.

```
php artisan module:seed
```

Create new controller for the specified module.

```
php artisan module:make-controller SiteController blog
```

Publish assets from the specified module to public directory.

```
php artisan module:publish blog
```

Publish assets from all modules to public directory.

```
php artisan module:publish
```

Create new model for the specified module.

```
php artisan module:make-model User blog

php artisan module:make-model User blog --fillable="username,email,password"
```

Create new service provider for the specified module.

```
php artisan module:make-provider MyServiceProvider blog
```

Publish migration for the specified module or for all modules.

This helpful when you want to rollback the migrations. You can also run `php artisan migrate` instead of `php artisan module:migrate` command for migrate the migrations.

For the specified module.

```
php artisan module:publish-migration blog
```

For all modules.

```
php artisan module:publish-migration
```

Publish Module configuration files

```
php artisan module:publish-config <module-name>
```

- (optional) `module-name`: The name of the module to publish configuration. Leaving blank will publish all modules.
- (optional) `--force`: To force the publishing, overwriting already published files

Enable the specified module.


```
php artisan module:enable blog
```

Disable the specified module.

```
php artisan module:disable blog
```

Generate new middleware class.

```
php artisan module:make-middleware Auth
```

Generate new mailable class.

```
php artisan module:make-mail WelcomeEmail
```

Generate new notification class.

```
php artisan module:make-notification InvoicePaid
```

Update dependencies for the specified module.

```
php artisan module:update ModuleName
```

Update dependencies for all modules.

```
php artisan module:update
```

Show the list of modules.

```
php artisan module:list
```

Generate `.gitkeep` files in empty folders.

```
php artisan module:gitkeep ModuleName
```

Remove `.gitkeep` files in module folders.

```
php artisan module:gitkeep:remove ModuleName
```
<a name="routes"></a>
## Routes

Show the list of modules routes.

```
php artisan module:route:list
```
- Show routes of an spacific module

```
php artisan module:route:list --module="<MODULE_MANE>"
```
### As the regular `php artisan route:list` you can use the others option filters
- The options available are:
  - host (--host="")
  - module (--module="")
  - method (--method="")
  - uri (--uri="")
  - name (--name="")
  - action (--action="")
  - middleware (--middleware="")

<a name="helpers"></a>
## Helpers

### Config

Get Generic module configs.

```php
module_config(<MODULE>.config.name);
```
```
config/
  ├── module/
      ├── example/
          ├── config.php
          ├── example.php
      ├── othermodule/
          ├── config.php
          ├── othermodule.php
```
For example if you have a module with the name "Example"
```php
module_config(example.config.name);
```
Get Specific module configs.
```php
example_config(config.name);
```

<a name="facades"></a>
## Facades

Get all modules.

```php
Module::all();
```

Get all cached modules.

```php
Module::getCached()
```

Get ordered modules. The modules will be ordered by the `priority` key in `module.json` file.

```php
Module::getOrdered();
```

Get scanned modules.

```php
Module::scan();
```

Find a specific module.

```php
Module::find('name');
// OR
Module::get('name');
```

Find a module, if there is one, return the `Module` instance, otherwise throw `Amamarul\Modules\Exeptions\ModuleNotFoundException`.

```php
Module::findOrFail('module-name');
```

Get scanned paths.

```php
Module::getScanPaths();
```

Get all modules as a collection instance.

```php
Module::toCollection();
```

Get modules by the status. 1 for active and 0 for inactive.

```php
Module::getByStatus(1);
```

Check the specified module. If it exists, will return `true`, otherwise `false`.

```php
Module::has('blog');
```

Get all enabled modules.

```php
Module::enabled();
```

Get all disabled modules.

```php
Module::disabled();
```

Get count of all modules.

```php
Module::count();
```

Get module path.

```php
Module::getPath();
```

Register the modules.

```php
Module::register();
```

Boot all available modules.

```php
Module::boot();
```

Get all enabled modules as collection instance.

```php
Module::collections();
```

Get module path from the specified module.

```php
Module::getModulePath('name');
```

Get assets path from the specified module.

```php
Module::assetPath('name');
```

Get config value from this package.

```php
Module::config('composer.vendor');
```

Get used storage path.

```php
Module::getUsedStoragePath();
```

Get used module for cli session.

```php
Module::getUsedNow();
// OR
Module::getUsed();
```

Set used module for cli session.

```php
Module::setUsed('name');
```

Get modules's assets path.

```php
Module::getAssetsPath();
```

Get asset url from specific module.

```php
Module::asset('blog:img/logo.img');
```

Install the specified module by given module name.

```php
Module::install('nwidart/hello');
```

Update dependencies for the specified module.

```php
Module::update('hello');
```

Add a macro to the module repository.

```php
Module::macro('hello', function() {
    echo "I'm a macro";
});
```

Call a macro from the module repository.

```php
Module::hello();
```

Get all required modules of a module

```php
Module::getRequirements('module name');
```

<a name="entity"></a>
## Module Entity

Get an entity from a specific module.

```php
$module = Module::find('blog');
```

Get module name.

```
$module->getName();
```

Get module name in lowercase.

```
$module->getLowerName();
```

Get module name in studlycase.

```
$module->getStudlyName();
```

Get module path.

```
$module->getPath();
```

Get extra path.

```
$module->getExtraPath('Assets');
```

Disable the specified module.

```
$module->disable();
```

Enable the specified module.

```
$module->enable();
```

Delete the specified module.

```
$module->delete();
```

Get an array of module requirements. Note: these should be aliases of the module.

```
$module->getRequires();
```

<a name="namespaces"></a>
## Custom Namespaces

When you create a new module it also registers new custom namespace for `Lang`, `View` and `Config`. For example, if you create a new module named blog, it will also register new namespace/hint blog for that module. Then, you can use that namespace for calling `Lang`, `View` or `Config`. Following are some examples of its usage:

Calling Lang:

```php
Lang::get('blog::group.name');
```

Calling View:

```php
View::make('blog::index')

View::make('blog::partials.sidebar')
```

Calling Config:

```php
Config::get('blog.name')
```

## Publishing Modules

Have you created a laravel modules? Yes, I've. Then, I want to publish my modules. Where do I publish it? That's the question. What's the answer ? The answer is [Packagist](http://packagist.org).

<a name="auto-scan-vendor-directory"></a>
### Auto Scan Vendor Directory

By default the `vendor` directory is not scanned automatically, you need to update the configuration file to allow that. Set `scan.enabled` value to `true`. For example :

```php
// file config/modules.php

return [
  //...
  'scan' => [
    'enabled' => true
  ]
  //...
]
```

You can verify the module has been installed using `module:list` command:

```
php artisan module:list
```

<a name="publishing-modules"></a>
## Publishing Modules
#### Two ways
1.  Console Way
 - Verify available Modules in LaravelModules list
    You can contribute to an existant Module to make improvements
 - Prepare for Github
    ``` bash
        php artisan module:share <module-name>
    ```
 Push to Github
   ``` bash
   php artisan module:push <module-name>
   ```

2. Manual Way
After creating a module and you are sure your module module will be used by other developers. You can push your module to [github](https://github.com) or [bitbucket](https://bitbucket.org) and after that you can submit your module to the packagist website.

You can follow this step to publish your module.

1. Create A Module.
2. Push the module to github.
3. Submit your module to the packagist website.
Submit to packagist is very easy, just give your github repository, click submit and you done.


## Credits

- [Maru Amallo - amamarul](https://github.com/amamarul)
- [All Contributors](../../contributors)


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
