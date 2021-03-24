<?php


namespace controllers;

use middleware\MiddlewareList;

class Controller
{
    private $middlewareList = [];
    protected $failed = [];
    protected $middlewares = [];

    public function __construct()
    {
        $this->middlewareList = (new MiddlewareList())->getMiddleware();
    }

    protected function middleware($array)
    {
        foreach ($array as $middleware){
            array_push($this->middlewares, $middleware);
        }
    }

    public function prepareMiddleware()
    {
        foreach ($this->middlewares as $middleware) {
            $object = new $this->middlewareList[$middleware];
            if(!$object->execute()){
                array_push($this->failed, $middleware);
                return false;
            }
        }
        return true;
    }
}