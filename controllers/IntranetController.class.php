<?php


namespace controllers;


use core\Application;
use core\View;

class IntranetController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Application::$app->layout = "employeeLayout";
    }

    public function showIndex()
    {
        return View::view('intranet.home');
    }

    public function showVerbruiksmeter()
    {
        return View::view('intranet.verbruiksmeter');
    }
}