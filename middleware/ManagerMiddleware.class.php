<?php


namespace middleware;

use core\Application;

class ManagerMiddleware extends Middleware
{
    public function execute()
    {
        return Application::$app->session->get('employee_group') == 'manager';
    }

}