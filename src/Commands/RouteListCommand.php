<?php

namespace Amamarul\Modules\Commands;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class RouteListCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:route:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all registered routes';

    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * An array of all the registered routes.
     *
     * @var \Illuminate\Routing\RouteCollection
     */
    protected $routes;

    /**
     * The table headers for the command.
     *
     * @var array
     */
    protected $headers = ['Domain', 'Module', 'Method', 'URI', 'Name', 'Action', 'Middleware'];

    /**
     * Create a new route command instance.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function __construct(Router $router)
    {
        parent::__construct();

        $this->router = $router;
        $this->routes = $router->getRoutes();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        if (count($this->routes) == 0) {
            return $this->error("Your application doesn't have any routes.");
        }

        $this->displayRoutes($this->getRoutes());
    }

    /**
     * Compile the routes into a displayable format.
     *
     * @return array
     */
    protected function getRoutes()
    {
        $routes = collect($this->routes)->map(function ($route) {
            if ($this->getRouteInformation($route)['module'] !== null) {
                return $this->getRouteInformation($route);
            }
        })->all();

        if ($sort = $this->option('sort')) {
            $routes = $this->sortRoutes($sort, $routes);
        }

        if ($this->option('reverse')) {
            $routes = array_reverse($routes);
        }

        return array_filter($routes);
    }

    /**
     * Get the route information for a given route.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @return array
     */
    protected function getRouteInformation(Route $route)
    {
        return $this->filterRoute([
            'host'   => $route->domain(),
            'module' => isset($route->action['module']) ? $route->action['module'] : null,
            'method' => implode('|', $route->methods()),
            'uri'    => $route->uri(),
            'name'   => $route->getName(),
            'action' => $route->getActionName(),
            'middleware' => $this->getMiddleware($route),
        ]);
    }

    /**
     * Sort the routes by a given element.
     *
     * @param  string  $sort
     * @param  array  $routes
     * @return array
     */
    protected function sortRoutes($sort, $routes)
    {
        return Arr::sort($routes, function ($route) use ($sort) {
            return $route[$sort];
        });
    }

    /**
     * Display the route information on the console.
     *
     * @param  array  $routes
     * @return void
     */
    protected function displayRoutes(array $routes)
    {
        $this->table($this->headers, $routes);
    }

    /**
     * Get before filters.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @return string
     */
    protected function getMiddleware($route)
    {
        $m = collect($route->gatherMiddleware())->map(function ($middleware) {
            return $middleware instanceof Closure ? 'Closure' : $middleware;
        })->toArray();
        return implode(",\n",$m);
    }

    /**
     * Filter the route by URI and / or name.
     *
     * @param  array  $route
     * @return array|null
     */
    protected function filterRoute(array $route)
    {
        if (($this->option('name') && ! Str::contains($route['name'], $this->option('name'))) ||
             $this->option('path') && ! Str::contains($route['uri'], $this->option('path')) ||
             $this->option('method') && ! Str::contains($route['method'], $this->option('method')) ||
             $this->option('module') && ! Str::contains($route['module'], $this->option('module'))) {
            return;
        }

        return $route;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['module', null, InputOption::VALUE_OPTIONAL, 'Filter the routes by module.'],
            ['method', null, InputOption::VALUE_OPTIONAL, 'Filter the routes by method.'],

            ['name', null, InputOption::VALUE_OPTIONAL, 'Filter the routes by name.'],

            ['path', null, InputOption::VALUE_OPTIONAL, 'Filter the routes by path.'],

            ['reverse', 'r', InputOption::VALUE_NONE, 'Reverse the ordering of the routes.'],

            ['sort', null, InputOption::VALUE_OPTIONAL, 'The column (host, method, uri, name, action, middleware) to sort by.', 'uri'],
        ];
    }
}
