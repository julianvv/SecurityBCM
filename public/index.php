<?php
use core\Application;
use core\LoadEnv;

spl_autoload_register(function ($className)
{
    $className = str_replace('\\', '/', $className);

    require dirname(__DIR__)."/".$className.".class.php";
});

$app = new Application(dirname(__DIR__), false);

$app->router->get('', [\controllers\ApplicationController::class, 'showLoginPage']);
$app->router->get('/', [\controllers\ApplicationController::class, 'showLoginPage']);

$app->router->post('/login', [\controllers\AuthController::class, 'login']);
$app->router->post('/register', [\controllers\RegisterController::class, 'register']);

$app->router->get('/verify', [\controllers\AuthTermsController::class, 'showVerifyPage']);
$app->router->get('/akkoord', [\controllers\AuthPageController::class, 'showAkkoordPage']);
$app->router->get('/letter', [\controllers\AuthTermsController::class, 'showLetterPage']);

//Intranet pagina's
$app->router->get('/intranet', [\controllers\IntranetController::class, 'showIndex']);
$app->router->get('/intranet/verbruiksmeter', [\controllers\IntranetController::class, 'showVerbruiksmeter']);

$app->router->post('/getSessionData', [\controllers\SessionController::class, 'getSessionData']);

//Navbar buttons
$app->router->post('/uitloggen', [\controllers\AuthController::class, 'logout']);
$app->router->get('/account', [\controllers\AuthTermsController::class, 'showAccountPage']);
$app->router->get('/verbruiksmeter', [\controllers\ApplicationController::class, 'showVerbruiksmeter']);

$app->router->get('/login-test', [\controllers\TestController::class, 'LoginTest']);
$app->router->get('/test/idk', [\controllers\TestController::class, 'test']);

$app->run();