<?php

require_once '../routes/web.php';
require_once '../controllers/HomeController.php';
require_once '../controllers/UserController.php';
require_once '../controllers/CategoryController.php';
require_once '../controllers/GoalController.php';
require_once '../controllers/CashController.php';
require_once '../core/Router.php';

$router = new Router($routes, '/finplanner');
$router->handleRequest();
