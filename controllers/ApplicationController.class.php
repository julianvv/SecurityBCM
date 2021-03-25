<?php


namespace controllers;


use core\Application;
use core\View;

class ApplicationController extends Controller
{
    public function __construct()
    {
        Application::$app->layout = 'customerLayout';
        parent::__construct();
        $this->middleware([
            'auth',
            'terms',
            'permission',
        ]);
    }

    public function showVoorwaardenPage(){
        return View::view('voorwaarden', ['title' => 'Voorwaarden']);
    }

    public function showLoginPage()
    {
        if($this->prepareMiddleware()){
            return Application::$app->response->redirect('/verbruiksmeter');
        }else{
            return View::view('home', [
                'title' => 'Home',
            ]);
        }
    }

    public function showVerbruiksmeter()
    {
        if(!$this->prepareMiddleware()){
            if($this->failed[0] === "terms"){
                return Application::$app->response->redirect('/akkoord');
            }
            Application::$app->session->setFlash('notification', ['type' => 'alert-danger', 'message' => 'Verifieer eerst uw account!']);
            return Application::$app->response->redirect('/verify');
        }else{
            return View::view('verbruiksmeter', ['title' => 'Verbruiksmeter']);
        }
    }
}