<?php


namespace controllers;


use core\Application;
use function Sodium\add;

class Controller
{
    public function login()
    {
        $login_data = Application::$app->request->getBody();
        $klantnummer = $login_data['klantnummer'];
        $wachtwoord = $login_data['wachtwoord'];
        $array = array();
        $error = "Dit is geen geldige combinatie.";

        if(Application::$app->ldap->authenticate($klantnummer, $wachtwoord)){
            $status = true;
        }




        $array['status'] = $status ?? false;
        $array['error'] = $error ?? '';
        die(json_encode($array));
    }

    public function logout()
    {
        echo "Logging out...";
        Application::$app->session->destroy();
        Application::$app->response->redirect('/');
    }
}