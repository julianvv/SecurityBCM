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

    public function authenticateEmployee($dn, $password) : bool
    {
        $bind = @ldap_bind(self::$conn, $dn, $password);

        if($bind)
        {
            return true;
        }
        Application::$app->logger->writeToEmployeeLog(sprintf("`%s` heeft zich onjuist ge-authenticeerd vanaf ip: %s", Application::$app->session->get('employee_data')['cn'][0], $_SERVER['REMOTE_ADDR']));
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
        $dn = "cn=".$data["cn"].",".$this->config["LDAP_EMPLOYEEDN"];

        if($this->getDataByCN($data['cn'], "employee")){
            die(json_encode(["status"=>false, "message"=>"CN al in gebruik."]));
        }

        if($this->getDataByUID($data['uid'], "employee")){
            die(json_encode(["status"=>false, "message"=>"UID al in gebruik."]));
        }

        $fields = Array();
        $fields['objectClass'][] = "top";
        $fields['objectClass'][] = "inetOrgPerson";
        $fields['objectClass'][] = "person";
        $fields['objectClass'][] = "organizationalPerson";

        $fields['cn'] = $data['cn'];
        $fields['givenName'] = $data['givenName'];
        $fields['sn'] = $data['sn'];
        $fields['uid'] = $data['uid'];

        //Create LDAP account for user auth
        $add_user = @ldap_add(self::$conn, $dn, $fields);

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

            if(ldap_modify(self::$conn, $dn, $entry) === false){
                die(json_encode(array("status" => false, "message" => "Wachtwoord encryptie niet gelukt.")));
            }
        }else{
            die(json_encode(array("status" => false, "message" => "Account aanmaken niet gelukt.")));
        }

        $groupDN = "cn=".ldap_escape($data["group"]).",ou=groups,dc=energiemeter,dc=local";
        $entry = [];
        $entry["uniqueMember"] = $dn;
        ldap_bind(self::$conn, $this->config["LDAP_ADMIN"], $this->config["LDAP_ADMIN_PASS"]);
        if(ldap_mod_add(self::$conn, $groupDN, $entry)===false) {
            return false;
        }

        Application::$app->logger->writeToEmployeeLog(sprintf("Nieuw medewerker aangemaakt met CN `%s` en groep %s door: `%s`", $dn, $data["group"], Application::$app->session->get('employee_data')["cn"][0]));
        die(json_encode(array("status" => true, "message" => "Account successvol aangemaakt.")));
    }

    public function changeEmployeePassword($dn, $newPass)
    {
        if (CRYPT_SHA512){
            $salt = uniqid(mt_rand(), true);

            $encoded_newPassword = "{CRYPT}". crypt($newPass, '$6$' . $salt . '$');
        }else{
            die(json_encode(array("status" => false, "message" => "Fout bij het versleuten van het wachtwoord.")));
        }
        //Modify user with new password
        $entry = ['userPassword' => $encoded_newPassword];
        ldap_bind(self::$conn, $this->config['LDAP_ADMIN'], $this->config['LDAP_ADMIN_PASS']);
        if(ldap_modify(self::$conn, $dn, $entry) === false){
            die(json_encode(array("status" => false, "message" => "Wachtwoord encryptie niet gelukt.")));
        }else{
            Application::$app->logger->writeToEmployeeLog(sprintf("`%s` heeft zijn/haar wachtwoord gewijzigd vanaf ip: %s", Application::$app->session->get('employee_data')['cn'][0], $_SERVER['REMOTE_ADDR']));
        }
    }

    public function exists($string, $type) : bool
    {
        if($this->getDataByMail($string, $type) || $this->getDataByUID($string, $type) || $this->getDataByCN($string, $type)){
            return true;
        }

        return false;
    }

    public function getDataByCN($cn, $type){
        if($type == 'klant'){
            $dn = $this->config['LDAP_BASEDN'];
        }else if($type == 'employee'){
            $dn = $this->config['LDAP_EMPLOYEEDN'];
        }else{
            die(json_encode(['status'=>false, 'message' => 'Verkeerd type opgegeven.']));
        }

        ldap_bind(self::$conn, $this->config['LDAP_USERNAME'], $this->config['LDAP_PASSWORD']);
        $query = @ldap_search(self::$conn, $dn, "(cn=".ldap_escape($cn).")");
        $result = @ldap_get_entries(self::$conn, $query);

        return $result[0] ?? false;
    }

    public function getDataByMail($mail, $type){
        if($type == 'klant'){
            $dn = $this->config['LDAP_BASEDN'];
        }else if($type == 'employee'){
            $dn = $this->config['LDAP_EMPLOYEEDN'];
        }else{
            die(json_encode(['status'=>false, 'message' => 'Verkeerd type opgegeven.']));
        }

        ldap_bind(self::$conn, $this->config['LDAP_USERNAME'], $this->config['LDAP_PASSWORD']);
        $query = @ldap_search(self::$conn, $dn, "(mail=".ldap_escape($mail).")");
        $result = @ldap_get_entries(self::$conn, $query);

        return $result[0] ?? false;
    }

    public function getDataByUID($uid, $type){
        if($type == 'klant'){
            $dn = $this->config['LDAP_BASEDN'];
        }else if($type == 'employee'){
            $dn = $this->config['LDAP_EMPLOYEEDN'];
        }else{
            die(json_encode(['status'=>false, 'message' => 'Verkeerd type opgegeven.']));
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


    public function deleteEmployee($cn)
    {
        $unescaped_dn = "cn=".$cn.",".$this->config["LDAP_EMPLOYEEDN"];
        $dn = "cn=".ldap_escape($cn).",".$this->config["LDAP_EMPLOYEEDN"];

        if($unescaped_dn === Application::$app->session->get('employee_data')['dn']){
            die(json_encode(['status'=>false, 'message'=>'Je kunt niet je eigen account verwijderen!']));
        }

        $group = $this->getEmployeeGroup($dn);

        if(!$group === "false"){
            $groupCN = "cn=".$group.",ou=groups,dc=energiemeter,dc=local";
            ldap_bind(self::$conn, $this->config["LDAP_ADMIN"], $this->config["LDAP_ADMIN_PASS"]);
            $entry = ["uniqueMember"=>$dn];
            if(ldap_mod_del(self::$conn, $groupCN, $entry)===false){
                return false;
            }
        }
        if(ldap_delete(self::$conn, $dn)===false){
            return false;
        }
        Application::$app->logger->writeToEmployeeLog(sprintf("`%s` is verwijderd door: `%s` vanaf ip: %s", $cn, Application::$app->session->get('employee_data')['cn'][0], $_SERVER['REMOTE_ADDR']));
        return true;
    }

    public function updateEmployee($data)
    {
        $dn = $data['dn'];
        $group = $data["group"];
        $oldGroup = $data["oldGroup"];
        $uid = $data["uid"];
        $oldUID = $data["oldUID"];

        if($uid != $oldUID){
            if($this->getDataByUID($data['uid'], "employee")){
                die(json_encode(["status"=>false, "message"=>"UID al in gebruik."]));
            }
        }

        $entry = [];
        $entry["givenName"] = $data["givenName"];
        $entry["sn"] = $data["sn"];
        $entry["uid"] = $uid;

        ldap_bind(self::$conn, $this->config['LDAP_USERNAME'], $this->config['LDAP_PASSWORD']);
        if(ldap_modify(self::$conn, $dn, $entry)===false) {
            return false;
        }

        if ($oldGroup != $group){
            //remove from old group
            if (!$oldGroup === "false"){
                $cn = "cn=" . ldap_escape($oldGroup) . ",ou=groups,dc=energiemeter,dc=local";
                $entry = ["uniqueMember" => $dn];
                //die(json_encode(["status"=>false, "message"=>$cn." ".$dn]));
                ldap_bind(self::$conn, $this->config["LDAP_ADMIN"], $this->config["LDAP_ADMIN_PASS"]);
                if (@ldap_mod_del(self::$conn, $cn, $entry) === false) {
                    return false;
                }
            }
            $groups = $this->getGroups();
            if (in_array($group, $groups)){
                //add to new group
                $cn = "cn=".ldap_escape($group).",ou=groups,dc=energiemeter,dc=local";
                $entry = [];
                $entry["uniqueMember"] = $dn;
                ldap_bind(self::$conn, $this->config["LDAP_ADMIN"], $this->config["LDAP_ADMIN_PASS"]);
                if(@ldap_mod_add(self::$conn, $cn, $entry)===false) {
                    return false;
                }
            }else{
                die(json_encode(['status'=>false, 'message'=>"Groep bestaat niet."]));
            }
        }
        return true;
    }

    public function getEmployees()
    {
        ldap_bind(self::$conn, $this->config['LDAP_USERNAME'], $this->config['LDAP_PASSWORD']);
        $query = @ldap_search(self::$conn, $this->config["LDAP_EMPLOYEEDN"], "(objectClass=inetOrgPerson)", ["gn", "cn", "sn", "uid", "dn"]);
        $queryResult = @ldap_get_entries(self::$conn, $query);
        $result = array();
        foreach ($queryResult as $employee){
            $group = $this->getEmployeeGroup($employee["dn"]);
            @$employee["group"] = $group;
            array_push($result, $employee);
        }
        return $result;
    }
}