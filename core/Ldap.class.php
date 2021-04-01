<?php


namespace core;

class Ldap
{
    public static $conn;
    private $config;

    public function __construct()
    {
        if(!Ldap::$conn){
            $this->config = Application::$config;
            try {
                self::$conn = ldap_connect($this->config["LDAP_URI"]);
                ldap_set_option(self::$conn, LDAP_OPT_PROTOCOL_VERSION, 3);
            }catch (\Exception $e){
                echo "Error opgetreden";
            }
        }
    }

    public function authenticate($mail, $password) : bool
    {
        $rdn = self::getDataByMail($mail, 'klant')['dn'];

        $bind = @ldap_bind(self::$conn, $rdn, $password);

        if($bind)
        {
        Application::$app->logger->writeToLog(sprintf("Gebruiker `%s` succesvol ingelogd vanaf IP: %s", Application::$app->db->getUserID($mail), $_SERVER['REMOTE_ADDR']));
            return true;
        }
        Application::$app->logger->writeToLog(sprintf("Gebruiker met `%s` geweigerd. Vanaf IP: %s", Application::$app->db->getUserID($mail), $_SERVER['REMOTE_ADDR']));
        return false;
    }

    //LDAP Registratie klant
    public function register_ldap_user($register_data, $user_data){
        ldap_bind(self::$conn, Application::$config['LDAP_USERNAME'], Application::$config['LDAP_PASSWORD']);
        $rdn = self::createRDN($register_data['klantnummer']);

        $fields = Array();

        $fields['objectClass'][] = "top";
        $fields['objectClass'][] = "inetOrgPerson";
        $fields['objectClass'][] = "person";
        $fields['objectClass'][] = "organizationalPerson";

        $fields['cn'] = $user_data['k_voornaam'];
        $fields['sn'] = $user_data['k_achternaam'];
        $fields['mail'] = $register_data['email'];

        //Create LDAP account for user auth
        $add_user = @ldap_add(self::$conn, $rdn, $fields);

        if($add_user === true){
            if (CRYPT_SHA512){
                $newPassword = $register_data['password'];

                $salt = uniqid(mt_rand(), true);

                $encoded_newPassword = "{CRYPT}". crypt($newPassword, '$6$' . $salt . '$');
            }else{
                die(json_encode(array("status" => false, "error" => "Fout bij het versleuten van het wachtwoord.")));
            }
            //Modify user with new password
            $entry = ['userPassword' => $encoded_newPassword];

            if(ldap_modify(self::$conn, $rdn, $entry) === false){
                die(json_encode(array("status" => false, "error" => "Wachtwoord encryptie niet gelukt.")));
            }
        }else{
            Application::$app->session->setFlash('notification', ["type" => "alert-success", "message" => "U heeft al een account bij YouthEnergy. Login met uw bestaande account."]);
            die(json_encode(array("status" => true, "redirect" => "/")));
        }
        Application::$app->logger->writeToLog(sprintf("Nieuw LDAP-account geregistreerd met email: %s vanaf IP: %s", $register_data['email'], $_SERVER['REMOTE_ADDR']));
        Application::$app->db->register_db_user($register_data);
    }

    public function createEmployee($data)
    {
        ldap_bind(self::$conn, Application::$config['LDAP_USERNAME'], Application::$config['LDAP_PASSWORD']);
        $rdn = "uid=".$data["uid"].",".$this->config["LDAP_EMPLOYEEDN"];

        if($this->exists($rdn, "employee")){
            die(json_encode(["status"=>false, "message"=>"Medewerker bestaat al."]));
        }

        $fields = Array();

        $fields['objectClass'][] = "top";
        $fields['objectClass'][] = "inetOrgPerson";
        $fields['objectClass'][] = "person";
        $fields['objectClass'][] = "organizationalPerson";

        $fields['cn'] = $data['cn'];
        $fields['sn'] = $data['sn'];
        $fields['uid'] = $data['uid'];

        //Create LDAP account for user auth
        $add_user = @ldap_add(self::$conn, $rdn, $fields);

        if($add_user === true){
            if (CRYPT_SHA512){
                $newPassword = $data['password'];

                $salt = uniqid(mt_rand(), true);

                $encoded_newPassword = "{CRYPT}". crypt($newPassword, '$6$' . $salt . '$');
            }else{
                die(json_encode(array("status" => false, "message" => "Fout bij het versleuten van het wachtwoord.")));
            }
            //Modify user with new password
            $entry = ['userPassword' => $encoded_newPassword];

            if(ldap_modify(self::$conn, $rdn, $entry) === false){
                die(json_encode(array("status" => false, "message" => "Wachtwoord encryptie niet gelukt.")));
            }
        }else{
            die(json_encode(array("status" => false, "message" => "Account aanmaken niet gelukt.")));
        }

        $cn = "cn=".ldap_escape($data["group"]).",ou=groups,dc=energiemeter,dc=local";
        $entry = [];
        $entry["uniqueMember"] = $rdn;
        ldap_bind(self::$conn, $this->config["LDAP_GROUP_MANAGER"], $this->config["LDAP_GROUP_PASSWORD"]);
        if(ldap_mod_add(self::$conn, $cn, $entry)===false) {
            return false;
        }

        Application::$app->logger->writeToEmployeeLog(sprintf("Nieuw medewerker aangemaakt met uid %s en groep %s", $data["uid"], $data["group"]));
        die(json_encode(array("status" => true, "message" => "Account succesvol aangemaakt.")));
    }

