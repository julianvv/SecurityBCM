<?php

use core\Application;

$app = Application::$app;
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
<nav class="navbar navbar-expand-lg navbar-dark customer-bg nav-height">
    <div class="container-fluid position-relative">
        <div class="row">
            <a class="navbar-brand brand" href="/"><img
                        src="../../assets/img/YouthEnergy.png"
                        alt="YouthEnergy Logo"></a>
            <?php if (Application::$app->session->get('logged_in')) { ?>
            <ul class='navbar-nav mr-auto nav-buttons'>
                <li class='nav-item mr-2'>
                    <a class='btn btn-greentheme' href='/account'>Account</a>
                </li>
                <li class='nav-item'>
                    <a class='btn btn-greentheme' href='/verbruiksmeter'>Verbruiksmeter</a>
                </li>
                <li class='right-button'>
                    <form action='/uitloggen' method='post'>
                        <button class='btn btn-greentheme' type='submit'>Uitloggen</button>
                    </form>
                </li>
            </ul>
            <ul class='navbar-nav nav-icons'>
                <li class='nav-item mr-2'>
                    <i class="fas fa-user fa-2x clickable" onclick="window.location.href = '/account'"></i>
                </li>
                <li class='nav-item'>
                    <i class="fas fa-chart-bar fa-2x clickable" onclick="window.location.href = '/verbruiksmeter'"></i>
                </li>
                <li class='right-button'>
                    <form action='/uitloggen' method='post'>
                        <i class="fas fa-sign-out-alt fa-2x clickable" onclick="$(this).closest('form').submit()"></i>
                    </form>
                </li>
            </ul>
            <?php } ?>
        </div>
    </div>


    <?= $app->timer ? sprintf("Time to execute in ms: %f", (hrtime(true) - $app->start) / 1e+6) : '' ?>


</nav>

<div id="error-box"
     class="alert <?= $app->session->getFlash('notification')['type'] ?>" <?= $app->session->getFlash('notification') ?  "style='display: block;'" : "" ?>>
    <button type="button" class="close" aria-label="Close">
        <span aria-hidden="true" onclick="hideError()">&times;</span>
    </button>
    <p class="error-text"><?= $app->session->getFlash('notification')['message'] ?></p>
</div>
<div class="background">
    {{content}}
</div>

<footer class="footer">
    <div class="container">
        <div class="row justify-content-center">
            <span class="text-muted">YouthEnergy&copy; - 2021 | </span>
            <a class="blue-link ml-1" href="/voorwaarden">Voorwaarden</a>
        </div>
    </div>
</footer>

<script>
    function hideError() {
        $("div#error-box").css('display', 'none');
    }
</script>
</body>
</html>