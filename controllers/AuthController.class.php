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
        $app = Application::$app;

        $login_data = $app->request->getBody();
        $email = $login_data['email'];
        $wachtwoord = $login_data['wachtwoord'];

        if (empty($email) || empty($wachtwoord)){
            $error = "Vul beide velden in...";
        }else {
            if($app->ldap->authenticate($email, $wachtwoord)){
                if(!$app->db->exists($email)){
                    $app->db->register_db_user(array(
                        'email' => $email,
                        'klantnummer' => $app->ldap->getDataByMail($email, 'klant')['uid'][0],
                        'password' => $wachtwoord));
                }
                $status = true;
            }
        }
        if($status ?? false){
            $app->db->startUserSession($email);
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