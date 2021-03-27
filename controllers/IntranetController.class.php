<?php


namespace controllers;


use core\Application;
use core\View;

class IntranetController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function showIndex()
    {
        $session = Application::$app->session;
        $employee_data = Application::$app->ldap->getDataByUID($_SERVER['PHP_AUTH_USER'], 'employee');
        $group = Application::$app->ldap->getEmployeeGroup($employee_data['dn']);


        $session->set('employee_data', $employee_data);
        $session->set('employee_group', $group);

        $voornaam = $session->get('employee_data')['cn'][0];
        $achternaam = $session->get('employee_data')['sn'][0];

        return View::view('intranet.home', ["title" => $voornaam.' '.$achternaam, 'voornaam' => $voornaam, 'achternaam' => $achternaam, 'group' => $group]);
    }

    public function showVerbruiksmeter()
    {
        return View::view('intranet.verbruiksmeter');
    }
}