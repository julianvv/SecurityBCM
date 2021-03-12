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

$app->router->get('/', 'login');
$app->router->get('/intranet', function(){
    echo "intranet";
});

$app->router->get('/intranet/', '../intranet/index');

$app->router->get('/register', 'register');
$app->router->post('/register', [\controllers\RegisterController::class, 'register']);

$app->router->post('/login', [controllers\Controller::class, 'login']);
$app->router->get('/wachtwoordvergeten', 'resetpassword');


//Navbar buttons
$app->router->post('/uitloggen', [controllers\Controller::class, 'logout']);
$app->router->get('/account', 'account');
$app->router->get('/verbruiksmeter', 'verbruiksmeter');


//meme stuff
$app->router->get('/password', function (){
    header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
});
$app->router->get('/secret/admin_login', function (){
    header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
});


$app->run();