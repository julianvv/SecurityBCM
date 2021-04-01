<div class="d-flex flex-column justify-content-center main-col scrollable invis-scrollbar">
    <div class="d-flex justify-content-center main-row">
        <div class="card opacity-background opacity-employee">
            <div class="row helpdesk-main-row">
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="card employee-function-card">
                        <div class="card-body">
                            <h3 style="font-weight: bolder">Werknemer:</h3>
                            <p style="font-weight: bolder">{{voornaam}} {{achternaam}} - {{group}}</p>
                        </div>
                    </div>
                    <div class="card customer-info-helpdesk">
                        <div class="card-header customer-info-header">
                            <form id="klantForm">
                                <label for="klantnummerInput">Klantnummer</label>
                                <input type="number" id="klantnummerInput" placeholder="12345678" name="klantnummer"><br>
                                <label for="postcodeInput">Postcode</label>
                                <input id="postcodeInput" placeholder="0000AA" name="postcode"><br>
                                <button type="button" class="customerinfo-request-button" onclick="getKlant()">Zoeken <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="card-body employee-customer-info">
                            <p style="font-weight: bolder">Klantnummer: </p>
                            <p id="klantnummer"></p>
                            <p style="font-weight: bolder">Naam: </p>
                            <p id="klantnaam"></p>
                            <p style="font-weight: bolder">Straatnaam + Huisnummer: </p>
                            <p id="adres"></p>
                            <p style="font-weight: bolder">Postcode + Woontplaats: </p>
                            <p id="postcode"></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="card card-verbruik">
                        <div class="card-header verbruiksmeter-header-intranet">
                            <h2>Verbruik Klant</h2>
                        </div>
                        <div class="card-body vebruiksmeter-cardbody-intranet">
                            <div class="card energie-kaart">
                                <div class="card-header header-stroom">
                                    <h2 style="text-align: center">Stroom</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <p id="stroomVerbruik"></p>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-gas">
                                    <h2>Gas</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <p id="gasVerbruik"></p>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-eigen">
                                    <h2>Opgewekt</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <p id="stroomOpwek"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="card card-verbruik">
                        <div class="card-header verbruiksmeter-header-intranet">
                            <h2>Lokaal</h2>
                        </div>
                        <div class="card-body vebruiksmeter-cardbody-intranet">
                            <div class="card energie-kaart">
                                <div class="card-header header-stroom">
                                    <h2 style="text-align: center">Stroom</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <p id="lokaalStroomVerbruik"></p>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-gas">
                                    <h2>Gas</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <p id="lokaalGasVerbruik"></p>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-eigen">
                                    <h2>Opgewekt</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <p id="lokaalStroomOpwek"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="card card-verbruik">
                        <div class="card-header verbruiksmeter-header-intranet">
                            <h2>Provincie</h2>
                        </div>
                        <div class="card-body vebruiksmeter-cardbody-intranet">
                            <div class="card energie-kaart">
                                <div class="card-header header-stroom">
                                    <h2 style="text-align: center">Stroom</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <p id="provincieStroomVerbruik"></p>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-gas">
                                    <h2>Gas</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <p id="provincieGasVerbruik"></p>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-eigen">
                                    <h2>Opgewekt</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <p id="provincieStroomOpwek"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let gas, energieLv, energieHv, teruggaveLv, teruggaveHv;
    let lokaalGas, lokaalHv, lokaalLv, lokaalTerugHv, lokaalTerugLv;
    let provincieGas, provincieHv, provincieLv, provincieTerugHv, provincieTerugLv;
    function getKlant(){
        $.ajax({
            url: '/intranet/intranetData',
            method: 'post',
            dataType: 'json',
            data: { type: 'klantplus', klantnummer: $("input#klantnummerInput").val(), postcode: $("input#postcodeInput").val() },
            success: (response) => {
                console.log(response)
                if(response.status){
                    console.log(response.klantgegevens.k_klantnummer)
                    gas = (response['verbruik'][0][2] - response['verbruik'][5][2]).toFixed(2)
                    energieLv = (response['verbruik'][1][2] - response['verbruik'][6][2]).toFixed(2)
                    energieHv = (response['verbruik'][2][2] - response['verbruik'][7][2]).toFixed(2)
                    teruggaveLv = (response['verbruik'][3][2] - response['verbruik'][8][2]).toFixed(2)
                    teruggaveHv = (response['verbruik'][4][2] - response['verbruik'][9][2]).toFixed(2)

                    //Klantgegevens
                    $("p#klantnummer").text(response.klantgegevens.k_klantnummer);
                    $("p#klantnaam").text(response.klantgegevens.k_voornaam + " " + response.klantgegevens.k_achternaam);
                    $("p#adres").text(response.klantgegevens.a_straatnaam + " " + response.klantgegevens.a_huisnummer);
                    $("p#postcode").text(response.klantgegevens.a_postcode + " " + response.klantgegevens.a_plaatsnaam);

                    //Klant Verbruik/Teruggave
                    $("p#stroomVerbruik").text("Laag tarief: "+ energieLv + " Hoog tarief: " + energieHv);
                    $("p#gasVerbruik").text("Gas verbruik: " + gas);
                    $("p#stroomOpwek").text("Laag tarief: "+ teruggaveLv + " Hoog tarief: " + teruggaveHv);

                    //Lokaal Verbruik/Terruggave
                    let lokaal = JSON.parse(response.lokaal);
                    lokaalGas = (lokaal['postcodeGasM1'][0] - lokaal['postcodeGasM2'][0]).toFixed(2)
                    lokaalHv = (lokaal['postcodeAvgHvM1'][0] - lokaal['postcodeAvgHvM2'][0]).toFixed(2)
                    lokaalLv = (lokaal['postcodeAvgLvM1'][0] - lokaal['postcodeAvgLvM2'][0]).toFixed(2)
                    lokaalTerugHv = (lokaal['postcodeTerugHvM1'][0] - lokaal['postcodeTerugHvM2'][0]).toFixed(2)
                    lokaalTerugLv = (lokaal['postcodeTerugLvM1'][0] - lokaal['postcodeTerugLvM2'][0]).toFixed(2)
                    $("p#lokaalStroomVerbruik").text("Laag tarief: "+ lokaalLv + " Hoog tarief: " + lokaalHv);
                    $("p#lokaalGasVerbruik").text("Gas verbruik: " + lokaalGas);
                    $("p#lokaalStroomOpwek").text("Laag tarief: "+ lokaalTerugLv + " Hoog tarief: " + lokaalTerugHv);

                    //Provincie Verbruik/Terruggave
                    let provincie = JSON.parse(response.provincie);
                    provincieGas = (provincie['provincieGasM1'][0] - provincie['provincieGasM2'][0]).toFixed(2)
                    provincieHv = (provincie['provincieAvgHvM1'][0] - provincie['provincieAvgHvM2'][0]).toFixed(2)
                    provincieLv = (provincie['provincieAvgLvM1'][0] - provincie['provincieAvgLvM2'][0]).toFixed(2)
                    provincieTerugHv = (provincie['provincieTerugHvM1'][0] - provincie['provincieTerugHvM2'][0]).toFixed(2)
                    provincieTerugLv = (provincie['provincieTerugLvM1'][0] - provincie['provincieTerugLvM2'][0]).toFixed(2)
                    $("p#provincieStroomVerbruik").text("Laag tarief: "+ provincieLv + " Hoog tarief: " + provincieHv);
                    $("p#provincieGasVerbruik").text("Gas verbruik: " + provincieGas);
                    $("p#provincieStroomOpwek").text("Laag tarief: "+ provincieTerugLv + " Hoog tarief: " + provincieTerugHv);


                }else{
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }

            }
        });
    }
</script>