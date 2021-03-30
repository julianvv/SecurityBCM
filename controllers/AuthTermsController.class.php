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
            $data['permission'] = $app->session->get('userdata')['akkoord_met_voorwaarden'];

            return View::view('account', $data);
        }
    }

    public function accountChange()
    {
        $data = Application::$app->request->getBody();

        switch($data['type']){
            case "password":
                return $this->changePassword($data);
            case "permission":
                return $this->changePermission($data);
        }
        return die(json_encode(array("status" => false, "message" => "Error: type undefined")));
    }

    private function changePassword($data)
    {
        $email = Application::$app->session->get('userdata')['email'];

        if(Application::$app->ldap->authenticate($email, $data['password'])){
            $status = true;
        }else{
            die(json_encode(array("status" => false, "message" => "Huidig wachtwoord incorrect.")));
        }

        if($data['password_new'] == $data['password']){
            die(json_encode(array("status" => false, "message" => "U kunt uw wachtwoord niet veranderen naar uw huidig wachtwoord.")));
        }

        if (!filter_var($data['password_new'], FILTER_VALIDATE_REGEXP, array("options"=>array("regexp" => "/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/")))){
            die(json_encode(array("status" => false, "message" => "Nieuw wachtwoord voldoet niet aan de eisen.")));
        }

        if($data['password_new'] !== $data['password_new_confirm']){
            die(json_encode(array("status" => false, "message" => "Wachtwoorden komen niet overeen.")));
        }

        Application::$app->ldap->changePassword($email, $data['password_new_confirm'], 'klant');

        Application::$app->session->setFlash('notification', ['type' => 'alert-success', 'message' => 'Uw wachtwoord is successvol gewijzigd.']);
        return die(json_encode(array("status" => true)));
    }

    private function changePermission($data)
    {
        $oldPermission = Application::$app->session->get('userdata')['akkoord_met_voorwaarden'];
        if($data['newPermission'] == $oldPermission){
            die(json_encode(array("status" => false, "message" => "Uw permissie is ongewijzigd.")));
        }else{
            if($oldPermission == 0){
                $newPermission = 1;
            }else{
                $newPermission = 0;
            }

            $stmt = Application::$app->db->prepare("UPDATE User SET akkoord_met_voorwaarden = :newPermission");
            $stmt->bindParam('newPermission', $newPermission, \PDO::PARAM_STR);
            $stmt->execute();

            Application::$app->logger->writeToLog(sprintf("Akkoord met voorwaarden aangepast voor gebruiker `%s` van %d naar %d", Application::$app->db->getUserID(Application::$app->session->get('userdata')['email']), $oldPermission, $newPermission));
            die(json_encode(array("status" => true)));
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

    public function processAkkoord()
    {
        $value = $_POST['privacy_statement'];
        $id = Application::$app->session->get('userdata')['id'];
        if($value != 1){
            die(json_encode(array("status" => false, "error" => "U dient akkoord te gaan met onze voorwaarden.")));
        }

        $stmt = Application::$app->db->prepare('UPDATE User SET akkoord_met_voorwaarden = 1 WHERE id = :id');
        $stmt->bindParam('id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        Application::$app->logger->writeToLog(sprintf("Gebruiker `%s` akkoord gegaan met de voorwaarden via de akkoord pagina vanaf IP: %s", $id, $_SERVER['REMOTE_ADDR']));
        die(json_encode(array("status" => true, "error" => "U dient akkoord te gaan met onze voorwaarden.")));
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