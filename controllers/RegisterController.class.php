<?php


namespace controllers;


use core\Application;
use models\User;

class RegisterController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function register()
    {
        $app = Application::$app;
        $register_data = $app->request->getBody();

        //Controle of het klantnummer bestaat.
        $stmt = $app->db->prepare("SELECT klant.k_idKlant, klant.k_klantnummer, klant.k_achternaam, klant.k_voornaam, adres.a_postcode FROM tbl_klanten AS klant INNER JOIN tbl_adressen AS adres ON klant.k_fk_idAdres = adres.a_idAdres WHERE klant.k_klantnummer = :klantnummer");
        $stmt->bindParam("klantnummer", $register_data['klantnummer'], \PDO::PARAM_INT);
        $stmt->execute();
        $user_data = $stmt->fetch();
        //Bestaat dit klantnummer? Klopt de postcode ook?
        if(($stmt->rowCount() < 1) || ($user_data['a_postcode'] != $register_data['postcode'])){
            die(json_encode(array("status" => false, "error" => "Combinatie klantnummer + postcode onbekend.")));
        }

        //Controle of het klantnummer niet al een account heeft.
        $stmt = $app->db->prepare("SELECT k_klantnummer FROM User WHERE k_klantnummer = :k_klantnummer");
        $stmt->bindParam("k_klantnummer", $register_data['klantnummer'], \PDO::PARAM_INT);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            die(json_encode(array("status" => false, "error" => "Dit klantnummer heeft al een account.")));
        }

        //Validate Email
        if(!filter_var($register_data['email'], FILTER_VALIDATE_EMAIL)){
            die(json_encode(array("status" => false, "error" => "Email niet valide.")));
        }

        if (!filter_var($register_data['password'], FILTER_VALIDATE_REGEXP, array("options"=>array("regexp" => "/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/")))){
            die(json_encode(array("status" => false, "error" => "Wachtwoord voldoet niet aan de eisen.")));
        }

        if($register_data['password'] !== $register_data['password-confirm']){
            die(json_encode(array("status" => false, "error" => "Wachtwoorden komen niet overeen.")));
        }

        if(!isset($register_data['privacy-statement'])){
            die(json_encode(array("status" => false, "error" => "U dient akkoord te gaan met onze privacy voorwaarden.")));
        }

        //Wordt het emailadres al gebruikt door een klant in de ldap?
        if($app->ldap->exists($register_data['klantnummer'], 'klant')){
            $register_data['email'] = $app->ldap->getDataByUID($register_data['klantnummer'], 'klant')['mail'][0];
            $app->db->register_db_user($register_data, 0);
        }

        $app->ldap->register_ldap_user($register_data, $user_data);
    }
}