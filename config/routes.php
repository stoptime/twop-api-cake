<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/*
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 */
/** @var \Cake\Routing\RouteBuilder $routes */
$routes->setRouteClass(DashedRoute::class);

$routes->scope('/', function (RouteBuilder $builder) {
    /*
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, templates/Pages/home.php)...
     */
    $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    /*
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $builder->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    $builder->connect('/shows', ['controller' => 'Shows', 'action' => 'index']);

    $builder->connect('/shows/{slug}',
        ['controller' => 'Shows', 'action' => 'view'])
        ->setMethods(['GET'])
        ->setPass(['slug'])
        ->setPatterns(['slug' => '[a-z0-9-_]+']);

    $builder->connect('/shows/{slug}/seasons',
        ['controller' => 'Shows', 'action' => 'getSeasonsForShow'])
        ->setMethods(['GET'])
        ->setPass(['slug'])
        ->setPatterns([
            'slug' => '[a-z0-9-_]+',
        ]);

    $builder->connect('/shows/{slug}/seasons/{season}',
        ['controller' => 'Shows', 'action' => 'getSeason'])
        ->setMethods(['GET'])
        ->setPass(['slug', 'season'])
        ->setPatterns([
            'slug' => '[a-z0-9-_]+',
            'season' => '[a-zA-Z0-9-_]+'
        ]);

    // same as above
    $builder->connect('/shows/{slug}/seasons/{season}/episodes',
        ['controller' => 'Shows', 'action' => 'getSeason'])
        ->setMethods(['GET'])
        ->setPass(['slug', 'season'])
        ->setPatterns([
            'slug' => '[a-z0-9-_]+',
            'season' => '[a-zA-Z0-9-_]+'
        ]);

    // same as above
    $builder->connect('/shows/{slug}/seasons/{season}/episodes/{episode}',
        ['controller' => 'Shows', 'action' => 'getEpisode'])
        ->setMethods(['GET'])
        ->setPass(['slug', 'season', 'episode'])
        ->setPatterns([
            'slug' => '[a-z0-9-_]+',
            'season' => '[a-zA-Z0-9-_]+',
            'episode' => '[a-zA-Z0-9-_]+'
        ]);

    // primarily for the 1Pager calls
    $builder->connect('/episode-from-slug/{slug1}/{slug2}',
        ['controller' => 'Shows', 'action' => 'getEpisodeFromSlug'])
        ->setMethods(['GET'])
        ->SetPass(['slug1', 'slug2'])
        ->setPatterns([
            'slug1' => '[a-z0-9-_]+',
            'slug2' => '[a-z0-9-_]+'
        ]);

    /*
     * Connect catchall routes for all controllers.
     *
     * The `fallbacks` method is a shortcut for
     *
     * ```
     * $builder->connect('/:controller', ['action' => 'index']);
     * $builder->connect('/:controller/:action/*', []);
     * ```
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $builder->fallbacks();
});

/*
 * If you need a different set of middleware or none at all,
 * open new scope and define routes there.
 *
 * ```
 * $routes->scope('/api', function (RouteBuilder $builder) {
 *     // No $builder->applyMiddleware() here.
 *     // Connect API actions here.
 * });
 * ```
 */
