<?php


namespace core;


class View
{
    public static function view($name, $layout, $args = [])
    {
        return self::generateView($name, $layout, $args);
    }

    private static function generateView($viewName, $layout, $args)
    {
        $viewContent = self::renderView($viewName);
        $layoutContent = self::getLayout($layout);
        $view = str_replace("{{content}}", $viewContent, $layoutContent);

        foreach ($args as $variable => $value) {
            $view = str_replace("{{{$variable}}}", $value, $view);
        }

        $view = self::clearPlaceholders($view);

        return $view;
    }

    private static function clearPlaceholders($content){
        return preg_replace("/{{\w*}}/", "", $content);
    }

    private static function renderView($view, $params = [])
    {
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }

    private static function getLayout($layout)
    {
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }
}