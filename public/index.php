<?php

use core\Application;
use core\LoadEnv;

spl_autoload_register(function ($className)
{
    $className = str_replace('\\', '/', $className);

    require "../".$className.".class.php";
});

spl_autoload_register(function ($className)
{
    $className = str_replace('\\', '/', $className);

    require "../database/".$className.".class.php";
});

$config = LoadEnv::load(dirname(__DIR__));

$app = new Application(dirname(__DIR__) ,$config);

$app->router->get('/', 'home');
$app->router->get('/register', 'registerGuest');
$app->router->get('/login', 'login');


$app->run();