<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// API route group
$router->group(['prefix' => 'api'], function () use ($router) {
    // login
    $router->post('login', 'AuthController@login');
    //logout
    $router->get('logout', 'AuthController@logout');
    //validate token
    $router->get('validate', 'UserController@validateToken');
    // register user
   	$router->post('user', 'UserController@create');
    // get one user
    $router->get('profile', 'UserController@profile');
    // get all users
    $router->post('users', 'UserController@show');
    // update user
    $router->put('users/{id}', 'UserController@update');
    // delete user
    $router->delete('users/{id}', 'UserController@delete');
    // user privileges
    $router->get('privileges/{module}', 'UserController@privileges');
    // get all movements
    $router->post('movements', 'MovimientoMigratorioController@show');
    // register user
    $router->post('movement', 'MovimientoMigratorioController@create');
    // get egate movements
    $router->post('egate', 'MovimientoMigratorioController@showEGateMovements');
    // update user
    //$router->put('movement/{id}', 'MovimientoMigratorioController@update');
    // delete user
    //$router->delete('movement/{id}', 'MovimientoMigratorioController@delete');
    // get all roles
    $router->post('roles', 'RolesController@show');
    // create role
    $router->post('role', 'RolesController@create');
    // update role
    $router->put('role/{id}', 'RolesController@update');
    // delete role
    $router->delete('role/{id}', 'RolesController@delete');
    // get all privileges
    $router->post('privileges/{id}', 'PrivilegesController@show');
    // create privileges
    $router->post('privilege', 'PrivilegesController@create');
    // update privileges
    $router->put('privilege/{id}', 'PrivilegesController@update');
    // delete privileges
    $router->delete('privilege/{id}', 'PrivilegesController@delete');
    // get all modules
    $router->post('modules', 'ModulesController@show');
    // get all alerts
    $router->post('alerts', 'PersonasSospechosasController@show');
    // create alert
    $router->post('alert', 'PersonasSospechosasController@create');
    // update alert
    $router->put('alert/{id}', 'PersonasSospechosasController@update');
    // delete alert
    $router->delete('alert/{id}', 'PersonasSospechosasController@delete');

    // get gender
    $router->post('gender', 'GeneroController@show');
    // get doc type
    $router->post('doctype', 'TipoDocumentoController@all');
    // get events
    $router->post('events', 'EventosController@show');
    // get countries
    $router->post('countryall', 'PaisesController@all');
    // get roles
    $router->post('rolall', 'RolesController@all');
    // get roles
    $router->get('borderall', 'FronterasController@all');

    // get all roles
    $router->post('types', 'TipoDocumentoController@show');
    // create role
    $router->post('type', 'TipoDocumentoController@create');
    // update role
    $router->put('type/{id}', 'TipoDocumentoController@update');
    // delete role
    $router->delete('type/{id}', 'TipoDocumentoController@delete');

    // get all roles
    $router->post('borders', 'FronterasController@show');
    // create role
    $router->post('border', 'FronterasController@create');
    // update role
    $router->put('border/{id}', 'FronterasController@update');
    // delete role
    $router->delete('border/{id}', 'FronterasController@delete');

    //reporting
    $router->get('movByDocument', 'ReportingController@showByDocument');
    $router->get('movByCountry', 'ReportingController@showByCountry');
    $router->get('movByBorder', 'ReportingController@showByBorder');
    $router->get('movByReason', 'ReportingController@showByReason');
    $router->post('lastSyncBorder/{name}', 'ReportingController@showLastSyncByBorder');

    // get all countries by rows
    $router->post('countries', 'PaisesController@show');
    // create country
    $router->post('country', 'PaisesController@create');
    // update country
    $router->put('country/{id}', 'PaisesController@update');
    // delete country
    $router->delete('country/{id}', 'PaisesController@delete');
});
