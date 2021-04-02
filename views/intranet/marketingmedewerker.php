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
                    <div class="card card-verbruik">
                        <div class="card-header verbruiksmeter-header-intranet">
                            <h2>Landelijk</h2>
                        </div>
                        <div class="card-body vebruiksmeter-cardbody-intranet" id="landelijk-data">
                            <div class="card energie-kaart">
                                <div class="card-header header-stroom">
                                    <h2 style="text-align: center">Stroom</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div id="stroom"></div>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-gas">
                                    <h2>Gas</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div id="gas"></div>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-eigen">
                                    <h2>Opgewekt</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div id="opgewekt"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="card employee-function-card">
                        <div class="card-body">
                            <h2>Klant Individueel</h2>
                            <input id="mKlantnummer" placeholder="1234567891011" name="klantnummer">
                            <button class="search-request" onclick="get_klant()"><i class="fas fa-search"></i>
                        </div>
                    </div>
                    <div class="card card-verbruik">
                        <div class="card-header verbruiksmeter-header-intranet">
                            <h2 id="searched_klant">>>Klantnaam<<</h2>
                        </div>
                        <div class="card-body vebruiksmeter-cardbody-intranet" id="klantnummer-data">
                            <div class="card energie-kaart">
                                <div class="card-header header-stroom">
                                    <h2 style="text-align: center" >Stroom</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div id="stroom"></div>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-gas">
                                    <h2>Gas</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div id="gas"></div>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-eigen">
                                    <h2>Opgewekt</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div id="opgewekt"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="card employee-function-card">
                        <div class="card-body postcode-marketing-header">
                            <h2>Postcode Selecteren</h2>
                            <input id="mPostcode" placeholder="0000AA" name="postcode">
                            <button class="search-request" onclick="get_postcode()"><i class="fas fa-search"></i>
                        </div>
                    </div>
                    <div class="card card-verbruik">
                        <div class="card-header verbruiksmeter-header-intranet">
                            <h2 id="searched_postcode">>>Postcode<<</h2>
                        </div>
                        <div class="card-body vebruiksmeter-cardbody-intranet"  id="postcode-data">
                            <div class="card energie-kaart">
                                <div class="card-header header-stroom">
                                    <h2 style="text-align: center">Stroom</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div id="stroom"></div>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-gas">
                                    <h2>Gas</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div id="gas"></div>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-eigen">
                                    <h2>Opgewekt</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div id="opgewekt"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="card employee-function-card">
                        <div class="card-body">
                            <h2>Provincie Selecteren</h2>
                            <select class="" name="provincie" id="marketing-provincie">
                                <option value="Friesland">
                                    Friesland
                                </option>
                                <option value="Groningen">
                                    Groningen
                                </option>
                                <option value="Drenthe">
                                    Drenthe
                                </option>
                                <option value="Overijssel">
                                    Overijssel
                                </option>
                                <option value="Noord-Holland">
                                    Noord-Holland
                                </option>
                                <option value="Flevoland">
                                    Flevoland
                                </option>
                                <option value="Gelderland">
                                    Gelderland
                                </option>
                                <option value="Utrecht">
                                    Utrecht
                                </option>
                                <option value="Zuid-Holland">
                                    Zuid-Holland
                                </option>
                                <option value="Noord-Brabant">
                                    Noord-Brabant
                                </option>
                                <option value="Zeeland">
                                    Zeeland
                                </option>
                                <option value="Limburg">
                                    Limburg
                                </option>
                            </select>
                            <button class="search-request" id="provincie-search" onclick="vergelijkProvinciaal()"><i class="fas fa-search"></i>
                        </div>
                    </div>
                    <div class="card card-verbruik">
                        <div class="card-header verbruiksmeter-header-intranet">
                            <h2 id="searched_provincie">>>Provincie<<</h2>
                        </div>
                        <div class="card-body vebruiksmeter-cardbody-intranet" id="provincie-data">
                            <div class="card energie-kaart">
                                <div class="card-header header-stroom">
                                    <h2 style="text-align: center">Stroom</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div id="stroom"></div>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-gas">
                                    <h2>Gas</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div id="gas"></div>
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-eigen">
                                    <h2>Opgewekt</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                    <div id="opgewekt"></div>
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
    $(document).ready(()=>{
       vergelijkLandelijk();
    });


    let gas = 0, energieHv = 0, energieLv = 0, changedGas = 0, teruggaveLv = 0, teruggaveHv = 0, changedHv= 0, changedLv = 0, changedTerugHv= 0, changedterugLv = 0;

    function get_klant() {
        hideError();
        $.ajax({
            url: "/intranet/get_data",
            data: { type:"klant",klantnummer:$("input#mKlantnummer").val()},
            dataType: 'json',
            method: 'post',
            success: (response) => {
                if(response.status){
                    $("h2#searched_klant").text(response.result.k_voornaam+" "+response.result.k_achternaam)
                }else{
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").removeClass("alert-success");
                    $("div#error-box").addClass("alert-danger");
                }
            }
        })
        $.ajax({
            url: "/intranet/intranetData",
            data: { type:"klant",klantnummer:$("input#mKlantnummer").val()},
            dataType: 'json',
            method: 'post',
            success: (response) => {
                if(response.status){
                    gas = (response['verbruik'][0][2] - response['verbruik'][5][2]).toFixed(2);
                    energieLv = (response['verbruik'][1][2] - response['verbruik'][6][2]).toFixed(2);
                    energieHv = (response['verbruik'][2][2] - response['verbruik'][7][2]).toFixed(2);
                    teruggaveLv = (response['verbruik'][3][2] - response['verbruik'][8][2]).toFixed(2);
                    teruggaveHv = (response['verbruik'][4][2] - response['verbruik'][9][2]).toFixed(2);
                    let data = $("#klantnummer-data");
                    data.find("#stroom").text("Laag tarief: "+energieLv+" Hoog tarief: "+energieHv)
                    data.find("#gas").text("Gas: "+gas)
                    data.find("#opgewekt").text("Laag tarief: "+teruggaveLv+" Hoog tarief: "+teruggaveHv)
                }else{
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").removeClass("alert-success");
                    $("div#error-box").addClass("alert-danger");
                }
            }
        })
    }

    let landelijkGas, landelijkHv, landelijkLv, landelijkTHv, landelijkTLv = 0;

    function vergelijkLandelijk()
    {
        $.ajax({
            url: "/intranet/intranetData",
            dataType: "json",
            method: "post",
            data: { type: "landelijk" },
            success: function(response)
            {
                if(response.status)
                {
                    landelijkGas = (response['landelijkGasM1'][0] - response['landelijkGasM2'][0]).toFixed(2);
                    landelijkHv = (response['landelijkAvgHvM1'][0] - response['landelijkAvgHvM2'][0]).toFixed(2);
                    landelijkLv = (response['landelijkAvgLvM1'][0] - response['landelijkAvgLvM2'][0]).toFixed(2);
                    landelijkTHv = (response['landelijkTerugHvM1'][0] - response['landelijkTerugHvM2'][0]).toFixed(2);
                    landelijkTLv = (response['landelijkTerugLvM1'][0] - response['landelijkTerugLvM2'][0]).toFixed(2);
                    let data = $("#landelijk-data");
                    data.find("#stroom").text("Laag tarief: "+landelijkLv+" Hoog tarief: "+landelijkHv)
                    data.find("#gas").text("Gas: "+landelijkGas)
                    data.find("#opgewekt").text("Laag tarief: "+landelijkTLv+" Hoog tarief: "+landelijkTHv)
                }else
                {
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").removeClass("alert-success");
                    $("div#error-box").addClass("alert-danger");
                }
            }
        })
    }
    let postcodeGas, postcodeHv, postcodeLv, postcodeTHv, postcodeTLv = 0;
    function get_postcode() {
        hideError();
        let postcode = $("input#mPostcode").val();
        $.ajax({
            url: "/intranet/intranetData",
            data: { type:"postcode",postcode:postcode},
            dataType: 'json',
            method: 'post',
            success: (response) =>{
                if (response.status){
                    console.log(response)
                    postcodeGas = (response['postcodeGasM1'][0] - response['postcodeGasM2'][0]).toFixed(2);
                    postcodeHv = (response['postcodeAvgHvM1'][0] - response['postcodeAvgHvM2'][0]).toFixed(2);
                    postcodeLv = (response['postcodeAvgLvM1'][0] - response['postcodeAvgLvM2'][0]).toFixed(2);
                    postcodeTHv = (response['postcodeTerugHvM1'][0] - response['postcodeTerugHvM2'][0]).toFixed(2);
                    postcodeTLv = (response['postcodeTerugLvM1'][0] - response['postcodeTerugLvM2'][0]).toFixed(2);
                    let data = $("#postcode-data");
                    data.find("#stroom").text("Laag tarief: "+postcodeLv+" Hoog tarief: "+postcodeHv)
                    data.find("#gas").text("Gas: "+postcodeGas)
                    data.find("#opgewekt").text("Laag tarief: "+postcodeTLv+" Hoog tarief: "+postcodeTHv)
                }else{
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").removeClass("alert-success");
                    $("div#error-box").addClass("alert-danger");
                }
                console.log(response);
                $("h2#searched_postcode").text(postcode);
            }
        });
    }
    let provincieGas, provincieHv, provincieLv, provincieTHv, provincieTLv = 0;
    function vergelijkProvinciaal()
    {
        let name = $("#marketing-provincie").val();
        $.ajax({
            url: "/intranet/intranetData",
            dataType: "json",
            method: "post",
            data: { type: "provincie", provincie: name},
            success: function(response){
                if(response.status)
                {
                    $("h2#searched_provincie").text(name);
                    provincieGas = (response['provincieGasM1'][0] - response['provincieGasM2'][0]).toFixed(2);
                    provincieHv = (response['provincieAvgHvM1'][0] - response['provincieAvgHvM2'][0]).toFixed(2);
                    provincieLv = (response['provincieAvgLvM1'][0] - response['provincieAvgLvM2'][0]).toFixed(2);
                    provincieTHv = (response['provincieTerugHvM1'][0] - response['provincieTerugHvM2'][0]).toFixed(2);
                    provincieTLv = (response['provincieTerugLvM1'][0] - response['provincieTerugLvM2'][0]).toFixed(2);
                    let data = $("#provincie-data");
                    data.find("#stroom").text("Laag tarief: "+provincieLv+" Hoog tarief: "+provincieHv)
                    data.find("#gas").text("Gas: "+provincieGas)
                    data.find("#opgewekt").text("Laag tarief: "+provincieTLv+" Hoog tarief: "+provincieTHv)
                }
                else
                {
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
            }
        })
    }
</script>


