<?php
$routes = [
    '/' => 'HomeController@index',
    '/user' => 'UserController@index',
    '/user/show/{id}' => 'UserController@show',
    '/category' => 'CategoryController@index',
    '/category/{id}' => 'CategoryController@show',
    '/goal' => 'GoalController@index',
    '/goal/{id}' => 'GoalController@show',
    '/cash' => 'CashController@index',
    '/cash/{id}' => 'CashController@show',
    '/login' => 'LoginController@index',
];
