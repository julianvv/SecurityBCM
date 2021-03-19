<?php
use core\Application;
use core\LoadEnv;

spl_autoload_register(function ($className)
{
    $className = str_replace('\\', '/', $className);

    require dirname(__DIR__)."/".$className.".class.php";
});

$app = new Application(dirname(__DIR__), false);

$app->router->get('/', [\controllers\AuthPageController::class, 'showLoginPage']);
$app->router->post('/login', [\controllers\AuthController::class, 'login']);
$app->router->post('/register', [\controllers\RegisterController::class, 'register']);
$app->router->get('/verify', [\controllers\AuthPageController::class, 'showVerifyPage']);
$app->router->get('/letter', [\controllers\AuthPageController::class, 'showLetterPage']);

$app->router->get('/intranet', function(){
    echo "intranet";
});
$app->router->get('/intranet/', '../intranet/index');


$app->router->post('/getSessionData', [\controllers\SessionController::class, 'getSessionData']);




//Navbar buttons
$app->router->post('/uitloggen', [\controllers\AuthController::class, 'logout']);
$app->router->get('/account', [\controllers\AuthPageController::class, 'showAccountPage']);
$app->router->get('/verbruiksmeter', [\controllers\ApplicationController::class, 'showVerbruiksmeter']);




$app->router->get('/login-test', [\controllers\TestController::class, 'test']);
$app->run();