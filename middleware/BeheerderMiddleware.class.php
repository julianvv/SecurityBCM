<?php


namespace middleware;

use core\Application;

class BeheerderMiddleware extends Middleware
{
    public function execute()
    {
        return Application::$app->session->get('employee_group') == 'beheerder';
    }
}