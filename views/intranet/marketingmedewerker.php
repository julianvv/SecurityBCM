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
                        <div class="card-body vebruiksmeter-cardbody-intranet">
                            <div class="card energie-kaart">
                                <div class="card-header header-stroom">
                                    <h2 style="text-align: center">Stroom</h2>
                                </div>
                                <div class="card-body energie-cardbody">
<!--                                    energie meter-->
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-gas">
                                    <h2>Gas</h2>
                                </div>
                                <div class="card-body energie-cardbody">
<!--                                    gas meter-->
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-eigen">
                                    <h2>Opgewekt</h2>
                                </div>
                                <div class="card-body energie-cardbody">
<!--                                    teruglevering-->
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
                        <div class="card-body vebruiksmeter-cardbody-intranet">
                            <div class="card energie-kaart">
                                <div class="card-header header-stroom">
                                    <h2 style="text-align: center">Stroom</h2>
                                </div>
                                <div class="card-body energie-cardbody">

                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-gas">
                                    <h2>Gas</h2>
                                </div>
                                <div class="card-body energie-cardbody">

                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-eigen">
                                    <h2>Opgewekt</h2>
                                </div>
                                <div class="card-body energie-cardbody">
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
                            <button class="search-request" id="provincie-search" onclick="get_provincie()"><i class="fas fa-search"></i>
                        </div>
                    </div>
                    <div class="card card-verbruik">
                        <div class="card-header verbruiksmeter-header-intranet">
                            <h2 id="searched_provincie">>>Provincie<<</h2>
                        </div>
                        <div class="card-body vebruiksmeter-cardbody-intranet">
                            <div class="card energie-kaart">
                                <div class="card-header header-stroom">
                                    <h2 style="text-align: center">Stroom</h2>
                                </div>
                                <div class="card-body energie-cardbody">
                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-gas">
                                    <h2>Gas</h2>
                                </div>
                                <div class="card-body energie-cardbody">

                                </div>
                            </div>
                            <div class="card energie-kaart">
                                <div class="card-header header-eigen">
                                    <h2>Opgewekt</h2>
                                </div>
                                <div class="card-body energie-cardbody">
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
                    gas = (response['verbruik'][0][2] - response['verbruik'][5][2])
                    energieLv = (response['verbruik'][1][2] - response['verbruik'][6][2])
                    energieHv = (response['verbruik'][2][2] - response['verbruik'][7][2])
                    teruggaveLv = (response['verbruik'][3][2] - response['verbruik'][8][2])
                    teruggaveHv = (response['verbruik'][4][2] - response['verbruik'][9][2])
                    $("#klantnummer-data").find("#stroom").text("Laag tarief: "+energieLv+"\nHoog tarief: "+energieHv)
                    $("#klantnummer-data").find("#gas").text("Gas: "+gas)
                    $("#klantnummer-data").find("#opgewekt").text("Laag tarief: "+teruggaveLv+"\nHoog tarief: "+teruggaveHv)
                }else{
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
            }
        })
    }

    function get_postcode() {
        hideError();
        $.ajax({
            url: "/intranet/get_data",
            data: { type:"postcode",postcode:$("input#mPostcode").val()},
            dataType: 'json',
            method: 'post',
            success: (response) => {
                if(response.status){
                    $("h2#searched_postcode").text(response.result[0].a_postcode)
                }else{
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
            }
        })
    }

    function get_provincie() {
        hideError();
        $.ajax({
            url: "/intranet/get_data",
            data: { type:"provincie",provincie:$("select#marketing-provincie").val()},
            dataType: 'json',
            method: 'post',
            success: (response) => {
                if(response.status){
                    $("h2#searched_provincie").text(response.result[0].a_provincie)
                }else{
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
            }
        })
    }

    $(document).ready(function(){

    })

    let chartklantHv, chartklantLv, chartGas, chartteruggaveLv, chartteruggaveHv;

    function getgebruikerData()
    {
        $.ajax({
            url: "/verbruiksmeter",
            dataType: "json",
            method: "post",
            data: { type: "klant" },
            success: function(response)
            {
                if(response.status)
                {
                    gas = (response['verbruik'][0][2] - response['verbruik'][5][2])
                    energieLv = (response['verbruik'][1][2] - response['verbruik'][6][2])
                    energieHv = (response['verbruik'][2][2] - response['verbruik'][7][2])
                    teruggaveLv = (response['verbruik'][3][2] - response['verbruik'][8][2])
                    teruggaveHv = (response['verbruik'][4][2] - response['verbruik'][9][2])
                }else
                {
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
            },
            fail: function()
            {
                $(load).attr('hidden', true)
            }
        })
    }

    function vergelijkLandelijk(element)
    {
        let load = $(element).find('i.fa-spin')
        $(load).attr('hidden', false)
        $.ajax({
            url: "/verbruiksmeter",
            dataType: "json",
            method: "post",
            data: { type: "landelijk" },
            success: function(response)
            {
                if(response.status)
                {
                    changedGas = (response['landelijkGasM1'][0] - response['landelijkGasM2'][0])
                    changedHv = (response['landelijkAvgHvM1'][0] - response['landelijkAvgHvM2'][0])
                    changedLv = (response['landelijkAvgLvM1'][0] - response['landelijkAvgLvM2'][0])
                    changedTerugHv = (response['landelijkTerugHvM1'][0] - response['landelijkTerugHvM2'][0])
                    changedterugLv = (response['landelijkTerugLvM1'][0] - response['landelijkTerugLvM2'][0])
                }else
                {
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
                $(load).attr('hidden', true)
            },
            fail: function()
            {
                $(load).attr('hidden', true)
            }
        })
    }

    function vergelijkPostcode(element)
    {
        let load = $(element).find('i.fa-spin')
        $(load).attr('hidden', false)
        $.ajax({
            url: "/verbruiksmeter",
            dataType: "json",
            method: "post",
            data: { type: "postcode" },
            success: function(response)
            {
                if(response.status)
                {
                    changedGas = (response['postcodeGasM1'][0] - response['postcodeGasM2'][0])
                    changedHv = (response['postcodeAvgHvM1'][0] - response['postcodeAvgHvM2'][0])
                    changedLv = (response['postcodeAvgLvM1'][0] - response['postcodeAvgLvM2'][0])
                    changedTerugHv = (response['postcodeTerugHvM1'][0] - response['postcodeTerugHvM2'][0])
                    changedterugLv = (response['postcodeTerugLvM1'][0] - response['postcodeTerugLvM2'][0])
                }else
                {
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
                $(load).attr('hidden', true)
            },
            fail: function()
            {
                $(load).attr('hidden', true)
            }
        })
    }

    function vergelijkProvinciaal(provincieNaam, element)
    {
        let load = $(element).find('i.fa-spin')
        $(load).attr('hidden', false)
        let name = provincieNaam
        $.ajax({
            url: "/verbruiksmeter",
            dataType: "json",
            method: "post",
            data: { type: "provincie", provincie: name},
            success: function(response){
                if(response.status)
                {
                    changedGas = (response['provincieGasM1'][0] - response['provincieGasM2'][0])
                    changedHv = (response['provincieAvgHvM1'][0] - response['provincieAvgHvM2'][0])
                    changedLv = (response['provincieAvgLvM1'][0] - response['provincieAvgLvM2'][0])
                    changedTerugHv = (response['provincieTerugHvM1'][0] - response['provincieTerugHvM2'][0])
                    changedterugLv = (response['provincieTerugLvM1'][0] - response['provincieTerugLvM2'][0])
                }
                else
                {
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
                $(load).attr('hidden', true)
            },
            fail: function()
            {
                $(load).attr('hidden', true)
            }
        })
    }
</script>


