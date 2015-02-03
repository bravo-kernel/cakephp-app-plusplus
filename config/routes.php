<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 *
 */
use Cake\Core\Plugin;
use Cake\Filesystem\Folder;
use Cake\Routing\Router;

/**
 * The default class to use for all routes
 */
Router::defaultRouteClass('InflectedRoute');

/**
 * Prefixed API routes (served using Crud.ApiListener).
 */
Router::prefix('api', function ($routes) {

    //  Enable .json extension parsing
    $routes->extensions(['json', 'xml']);

    // mapResources for all Controller files found in /src/Controller/Api
    $dir = new Folder(APP . 'Controller' . DS . 'Api');
    $controllerFiles = $dir->find('.*Controller\.php');
    if ($controllerFiles) {
        foreach ($controllerFiles as $controllerFile) {
            $routes->resources(substr($controllerFile, 0, strlen($controllerFile) - 14));
         }
    }
});

/**
 * Default routes used by the frontend/client application.
 */
Router::scope('/', function ($routes) {

    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `InflectedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'InflectedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'InflectedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks('InflectedRoute');

});

/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
