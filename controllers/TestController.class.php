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

    public function test()
    {
        echo "Je bent ingelogd";
    }
}