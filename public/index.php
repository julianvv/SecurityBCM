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
$app->router->post('/forgot-password', [\controllers\ApplicationController::class, 'forgotPassword']);

$app->router->get('/verify', [\controllers\ApplicationController::class, 'showVerifyPage']);
$app->router->post('/verify', [\controllers\ApplicationController::class, 'verifyCode']);
$app->router->get('/akkoord', [\controllers\AuthTermsController::class, 'showAkkoordPage']);
$app->router->post('/akkoord', [\controllers\AuthTermsController::class, 'processAkkoord']);
$app->router->get('/letter', [\controllers\AuthTermsController::class, 'showLetterPage']);
$app->router->get('/voorwaarden', [\controllers\ApplicationController::class, 'showVoorwaardenPage']);

//Intranet pagina's
$app->router->get('/intranet', [\controllers\IntranetController::class, 'showIndex']);
$app->router->post('/intranet/get_data', [\controllers\IntranetController::class, 'get_data']);
$app->router->post('/intranet/get_employees', [\controllers\IntranetController::class, 'get_employee_data']);
//$app->router->get('/intranet/verbruiksmeter', [\controllers\IntranetController::class, 'showVerbruiksmeter']);

$app->router->post('/getSessionData', [\controllers\SessionController::class, 'getSessionData']);

//Navbar buttons
$app->router->post('/uitloggen', [\controllers\AuthController::class, 'logout']);
$app->router->get('/account', [\controllers\AuthTermsController::class, 'showAccountPage']);
$app->router->post('/account', [\controllers\AuthTermsController::class, 'accountChange']);
$app->router->get('/verbruiksmeter', [\controllers\ApplicationController::class, 'showVerbruiksmeter']);

$app->router->get('/login-test', [\controllers\TestController::class, 'LoginTest']);
$app->router->get('/test/idk', [\controllers\TestController::class, 'test']);

$app->run();