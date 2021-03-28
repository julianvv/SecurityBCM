<?php


namespace controllers;


use core\Application;
use core\View;

class ApplicationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware([
            'auth',
            'terms',
            'permission',
        ]);
    }

    public function forgotPassword()
    {
        $data = Application::$app->request->getBody();
        
    }

    public function showVoorwaardenPage(){
        return View::view('voorwaarden', ['title' => 'Voorwaarden']);
    }


    public function showVerifyPage()
    {
        $this->prepareMiddleware();
        if($this->failed[0] == 'permission'){
            return View::view('verify', [
                'title' => 'Verifiëren',
            ]);
        }else if($this->failed[0] == 'terms'){
            return Application::$app->response->redirect('/akkoord');
        }else{
            return Application::$app->response->redirect('/account');
        }
    }

    public function showLoginPage()
    {
        $this->prepareMiddleware();
        if($this->failed[0] != 'auth'){
            return Application::$app->response->redirect('/account');
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