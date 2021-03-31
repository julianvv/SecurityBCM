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
                            <h2>Klant Individueel</h2>
                            <input id="mKlantnummer" placeholder="1234567891011" name="klantnummer">
                            <button class="search-request" onclick="get_klant()"><i class="fas fa-search"></i>
                        </div>
                    </div>
                    <div class="card card-verbruik">
                        <div class="card-header verbruiksmeter-header-intranet">
                            <h2 id="searched_klant">>>Klantnaam<<</h2>
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

</script>


