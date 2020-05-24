<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use Laravel\Lumen\Routing\Router;

/** @var Router $router */
$router->get('/', 'Controller@callback');

$router->group(['prefix' => 'github'], function (Router $router) {
    $router->get('/user', 'GithubController@userByToken');
    $router->get('/email', 'GithubController@emailByToken');

    $router->group(['middleware' => 'auth'], function (Router $router) {
        $router->get('/auth', 'GithubController@authUrl');
        $router->post('/token', ['middleware' => 'auth:client_secret', 'uses' => 'GithubController@tokenUrl']);
    });
});

$router->group(['prefix' => 'google'], function (Router $router) {
    $router->get('/user', 'GoogleController@userByToken');

    $router->group(['middleware' => 'auth'], function (Router $router) {
        $router->get('/auth', 'GoogleController@authUrl');
        $router->post('/token', ['middleware' => 'auth:client_secret', 'uses' => 'GoogleController@tokenUrl']);
    });
});

$router->group(['prefix' => 'linkedin'], function (Router $router) {
    $router->get('/user/{scope:.*}', 'LinkedinController@userByToken');

    $router->group(['middleware' => 'auth'], function (Router $router) {
        $router->get('/auth', 'LinkedinController@authUrl');
        $router->post('/token', ['middleware' => 'auth:client_secret', 'uses' => 'LinkedinController@tokenUrl']);
    });
});
