<?php


namespace core;


class Database
{   
    public static $pdo;

    public function __construct()
    {
        if(!Database::$pdo){
            $databaseConfig = Application::$config;

            try {
                $dsn = "mysql:host=".$databaseConfig["DB_HOST"].";port=".$databaseConfig['DB_PORT'].";dbname=".$databaseConfig["DB_NAME"];
                $username = $databaseConfig["DB_USER"];
                $password = $databaseConfig["DB_PASSWORD"];

                self::$pdo = new \PDO($dsn, $username, $password, array(\PDO::ATTR_TIMEOUT => 1, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
            } catch (\PDOException $e){
                Application::$app->session->setFlash('notification', ["type" => "alert-danger", "message" => "Er is een fout opgetreden bij het verbinden met de database."]);
            }
        }
    }

    public function connected()
    {
        if(self::$pdo){
            return true;
        }
        return false;
    }

    public function getRoleRank($group)
    {
        $stmt = self::prepare("SELECT id FROM Roles WHERE name=:group");
        $stmt->bindParam("group", $group, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch()["id"] ?? PHP_INT_MAX;
    }

    public function startUserSession($mail)
    {
        $_SESSION['logged_in'] = true;
        Application::$app->db->fetchByEmail($mail);
    }

    public function prepare($sql){
        return self::$pdo->prepare($sql);
    }

    public function query($sql)
    {
        return self::$pdo->query($sql);
    }

    public function register_db_user($register_data, $new = 1)
    {
        $permission_role = "Klant";
        $permission_granted = 0;
        $pending = 1;

        if(isset($register_data['privacy-statement'])){
            $akkoord = 1;
        }else{
            $akkoord = 0;
        }

        $stmt = Application::$app->db->prepare("INSERT INTO User (email, permission_role, k_klantnummer, permission_granted, pending, akkoord_met_voorwaarden) VALUES (:email, :permission_role, :k_klantnummer, :permission_granted, :pending, :akkoord)");
        $stmt->bindParam("email", $register_data['email'], \PDO::PARAM_STR);
        $stmt->bindParam("permission_role", $permission_role, \PDO::PARAM_STR);
        $stmt->bindParam("k_klantnummer", $register_data['klantnummer'], \PDO::PARAM_STR);
        $stmt->bindParam("permission_granted", $permission_granted, \PDO::PARAM_INT);
        $stmt->bindParam("pending", $pending, \PDO::PARAM_INT);
        $stmt->bindParam("akkoord", $akkoord, \PDO::PARAM_INT);
        $stmt->execute();

        $stmt = Application::$app->db->prepare("SELECT * FROM User WHERE k_klantnummer = :klantnummer");
        $stmt->bindParam("klantnummer", $register_data['klantnummer'], \PDO::PARAM_STR);
        $stmt->execute();
        $id = $stmt->fetch()['id'];

        $verify_code = rand(100000000000,999999999999);
        $stmt = Application::$app->db->prepare("INSERT INTO tbl_klanten_verificatie (v_fk_idUser, v_code) VALUES (:id, :code)");
        $stmt->bindParam("id", $id, \PDO::PARAM_INT);
        $stmt->bindParam("code", $verify_code, \PDO::PARAM_STR);
        $stmt->execute();

        if($new === 1){
            Application::$app->logger->writeToLog(sprintf("Nieuw account geregistreerd met e-mail: %s en ID: `%s` vanaf IP: %s", $register_data['email'], $id, $_SERVER['REMOTE_ADDR']));
            Application::$app->ldap->authenticate($register_data['email'], $register_data['password']);
            self::startUserSession($register_data['email']);
            die(json_encode(array("status" => true, "redirect" => "/")));
        }else{
            Application::$app->logger->writeToLog(sprintf("Bestaand account geregistreerd met email: %s en ID: `%s` vanaf IP: %s", $register_data['email'], $id, $_SERVER['REMOTE_ADDR']));
            Application::$app->session->setFlash('notification', ["type" => "alert-success", "message" => "U heeft al een account bij YouthEnergy. Login met uw bestaande account."]);
            die(json_encode(array("status" => true, "redirect" => "/")));
        }
    }

    public function exists($email)
    {
        $stmt = self::prepare("SELECT * FROM User WHERE email = :email");
        $stmt->bindParam("email", $email, \PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->fetch() > 0){
            return true;
        }
        return false;
    }

    public function fetchByEmail($email)
    {
        //Fetch userdata by email
        $stmt = Database::$pdo->prepare("SELECT * FROM User WHERE email= :email");
        $stmt->bindParam("email", $email);
        $stmt->execute();
        $data = $stmt->fetch();
        Application::$app->session->set('userdata', $data);
    }

    public function getUserID($email)
    {
        $stmt = Database::$pdo->prepare("SELECT * FROM User WHERE email= :email");
        $stmt->bindParam("email", $email);
        $stmt->execute();
        $data = $stmt->fetch();
        return $data['id'];
    }

    public function refreshSession()
    {
        $email = Application::$app->session->get('userdata')['email'] ?? false;
        self::fetchByEmail($email);
    }
}