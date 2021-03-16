<?php


namespace core;

class Application
{
    public static $app;
    public static $config = [];
    public static $ROOT_DIR;

    public $router;
    public $request;
    public $response;
    public $session;
    public $db;
    public $ldap;

    public function __construct($rootDir)
    {
        self::$ROOT_DIR = $rootDir;
        self::$app = $this;
        self::$config = LoadEnv::Load($rootDir);
        $this->request = new Request();
        $this->session = new Session();
        $this->router = new Router($this->request);
        $this->response = new Response();

        $this->ldap = new Ldap();
        $this->db = new Database();
    }

    public function run()
    {
        echo $this->router->resolve();
    }

}