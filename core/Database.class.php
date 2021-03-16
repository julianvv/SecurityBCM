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

    public function prepare($sql){
        return self::$pdo->prepare($sql);
    }

    public function query($sql)
    {
        return self::$pdo->query($sql);
    }
}