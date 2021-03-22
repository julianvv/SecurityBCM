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
            $_404Content = $this->renderView("_404");
            $layoutContent = $this->getLayout("mainLayout");
            Application::$app->response->setStatusCode(404);
            return str_replace("{{content}}", $_404Content, $layoutContent);
        }

        if(is_string($callback))
        {
            return View::view($callback, 'mainLayout');
        }

        $controller = new $callback[0];

        return $controller->{$callback[1]}($callback[2] ?? null);
    }

    public function generateView($viewName, $layout)
    {
        $viewContent = $this->renderView($viewName);
        $layoutContent = $this->getLayout($layout);
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