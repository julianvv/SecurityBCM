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
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../assets/css/custom.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark employee-bg" style="height: 8vh;">
    <a class="navbar-brand brand" href="/"><img
                src="../../assets/img/YouthEnergy.png"
                alt="YouthEnergy Logo"></a>

    <?= $app->timer ? sprintf("Time to execute in ms: %f", (hrtime(true) - $app->start)/1e+6) : '' ?>

    <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
        <span class='navbar-toggler-icon'></span>
    </button>
    <?php if(Application::$app->session->get('logged_in')){ ?>

        <div class='collapse navbar-collapse' id='navbarSupportedContent'>
            <ul class='navbar-nav mr-auto'>
                <li class='nav-item mr-2'>
                    <a class='btn btn-greentheme' href='/account'>Account</a>
                </li>
                <li class='nav-item mr-2'>
                    <a class='btn btn-greentheme' href='/verbruiksmeter'>Verbruiksmeter</a>
                </li>
            </ul>
            <ul class='navbar-nav'>
                <li class='nav-item mr-2'>
                    <form action='/uitloggen' method='post'>
                        <button class='btn btn-greentheme' type='submit'>Uitloggen</button>
                    </form>
                </li>
            </ul>
        </div>
    <?php } ?>
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

<script>
    function hideError() {
        $("div#error-box").css('display', 'none');
    }
</script>

</body>
</html>