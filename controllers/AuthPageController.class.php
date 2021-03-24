<?php


namespace controllers;


use core\Application;
use core\View;

class AuthPageController extends Controller
{
    public function __construct()
    {
        Application::$app->layout = 'customerLayout';
        parent::__construct();
        $this->middleware([
            'auth'
        ]);
    }

    public function showAkkoordPage()
    {
        if(!$this->prepareMiddleware()){
            return Application::$app->response->redirect('/');
        }else{
            return View::view('akkoord', [
                'title' => 'Voorwaarden'
            ]);
        }
    }
}