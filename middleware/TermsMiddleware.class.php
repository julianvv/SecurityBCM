<?php


namespace middleware;


use core\Application;

class TermsMiddleware extends Middleware
{
    public function execute()
    {
        return Application::$app->session->get('userdata')['akkoord_met_voorwaarden'] ?? false;
    }
}