<div class="d-flex flex-column justify-content-center main-col">
    <div class="d-flex justify-content-center main-row">
        <div class="login-wrapper">
            <div class="card border border-success home-card">
                <div class="card-body loginpage">
                    <form method="post" id="login-form">
                        <h3 class="card-title">Inloggen</h3>
                        <p class="card-text">Login met uw emailadres</p>
                        <div class="form-group">
                            <label class="ml-1" for="klantnummer">Email</label>
                            <input type="text" class="form-control" id="email" placeholder="Email" name="email"
                                   autocomplete="email">
                        </div>
                        <div class="form-group">
                            <label class="ml-1" for="wachtwoord">Wachtwoord</label>
                            <input type="password" class="form-control" id="login-wachtwoord" placeholder="Wachtwoord"
                                   name="wachtwoord"
                                   autocomplete="current-password">
                            <small>
                                <p class="form-text text-muted ml-1 p-btn" onclick="changeForm('forgotpassword-form')">
                                    Wachtwoord
                                    vergeten? </p>
                            </small>
                        </div>
                        <button type="submit" class="btn btn-greentheme" id="login-submit">Inloggen</button>
                        <button class="btn btn-greentheme float-right" type="button"
                                onclick="changeForm('register-form')">
                            Registreren?
                        </button>
                    </form>


                    <form method="post" id="register-form" hidden>
                        <h3 class="card-title">Registreren</h3>
                        <p class="card-text">Registreren is alleen mogelijk indien u al klant bij ons bent.</p>
                        <div class="form-group">
                            <label class="ml-1" for="klantnummer">Klantnummer</label>
                            <input type="number" class="form-control" id="klantnummer" placeholder="Klantnummer"
                                   name="klantnummer">
                            <small id="klantnummerhelp" class="form-text text-muted ml-1">Deze kunt u vinden op een van
                                uw
                                facturen.</small>
                        </div>
                        <div class="form-group">
                            <label class="ml-1" for="postcode">Postcode</label>
                            <input type="text" class="form-control" id="postcode" placeholder="Postcode"
                                   name="postcode">
                            <small id="postcodehelp" class="form-text text-muted ml-1">In het format: 1234AB</small>
                        </div>
                        <div class="form-group">
                            <label class="ml-1" for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Email" name="email">
                        </div>
                        <div class="form-group">
                            <label class="ml-1" for="wachtwoord">Wachtwoord</label>
                            <input type="password" class="form-control pr-password" id="wachtwoord"
                                   placeholder="Wachtwoord"
                                   name="password">
                            <small id="klantnummerhelp" class="form-text text-muted ml-1">Minimaal 8 karakters lang, 1
                                cijfer, 1
                                speciaal teken en een hoofdletter.</small>
                        </div>
                        <div class="form-group">
                            <label class="ml-1" for="wachtwoord-confirm">Wachtwoord Bevestigen</label>
                            <input type="password" class="form-control" id="wachtwoord-confirm"
                                   placeholder="Wachtwoord bevestigen"
                                   name="password-confirm">
                        </div>
                        <div class="custom-control custom-checkbox pb-3">
                            <input type="checkbox" class="custom-control-input" id="privacy-statement" name="privacy-statement">
                            <label for="privacy-statement" class="custom-control-label">Ik ga akkoord met <a
                                        href="/voorwaarden">voorwaarden</a>.</label>
                        </div>
                        <button class="btn btn-greentheme" type="button" onclick="changeForm('login-form')">Terug
                        </button>
                        <button type="submit" class="btn btn-greentheme float-right" id="register-submit">Registreren
                        </button>
                    </form>


                    <form method="post" id="forgotpassword-form" hidden>
                        <h3 class="card-title">Wachtwoord herstellen</h3>
                        <p class="card-text">Na het invullen van uw email en klantnummer zullen wij u een reset-link
                            sturen.</p>
                        <div class="form-group">
                            <label class="ml-1" for="klantnummer">Klantnummer</label>
                            <input type="text" class="form-control" id="klantnummer" placeholder="Klantnummer"
                                   name="klantnummer"
                                   autocomplete="username">
                            <small id="klantnummerhelp" class="form-text ml-1">Deze kunt u vinden op een van uw
                                facturen.</small>
                        </div>
                        <div class="form-group">
                            <label class="ml-1" for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Email" name="email"
                                   autocomplete="email">
                        </div>
                        <button type="submit" class="btn btn-greentheme" id="resetpassword-submit">Reset</button>
                        <button class="btn btn-greentheme float-right" type="button" onclick="changeForm('login-form')">
                            Terug
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function changeForm(form) {
        $("form").attr("hidden", true);
        $("form#" + form).attr("hidden", false);
        resetErrorBox();
    }

    function resetErrorBox() {
        $("div#error-box > p").text('');
        $("div#error-box").css("display", 'none');
        $("div#error-box").removeClass("alert-danger");
    }

    $("button#login-submit").click(function (e) {
        e.preventDefault();
        $.ajax({
            url: "/login",
            data: $("form#login-form").serialize(),
            dataType: 'json',
            method: "post",
            success: function (response) {
                if (response.status) {
                    window.location = "/verbruiksmeter";
                } else {
                    console.log(response)
                    $("div#error-box > p").text(response.error);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
            }
        });
    });

    $("button#register-submit").click(function (e) {
        e.preventDefault();
        $.ajax({
            url: "/register",
            data: $("form#register-form").serialize(),
            dataType: 'json',
            method: 'post',
            success: function (response) {
                if (!response.status) {
                    $("div#error-box > p").text(response.error);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }

                if (response.status) {
                    window.location.href = response.redirect;
                }
            }
        });
    });

    $("button#resetpassword-submit").click(function (e) {
        e.preventDefault();
        $.ajax({
            url: "/forgot-password",
            data: $("form#forgotpassword-form").serialize(),
            dataType: 'json',
            method: 'post',
            success: function (response) {
                if(response.status){
                    window.location.href = '/'
                }else{
                    resetErrorBox();
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
            }
        });
    });
</script>