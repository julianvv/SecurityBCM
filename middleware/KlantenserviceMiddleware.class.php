<?php


namespace middleware;

use core\Application;

class KlantenserviceMiddleware extends Middleware
{
    public function execute()
    {
        return Application::$app->session->get('employee_group') == 'klantenservice';
    }

}