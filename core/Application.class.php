<?php


namespace core;

use middleware\AuthMiddleware;

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
    public $layout;

    //Performance timer
    public $start;

    public function __construct($rootDir, $timer)
    {
        $this->start = hrtime(true);
        $this->timer = $timer;
        self::$ROOT_DIR = $rootDir;
        self::$app = $this;
        self::$config = LoadEnv::Load($rootDir);

        $this->ldap = new Ldap();
        $this->db = new Database();

        $this->session = new Session();
        $this->request = new Request();
        $this->router = new Router($this->request);
        $this->response = new Response();

        $this->db->refreshSession();
        $this->session->setFlash("notification", ["type"=>"alert-danger","message"=>"Jo, je hebt geen verbinding met de database bitch. Ik weet niet wat ik moet doen *sadface*"]);
    }

    public function run()
    {
        echo $this->router->resolve();
    }

}