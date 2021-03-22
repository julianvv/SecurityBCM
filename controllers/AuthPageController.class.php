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
            return View::view('home', 'mainLayout', [
                'title' => 'Home',
            ]);
        }
    }

    public function showAccountPage()
    {
        if(!$this->prepareMiddleware()){
            return Application::$app->response->redirect('/');
        }else{
            return View::view('account', 'mainLayout', [
                'title' => 'Account',
            ]);
        }
    }

    public function showVerifyPage()
    {
        if(!$this->prepareMiddleware()){
            return Application::$app->response->redirect('/');
        }else{
            return View::view('verify', 'mainLayout', [
                'title' => 'Verifiëren',
            ]);
        }
    }

    public function showLetterPage()
    {
        //TODO: Data ophalen uit DB;

        if(!$this->prepareMiddleware()){
            return Application::$app->response->redirect('/');
        }else{
            return View::view('letter', 'mainLayout', [
                'title' => 'Brief',
                'verificatiecode' => '3210987',
                'klantnaam' => "Kevin Schiphof"
            ]);
        }
    }
}