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
                echo $e;
            }
        }
        ldap_set_option(self::$conn, LDAP_OPT_PROTOCOL_VERSION, 3);
    }

    public function authenticate($username, $password) : bool
    {
        $username = "uid=".ldap_escape($username, LDAP_ESCAPE_DN);
        $pwd = $password;

        $baseDN = $this->config["LDAP_BASEDN"];

        $bind = @ldap_bind(self::$conn, $username.','.$baseDN, $pwd);
        if($bind)
        {
            $data = ldap_search(self::$conn, $username.','.$baseDN, '(cn=*)', array("uid"));
            $data = ldap_get_entries(self::$conn, $data);

            $stmt = Database::$pdo->prepare("SELECT id, email, permission_role FROM User WHERE uid= :uid");
            $stmt->bindParam("uid", $data[0]['uid'][0]);
            $stmt->execute();
            $_SESSION['logged_in'] = true;
            $_SESSION['userdata'] = $stmt->fetchAll();

            return true;

        }

        return false;
    }
}