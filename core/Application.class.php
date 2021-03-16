<?php


namespace core;

class Application
{
    public static $app;
    public static $config = [];
    public static $ROOT_DIR;

    public $timer;
    public $router;
    public $request;
    public $response;
    public $session;
    public $db;
    public $ldap;

    //Performance timer
    public $start;
    public $end;

    public function __construct($rootDir, $timer)
    {
        $this->start = hrtime(true);
        $this->timer = $timer;
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