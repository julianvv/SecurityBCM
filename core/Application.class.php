<?php


namespace core;


use core\Request;
use core\Router;

class Application
{
    public static $config = [];
    public static $app;
    public static $ROOT_DIR;

    public $router;
    public $request;
    public $response;

    public function __construct($rootDir)
    {
        self::$ROOT_DIR = $rootDir;
        self::$app = $this;
        self::$config = LoadEnv::Load($rootDir);
        $this->request = new Request();
        $this->router = new Router($this->request);
        $this->response = new Response();
    }

    public function run()
    {
        echo $this->router->resolve();
    }

}