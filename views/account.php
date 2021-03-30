<div class="d-flex flex-column justify-content-center main-col">
    <div class="d-flex justify-content-center main-row">
        <div class="container">
            <div class="card account-maincard">
                <div class="card-header account-header">
                    <h1>Account</h1>
                </div>
                <div class="card-body" id="main">
                    <div class="row persoonsgegevensrow position-relative">
                        <div class="col persoonsgegevens-col">
                            <h4 class="account-title">Persoonsgegevens</h4>
                        </div>
                        <p class="account-verwijderen">Account Verwijderen</p>
                    </div>
                    <div class="row justify-content-center">
                        <div class="card card-klantgegevens">
                            <div class="card-body">
                                <p>{{klantnummer}}</p>
                                <p>{{email}}</p>
                                <p>{{voornaam}} {{achternaam}}</p>
                                <p>{{straatnaam}} {{huisnummer}}</p>
                                <p>{{postcode}} {{plaatsnaam}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row row-accountbutton">
                        <button class="button-account" onclick="changeCard('change-permission', 'main')">Toestemming Slimme meter</button>
                    </div>
                    <div class="row row-accountbutton">
                        <button class="button-account" onclick="changeCard('change-password', 'main')">Wachtwoord Veranderen</button>
                    </div>
                </div>
                <div class="card-body" id="change-password" hidden>
                    <form method="post" id="changepassword-form">
                        <h3 class="card-title">Wachtwoord veranderen</h3>
                        <p class="card-text">Hier kunt u uw wachtwoord aanpassen.</p>
                        <div class="form-group">
                            <label class="ml-1" for="klantnummer">Wachtwoord</label>
                            <input type="password" class="form-control" id="password" placeholder="Wachtwoord"
                                   name="password"
                                   autocomplete="password">
                            <small id="wachtwoordhelp" class="form-text text-muted ml-1">Uw huidige wachtwoord</small>
                        </div>
                        <div class="form-group">
                            <label class="ml-1" for="email">Nieuw Wachtwoord</label>
                            <input type="password" class="form-control" id="password-new" placeholder="Nieuw Wachtwoord" name="password-new">
                            <small id="klantnummerhelp" class="form-text text-muted ml-1">Minimaal 8 karakters lang, 1
                                cijfer, 1
                                speciaal teken en een hoofdletter.</small>
                        </div>
                        <div class="form-group">
                            <label class="ml-1" for="email">Nieuw Wachtwoord Bevestigen</label>
                            <input type="password" class="form-control" id="password-new-confirm" placeholder="Nieuw Wachtwoord Bevestigen" name="password-new-confirm">
                        </div>
                        <button type="submit" class="btn btn-greentheme" id="changepassword-submit">Verander wachtwoord</button>
                        <button class="btn btn-greentheme float-right" type="button" onclick="changeCard('main', 'change-password')">
                            Terug
                        </button>
                    </form>
                </div>
                <div class="card-body" id="change-permission" hidden>
                    <form>
                        <h3 class="card-title">Toestemming aanpassen</h3>
                        <p class="card-text">Hiermee past u uw toestemming aan.
                            Als u uw toestemming intrekt zal YouthEnergy uw slimme meter niet meer op reguliere basis uitlezen.
                            Ook zult u geen gebruik meer kunnen maken van de verbruiksmeter app zoals beschreven in onze voorwaarden.</p>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="customSwitch1" name="permission" value="false">

                                <label class="custom-control-label" for="customSwitch1">Ik geef toestemming</label>
                                <small id="switchhelp" class="form-text ml-1">Hiermee geeft u expliciet toegang aan YouthEnergy om uw slimme meter op reguliere basis uit te lezen.</small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-greentheme" id="changepermission-submit">Opslaan</button>
                        <button class="btn btn-greentheme float-right" type="button" onclick="changeCard('main', 'change-permission')">
                            Terug
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#customSwitch1").prop('checked', {{permission}})
    });

    $('button#changepermission-submit').click(function (e){
        e.preventDefault();
        let permission = $("input#customSwitch1").is(":checked") ? 1 : 0;
        $.ajax({
            url: '/account',
            method: 'post',
            data: { type: "permission", newPermission: permission},
            dataType: 'json',
            success: function (response){
                if(response.status){
                    window.location.href = '/account';
                }else{
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
            }
        });
    });

    $('button#changepassword-submit').click(function (e){
        e.preventDefault();
        $.ajax({
            url: '/account',
            method: 'post',
            data: { type: "password", password: $("input#password").val(), password_new: $("input#password-new").val(), password_new_confirm: $("input#password-new-confirm").val()},
            dataType: 'json',
            success: function (response){
                if(response.status){
                    window.location.href = '/account';
                }else{
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
            }
        });
    });

    function changeCard(card, oldCard){
        $('div#'+oldCard).attr('hidden', true);
        $('div#'+card).attr('hidden', false);
    }

</script>