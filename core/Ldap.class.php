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
        ldap_set_option(self::$conn, LDAP_OPT_PROTOCOL_VERSION, 3);
    }

    public function authenticate($mail, $password) : bool
    {
        $rdn = self::getRDN($mail);

        $bind = @ldap_bind(self::$conn, $rdn, $password);
        if($bind)
        {
            $stmt = Database::$pdo->prepare("SELECT id, email, permission_role, permission_granted FROM User WHERE email= :email");
            $stmt->bindParam("email", $mail);
            $stmt->execute();
            $_SESSION['logged_in'] = true;
            $_SESSION['userdata'] = $stmt->fetchAll();

            return true;
        }

        return false;
    }

    public function register_user($register_data, $user_data){
        $rdn = self::createRDN($register_data['klantnummer']);

        $fields = Array();

        $fields['objectClass'][] = "top";
        $fields['objectClass'][] = "inetOrgPerson";
        $fields['objectClass'][] = "person";
        $fields['objectClass'][] = "organizationalPerson";

        $fields['cn'] = $user_data['k_voornaam'];
        $fields['sn'] = $user_data['k_achternaam'];
        $fields['mail'] = $register_data['email'];

        $add_user = ldap_add(self::$conn, $rdn, $fields);

        if($add_user === true){
            if (CRYPT_SHA512){
                $newPassword = $register_data['password'];

                $salt = uniqid(mt_rand(), true);

                $encoded_newPassword = "{CRYPT}". crypt($newPassword, '$6$' . $salt . '$');
            }else{
                //Geen CRYPT_SHA512 Module (Error?)
            }
            //Modify user with new password
            $entry = ['userPassword' => $encoded_newPassword];

            if(ldap_modify(self::$conn, $rdn, $entry) === false){
                //Error logica voor het toevoegen van een password
            }

        }else{
            //TODO: Error logica
        }


        //TODO: Add user to customer group in LDAP

        //TODO: Pending status
        $permission_role = "Klant";
        $permission_granted = 0;
        $stmt =Application::$app->db->prepare("INSERT INTO User (email, permission_role, k_klantnummer, permission_granted) VALUES (:email, :permission_role, :k_klantnummer, :permission_granted)");
        $stmt->bindParam("email", $register_data['email'], \PDO::PARAM_STR);
        $stmt->bindParam("permission_role", $permission_role, \PDO::PARAM_STR);
        $stmt->bindParam("k_klantnummer", $register_data['klantnummer'], \PDO::PARAM_STR);
        $stmt->bindParam("permission_granted", $permission_granted, \PDO::PARAM_INT);
        $stmt->execute();


        $this->authenticate($register_data['email'], $register_data['password']);
        die(json_encode(array("status" => true, "redirect" => "/")));
    }

    public function exists($email) : bool
    {
        $filter = "(mail=".ldap_escape($email).")";
        ldap_bind(self::$conn, Application::$config['LDAP_USERNAME'], Application::$config['LDAP_PASSWORD']);
        $result = ldap_search(self::$conn, Application::$config['LDAP_BASEDN'], $filter);
        $result = ldap_get_entries(self::$conn, $result);
        if($result['count'] != 0){
            return true;
        }

        return false;
    }

    private function getRDN($mail){
        //ldap_bind();
        $query = ldap_search(self::$conn, $this->config['LDAP_BASEDN'], "(mail=".ldap_escape($mail).")");
        $result = ldap_get_entries(self::$conn, $query);

        return $result[0]["dn"];
    }

    private function createRDN($klantnummer)
    {
        return "uid=$klantnummer,".$this->config['LDAP_BASEDN'];
    }

    private static function createEncryptedPassword($password){
        $salt = strtr(base64_encode(uniqid(mt_rand(), true)), '+', '.');
        return sprintf("{CRYPT}%s", crypt($password, '$2$10$'.$salt.'$'));
    }
}