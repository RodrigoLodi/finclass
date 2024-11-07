<?php
$routes = [
    '/' => 'HomeController@index',

    '/api/user' => 'UserController@index',
    '/api/user/show/{id}' => 'UserController@show',
    '/api/user/create' => 'UserController@create',
    '/api/user/show/{id}' => 'UserController@read',
    '/api/user/update/{id}' => 'UserController@update',
    '/api/user/delete/{id}' => 'UserController@delete',


    '/api/category' => 'CategoryController@index',
    '/api/category/create' => 'CategoryController@create',
    '/api/category/show/{id}' => 'CategoryController@read',
    '/api/category/update/{id}' => 'CategoryController@update',
    '/api/category/delete/{id}' => 'CategoryController@delete',

    '/api/goal' => 'GoalController@index',
    '/api/goal/create' => 'GoalController@create',
    '/api/goal/show/{id}' => 'GoalController@read',
    '/api/goal/update/{id}' => 'GoalController@update',
    '/api/goal/delete/{id}' => 'GoalController@delete',

    '/api/cash' => 'CashController@index',
    '/api/cash/create' => 'CashController@create',
    '/api/cash/show/{id}' => 'CashController@read',
    '/api/cash/update/{id}' => 'CashController@update',
    '/api/cash/delete/{id}' => 'CashController@delete',

    //'/login' => 'LoginController@index',
];