    public function exists($string, $type) : bool
    {
        if($this->getDataByMail($string, $type) || $this->getDataByUID($string, $type)){
            return true;
        }

        return false;
    }

    public function getDataByMail($mail, $type){
        if($type == 'klant'){
            $dn = $this->config['LDAP_BASEDN'];
        }else{
            $dn = $this->config['LDAP_EMPLOYEEDN'];
        }

        ldap_bind(self::$conn, $this->config['LDAP_USERNAME'], $this->config['LDAP_PASSWORD']);
        $query = @ldap_search(self::$conn, $dn, "(mail=".ldap_escape($mail).")");
        $result = @ldap_get_entries(self::$conn, $query);

        return $result[0] ?? false;
    }

    public function getDataByUID($uid, $type){
        if($type == 'klant'){
            $dn = $this->config['LDAP_BASEDN'];
        }else{
            $dn = $this->config['LDAP_EMPLOYEEDN'];
        }

        ldap_bind(self::$conn, $this->config['LDAP_USERNAME'], $this->config['LDAP_PASSWORD']);
        $query = @ldap_search(self::$conn, $dn, "(uid=".ldap_escape($uid).")");
        $result = @ldap_get_entries(self::$conn, $query);

        return $result[0] ?? false;
    }

    public function changePassword($email, $newPassword, $type)
    {
        $rdn = $this->getDataByMail($email, $type)['dn'];

        if (CRYPT_SHA512){
            $salt = uniqid(mt_rand(), true);

            $encoded_newPassword = "{CRYPT}". crypt($newPassword, '$6$' . $salt . '$');
        }else{
            die(json_encode(array("status" => false, "error" => "Fout bij het versleuten van het wachtwoord.")));
        }
        //Modify user with new password
        $entry = ['userPassword' => $encoded_newPassword];

        if(ldap_modify(self::$conn, $rdn, $entry) === false){
            die(json_encode(array("status" => false, "error" => "Wachtwoord encryptie niet gelukt.")));
        }else{
            Application::$app->logger->writeToLog(sprintf("Wachtwoord veranderd voor %s vanaf IP: %s", $email, $_SERVER['REMOTE_ADDR']));
        }
    }
    
    private function createRDN($klantnummer)
    {
        return "uid=$klantnummer,".$this->config['LDAP_BASEDN'];
    }

    public function getEmployeeGroup($userDN)
    {
        ldap_bind(self::$conn, $this->config['LDAP_USERNAME'], $this->config['LDAP_PASSWORD']);
        $query = @ldap_search(self::$conn, "ou=groups,dc=energiemeter,dc=local", "(uniqueMember=".ldap_escape($userDN).")");
        $result = @ldap_get_entries(self::$conn, $query);

        return $result[0]['cn'][0] ?? false;
    }

    public function getGroups()
    {
        ldap_bind(self::$conn, $this->config['LDAP_USERNAME'], $this->config['LDAP_PASSWORD']);
        $query = @ldap_search(self::$conn, "ou=groups,dc=energiemeter,dc=local", "(objectClass=groupOfUniqueNames)",["cn"]);
        $result = @ldap_get_entries(self::$conn, $query);
        $tmp =[];
        foreach($result as $group){
            $tmp[]=$group["cn"][0];
        }

        return $tmp;
    }

    public function updateEmployee($data)
    {
        $oldUID = $data["oldUID"];
        $group = $data["group"];
        $oldGroup = $data["oldGroup"];

        $dn = "uid=".$oldUID.",".$this->config["LDAP_EMPLOYEEDN"];
        $entry = [];
        $entry["cn"] = $data["cn"];
        $entry["sn"] = $data["sn"];
        $entry["uid"] = $data["uid"];

        ldap_bind(self::$conn, $this->config['LDAP_USERNAME'], $this->config['LDAP_PASSWORD']);
        if(ldap_modify(self::$conn, $dn, $entry)===false) {
            return false;
        }

        //remove from old group
        $cn = "cn=".ldap_escape($oldGroup).",ou=groups,dc=energiemeter,dc=local";
        $entry = [];
        $entry["uniqueMember"] = $dn;
        ldap_bind(self::$conn, $this->config["LDAP_GROUP_MANAGER"], $this->config["LDAP_GROUP_PASSWORD"]);
        if(ldap_mod_del(self::$conn, $cn, $entry)===false) {
            return false;
        }

        $cn = "cn=".ldap_escape($group).",ou=groups,dc=energiemeter,dc=local";
        $entry = [];
        $entry["uniqueMember"] = $dn;
        ldap_bind(self::$conn, $this->config["LDAP_GROUP_MANAGER"], $this->config["LDAP_GROUP_PASSWORD"]);
        if(ldap_mod_add(self::$conn, $cn, $entry)===false) {
            return false;
        }
        return true;
    }

    public function getEmployees()
    {
        ldap_bind(self::$conn, $this->config['LDAP_USERNAME'], $this->config['LDAP_PASSWORD']);
        $query = @ldap_search(self::$conn, $this->config["LDAP_EMPLOYEEDN"], "(objectClass=inetOrgPerson)", ["cn", "sn", "uid", "dn"]);
        $queryResult = @ldap_get_entries(self::$conn, $query);
        $result = array();
        foreach ($queryResult as $employee){
            $group = $this->getEmployeeGroup($employee["dn"]);
            @$employee["group"] = $group;
            //$queryResult[] = $employee;
            array_push($result, $employee);
        }
        return $result;
    }
}