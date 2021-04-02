<div class="d-flex flex-column justify-content-center main-col">
    <div class="d-flex justify-content-center main-row">
        <div class="container account-container">
            <div class="card account-maincard">
                <div class="card-header account-header-intranet">
                    <h1 id="accountTitle">Account medewerker</h1>
                    <h1 id="resetTitle" hidden>Wachtwoord veranderen</h1>
                </div>
                <div class="card-body" style="background-color: white">
                    <div id="mainScreen">
                        <div class="card account-gegevens-intranet">
                            <p style="font-weight: bolder">Naam:</p>
                            <p>{{voornaam}} {{achternaam}}</p>
                            <p style="font-weight: bolder">Rol:</p>
                            <p>{{group}}</p>
                        </div>
                        <div class="row row-accountbutton">
                            <button class="button-account-intranet" onclick="showReset()">Wachtwoord Veranderen</button>
                        </div>
                    </div>
                    <div id="resetScreen" hidden>
                        <form method="post" id="changepassword-form">
                            <p class="card-text">Hier kunt u uw wachtwoord aanpassen.</p>
                            <div class="form-group">
                                <label class="ml-1" for="password">Wachtwoord</label>
                                <input type="password" class="form-control" id="password" placeholder="Wachtwoord"
                                       name="password"
                                       autocomplete="password">
                                <small id="passwordhelp" class="form-text text-muted ml-1">Uw huidige
                                    wachtwoord</small>
                            </div>
                            <div class="form-group">
                                <label class="ml-1" for="pasword_new">Nieuw Wachtwoord</label>
                                <input type="password" class="form-control" id="password_new"
                                       placeholder="Nieuw Wachtwoord" name="password_new">
                                <small id="password_newhelp" class="form-text text-muted ml-1">Minimaal 8 karakters lang,
                                    1
                                    cijfer, 1
                                    speciaal teken en een hoofdletter.</small>
                            </div>
                            <div class="form-group">
                                <label class="ml-1" for="password_new_confirm">Nieuw Wachtwoord Bevestigen</label>
                                <input type="password" class="form-control" id="password_new_confirm"
                                       placeholder="Nieuw Wachtwoord Bevestigen" name="password_new_confirm">
                            </div>
                        </form>
                        <div class="row row-accountbutton">
                            <div class="col">
                                <button class="button-account-intranet" onclick="showMain()">Terug</button>
                            </div>
                            <div class="col">
                                <button class="button-account-intranet" onclick="changePassword()">Wachtwoord
                                    veranderen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    function showMain() {
        $("div#mainScreen").attr("hidden", false);
        $("h1#accountTitle").attr("hidden", false);
        $("div#resetScreen").attr("hidden", true);
        $("h1#resetTitle").attr("hidden", true);
    }

    function showReset() {
        $("div#mainScreen").attr("hidden", true);
        $("h1#accountTitle").attr("hidden", true);
        $("div#resetScreen").attr("hidden", false);
        $("h1#resetTitle").attr("hidden", false);
    }

    function changePassword() {
        $.ajax({
            url: "/intranet/change_password",
            method: "post",
            data: $("form#changepassword-form").serialize(),
            dataType: 'json',
            success: (response) => {
                if (response.status) {
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-success");
                    $("div#error-box").removeClass("alert-danger");
                    window.location.href = "/intranet";
                } else {
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                    $("div#error-box").removeClass("alert-success");
                }
            },
            fail: () => {
                $("div#error-box > p").text("Er is iets misgegaan...");
                $("div#error-box").css("display", 'block');
                $("div#error-box").addClass("alert-danger");
                $("div#error-box").removeClass("alert-success");
            }
        });
    }

</script>