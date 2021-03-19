<?php


namespace middleware;

use core\Application;

class AuthMiddleware extends Middleware
{
    public function execute()
    {
        return Application::$app->session->get('logged_in');
    }
}