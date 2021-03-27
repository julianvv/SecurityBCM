<?php


namespace controllers;


use core\Application;
use core\View;

class AuthTermsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware([
            'auth',
            'terms'
        ]);
    }

    public function showAccountPage()
    {
        if(!$this->prepareMiddleware()){
            return self::redirect();
        }else{
            $app = Application::$app;
            $stmt = $app->db->prepare("SELECT  klant.k_klantnummer, klant.k_voornaam, klant.k_achternaam, adres.a_straatnaam, adres.a_huisnummer, adres.a_postcode, adres.a_plaatsnaam, user.email
                                           FROM ((tbl_adressen as adres
                                           INNER JOIN tbl_klanten as klant ON adres.a_idAdres = klant.k_fk_idAdres)
                                           INNER JOIN User as user ON user.k_klantnummer = klant.k_klantnummer)
                                           WHERE klant.k_klantnummer = :klantnummer");
            $stmt->bindParam('klantnummer', $app->session->get('userdata')['k_klantnummer']);
            $stmt->execute();
            $klant_gegevens = $stmt->fetch();

            $data['voornaam'] = $klant_gegevens['k_voornaam'];
            $data['achternaam'] = $klant_gegevens['k_achternaam'];
            $data['straatnaam'] = $klant_gegevens['a_straatnaam'];
            $data['huisnummer'] = $klant_gegevens['a_huisnummer'];
            $data['postcode'] = $klant_gegevens['a_postcode'];
            $data['plaatsnaam'] = $klant_gegevens['a_plaatsnaam'];
            $data['klantnummer'] = $klant_gegevens['k_klantnummer'];
            $data['email'] = $klant_gegevens['email'];

            $data['title'] = "Mijn Account";

            return View::view('account', $data);
        }
    }

    public function showAkkoordPage()
    {
        $this->prepareMiddleware();
        if($this->failed[0] == 'auth'){
            return Application::$app->response->redirect('/');
        }else if($this->failed[0] == 'terms'){
            return View::view('akkoord', [
                'title' => 'Voorwaarden'
            ]);
        }else{
            Application::$app->session->setFlash('notification', ['type' => 'alert-danger', 'message' => 'Uw heeft al akkoord gegeven.']);
            return Application::$app->response->redirect('/verbruiksmeter');
        }
    }

    public function showLetterPage()
    {
        if(!$this->prepareMiddleware()){
            return self::redirect();
        }else{
            $app = Application::$app;
            $stmt = $app->db->prepare("SELECT  klant.k_klantnummer, klant.k_voornaam, klant.k_achternaam, adres.a_straatnaam, adres.a_huisnummer, adres.a_postcode, adres.a_plaatsnaam, kv.v_code 
                                           FROM (((tbl_adressen as adres
                                           INNER JOIN tbl_klanten as klant ON adres.a_idAdres = klant.k_fk_idAdres)
                                           INNER JOIN User as user ON user.k_klantnummer = klant.k_klantnummer)
                                           INNER JOIN tbl_klanten_verificatie as kv ON kv.v_fk_idUser = user.id)
                                           WHERE klant.k_klantnummer = :klantnummer");
            $stmt->bindParam('klantnummer', $app->session->get('userdata')['k_klantnummer']);
            $stmt->execute();
            $klant_gegevens = $stmt->fetch();

            $data['voornaam'] = $klant_gegevens['k_voornaam'];
            $data['achternaam'] = $klant_gegevens['k_achternaam'];
            $data['straatnaam'] = $klant_gegevens['a_straatnaam'];
            $data['huisnummer'] = $klant_gegevens['a_huisnummer'];
            $data['postcode'] = $klant_gegevens['a_postcode'];
            $data['plaatsnaam'] = $klant_gegevens['a_plaatsnaam'];
            $data['klantnummer'] = $klant_gegevens['k_klantnummer'];
            $data['verificatiecode'] = $klant_gegevens['v_code'];

            //$data['datum'] = strftime("%A %e %B, %Y");
            $data['datum'] = strftime("%e-%m-%Y");

            $data['title'] = 'Brief';

            return View::view('letter', $data);
        }
    }

    public function redirect()
    {
        switch ($this->failed[0]){
            case 'auth':
                return Application::$app->response->redirect('/');
            case 'terms':
                return Application::$app->response->redirect('/akkoord');
        }
        return null;
    }
}