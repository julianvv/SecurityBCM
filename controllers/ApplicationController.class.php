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

    public function showResetPage()
    {
        return View::view('resetpassword', ['title' => "Wachtwoord herstellen"]);
    }

    public function processResetPage()
    {
        $data = Application::$app->request->getBody();
        $pass = $data['password'];
        $pass_confirm = $data['password_new'];
        $email = $data['email'];
        $code = $data['code'];
        if (empty($pass) || empty($pass_confirm) || empty($email) || empty($code)){
            die(json_encode(['status'=>false, 'message'=>'Vul alle velden in.']));
        }

        if (!filter_var($pass, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp" => "/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/")))){
            die(json_encode(array("status" => false, "message" => "Wachtwoord voldoet niet aan de eisen.")));
        }

        if ($pass != $pass_confirm){
            die(json_encode(['status'=>false, 'message'=>'Wachtwoorden komen niet overeen.']));
        }

        $stmt = Application::$app->db->prepare("SELECT User.email, tbl_klanten_verificatie.v_code
                                                    FROM tbl_klanten_verificatie
                                                    JOIN User ON User.id = tbl_klanten_verificatie.v_fk_idUser
                                                    WHERE User.email = :email AND tbl_klanten_verificatie.v_code = :code");
        $stmt->bindParam("email", $email, \PDO::PARAM_STR);
        $stmt->bindParam("code", $code, \PDO::PARAM_INT);
        $stmt->execute();
        $fetch = $stmt->fetch();
        if(!$fetch){
            Application::$app->logger->writeToLog(sprintf("Gebruiker `%s` heeft onsuccesvol zijn/haar wachtwoord proberen te wijzigen.", $fetch['id']));
            die(json_encode(['status'=>false, 'message'=>'Combinatie incorrect.']));
        }else{
            $stmt = Application::$app->db->prepare("DELETE FROM tbl_klanten_verificatie WHERE v_fk_idUser = :id AND v_code = :code");
            $stmt->bindParam("id", $fetch['id'], \PDO::PARAM_INT);
            $stmt->bindParam("code", $code, \PDO::PARAM_INT);
            $stmt->execute();

            Application::$app->ldap->changePassword($email, $pass, "klant");
            Application::$app->logger->writeToLog(sprintf("Gebruiker `%s` heeft succesvol zijn/haar wachtwoord gewijzigd.", $fetch['id']));
            Application::$app->session->setFlash('notification', ['type' => 'alert-success', 'message' => 'Wachtwoord succesvol gewijzigd.']);
            die(json_encode(['status'=>true]));
        }
    }

    public function forgotPassword()
    {
        $data = Application::$app->request->getBody();
        $email = $data['email'];
        $klant_nummer = $data['klantnummer'];

        $stmt = Application::$app->db->prepare("SELECT * FROM User WHERE email = :email AND k_klantnummer = :klantnummer");
        $stmt->bindParam('email', $email, \PDO::PARAM_STR);
        $stmt->bindParam('klantnummer', $klant_nummer, \PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();
        if($result){
            $message = "Uw heeft een e-mail ontvangen met instructies om het wachtwoord te veranderen.";
            $stmt = Application::$app->db->prepare("DELETE FROM Password_reset WHERE reset_fk_email = :email");
            $stmt->bindParam('email', $email, \PDO::PARAM_STR);
            $stmt->execute();
        }else{
            Application::$app->logger->writeToLog(sprintf("Wachtwoord vergeten poging zonder success voor gebruiker: `%s` vanaf IP: %s", Application::$app->db->getUserID($email), $_SERVER['REMOTE_ADDR']));
            die(json_encode(array("status" => false, "message" => "Geen geldige combinatie.")));
        }

        $verify_code = rand(100000000000,999999999999);
        $stmt = Application::$app->db->prepare("INSERT INTO Password_reset (reset_fk_email, reset_code) VALUES(:email, :code)");
        $stmt->bindParam('email', $email, \PDO::PARAM_STR);
        $stmt->bindParam('code', $verify_code, \PDO::PARAM_INT);
        $stmt->execute();

        Application::$app->logger->writeToLog(sprintf("Wachtwoord vergeten succesvol aangevraagd voor gebruiker: `%s` vanaf IP: %s met code: %s", Application::$app->db->getUserID($email), $_SERVER['REMOTE_ADDR'], $verify_code));
        Application::$app->session->setFlash('notification', ['type' => 'alert-success', 'message' => 'Uw heeft een e-mail ontvangen met instructies om het wachtwoord te veranderen.']);
        $array = array('status' => true, 'message' => $message ?? "Geen geldige combinatie, controleer uw gegevens.");
        die(json_encode($array));
    }

    public function verifyCode()
    {
        $data = Application::$app->request->getBody();
        if (!Application::$app->session->get('logged_in')){
            die(json_encode(['status' => false, 'message' => 'U bent niet ge-authenticeerd.']));
        }
        $id = Application::$app->session->get('userdata')['id'];
        $code = $data['verificatieCode'];

        $stmt = Application::$app->db->prepare("SELECT * FROM tbl_klanten_verificatie WHERE v_fk_idUser = :id");
        $stmt->bindParam('id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();

        if($code == $result['v_code']){
            $stmt = Application::$app->db->prepare("UPDATE User SET permission_granted = 1 WHERE id = :id");
            $stmt->bindParam('id', $id, \PDO::PARAM_INT);
            $stmt->execute();

            Application::$app->logger->writeToLog(sprintf("Gebruiker met het id: `%s` heeft zich onsuccesvol proberen te verifiëren met de code: %s vanaf %s", $id, $code, $_SERVER["REMOTE_ADDR"]));
            Application::$app->session->setFlash('notification', ['type' => 'alert-success', 'message' => 'Bedankt voor het verifiëren van uw account.']);
            die(json_encode(array("status" => true)));
        }

        Application::$app->logger->writeToLog(sprintf("Wachtwoord vergeten poging zonder success voor gebruiker met het `id` %s vanaf IP: %s", $id, $_SERVER['REMOTE_ADDR']));
        die(json_encode(array("status" => false, "message" => "Deze verificatiecode is onjuist.")));
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