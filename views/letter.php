<div class="d-flex flex-column justify-content-center main-col">
    <div class="d-flex justify-content-center main-row">
        <div class="card width-spacing height-spacing scrollable">
            <header class="m-3">
                <address class="client-address">
                    {{voornaam}} {{achternaam}}<br/>
                    {{straatnaam}} {{huisnummer}}<br/>
                    {{postcode}} {{plaatsnaam}}<br/>
                </address>
                <time datetime="2021-04-21">{{datum}}</time>
            </header>
            <div class="card-title ml-2">
                <h4> Betreft:  Verificatie code YouthEnergy</h4>
                <p>Klantnummer: {{klantnummer}}<br/>
            </div>
            <div class="card-body">
                <div class="content"> <!-- use this div only if it is required for styling -->
                    <p>Geachte Klant, <br/></p>

                    <p>U krijgt deze brief omdat uw Youth Energy account is geregistreerd voor de online verbruiksmeter.<br/>
                        Wanneer u niet de persoon bent die uw account geregistreerd heeft neem dan alstublieft contact <br/>
                        op met onze klantenservice via telefoonnummer <b>0900 1234</b>.</p>
                    </p>
                    <p>Als u probeert in te loggen met uw geregistreerde account op www.youthenergy.nl/verbruiksmeter dan <br/>
                        krijgt u de vraag om uw verificatiecode in te vullen.<br/>
                        U vult dan in het lege veld onderaan de pagina uw onderstaande verificatiecode in.<br/>
                    </p>
                    <p>
                        Uw verificatiecode is: <b>{{verificatiecode}}</b>
                    </p>
                    <p>
                        Wanneer u een foutmelding krijgt of wanneer u vragen heeft, kunt u contact opnemen<br/>
                        met onze klantenservice, dan helpen wij u graag verder.
                    </p>
                </div>

                <p>
                    Met vriendelijke groeten,<br/>
                    <br/>
                    YouthEnergy <br/>
                    Spaklerweg 20 <br/>
                    1096 BA Amsterdam.
                </p>
            </div>
        </div>
    </div>
</div>
