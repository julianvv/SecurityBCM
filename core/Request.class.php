<?php


namespace core;


class Request
{
    public function getUrl()
    {
        $path = rtrim($_SERVER['REQUEST_URI'], '/');
        $pos = strpos($path, '?');
        if ($pos !== false)
        {
            return substr($path, 0, $pos);
        }
        return $path;
    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getBody()
    {
        $data = [];
        if ($this->getMethod() === 'get') {
            foreach ($_GET as $key => $value) {
                $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->getMethod() === 'post') {
            foreach ($_POST as $key => $value) {
                $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $data;
    }

    public function  getIP(){
        return $_SERVER['REMOTE_ADDR'];
    }
}