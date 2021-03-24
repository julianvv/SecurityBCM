<?php


namespace core;


class View
{
    public static function view($name, $args = [])
    {
        return self::generateView($name, $args);
    }

    private static function generateView($viewName, $args)
    {
        $viewContent = self::renderView($viewName);
        $layoutContent = self::getLayout(Application::$app->layout);
        $view = str_replace("{{content}}", $viewContent, $layoutContent);

        foreach ($args as $variable => $value) {
            $view = str_replace("{{{$variable}}}", htmlspecialchars($value), $view);
            $view = str_replace("{!!{$variable}!!}", $value, $view);
        }

        $view = self::clearPlaceholders($view);

        return $view;
    }

    private static function clearPlaceholders($content){
        $content = preg_replace("/{!!\w*!!}/", "", $content);
        return preg_replace("/{{\w*}}/", "", $content);
    }

    private static function renderView($view)
    {
        $viewName = str_replace(".", "/", $view);

        ob_start();
        include_once Application::$ROOT_DIR."/views/$viewName.php";
        return ob_get_clean();
    }

    private static function getLayout($layout)
    {
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }
}