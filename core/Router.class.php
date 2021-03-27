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

        if(preg_match("/\/intranet\/?/", $path)){
            Application::$app->layout = "employeeLayout";
        }else{
            Application::$app->layout = "customerLayout";
        }

        if(!$callback)
        {
            Application::$app->response->setStatusCode(404);
            return View::view('_404', [
                'title' => '404',
            ]);
        }

        if(is_string($callback))
        {
            return View::view($callback);
        }

        $controller = new $callback[0];

        return $controller->{$callback[1]}($callback[2] ?? null);
    }
}