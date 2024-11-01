<?php

require_once '../routes/web.php';
require_once '../controllers/HomeController.php';
require_once '../controllers/UserController.php';
require_once '../controllers/ProductController.php';
require_once '../core/Router.php';

$router = new Router($routes, '/site-imperio2');
$router->handleRequest();
