<?php

use App\Core\Router;

$routes = [
    'GET' => [
        '/' => 'HomeController@index',
        '/api/user' => 'UserController@index',
        '/api/category' => 'CategoryController@index',
        '/api/category/show/{id}' => 'CategoryController@read',
        '/api/goal' => 'GoalController@index',
        '/api/goal/show/{id}' => 'GoalController@read',
        '/api/cash/show/' => 'CashController@read',
        '/api/cash/show/{id}' => 'CashController@read',
        '/api/user/show/' => 'UserController@read',
        '/api/user/show/{id}' => 'UserController@read',
        '/cash' => 'CashController@index',
        '/category' => 'CategoryController@index',
        '/goal' => 'GoalController@index',
        '/user' => 'UserController@index',
    ],
    'POST' => [
        '/api/user/create' => 'UserController@create',
        '/api/category/create' => 'CategoryController@create',
        '/api/goal/create' => 'GoalController@create',
        '/api/cash/create' => 'CashController@create',
    ],
    'PUT' => [
        '/api/user/update/{id}' => 'UserController@update',
        '/api/category/update/{id}' => 'CategoryController@update',
        '/api/goal/update/{id}' => 'GoalController@update',
        '/api/cash/update/{id}' => 'CashController@update',
    ],
    'DELETE' => [
        '/api/user/delete/{id}' => 'UserController@delete',
        '/api/category/delete/{id}' => 'CategoryController@delete',
        '/api/goal/delete/{id}' => 'GoalController@delete',
        '/api/cash/delete/{id}' => 'CashController@delete',
    ],
];

$router = new Router($routes, '/finplanner');
$router->handleRequest();