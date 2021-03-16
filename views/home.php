<div class="card border border-success" style="min-width: 18vw; border-radius: 3%;">
    <div class="card-body">


        <form id="login-form">
            <h3 class="card-title">Inloggen</h3>
            <p class="card-text">Login met uw emailadres</p>
            <div id="login-feedback" class="invalid-feedback ml-1"></div>
            <div class="form-group">
                <label class="ml-1" for="klantnummer">Email</label>
                <input type="text" class="form-control" id="email" placeholder="Email" name="email"
                       autocomplete="email">
            </div>
            <div class="form-group">
                <label class="ml-1" for="wachtwoord">Wachtwoord</label>
                <input type="password" class="form-control" id="wachtwoord" placeholder="Wachtwoord" name="wachtwoord"
                       autocomplete="current-password">
                <small>
                    <p class="form-text text-muted ml-1 p-btn" onclick="changeForm('forgotpassword-form')">Wachtwoord
                        vergeten? </p>
                </small>
            </div>
            <button type="submit" class="btn btn-greentheme" id="login-submit">Inloggen</button>
            <button class="btn btn-greentheme float-right" type="button" onclick="changeForm('register-form')">
                Registreren?
            </button>
        </form>


        <form id="register-form" hidden>
            <h3 class="card-title">Registreren</h3>
            <p class="card-text">Registreren is alleen mogelijk indien u al klant bij ons bent.</p>
            <div id="register-feedback" class="invalid-feedback ml-1"></div>
            <div class="form-group">
                <label class="ml-1" for="klantnummer">Klantnummer</label>
                <input type="number" class="form-control" id="klantnummer" placeholder="Klantnummer" name="klantnummer">
                <small id="klantnummerhelp" class="form-text ml-1">Deze kunt u vinden op een van uw facturen.</small>
            </div>
            <div class="form-group">
                <label class="ml-1" for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Email" name="email">
            </div>
            <div class="form-group">
                <label class="ml-1" for="wachtwoord">Wachtwoord</label>
                <input type="password" class="form-control" id="wachtwoord" placeholder="Wachtwoord" name="password">
            </div>
            <div class="form-group mb-3">
                <label class="ml-1" for="wachtwoord-confirm">Wachtwoord Bevestigen</label>
                <input type="password" class="form-control" id="wachtwoord-confirm" placeholder="Wachtwoord bevestigen"
                       name="password-confirm">
            </div>
            <button type="submit" class="btn btn-greentheme" id="register-submit">Registreren</button>
            <button class="btn btn-greentheme float-right" type="button" onclick="changeForm('login-form')">Terug
            </button>
        </form>


        <form id="forgotpassword-form" hidden>
            <h3 class="card-title">Wachtwoord herstellen</h3>
            <p class="card-text">Na het invullen van uw email en klantnummer zullen wij u een reset-link sturen.</p>
            <div id="resetpassword-feedback" class="valid-feedback"></div>
            <div id="resetpassword-invalid-feedback" class="invalid-feedback"></div>
            <div class="form-group">
                <label class="ml-1" for="klantnummer">Klantnummer</label>
                <input type="text" class="form-control" id="klantnummer" placeholder="Klantnummer" name="klantnummer"
                       autocomplete="username">
                <small id="klantnummerhelp" class="form-text ml-1">Deze kunt u vinden op een van uw facturen.</small>
            </div>
            <div class="form-group">
                <label class="ml-1" for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Email" name="email"
                       autocomplete="email">
            </div>
            <button type="submit" class="btn btn-greentheme" id="resetpassword-submit">Reset</button>
            <button class="btn btn-greentheme float-right" type="button" onclick="changeForm('login-form')">Terug
            </button>
        </form>


    </div>
</div>


<script>
    function changeForm(form) {
        $("form").attr("hidden", true);
        $("form#" + form).attr("hidden", false);
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
                    console.log(response);
                    $("div#login-feedback").text(response.error);
                    $("div#login-feedback").css("display", "block");
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
                    $("div#register-feedback").text(response.error);
                    $("div#register-feedback").css("display", "block");
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
            url: "/resetpassword",
            data: $("form#resetpassword-form").serialize(),
            dataType: 'json',
            method: 'post',
            success: function (response) {
                console.log(response);
            }
        });
    });
</script>