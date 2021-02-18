<?php


namespace core;


use core\Request;
use core\Router;

class Application
{
    public static $config = [];
    public static $app;
    public $rootDir;

    public $router;
    public $request;
    public $response;

    public function __construct($rootDir, $config)
    {
        $this->rootDir = $rootDir;
        self::$app = $this;
        self::$config = $config;
        $this->request = new Request();
        $this->router = new Router($this->request);
    }

    public function run(){
        $this->router->resolve();
    }

}