<?php

use core\Application;
use core\LoadEnv;

spl_autoload_register(function ($className)
{
    $className = str_replace('\\', '/', $className);

    require dirname(__DIR__)."/".$className.".class.php";
});

spl_autoload_register(function ($className)
{
    $className = str_replace('\\', '/', $className);

    require "../database/".$className.".class.php";
});

$app = new Application(dirname(__DIR__));

$app->router->get('/', 'home');
$app->router->get('/register', 'register');
$app->router->get('/login', 'login');
$app->router->get('/about-us', [controllers\AboutController::class, 'load']);

//meme stuff
$app->router->get('/password', function (){
    header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
});
$app->router->get('/secret/admin_login', function (){
    header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
});


$app->run();