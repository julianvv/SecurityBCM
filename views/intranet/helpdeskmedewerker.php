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
                                <input type="number" id="klantnummerInput" placeholder="12345678"
                                       name="klantnummer"><br>
                                <label for="postcodeInput">Postcode</label>
                                <input id="postcodeInput" placeholder="0000AA" name="postcode"><br>
                                <button type="button" class="customerinfo-request-button" onclick="getKlant()">Zoeken <i
                                            class="fas fa-search"></i>
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
                <div class="col-lg-3 col-md-12 col-sm-12 scrollable invis-scrollbar">
                    <div class="card card-verbruik">
                        <div class="card-header verbruiksmeter-header-intranet">
                            <h2>Verbruik Klant</h2>
                        </div>
                        <div class="card-body vebruiksmeter-cardbody-intranet">
                            <p style="font-weight: bolder">Weergave maandelijkse vebruik klant.</p>
                            <div class="card energie-kaart">
                                <div class="card-header header-stroom">
                                    <h2 style="text-align: center">Stroom</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div class="row">
                                        <div class="col">
                                            <p style="font-weight: bolder">Verbruik Laag Tarief:</p>
                                        </div>
                                        <div class="col">
                                            <p id="stroomVerbruikLaag"></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p style="font-weight: bolder">Verbruik hoog Tarief:</p>
                                        </div>
                                        <div class="col">
                                            <p id="stroomVerbruikHoog"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-gas">
                                    <h2>Gas</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div class="row">
                                        <div class="col">
                                            <p style="font-weight: bolder">Gasverbruik:</p>
                                        </div>
                                        <div class="col">
                                            <p id="gasVerbruik"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-eigen">
                                    <h2>Opgewekt</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div class="row">
                                        <div class="col">
                                            <p style="font-weight: bolder">Opgewekt Laag Tarief:</p>
                                        </div>
                                        <div class="col">
                                            <p id="stroomOpwekLaag"></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p style="font-weight: bolder">Opgewekt hoog Tarief:</p>
                                        </div>
                                        <div class="col">
                                            <p id="stroomOpwekHoog"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p>Laag tarief is stroom verbruikt tussen 23:00 en 07:00</p>
                            <p>Hoog tarief is stroom verbruikt tussen 07:00 en 23:00</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 scrollable invis-scrollbar">
                    <div class="card card-verbruik">
                        <div class="card-header verbruiksmeter-header-intranet">
                            <h2>Lokaal</h2>
                        </div>
                        <div class="card-body vebruiksmeter-cardbody-intranet">
                            <p style="font-weight: bolder">Weergave in maandelijkse lokale gemiddelden.</p>
                            <div class="card energie-kaart">
                                <div class="card-header header-stroom">
                                    <h2 style="text-align: center">Stroom</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div class="row">
                                        <div class="col">
                                            <p style="font-weight: bolder">Verbruik Laag Tarief:</p>
                                        </div>
                                        <div class="col">
                                            <p id="lokaalStroomVerbruikLaag"></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p style="font-weight: bolder">Verbruik hoog Tarief:</p>
                                        </div>
                                        <div class="col">
                                            <p id="lokaalStroomVerbruikHoog"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-gas">
                                    <h2>Gas</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div class="row">
                                        <div class="col">
                                            <p style="font-weight: bolder">Gasverbruik:</p>
                                        </div>
                                        <div class="col">
                                            <p id="lokaalGasVerbruik"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-eigen">
                                    <h2>Opgewekt</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div class="row">
                                        <div class="col">
                                            <p style="font-weight: bolder">Opgewekt Laag Tarief:</p>
                                        </div>
                                        <div class="col">
                                            <p id="lokaalStroomOpwekLaag"></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p style="font-weight: bolder">Opgewekt hoog Tarief:</p>
                                        </div>
                                        <div class="col">
                                            <p id="lokaalStroomOpwekHoog"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p>Laag tarief is stroom verbruikt tussen 23:00 en 07:00</p>
                            <p>Hoog tarief is stroom verbruikt tussen 07:00 en 23:00</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 scrollable invis-scrollbar">
                    <div class="card card-verbruik">
                        <div class="card-header verbruiksmeter-header-intranet">
                            <h2>Provincie</h2>
                        </div>
                        <div class="card-body vebruiksmeter-cardbody-intranet">
                            <p style="font-weight: bolder">Weergave in maandelijkse provinciale gemiddelden.</p>
                            <div class="card energie-kaart">
                                <div class="card-header header-stroom">
                                    <h2 style="text-align: center">Stroom</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div class="row">
                                        <div class="col">
                                            <p style="font-weight: bolder">Verbruik Laag Tarief:</p>
                                        </div>
                                        <div class="col">
                                            <p id="provincieStroomVerbruikLaag"></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p style="font-weight: bolder">Verbruik hoog Tarief:</p>
                                        </div>
                                        <div class="col">
                                            <p id="provincieStroomVerbruikHoog"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-gas">
                                    <h2>Gas</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div class="row">
                                        <div class="col">
                                            <p style="font-weight: bolder">Gasverbruik:</p>
                                        </div>
                                        <div class="col">
                                            <p id="provincieGasVerbruik"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-eigen">
                                    <h2>Opgewekt</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div class="row">
                                        <div class="col">
                                            <p style="font-weight: bolder">Opgewekt Laag Tarief:</p>
                                        </div>
                                        <div class="col">
                                            <p id="provincieStroomOpwekLaag"></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p style="font-weight: bolder">Opgewekt hoog Tarief:</p>
                                        </div>
                                        <div class="col">
                                            <p id="provincieStroomOpwekHoog"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p>Laag tarief is stroom verbruikt tussen 23:00 en 07:00</p>
                            <p>Hoog tarief is stroom verbruikt tussen 07:00 en 23:00</p>
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

    function getKlant() {
        $.ajax({
            url: '/intranet/intranetData',
            method: 'post',
            dataType: 'json',
            data: {
                type: 'klantplus',
                klantnummer: $("input#klantnummerInput").val(),
                postcode: $("input#postcodeInput").val()
            },
            success: (response) => {
                console.log(response)
                if (response.status) {
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
                    $("p#stroomVerbruikLaag").text(energieLv + " kWh");
                    $("p#stroomVerbruikHoog").text(energieHv + " kWh");
                    $("p#gasVerbruik").text(gas + " m³");
                    $("p#stroomOpwekLaag").text(teruggaveLv + " kWh");
                    $("p#stroomOpwekHoog").text(teruggaveHv + " kWh");

                    //Lokaal Verbruik/Terruggave
                    let lokaal = JSON.parse(response.lokaal);
                    lokaalGas = (lokaal['postcodeGasM1'][0] - lokaal['postcodeGasM2'][0]).toFixed(2)
                    lokaalHv = (lokaal['postcodeAvgHvM1'][0] - lokaal['postcodeAvgHvM2'][0]).toFixed(2)
                    lokaalLv = (lokaal['postcodeAvgLvM1'][0] - lokaal['postcodeAvgLvM2'][0]).toFixed(2)
                    lokaalTerugHv = (lokaal['postcodeTerugHvM1'][0] - lokaal['postcodeTerugHvM2'][0]).toFixed(2)
                    lokaalTerugLv = (lokaal['postcodeTerugLvM1'][0] - lokaal['postcodeTerugLvM2'][0]).toFixed(2)
                    $("p#lokaalStroomVerbruikLaag").text(lokaalLv + " kWh");
                    $("p#lokaalStroomVerbruikHoog").text(lokaalHv + " kWh");
                    $("p#lokaalGasVerbruik").text(lokaalGas + "m3");
                    $("p#lokaalStroomOpwekLaag").text(lokaalTerugLv + " kWh");
                    $("p#lokaalStroomOpwekHoog").text(lokaalTerugHv + " kWh");

                    //Provincie Verbruik/Terruggave
                    let provincie = JSON.parse(response.provincie);
                    provincieGas = (provincie['provincieGasM1'][0] - provincie['provincieGasM2'][0]).toFixed(2)
                    provincieHv = (provincie['provincieAvgHvM1'][0] - provincie['provincieAvgHvM2'][0]).toFixed(2)
                    provincieLv = (provincie['provincieAvgLvM1'][0] - provincie['provincieAvgLvM2'][0]).toFixed(2)
                    provincieTerugHv = (provincie['provincieTerugHvM1'][0] - provincie['provincieTerugHvM2'][0]).toFixed(2)
                    provincieTerugLv = (provincie['provincieTerugLvM1'][0] - provincie['provincieTerugLvM2'][0]).toFixed(2)
                    $("p#provincieStroomVerbruikLaag").text(provincieLv + " kWh");
                    $("p#provincieStroomVerbruikHoog").text(provincieHv + " kWh");
                    $("p#provincieGasVerbruik").text(provincieGas + "m³");
                    $("p#provincieStroomOpwekLaag").text(provincieTerugLv + " kWh");
                    $("p#provincieStroomOpwekHoog").text(provincieTerugHv + " kWh");


                } else {
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
            }
        });
    }
</script>