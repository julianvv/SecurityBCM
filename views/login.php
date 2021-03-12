<div class="row">
    <div class="card">
        <div class="card-body border border-success">
            <h3 class="card-title">Inloggen</h3>
            <p class="card-text">Login met uw klantennummer of emailadres</p>
            <form id="login-form">
                <div id="login-feedback" class="invalid-feedback"></div>
                <div class="form-group">
                    <label class="ml-1" for="klantnummer">Klantnummer</label>
                    <input type="text" class="form-control" id="klantnummer" placeholder="Klantnummer" name="klantnummer">
                    <small class="ml-1" id="klantnummerhelp" class="form-text">Deze kunt u vinden op een van uw facturen.</small>
                </div>
                <div class="form-group">
                    <label class="ml-1" for="wachtwoord">Wachtwoord</label>
                    <input type="password" class="form-control" id="wachtwoord" placeholder="Wachtwoord" name="wachtwoord">
                    <small>
                        <a class="form-text text-muted ml-1" href="/wachtwoordvergeten">Wachtwoord vergeten? </a>
                    </small>
                </div>
                <button type="submit" class="btn btn-greentheme" id="login-submit">Inloggen</button>
            </form>
        </div>
    </div>
</div>

<script>
    $("form#loginform").keydown(function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    $("button#login-submit").click(function (e){
        e.preventDefault();
        $.ajax({
            url:"/login",
            data: $("form#login-form").serialize(),
            dataType: 'json',
            method: "post",
            success: function (response){
                if(response.status){
                    window.location = "/verbruiksmeter";
                }else{
                    console.log(response);
                    $("div#login-feedback").text(response.error);
                    $("div#login-feedback").css("display", "block");
                }
            }
        });
    })
</script>