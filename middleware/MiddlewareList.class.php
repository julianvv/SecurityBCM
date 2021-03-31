<?php


namespace middleware;


use core\Application;

class MiddlewareList
{
    private $middleware;

    public function __construct()
    {
        $this->middleware = array(
            'auth' => AuthMiddleware::class,
            'permission' => PermMiddleware::class,
            'terms' => TermsMiddleware::class,
            'beheerder' => BeheerderMiddleware::class,
            'marketing' => MarketingMiddleware::class,
            'manager' => ManagerMiddleware::class,
            'klantenservice' => KlantenserviceMiddleware::class
        );
    }

    public function getMiddleware()
    {
        return $this->middleware;
    }
}