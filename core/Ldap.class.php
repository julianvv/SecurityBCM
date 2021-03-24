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
        $rdn = self::getDataByMail($mail)['dn'];

        $bind = @ldap_bind(self::$conn, $rdn, $password);

        if($bind)
        {
            $stmt = Database::$pdo->prepare("SELECT * FROM User WHERE email= :email");
            $stmt->bindParam("email", $mail);
            $stmt->execute();
            $_SESSION['logged_in'] = true;
            $_SESSION['userdata'] = $stmt->fetch();

            return true;
        }

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

        Application::$app->db->register_db_user($register_data);
    }

    public function exists($string) : bool
    {
        if($this->getDataByMail($string) || $this->getDataByUID($string)){
            return true;
        }

        return false;
    }

    public function getDataByMail($mail){
        ldap_bind(self::$conn, $this->config['LDAP_USERNAME'], $this->config['LDAP_PASSWORD']);
        $query = @ldap_search(self::$conn, $this->config['LDAP_BASEDN'], "(mail=".ldap_escape($mail).")");
        $result = @ldap_get_entries(self::$conn, $query);

        return $result[0] ?? false;
    }

    public function getDataByUID($uid){
        ldap_bind(self::$conn, $this->config['LDAP_USERNAME'], $this->config['LDAP_PASSWORD']);
        $query = @ldap_search(self::$conn, $this->config['LDAP_BASEDN'], "(uid=".ldap_escape($uid).")");
        $result = @ldap_get_entries(self::$conn, $query);

        return $result[0] ?? false;
    }

    private function createRDN($klantnummer)
    {
        return "uid=$klantnummer,".$this->config['LDAP_BASEDN'];
    }
}