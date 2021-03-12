<?php
use core\Application;
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <script src="https://kit.fontawesome.com/6a8e8c04fa.js" crossorigin="anonymous"></script>

    <title>Hello, world!</title>


    <link rel="stylesheet" href="../../assets/css/custom.css">
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-custom" style="max-height: 8vh;">
    <a class="navbar-brand brand" href="/"><img
                style="height: 11vh;width: auto; margin: -15px;" src="../../assets/YouthEnergy.png"
                alt="YouthEnergy Logo"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <?php
        $url = Application::$app->request->getUrl();
        if($url == "/" && Application::$app->session->get("logged_in")){
            Application::$app->response->redirect('/verbruiksmeter');
        }

        if(($url == "/verbruiksmeter" || $url == "/account") && !Application::$app->session->get("logged_in")){
            Application::$app->response->redirect('/');
        }

        if (isset($_SESSION['logged_in'])) {
            if ($_SESSION['logged_in']) {
                echo "
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
                    ";
            }
        }
        ?>
    </div>
</nav>
<div class="d-flex justify-content-center align-items-center" style="height: 92vh;">
{{content}}
</div>

</body>
</html>