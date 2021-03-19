<?php


namespace controllers;


use core\Application;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        $login_data = Application::$app->request->getBody();
        $email = $login_data['email'];
        $wachtwoord = $login_data['wachtwoord'];
        $array = Array();

        if (empty($email) || empty($wachtwoord)){
            $error = "Vul beide velden in...";
        }else if(Application::$app->ldap->authenticate($email, $wachtwoord)){
            $status = true;
        }

        $array['status'] = $status ?? false;
        $array['error'] = $error ?? "Dit is geen geldige combinatie.";
        die(json_encode($array));
    }

    public function logout()
    {
        echo "Logging out...";
        Application::$app->session->destroy();
        Application::$app->response->redirect('/');
    }
}