<?php


namespace controllers;


use core\Application;
use models\User;

class RegisterController
{

    public function register()
    {
        $app = Application::$app;

        $register_data = $app->request->getBody();

        $stmt = $app->db->prepare("SELECT * FROM tbl_klanten WHERE k_klantnummer = :klantnummer");
        $stmt->bindParam("klantnummer", $register_data['klantnummer'], \PDO::PARAM_INT);
        $stmt->execute();
        $user_data = $stmt->fetch();
        if($stmt->rowCount() < 1){
            die(json_encode(array("status" => false, "error" => "Klantnummer onbekend.")));
        }

        //Is the customernumber used for an account?
        $stmt = $app->db->prepare("SELECT k_klantnummer FROM User WHERE k_klantnummer = :k_klantnummer");
        $stmt->bindParam("k_klantnummer", $register_data['klantnummer'], \PDO::PARAM_INT);
        $stmt->execute();
        if($stmt->rowCount() != 0){
            die(json_encode(array("status" => false, "error" => "Dit klantnummer heeft al een account.")));
        }
        //TODO: Validation
        if(!filter_var($register_data['email'], FILTER_VALIDATE_EMAIL)){
            die(json_encode(array("status" => false, "error" => "Email niet valide.")));
        }

        //Does customer have an account?
        if($app->ldap->exists($register_data['email'])){
            die(json_encode(array("status" => false, "error" => "Email al in gebruik!")));
        }

        if($register_data['password'] !== $register_data['password-confirm']){
            die(json_encode(array("status" => false, "error" => "Wachtwoorden komen niet overeen.")));
        }

        Application::$app->ldap->register_user($register_data, $user_data);
    }
}