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

    public function showLoginPage()
    {
        if($this->prepareMiddleware()){
            return Application::$app->response->redirect('/verbruiksmeter');
        }else{
            return View::view('home', 'mainLayout');
        }
    }

    public function showAccountPage()
    {
        if(!$this->prepareMiddleware()){
            return Application::$app->response->redirect('/');
        }else{
            return View::view('account', 'mainLayout');
        }
    }

    public function showVerifyPage()
    {
        if(!$this->prepareMiddleware()){
            return Application::$app->response->redirect('/');
        }else{
            return View::view('verify', 'mainLayout');
        }
    }

    public function showLetterPage()
    {
        if(!$this->prepareMiddleware()){
            return Application::$app->response->redirect('/');
        }else{
            return View::view('letter', 'mainLayout', [
                'verificatiecode' => '123',
                'klantnaam' => "Ryan Boersma"
            ]);
        }
    }
}