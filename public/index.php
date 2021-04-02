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
$app->router->get('/reset-password', [\controllers\ApplicationController::class, 'showResetPage']);


$app->router->post('/login', [\controllers\AuthController::class, 'login']);
$app->router->post('/register', [\controllers\RegisterController::class, 'register']);
$app->router->post('/forgot-password', [\controllers\ApplicationController::class, 'forgotPassword']);
$app->router->post('/reset-password', [\controllers\ApplicationController::class, 'processResetPage']);

$app->router->get('/verify', [\controllers\ApplicationController::class, 'showVerifyPage']);
$app->router->post('/verify', [\controllers\ApplicationController::class, 'verifyCode']);
$app->router->get('/akkoord', [\controllers\AuthTermsController::class, 'showAkkoordPage']);
$app->router->post('/akkoord', [\controllers\AuthTermsController::class, 'processAkkoord']);
$app->router->get('/letter', [\controllers\AuthTermsController::class, 'showLetterPage']);
$app->router->get('/voorwaarden', [\controllers\ApplicationController::class, 'showVoorwaardenPage']);

//Intranet pagina's
$app->router->get('/intranet', [\controllers\IntranetController::class, 'showIndex']);
$app->router->get('/intranet/rollen', [\controllers\IntranetController::class, 'showRolePage']);
$app->router->get('/intranet/account', [\controllers\IntranetController::class, 'showAccountPage']);
$app->router->post('/intranet/logout', [\controllers\IntranetController::class, 'logout']);
$app->router->post('/intranet/get_data', [\controllers\IntranetController::class, 'get_data']);
$app->router->post('/intranet/get_logs', [\controllers\IntranetController::class, 'getEmployeeLog']);
$app->router->post('/intranet/get_employees', [\controllers\IntranetController::class, 'get_employee_data']);
$app->router->post('/intranet/get_groups', [\controllers\IntranetController::class, 'get_groups']);
$app->router->post('/intranet/delete_employee', [\controllers\IntranetController::class, 'delete_employee']);
$app->router->post('/intranet/change_employee', [\controllers\IntranetController::class, 'change_employee']);

$app->router->post('/intranet/change_password', [\controllers\IntranetController::class, 'change_password']);
$app->router->post('/intranet/create_employee', [\controllers\IntranetController::class, 'create_employee']);
$app->router->post('/intranet/intranetData', [\controllers\SessionController::class, 'intranetData']);

$app->router->post('/verbruiksmeter', [\controllers\SessionController::class, 'verbruiksmeterData']);

//Navbar buttons
$app->router->post('/uitloggen', [\controllers\AuthController::class, 'logout']);
$app->router->get('/account', [\controllers\AuthTermsController::class, 'showAccountPage']);
$app->router->post('/account', [\controllers\AuthTermsController::class, 'accountChange']);
$app->router->get('/verbruiksmeter', [\controllers\ApplicationController::class, 'showVerbruiksmeter']);

$app->router->get('/login-test', [\controllers\TestController::class, 'LoginTest']);
$app->router->get('/test/idk', [\controllers\TestController::class, 'test']);

$app->run();