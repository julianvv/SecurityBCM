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
            $layoutContent = $this->getLayout("mainLayout");
            $_404Content = $this->renderView("_404");
            Application::$app->response->setStatusCode(404);
            return str_replace("{{content}}", $_404Content, $layoutContent);
        }

        if(is_string($callback))
        {
            return $this->generateView($callback, "mainLayout");
        }else{
            call_user_func($callback);
        }

    }

    public function generateView($viewName, $layout)
    {
        $layoutContent = $this->getLayout($layout);
        $viewContent = $this->renderView($viewName);
        return str_replace("{{content}}", $viewContent, $layoutContent);
    }

    public function renderView($view, $params = [])
    {
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }

    private function getLayout($layout)
    {
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }
}