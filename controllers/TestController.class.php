<?php


namespace controllers;


class TestController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware([
            'auth'
        ]);
    }

    public function loginTest()
    {
        if($this->prepareMiddleware()){
            echo "Ingelogd";
        }else{
            echo "Fail";
        }
    }

    public function test($test)
    {
        var_dump($test);
    }
}