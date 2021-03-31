<?php


namespace middleware;

use core\Application;

class MarketingMiddleware extends Middleware
{
    public function execute()
    {
        return Application::$app->session->get('employee_group') == 'marketing';
    }

}