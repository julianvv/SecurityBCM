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
            'permission'
        ]);
    }

    public function showVerbruiksmeter()
    {
        if(!$this->prepareMiddleware()){
            Application::$app->session->setFlash('notification', ['type' => 'alert-danger', 'message' => 'Verifieer eerst uw account!']);
            return Application::$app->response->redirect('/verify');
        }else{
            return View::view('verbruiksmeter', 'mainLayout');
        }
    }
}