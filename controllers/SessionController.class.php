<?php


namespace controllers;


use core\Application;

class SessionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware([
            'auth',
            'terms'
        ]);

    }

    public function verbruiksmeterData()
    {
        if(!$this->prepareMiddleware())
        {
            die(json_encode(array('status'=>false, 'message'=>'U heeft geen toegang tot deze data.')));
        }
        $data = Application::$app->request->getBody();
        $type = $data['type'];

        switch($type)
        {
            case 'klant':
                die($this->getData(Application::$app->session->get('userdata')['k_klantnummer']));
            case 'provincie':
                die($this->getProvinceAvg($data['provincie']));
            case 'landelijk':
                die($this->getLandelijk());
            case 'postcode':
                $app = Application::$app;
                $stmt = $app->db->prepare("SELECT tbl_adressen.a_postcode
                                        FROM tbl_adressen
                                        JOIN tbl_klanten
                                        ON tbl_adressen.a_idAdres = tbl_klanten.k_fk_idAdres
                                        WHERE tbl_klanten.k_klantnummer = :klantnummer;");
                $stmt->bindParam('klantnummer', $app->session->get('userdata')['k_klantnummer']);
                $stmt->execute();
                $postcode1 = $stmt->fetch();
                $postcode = $postcode1[0];
                die($this->getPostcode($postcode));
        }
    }

    public function intranetData()
    {
        $data = Application::$app->request->getBody();
        $type = $data['type'];

        switch($type)
        {
            case 'klant':
                die($this->getData($data["klantnummer"]));
            case 'provincie':
                die($this->getProvinceAvg($data['provincie']));
            case 'landelijk':
                die($this->getLandelijk());
            case 'postcode':
                die($this->getPostcode($data["postcode"]));
            case 'klantplus':
                if(empty($data['klantnummer'])||empty($data['postcode'])){
                    die(json_encode(['status'=>false, 'message'=>'Vul beide velden in.']));
                }
                die($this->getCustomerData($data));
        }
    }

    public function getCustomerData($data)
    {
        $app = Application::$app;
        $stmt = $app->db->prepare("SELECT ms_idMeterstand, ms_fk_idMeterTelwerk, ms_stand, ms_datum 
												FROM tbl_meters_standen 
												JOIN tbl_meter_telwerken
												ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
												JOIN tbl_meters
												ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
												JOIN tbl_klanten 
												ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
												JOIN tbl_adressen
												ON tbl_adressen.a_idAdres = tbl_klanten.k_fk_idAdres
												WHERE tbl_klanten.k_klantnummer = :klantnummer AND tbl_adressen.a_postcode = :postcode
												ORDER BY ms_datum DESC, ms_fk_idMeterTelwerk ASC 
												LIMIT 10;");
        $stmt->bindParam('klantnummer', $data['klantnummer']);
        $stmt->bindParam('postcode', $data['postcode']);
        $stmt->execute();
        $klantVerbruik = $stmt->fetchAll();
        if(!$klantVerbruik )
        {
            return json_encode(array('status'=>false, 'message'=>'Geen data bekend'));
        }

        $stmt = $app->db->prepare("SELECT tbl_klanten.k_klantnummer, tbl_klanten.k_voornaam, tbl_klanten.k_achternaam, tbl_adressen.a_straatnaam, tbl_adressen.a_huisnummer, tbl_adressen.a_plaatsnaam, tbl_adressen.a_postcode, tbl_adressen.a_provincie
                                        FROM tbl_klanten 
                                        JOIN tbl_adressen
                                        ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                        WHERE tbl_adressen.a_postcode = :postcode AND tbl_klanten.k_klantnummer = :klantnummer");
        $stmt->bindParam('klantnummer', $data['klantnummer']);
        $stmt->bindParam('postcode', $data['postcode']);
        $stmt->execute();
        $klantGegevens = $stmt->fetch();

        $lokaal = $this->getPostcode($data['postcode']);
        $provincie = $this->getProvinceAvg($klantGegevens['a_provincie']);


        return json_encode(array('status'=>true, 'verbruik'=>$klantVerbruik, 'klantgegevens'=>$klantGegevens, "lokaal" => $lokaal, "provincie" => $provincie));
    }

    public function getData($klantnummer)
    {
        $app = Application::$app;
        $stmt = $app->db->prepare("SELECT ms_idMeterstand, ms_fk_idMeterTelwerk, ms_stand, ms_datum 
												FROM tbl_meters_standen 
												JOIN tbl_meter_telwerken
												ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
												JOIN tbl_meters
												ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
												JOIN tbl_klanten 
												ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
												WHERE tbl_klanten.k_klantnummer = :klantnummer
												ORDER BY ms_datum DESC, ms_fk_idMeterTelwerk ASC 
												LIMIT 10;");
        $stmt->bindParam('klantnummer', $klantnummer, \PDO::PARAM_INT);
        $stmt->execute();
        $klantVerbruik = $stmt->fetchAll();
        if(!$klantVerbruik )
        {
            return json_encode(array('status'=>false, 'message'=>'Geen data bekend'));
        }

        return json_encode(array('status'=>true, 'verbruik'=>$klantVerbruik));
    }

    public function getLandelijk()
    {
        $app = Application::$app;
        $end = date('c', strtotime('-14 month'));
        $start = date('c', strtotime('-15 month'));
        $end2 = date('c', strtotime('-15 month'));
        $start2 = date('c', strtotime('-16 month'));

        $stmt = $app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_meters_standen.ms_datum BETWEEN :start AND :end AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 1;");
        $stmt->bindParam('start', $start, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end, \PDO::PARAM_STR);
        $stmt->execute();
        $avgGasM1 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_meters_standen.ms_datum BETWEEN :start AND :end AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 1;");
        $stmt->bindParam('start', $start2, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end2, \PDO::PARAM_STR);
        $stmt->execute();
        $avgGasM2 = $stmt->fetch();

        $stmt = $app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_meters_standen.ms_datum BETWEEN :start AND :end AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 3;");
        $stmt->bindParam('start', $start, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end, \PDO::PARAM_STR);
        $stmt->execute();
        $avgHvM1 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_meters_standen.ms_datum BETWEEN :start AND :end AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 3;");
        $stmt->bindParam('start', $start2, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end2, \PDO::PARAM_STR);
        $stmt->execute();
        $avgHvM2 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_meters_standen.ms_datum BETWEEN :start AND :end AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 2;");
        $stmt->bindParam('start', $start, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end, \PDO::PARAM_STR);
        $stmt->execute();
        $avgLvM1 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_meters_standen.ms_datum BETWEEN :start AND :end AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 2;");
        $stmt->bindParam('start', $start2, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end2, \PDO::PARAM_STR);
        $stmt->execute();
        $avgLvM2 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_meters_standen.ms_datum BETWEEN :start AND :end AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 4;");
        $stmt->bindParam('start', $start, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end, \PDO::PARAM_STR);
        $stmt->execute();
        $returnHvM1 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_meters_standen.ms_datum BETWEEN :start AND :end AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 4;");
        $stmt->bindParam('start', $start2, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end2, \PDO::PARAM_STR);
        $stmt->execute();
        $returnHvM2 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_meters_standen.ms_datum BETWEEN :start AND :end AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 0;");
        $stmt->bindParam('start', $start, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end, \PDO::PARAM_STR);
        $stmt->execute();
        $returnLvM1 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_meters_standen.ms_datum BETWEEN :start AND :end AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 0;");
        $stmt->bindParam('start', $start2, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end2, \PDO::PARAM_STR);
        $stmt->execute();
        $returnLvM2 = $stmt->fetch();

        if(!$avgHvM1 || !$avgHvM2 || !$avgLvM1 || !$avgLvM2 || !$returnHvM1 || !$returnHvM2 || !$returnLvM1 || !$returnLvM2 || !$avgGasM1 )
        {
            return json_encode(array('status'=>false, 'message'=>'Geen data bekend'));
        }

        return json_encode(array('status'=>true, 'landelijkAvgHvM1'=>$avgHvM1,'landelijkAvgHvM2'=>$avgHvM2, 'landelijkAvgLvM1'=>$avgLvM1, 'landelijkAvgLvM2'=>$avgLvM2, 'landelijkTerugHvM1'=>$returnHvM1, 'landelijkTerugHvM2'=>$returnHvM2, 'landelijkTerugLvM1'=>$returnLvM1, 'landelijkTerugLvM2'=>$returnLvM2, 'landelijkGasM1'=>$avgGasM1, 'landelijkGasM2'=>$avgGasM2));
    }

    public function getProvinceAvg($provincie)
    {
        $app = Application::$app;
        $end = date('c', strtotime('-14 month'));
        $start = date('c', strtotime('-15 month'));
        $end2 = date('c', strtotime('-15 month'));
        $start2 = date('c', strtotime('-16 month'));

        $stmt = $app->db->prepare("SELECT * FROM tbl_adressen WHERE a_provincie = :provincie");
        $stmt->bindParam('provincie', $provincie, \PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();

        if(!$result)
        {
            return json_encode(array('status'=>false, 'message'=>'Geen data bekend van '.htmlspecialchars($provincie)));
        }

        $stmt = $app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_provincie = :provincie AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 1;");
        $stmt->bindParam('provincie', $provincie, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end, \PDO::PARAM_STR);
        $stmt->execute();
        $avgGasM1 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_provincie = :provincie AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 1;");
        $stmt->bindParam('provincie', $provincie, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start2, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end2, \PDO::PARAM_STR);
        $stmt->execute();
        $avgGasM2 = $stmt->fetch();

        $stmt = $app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_provincie = :provincie AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 3;");
        $stmt->bindParam('provincie', $provincie, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end, \PDO::PARAM_STR);
        $stmt->execute();
        $avgHvM1 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_provincie = :provincie AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 3;");
        $stmt->bindParam('provincie', $provincie, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start2, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end2, \PDO::PARAM_STR);
        $stmt->execute();
        $avgHvM2 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_provincie = :provincie AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 2;");
        $stmt->bindParam('provincie', $provincie, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end, \PDO::PARAM_STR);
        $stmt->execute();
        $avgLvM1 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_provincie = :provincie AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 2;");
        $stmt->bindParam('provincie', $provincie, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start2, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end2, \PDO::PARAM_STR);
        $stmt->execute();
        $avgLvM2 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_provincie = :provincie AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 4;");
        $stmt->bindParam('provincie', $provincie, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end, \PDO::PARAM_STR);
        $stmt->execute();
        $returnHvM1 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_provincie = :provincie AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 4;");
        $stmt->bindParam('provincie', $provincie, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start2, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end2, \PDO::PARAM_STR);
        $stmt->execute();
        $returnHvM2 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_provincie = :provincie AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 0;");
        $stmt->bindParam('provincie', $provincie, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end, \PDO::PARAM_STR);
        $stmt->execute();
        $returnLvM1 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_provincie = :provincie AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 0;");
        $stmt->bindParam('provincie', $provincie, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start2, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end2, \PDO::PARAM_STR);
        $stmt->execute();
        $returnLvM2 = $stmt->fetch();

        if(!$avgHvM1 || !$avgHvM2 || !$avgLvM1 || !$avgLvM2 || !$returnHvM1 || !$returnHvM2 || !$returnLvM1 || !$returnLvM2 || !$avgGasM1 )
        {
            return json_encode(array('status'=>false, 'message'=>'Geen data bekend'));
        }

        return json_encode(array('status'=>true, 'provincieAvgHvM1'=>$avgHvM1,'provincieAvgHvM2'=>$avgHvM2, 'provincieAvgLvM1'=>$avgLvM1, 'provincieAvgLvM2'=>$avgLvM2, 'provincieTerugHvM1'=>$returnHvM1, 'provincieTerugHvM2'=>$returnHvM2, 'provincieTerugLvM1'=>$returnLvM1, 'provincieTerugLvM2'=>$returnLvM2, 'provincieGasM1'=>$avgGasM1, 'provincieGasM2'=>$avgGasM2));
    }

    public function getPostcode($postcode)
    {
        $app = Application::$app;
        $end = date('c', strtotime('-14 month'));
        $start = date('c', strtotime('-15 month'));
        $end2 = date('c', strtotime('-15 month'));
        $start2 = date('c', strtotime('-16 month'));




        $stmt = $app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_postcode = :postcode AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 1;");
        $stmt->bindParam('postcode', $postcode, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end, \PDO::PARAM_STR);
        $stmt->execute();
        $avgGasM1 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_postcode = :postcode AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 1;");
        $stmt->bindParam('postcode', $postcode, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start2, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end2, \PDO::PARAM_STR);
        $stmt->execute();
        $avgGasM2 = $stmt->fetch();

        $stmt = $app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_postcode = :postcode AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 3;");
        $stmt->bindParam('postcode', $postcode, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end, \PDO::PARAM_STR);
        $stmt->execute();
        $avgHvM1 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_postcode = :postcode AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 3;");
        $stmt->bindParam('postcode', $postcode, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start2, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end2, \PDO::PARAM_STR);
        $stmt->execute();
        $avgHvM2 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_postcode = :postcode AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 2;");
        $stmt->bindParam('postcode', $postcode, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end, \PDO::PARAM_STR);
        $stmt->execute();
        $avgLvM1 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_postcode = :postcode AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 2;");
        $stmt->bindParam('postcode', $postcode, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start2, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end2, \PDO::PARAM_STR);
        $stmt->execute();
        $avgLvM2 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_postcode = :postcode AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 4;");
        $stmt->bindParam('postcode', $postcode, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end, \PDO::PARAM_STR);
        $stmt->execute();
        $returnHvM1 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_postcode = :postcode AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 4;");
        $stmt->bindParam('postcode', $postcode, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start2, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end2, \PDO::PARAM_STR);
        $stmt->execute();
        $returnHvM2 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_postcode = :postcode AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 0;");
        $stmt->bindParam('postcode', $postcode, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end, \PDO::PARAM_STR);
        $stmt->execute();
        $returnLvM1 = $stmt->fetch();

        $stmt = Application::$app->db->prepare("SELECT AVG(ms_stand)
                                                    FROM tbl_meters_standen
                                                    JOIN tbl_meter_telwerken
                                                    ON tbl_meters_standen.ms_fk_idMeterTelwerk = tbl_meter_telwerken.mt_idMeterTelwerk
                                                    JOIN tbl_meters
                                                    ON tbl_meter_telwerken.mt_fk_idMeter = tbl_meters.m_idMeter
                                                    JOIN tbl_klanten 
                                                    ON tbl_meters.m_fk_idAdres = tbl_klanten.k_fk_idAdres
                                                    JOIN tbl_adressen
                                                    ON tbl_klanten.k_fk_idAdres = tbl_adressen.a_idAdres
                                                    WHERE tbl_adressen.a_postcode = :postcode AND (tbl_meters_standen.ms_datum BETWEEN :start AND :end) AND tbl_meters_standen.ms_fk_idMeterTelwerk % 5 = 0;");
        $stmt->bindParam('postcode', $postcode, \PDO::PARAM_STR);
        $stmt->bindParam('start', $start2, \PDO::PARAM_STR);
        $stmt->bindParam('end', $end2, \PDO::PARAM_STR);
        $stmt->execute();
        $returnLvM2 = $stmt->fetch();

        if(!$avgHvM1 || !$avgHvM2 || !$avgLvM1 || !$avgLvM2 || !$returnHvM1 || !$returnHvM2 || !$returnLvM1 || !$returnLvM2 || !$avgGasM1 )
        {
            return json_encode(array('status'=>false, 'message'=>'Geen data bekend'));
        }

        return json_encode(array('status'=>true, 'postcodeAvgHvM1'=>$avgHvM1,'postcodeAvgHvM2'=>$avgHvM2, 'postcodeAvgLvM1'=>$avgLvM1, 'postcodeAvgLvM2'=>$avgLvM2, 'postcodeTerugHvM1'=>$returnHvM1, 'postcodeTerugHvM2'=>$returnHvM2, 'postcodeTerugLvM1'=>$returnLvM1, 'postcodeTerugLvM2'=>$returnLvM2, 'postcodeGasM1'=>$avgGasM1, 'postcodeGasM2'=>$avgGasM2));
    }

}