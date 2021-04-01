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
            'marketing',
            'klantenservice',
            'manager',
            'beheerder'
        ]);
    }

    public function showIndex()
    {
        $session = Application::$app->session;
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
        $voornaam = $session->get('employee_data')['cn'][0];
        $achternaam = $session->get('employee_data')['sn'][0];

        Application::$app->logger->writeToEmployeeLog(sprintf("%s %s is ingelogd", $voornaam, $achternaam));

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

    public function get_groups()
    {
        $perms = Application::$app->session->get("permissions");
        if(!in_array("get_employee_data", $perms)){
            die(json_encode(["status"=>false, "message"=>"Invalide rechten"]));
        }
        $groups = Application::$app->ldap->getGroups();
        die(json_encode(["status"=>true,"result"=>$groups]));
    }

    public function change_employee()
    {
        $data = Application::$app->request->getBody();
        $perms = Application::$app->session->get("permissions");
        if(!in_array("update_employee", $perms)){
            die(json_encode(["status"=>false, "message"=>"Invalide rechten"]));
        }
        if(Application::$app->ldap->updateEmployee($data)){
            die(json_encode(["status"=>true, "message"=>"Medewerker succesvol aangepast"]));
        }
        die(json_encode(["status"=>false, "message"=>"Er is een fout opgetreden"]));

    }

    public function create_employee()
    {
        $data = Application::$app->request->getBody();
        $perms = Application::$app->session->get("permissions");
        if(!in_array("create_employee", $perms)){
            die(json_encode(["status"=>false, "message"=>"Invalide rechten"]));
        }
        if(empty($data["uid"])||empty($data["cn"])||empty($data["sn"])||empty($data["group"])||empty($data["password"])||empty($data["passwordConfirm"])){
            die(json_encode(["status"=>false, "message"=>"Vul alle velden in."]));
        }
        if(Application::$app->db->getRoleRank($data["group"])>Application::$app->db->getRoleRank(Application::$app->session->get("employee_group"))){
            if(!filter_var($data['password'], FILTER_VALIDATE_REGEXP, array("options"=>array("regexp" => "/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/")))){
                die(json_encode(["status"=>false, "message"=>"Wachtwoord voldoet niet aan de eisen"]));
            }
            if($data["password"]!=$data["passwordConfirm"]){
                die(json_encode(["status"=>false, "message"=>"Wachtwoord is niet gelijk"]));
            }else{
                Application::$app->ldap->createEmployee($data);
            }
        }
        die(json_encode(["status"=>false, "message"=>"Je hebt niet de juiste rechten deze rol te geven."]));

    }

    public function delete_employee()
    {
        $perms = Application::$app->session->get("permissions");
        if(!in_array("remove_employee", $perms)){
            die(json_encode(["status"=>false, "message"=>"Invalide rechten"]));
        }
        //delete employee in ldap
        die(json_encode(["status"=>true, "message"=>"Medewerker succesvol verwijderd"]));
    }

    public function get_employee_data()
    {
        $perms = Application::$app->session->get("permissions");
        if (!in_array("get_employee_data", $perms)) {
            die(json_encode(array("status" => false, "message" => "Invalide rechten")));
        }
        $employees = Application::$app->ldap->getEmployees();
        die(json_encode(["status"=>true,"result"=>$employees]));
    }

    public function get_data()
    {
        $data = Application::$app->request->getBody();
        $perms = Application::$app->session->get("permissions");
        if(!in_array("get_customer_data", $perms)){
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
            return json_encode(array("status"=>false, "message"=>"Ongeldig postcode"));
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