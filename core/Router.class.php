<?php


namespace core;
use core\Request;

class Router
{
    protected $request;
    private $routes;
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get($path, $callback)
    {

        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function put($path, $callback)
    {
        $this->routes['put'][$path] = $callback;
    }

    public function delete($path, $callback)
    {
        $this->routes['delete'][$path] = $callback;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function resolve()
    {
        $path = $this->request->getUrl();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;
        if(!$callback)
        {
            echo "Error 404";
            exit;
        }
        echo $callback;
    }

    public function renderView($view, $params = [])
    {
        ob_start();
        include Application::$app->rootDir.'/views/layouts/standard.php';
        
        return ob_get_clean();
    }
}