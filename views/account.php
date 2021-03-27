<div class="d-flex flex-column justify-content-center main-col">
    <div class="d-flex justify-content-center main-row">
        <div class="container">
            <div class="card account-maincard">
                <div class="card-header account-header">
                    <h1>Account</h1>
                </div>
                <div class="card-body">
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
                                <p>{{voornaam}} {{achternaam}}</p>
                                <p>{{straatnaam}} {{huisnummer}}</p>
                                <p>{{postcode}} {{plaatsnaam}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row row-accountbutton">
                        <button class="button-account">Toestemming Slimme meter</button>
                    </div>
                    <div class="row row-accountbutton">
                        <button class="button-account">Wachtwoord Veranderen</button>
                    </div>
                    <div class="row row-accountbutton">
                        <button class="button-account">E-mailadres Veranderen</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let email = "{{email}}";

</script>