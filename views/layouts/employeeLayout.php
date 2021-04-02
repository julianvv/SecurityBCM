<?php

use core\Application;

$app = Application::$app;

include "employeenavbarlogics.php";
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../../assets/img/favicon.ico">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <script src="https://kit.fontawesome.com/6a8e8c04fa.js" crossorigin="anonymous"></script>

    <title>YE: {{title}}</title>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.js"
            integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../assets/css/custom.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark employee-bg nav-height">
    <div class="container-fluid position-relative height-100">
        <div class="d-flex align-items-center height-100">
            <a class="navbar-brand brand" href="/intranet"><img
                        src="../../assets/img/YouthEnergy.png"
                        alt="YouthEnergy Logo"></a>
            <ul class='navbar-nav mr-auto nav-buttons'>
                <?= $nav_buttons ?>
            </ul>
            <ul class='navbar-nav nav-icons'>
                <?= $nav_icons ?>
            </ul>
        </div>
    </div>
    <?= $app->timer ? sprintf("Time to execute in ms: %f", (hrtime(true) - $app->start) / 1e+6) : '' ?>
</nav>

<div id="error-box"
     class="alert <?= $app->session->getFlash('notification')['type'] ?>">
    <button type="button" class="close" aria-label="Close">
        <span aria-hidden="true" onclick="hideError()">&times;</span>
    </button>
    <p class="error-text"><?= $app->session->getFlash('notification')['message'] ?></p>
</div>
<div class="background">
    {{content}}
</div>

<footer class="footer employee-bg">
    <div class="container">
        <div class="row justify-content-center">
            <span>YouthEnergy&copy; - 2021 | </span>
            <a class="ml-1" href="/voorwaarden" style="color: #ace395">Voorwaarden</a>
        </div>
    </div>
</footer>

<script>
    function hideError() {
        $("div#error-box").css('display', 'none');
    }

    function logout() {
        $.ajax({
            type: 'POST',
            url: '/intranet/logout',
            success: function () {
                logoutAuth()
            }
        });

    }

    function logoutAuth(){
        window.location.href = '/';
        $.ajax({
            type: 'GET',
            url: '/intranet',
            headers: {
                "Authorization": "Basic " + btoa('' + ':' + '')
            },
            success: function () {
                console.log("Succesvol uitgelogd!");
            }
        });
    }
</script>

</body>
</html>