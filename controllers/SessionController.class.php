<?php


namespace controllers;


use core\Application;

class SessionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSessionData(){
        $app = Application::$app;

        if(!$app->session->get('logged_in')){
            die(json_encode(array("status" => "false")));
        }

        die(json_encode(array("labels" => ["Gas", "Licht"], "verbruik" => [123, 12])));
        //labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        //            datasets: [{
        //                label: '# of Votes',
        //                data: kaas,
        //                backgroundColor: [
        //                    'rgba(255, 99, 132, 0.2)',
        //                    'rgba(54, 162, 235, 0.2)',
        //                    'rgba(255, 206, 86, 0.2)',
        //                    'rgba(75, 192, 192, 0.2)',
        //                    'rgba(153, 102, 255, 0.2)',
        //                    'rgba(255, 159, 64, 0.2)'
        //                ],
        //                borderColor: [
        //                    'rgba(255, 99, 132, 1)',
        //                    'rgba(54, 162, 235, 1)',
        //                    'rgba(255, 206, 86, 1)',
        //                    'rgba(75, 192, 192, 1)',
        //                    'rgba(153, 102, 255, 1)',
        //                    'rgba(255, 159, 64, 1)'
        //                ],
        //                borderWidth: 1
        //            }]
    }
}