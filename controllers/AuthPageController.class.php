<?php


namespace controllers;


use core\Application;
use core\View;

class AuthPageController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware([
            'auth',
        ]);
    }
}