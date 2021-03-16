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

$app = new Application(dirname(__DIR__), false);

$app->router->get('/', 'home');
$app->router->get('/intranet', function(){
    echo "intranet";
});

$app->router->get('/intranet/', '../intranet/index');

$app->router->post('/login', [controllers\Controller::class, 'login']);
$app->router->post('/register', [\controllers\RegisterController::class, 'register']);

$app->router->post('/forgotpassword', function (){
    die(json_encode(array("key" => "lol")));
});


$app->router->post('/getSessionData', [\controllers\SessionController::class, 'getSessionData']);

//Navbar buttons
$app->router->post('/uitloggen', [controllers\Controller::class, 'logout']);
$app->router->get('/account', 'account');
$app->router->get('/verbruiksmeter', 'verbruiksmeter');

$app->run();