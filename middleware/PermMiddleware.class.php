<?php


namespace middleware;


use core\Application;

class PermMiddleware extends Middleware
{
    function execute()
    {
        return Application::$app->session->get('userdata')['permission_granted'] ?? false;
    }
}