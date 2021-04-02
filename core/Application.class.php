<?php


namespace core;

class Application
{
    public static $app;
    public static $config = [];
    public static $ROOT_DIR = '';

    public $timer;
    public $router;
    public $request;
    public $response;
    public $session;
    public $db;
    public $ldap;
    public $layout;
    public $logger;

    //Performance timer
    public $start;

    public function __construct($rootDir, $timer)
    {
        self::$ROOT_DIR = $rootDir;
        self::$app = $this;
        self::$config = LoadEnv::Load($rootDir);

        $this->logger = new Logger();
        $this->start = hrtime(true);
        $this->timer = $timer;

        $this->session = new Session();
        $this->request = new Request();
        $this->router = new Router($this->request);
        $this->response = new Response();

        $this->ldap = new Ldap();
        $this->db = new Database();

        if($this->db->connected()){
            $this->db->refreshSession();
        }
    }

    public function run()
    {
        echo $this->router->resolve();
    }

}