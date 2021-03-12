<?php


namespace core;


class Request
{
    public function getUrl()
    {
        $path = $_SERVER['REQUEST_URI'];
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
        return $_POST;
    }

    public function  getIP(){
        return $_SERVER['REMOTE_ADDR'];
    }
}