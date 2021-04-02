<div class="d-flex flex-column justify-content-center main-col">
    <div class="d-flex justify-content-center main-row">
        <div class="container">
            <div class="card account-maincard">
                <div class="card-header account-header">
                    <h1>Wachtwoord herstellen</h1>
                </div>
                <div class="card-body" id="main">
                    <form id="reset-pass-form">
                        <div class="form-group">
                            <label class="ml-1" for="newPassword">Nieuw Wachtwoord</label>
                            <input type="password" class="form-control" id="newPassword" placeholder="Nieuw Wachtwoord"
                                   name="password"
                                   autocomplete="password">
                            <small id="passwordHelp" class="form-text text-muted ml-1">Minimaal 8 karakters lang, 1
                                cijfer, 1
                                speciaal teken en een hoofdletter.</small>
                        </div>
                        <div class="form-group">
                            <label class="ml-1" for="password_new">Nieuw Wachtwoord herhalen</label>
                            <input type="password" class="form-control" id="password_new" placeholder="Nieuw Wachtwoord" name="password_new">

                        </div>
                        <div class="form-group">
                            <label class="ml-1" for="emailConfirm">Emailadres bevestigen</label>
                            <input type="email" class="form-control" id="emailConfirm" placeholder="Emailadres" name="email">
                        </div>
                        <div class="form-group">
                            <label class="ml-1" for="code">Herstelcode</label>
                            <input type="text" class="form-control" id="code" placeholder="Herstelcode" name="code">
                            <small id="codeHelp" class="form-text text-muted ml-1">De herstelcode heeft u per mail gekregen</small>
                        </div>
                        <button type="button" class="btn btn-greentheme" id="changepassword-submit" onclick="resetPassword()">Verander wachtwoord</button>
                        <button class="btn btn-greentheme float-right" type="button" onclick="homePage()">
                            Annuleren
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function resetPassword(){
        $.ajax({
            url: '/reset-password',
            method: 'post',
            data: $("form#reset-pass-form").serialize(),
            dataType: 'json',
            success: (response) => {
                if (response.status){
                    window.location.href = "/";
                }else{
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                    $("div#error-box").removeClass("alert-success");
                }

            }
        });
    }

    function homePage(){
        window.location.href = "/";
    }
</script>


