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
            'terms' => TermsMiddleware::class
        );
    }

    public function getMiddleware()
    {
        return $this->middleware;
    }
}