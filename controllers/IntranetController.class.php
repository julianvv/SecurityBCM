<?php


namespace controllers;


use core\Application;
use core\View;
use mysql_xdevapi\Result;

class IntranetController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware([
            'beheerder'
        ]);
    }

    public function showIndex()
    {
        $session = Application::$app->session;
        if (!$session->get('employee_group')){
            $employee_data = Application::$app->ldap->getDataByUID($_SERVER['PHP_AUTH_USER'], 'employee');
            $group = Application::$app->ldap->getEmployeeGroup($employee_data['dn']);
            $stmt = Application::$app->db->prepare('SELECT permission_name FROM Permission WHERE id IN 
	                                                    (SELECT p_fk_id FROM Role_To_Permissions 
                                                        INNER JOIN Roles ON Roles.id = Role_To_Permissions.r_fk_id 
                                                        WHERE Roles.name = :rolename)');
            $stmt->bindParam("rolename", $group, \PDO::PARAM_STR);
            $stmt->execute();
            $permissions = $stmt->fetchAll();
            $perms = [];
            foreach ($permissions as $permission){
                $perms[] = $permission["permission_name"];
            }

            $session->set('employee_data', $employee_data);
            $session->set('employee_group', $group);
            $session->set('permissions', $perms);

            Application::$app->logger->writeToEmployeeLog(sprintf("`%s` is ingelogd vanaf ip: %s", $employee_data['cn'][0], $_SERVER['REMOTE_ADDR']));
        }

        $group = Application::$app->ldap->getEmployeeGroup($session->get('employee_data')['dn']);
        $voornaam = $session->get('employee_data')['givenname'][0];
        $achternaam = $session->get('employee_data')['sn'][0];


        switch ($group){
            case 'marketing':
                return View::view('intranet.marketingmedewerker', ["title" => $voornaam.' '.$achternaam, 'voornaam' => $voornaam, 'achternaam' => $achternaam, 'group' => $group]);
            case 'klantenservice':
                return View::view('intranet.helpdeskmedewerker', ["title" => $voornaam.' '.$achternaam, 'voornaam' => $voornaam, 'achternaam' => $achternaam, 'group' => $group]);
            case 'manager':
                return View::view('intranet.manager', ["title" => $voornaam.' '.$achternaam, 'voornaam' => $voornaam, 'achternaam' => $achternaam, 'group' => $group]);
            case 'beheerder':
                return View::view('intranet.beheerder', ["title" => $voornaam.' '.$achternaam, 'voornaam' => $voornaam, 'achternaam' => $achternaam, 'group' => $group]);
            default:
                return View::view('intranet.home', ["title" => $voornaam.' '.$achternaam, 'voornaam' => $voornaam, 'achternaam' => $achternaam, 'group' => $group]);
        }
    }

    public function showAccountPage()
    {
        $session = Application::$app->session;
        $voornaam = $session->get('employee_data')['givenname'][0];
        $achternaam = $session->get('employee_data')['sn'][0];
        $group = $session->get('employee_group');

        return View::view('intranet.account',  ["title" => $voornaam.' '.$achternaam, 'voornaam' => $voornaam, 'achternaam' => $achternaam, 'group' => $group]);
    }

    public function showRolePage()
    {
        $session = Application::$app->session;
        $voornaam = $session->get('employee_data')['givenname'][0];
        $achternaam = $session->get('employee_data')['sn'][0];
        $group = $session->get('employee_group');
        if ($this->prepareMiddleware()){
            return View::view('intranet.roles',  ["title" => $voornaam.' '.$achternaam, 'voornaam' => $voornaam, 'achternaam' => $achternaam, 'group' => $group]);
        }
        return View::view('intranet.home',  ["title" => $voornaam.' '.$achternaam, 'voornaam' => $voornaam, 'achternaam' => $achternaam, 'group' => $group]);
    }

    public function logout()
    {
        Application::$app->session->destroy();
    }

    public function get_groups()
    {
        $perms = Application::$app->session->get("permissions");
        if(!in_array("get_employee_data", $perms)){
            Application::$app->logger->writeToEmployeeLog(sprintf("`%s` heeft geprobeerd alle ldap groepen op te halen vanaf ip: %s", Application::$app->session->get('employee_data')['cn'][0], $_SERVER['REMOTE_ADDR']));
            die(json_encode(["status"=>false, "message"=>"Invalide rechten"]));
        }
        $groups = Application::$app->ldap->getGroups();
        die(json_encode(["status"=>true,"result"=>$groups]));
    }

    public function change_password()
    {
        $data = Application::$app->request->getBody();
        $dn = Application::$app->session->get('employee_data')['dn'];

        if(Application::$app->ldap->authenticateEmployee($dn, $data['password'])){
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

        Application::$app->ldap->changeEmployeePassword($dn, $data['password_new']);

        Application::$app->session->setFlash('notification', ['type' => 'alert-success', 'message' => 'Uw wachtwoord is successvol gewijzigd.']);
        return die(json_encode(array("status" => true, "message" => "Uw wachtwoord is successvol gewijzigd.")));
    }

    public function change_employee()
    {
        $data = Application::$app->request->getBody();
        $perms = Application::$app->session->get("permissions");
        if(!in_array("update_employee", $perms)){
            Application::$app->logger->writeToEmployeeLog(sprintf("`%s` heeft geprobeerd gebruiker `%s` aan te passen vanaf ip: %s", Application::$app->session->get('employee_data')['cn'][0], $data['dn'], $_SERVER['REMOTE_ADDR']));
            die(json_encode(["status"=>false, "message"=>"Invalide rechten"]));
        }
        if(empty($data["uid"])||empty($data["givenName"])||empty($data["sn"])||empty($data["group"])||empty($data['oldGroup'])||empty($data['oldUID'])||empty($data['dn'])){
            die(json_encode(["status"=>false, "message"=>"Vul alle velden in."]));
        }
        if (Application::$app->db->getRoleRank(Application::$app->ldap->getEmployeeGroup($data['dn'])) > Application::$app->db->getRoleRank(Application::$app->session->get("employee_group"))){
            if(Application::$app->db->getRoleRank($data["group"])>Application::$app->db->getRoleRank(Application::$app->session->get("employee_group"))) {
                if (Application::$app->ldap->updateEmployee($data)) {
                    Application::$app->logger->writeToEmployeeLog(sprintf("`%s` heeft gebruiker `%s` aangepast vanaf ip: %s", Application::$app->session->get('employee_data')['cn'][0], $data['dn'], $_SERVER['REMOTE_ADDR']));
                    die(json_encode(["status" => true, "message" => "Medewerker succesvol aangepast"]));
                }else{
                    die(json_encode(["status" => false, "message" => "Er is een fout opgetreden."]));
                }
            }else{
                die(json_encode(["status" => false, "message" => "U kunt geen groep geven gelijk of hoger aan uw eigen groep."]));
            }
        }else{
            die(json_encode(["status" => false, "message" => "U kunt geen account met hogere rechten dan uzelf aanpassen."]));
        }
    }

    public function create_employee()
    {
        $data = Application::$app->request->getBody();
        $perms = Application::$app->session->get("permissions");
        if(!in_array("create_employee", $perms)){
            Application::$app->logger->writeToEmployeeLog(sprintf("`%s` heeft geprobeerd een gebruiker aan te maken vanaf ip: %s", Application::$app->session->get('employee_data')['cn'][0], $_SERVER['REMOTE_ADDR']));
            die(json_encode(["status"=>false, "message"=>"Invalide rechten"]));
        }
        if(empty($data["cn"])||empty($data["uid"])||empty($data["givenName"])||empty($data["sn"])||empty($data["group"])||empty($data["password"])||empty($data["passwordConfirm"])){
            die(json_encode(["status"=>false, "message"=>"Vul alle velden in."]));
        }
        if(Application::$app->db->getRoleRank($data["group"])>Application::$app->db->getRoleRank(Application::$app->session->get("employee_group"))){
            if(!filter_var($data['password'], FILTER_VALIDATE_REGEXP, array("options"=>array("regexp" => "/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/")))){
                die(json_encode(["status"=>false, "message"=>"Wachtwoord voldoet niet aan de eisen"]));
            }
            if($data["password"]!=$data["passwordConfirm"]){
                die(json_encode(["status"=>false, "message"=>"Wachtwoord is niet gelijk"]));
            }
            Application::$app->ldap->createEmployee($data);
        }
        die(json_encode(["status"=>false, "message"=>"Je hebt niet de juiste rechten deze rol te geven."]));

    }

    public function delete_employee()
    {
        $data = Application::$app->request->getBody();
        $perms = Application::$app->session->get("permissions");
        if(!in_array("remove_employee", $perms)){
            Application::$app->logger->writeToEmployeeLog(sprintf("`%s` heeft geprobeerd gebruiker `%s` te verwijderen ip: %s", Application::$app->session->get('employee_data')['cn'][0], $data['cn'], $_SERVER['REMOTE_ADDR']));
            die(json_encode(["status"=>false, "message"=>"Invalide rechten"]));
        }
        $cn = $data['cn'] ?? '';
        $dn = "cn=".$cn.",ou=employees,ou=users,ou=energiemeter,dc=energiemeter,dc=local";
        if(Application::$app->db->getRoleRank(Application::$app->ldap->getEmployeeGroup($dn))>Application::$app->db->getRoleRank(Application::$app->session->get("employee_group"))){
            if(Application::$app->ldap->deleteEmployee($cn)){
                die(json_encode(["status"=>true, "message"=>"Medewerker succesvol verwijderd."]));
            }
        }else{
            die(json_encode(["status"=>false, "message"=>"U bent niet gemachtigd dit account te verwijderen."]));
        }
        die(json_encode(["status"=>true, "message"=>"Medewerker succesvol verwijderd."]));
    }

    public function get_employee_data()
    {
        $perms = Application::$app->session->get("permissions");
        if (!in_array("get_employee_data", $perms)) {
            Application::$app->logger->writeToEmployeeLog(sprintf("`%s` heeft geprobeerd medewerker data op te halen vanaf ip: %s", Application::$app->session->get('employee_data')['cn'][0], $_SERVER['REMOTE_ADDR']));
            die(json_encode(array("status" => false, "message" => "Invalide rechten")));
        }
        $employees = Application::$app->ldap->getEmployees();
        die(json_encode(["status"=>true,"result"=>$employees]));
    }

    public function getEmployeeLog()
    {
        $data = Application::$app->request->getBody();
        $perms = Application::$app->session->get("permissions");
        if(!in_array("get_logs", $perms)){
            Application::$app->logger->writeToEmployeeLog(sprintf("`%s` heeft geprobeerd gebruiker logs op te halen vanaf ip: %s", Application::$app->session->get('employee_data')['cn'][0], $_SERVER['REMOTE_ADDR']));
            die(json_encode(["status"=>false, "message"=>"Invalide rechten"]));
        }
        $cn = $data['cn'] ?? "";
        die(json_encode(['status'=> true, 'result'=>Application::$app->logger->readLogByCN($cn)]));
    }

    public function get_data()
    {
        $data = Application::$app->request->getBody();
        $perms = Application::$app->session->get("permissions");
        if(!in_array("get_customer_data", $perms)){
            Application::$app->logger->writeToEmployeeLog(sprintf("`%s` heeft geprobeerd klanten data op te halen vanaf ip: %s", Application::$app->session->get('employee_data')['cn'][0], $data['dn'], $_SERVER['REMOTE_ADDR']));
            die(json_encode(array("status"=>false, "message"=>"Invalide rechten")));
        }
        switch ($data["type"]){
            case "klant":
                die($this->get_klant());
            case "postcode":
                die($this->get_postcode());
            case "provincie":
                die($this->get_provincie());
            default:
                die(json_encode(array("status"=>false, "message"=>"UwU, our monkeys awe wonking VEWY HAWD")));
        }
    }

    public function get_klant()
    {
        $data = Application::$app->request->getBody();

        $stmt = Application::$app->db->prepare("SELECT * FROM tbl_klanten WHERE k_klantnummer = :klantnummer");
        $stmt->bindParam("klantnummer", $data["klantnummer"], \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();

        if(!$result){
            return json_encode(array("status"=>false, "message"=>"Klantnummer niet gevonden."));
        }

        return json_encode(array("status"=>true,"result"=>$result));
    }

    public function get_postcode()
    {
        $data = Application::$app->request->getBody();

        if(!preg_match("/\d{4}\w{2}/", $data["postcode"])){
            return json_encode(array("status"=>false, "message"=>"Ongeldige postcode"));
        }
        $postcode = strtoupper($data["postcode"]);

        $stmt = Application::$app->db->prepare("SELECT * FROM tbl_adressen WHERE a_postcode = :postcode");
        $stmt->bindParam("postcode", $postcode, \PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if(!$result){
            return json_encode(array("status"=>false, "message"=>"Postcode niet gevonden."));
        }

        return json_encode(array("status"=>true,"result"=>$result));
    }

    public function get_provincie()
    {
        $data = Application::$app->request->getBody();

        $stmt = Application::$app->db->prepare("SELECT * FROM tbl_adressen WHERE a_provincie = :provincie");
        $stmt->bindParam("provincie", $data["provincie"]);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if(!$result){
            return json_encode(array("status"=>false, "message"=>"Provincie niet gevonden".$data["provincie"]));
        }

        return json_encode(array("status"=>true, "result"=>$result));
    }

    public function showVerbruiksmeter()
    {
        return View::view('intranet.verbruiksmeter');
    }
}