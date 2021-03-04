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
            $path = substr($path, 0, $pos);
        }
        return $path;
    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getBody()
    {
        
    }
}