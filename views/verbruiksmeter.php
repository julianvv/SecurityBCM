<div class="container-fluid scrollable invis-scrollbar">
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12 verbruiksmeter-card">
            <div class="card card-verbruik">
                <div class="card-header verbruiksmeter-header">
                    <h2>Eigen Verbruik</h2>
                </div>
                <div class="card-body vebruiksmeter-cardbody">
                    <div class="card energie-kaart">
                        <div class="card-header header-stroom">
                            <h2 style="text-align: center">Stroomverbruik deze maand</h2>
                        </div>
                        <div class="card-body energie-cardbody">
                            <div class="d-flex justify-content-center">
                                <div id="energie-div-laag">
                                </div>
                                <div id="energie-div-hoog">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card energie-kaart">
                        <div class="card-header header-gas ">
                            <h2>Gasverbruik deze maand</h2>
                        </div>
                        <div class="card-body energie-cardbody d-flex justify-content-center">
                            <div id="gas-div">

                            </div>
                        </div>
                    </div>
                    <div class="card energie-kaart">
                        <div class="card-header header-eigen">
                            <h2>Opgewekt deze maand</h2>
                        </div>
                        <div class="card-body energie-cardbody">
                            <div class="d-flex justify-content-center">
                                <div id="teruggave-div-laag">

                                </div>
                                <div id="teruggave-div-hoog">

                                </div>
                            </div>
                        </div>
                    </div>
                    <p style="font-weight: bolder">Laag tarief is stroom verbruikt tussen 23:00 en 07:00</p>
                    <p style="font-weight: bolder">Hoog tarief is stroom verbruikt tussen 07:00 en 23:00</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 verbruiksmeter-card">
            <div class="card card-vergelijken">
                <div class="card-header verbruiksmeter-header">
                    <h2>Vergelijken</h2>
                </div>
                <div class="card-body vebruiksmeter-cardbody">
                    <button class="landelijk-button" onclick="vergelijkLandelijk(this)">Landelijk Vergelijken
                        <i class="fa fa-circle-o-notch fa-spin" hidden></i>
                    </button>

                    <button class="accordion-provinciaal" onclick="changeIcon(this)">Provinciaal Vergelijken
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <div class="panel">
                        <button class="provincie-button" onclick="vergelijkProvinciaal('Friesland', this)">Friesland <i class="fa fa-circle-o-notch fa-spin" hidden></i></button>
                        <button class="provincie-button" onclick="vergelijkProvinciaal('Groningen', this)">Groningen <i class="fa fa-circle-o-notch fa-spin" hidden></i></button>
                        <button class="provincie-button" onclick="vergelijkProvinciaal('Drenthe', this)">Drenthe <i class="fa fa-circle-o-notch fa-spin" hidden></i></button>
                        <button class="provincie-button" onclick="vergelijkProvinciaal('Overijssel', this)">Overijssel <i class="fa fa-circle-o-notch fa-spin" hidden></i></button>
                        <button class="provincie-button" onclick="vergelijkProvinciaal('Gelderland', this)">Gelderland <i class="fa fa-circle-o-notch fa-spin" hidden></i></button>
                        <button class="provincie-button" onclick="vergelijkProvinciaal('Noord-Holland', this)">Noord-Holland <i class="fa fa-circle-o-notch fa-spin" hidden></i></button>
                        <button class="provincie-button" onclick="vergelijkProvinciaal('Zuid-Holland', this)">Zuid-Holland <i class="fa fa-circle-o-notch fa-spin" hidden></i></button>
                        <button class="provincie-button" onclick="vergelijkProvinciaal('Utrecht', this)">Utrecht <i class="fa fa-circle-o-notch fa-spin" hidden></i></button>
                        <button class="provincie-button" onclick="vergelijkProvinciaal('Noord-Brabant', this)">Noord-Brabant <i class="fa fa-circle-o-notch fa-spin" hidden></i></button>
                        <button class="provincie-button" onclick="vergelijkProvinciaal('Zeeland', this)">Zeeland <i class="fa fa-circle-o-notch fa-spin" hidden></i></button>
                        <button class="provincie-button" onclick="vergelijkProvinciaal('Limburg', this)">Limburg <i class="fa fa-circle-o-notch fa-spin" hidden></i></button>
                        <button class="provincie-button" onclick="vergelijkProvinciaal('Flevoland', this)">Flevoland <i class="fa fa-circle-o-notch fa-spin" hidden></i></button>
                    </div>
                    <button class="lokaal-button" onclick="vergelijkPostcode(this)">Lokaal Vergelijken
                        <i class="fa fa-circle-o-notch fa-spin" hidden></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!--<canvas id="myChart" width="400" height="400" style="background: white;"></canvas>-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>



    $('button.accordion-provinciaal').on("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    });


    function changeIcon(button){
        $(button).find("i").toggleClass("fa-chevron-right fa-chevron-down");
    }


    let gas = 0, energieHv = 0, energieLv = 0, changedGas = 0, teruggaveLv = 0, teruggaveHv = 0, changedHv= 0, changedLv = 0, changedTerugHv= 0, changedterugLv = 0;


    $(document).ready(function(){
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

    })

    let chartklantHv, chartklantLv, chartGas, chartteruggaveLv, chartteruggaveHv;

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

                    chartklantHv.clearChart();

                    reloadHv()
                    reloadLv()
                    reloadTerugHv()
                    reloadTerugLv()
                    reloadGas()

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

                    chartklantHv.clearChart();

                    reloadHv()
                    reloadLv()
                    reloadTerugHv()
                    reloadTerugLv()
                    reloadGas()

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
                    chartklantHv.clearChart();

                    reloadHv()
                    reloadLv()

                    reloadTerugHv()
                    reloadTerugLv()
                    reloadGas()

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

    function reloadGas()
    {
        chartgas = drawChart([['Label', 'Value'],['test', gas]],{
            width: 200,
            height: 200,
            redFrom: changedGas + 40, redTo: changedGas + 100,
            yellowFrom: changedGas - 20,  yellowTo: changedGas + 40,
            greenFrom: changedGas - 100, greenTo: changedGas - 20,

            minorTicks: 10,
            max: changedGas + 100,
            min: changedGas - 100,
            majorTicks: [ Math.round(changedGas-100) , Math.round(changedGas + 100)]
        }, 'gas-div')
    }

    function reloadHv()
    {
        chartklantHv = drawChart([['Label', 'Value'],['Hv', energieHv]],{
            width: 200,
            height: 200,
            redFrom: changedHv + 40, redTo: changedHv + 100,
            yellowFrom: changedHv - 20,  yellowTo: changedHv + 40,
            greenFrom: changedHv - 100, greenTo: changedHv - 20,

            minorTicks: 10,
            max: changedHv + 100,
            min: changedHv - 100,
            majorTicks: [ Math.round(changedHv-100) , Math.round(changedHv + 100)]
        }, 'energie-div-hoog')
    }

    function reloadLv()
    {
        chartklantHv = drawChart([['Label', 'Value'],['Lv', energieLv]],{
            width: 200,
            height: 200,
            redFrom: changedLv + 40, redTo: changedLv + 100,
            yellowFrom: changedLv - 20,  yellowTo: changedLv + 40,
            greenFrom: changedLv - 100, greenTo: changedLv - 20,

            minorTicks: 10,
            max: changedLv + 100,
            min: changedLv - 100,
            majorTicks: [ Math.round(changedLv-100) , Math.round(changedLv + 100)]
        }, 'energie-div-laag')
    }

    function reloadTerugHv()
    {
        chartteruggaveHv = drawChart([['Label', 'Value'],['Hv', teruggaveHv]],{
            width: 200,
            height: 200,
            greenFrom: changedTerugHv + 40, greenTo: changedTerugHv + 100,
            yellowFrom: changedTerugHv - 20,  yellowTo: changedTerugHv + 40,
            redFrom: changedTerugHv - 100, redTo: changedTerugHv - 20,

            minorTicks: 10,
            max: changedTerugHv + 100,
            min: changedTerugHv - 100,
            majorTicks: [ Math.round(changedTerugHv-100) , Math.round(changedTerugHv + 100)]
        }, 'teruggave-div-hoog')
    }

    function reloadTerugLv()
    {
        chartteruggaveLv = drawChart([['Label', 'Value'],['Lv', teruggaveLv]],{
            width: 200,
            height: 200,
            greenFrom: changedterugLv + 40, greenTo: changedterugLv + 100,
            yellowFrom: changedterugLv - 20,  yellowTo: changedterugLv + 40,
            redFrom: changedterugLv - 100, redTo: changedterugLv - 20,

            minorTicks: 10,
            max: changedterugLv + 100,
            min: changedterugLv - 100,
            majorTicks: [ Math.round(changedterugLv-100) , Math.round(changedterugLv + 100)]
        }, 'teruggave-div-laag')
    }


    function drawChart(data, options, position)
    {
        let info = google.visualization.arrayToDataTable(data)
        let chart = new google.visualization.Gauge(document.getElementById(position))
        chart.draw(info, options)
        return chart
    }


    google.charts.load('current', {'packages':['gauge']});
    google.charts.setOnLoadCallback(drawInitial);

    function drawInitial() {
        //gasverbruik
        var data = google.visualization.arrayToDataTable([
            ['Label', 'Value'],
            ['Gas', gas]
        ]);

        var options = {
            width: 200,
            height: 200,
            minorTicks: 20,
            max: 1000,
            min: 0,
            majorTicks: ['0 m³', '1000 m³']
        };

        chartGas = new google.visualization.Gauge(document.getElementById('gas-div'));
        chartGas.draw(data, options);

        //energie laagverbruik
        var data = google.visualization.arrayToDataTable([
            ['Label', 'Value'],
            ['lv', energieLv],
        ]);

        var options = {
            width: 200,
            height: 200,
            minorTicks: 10,
            max: 800,
            min: 0,
            majorTicks: ['0kwh', '100kwh']
        };

        chartklantLv = new google.visualization.Gauge(document.getElementById('energie-div-laag'));
        chartklantLv.draw(data, options);

        // energie hoogverbruik
        var data = google.visualization.arrayToDataTable([
            ['Label', 'kwh'],
            ['hv', energieHv]

        ]);

        var options = {
            width: 200,
            height: 200,
            minorTicks: 20,
            max: 1000,
            min: 0,
            majorTicks: ['0', '1000kwh']
        };

        chartklantHv = new google.visualization.Gauge(document.getElementById('energie-div-hoog'));
        chartklantHv.draw(data, options);

        //teruggave laagverbruik
        var data = google.visualization.arrayToDataTable([
            ['terrugavelaagverbruik', 'Value'],
            ['lv', teruggaveLv]

        ]);

        var options = {
            width: 200,
            height: 200,
            minorTicks: 20,
            max: 1000,
            min: 0,
            majorTicks: ['0', '1000']
        };

        chartteruggaveLv = new google.visualization.Gauge(document.getElementById('teruggave-div-laag'));
        chartteruggaveLv.draw(data, options);

        //teruggave hoogverbruik
        var data = google.visualization.arrayToDataTable([['Label', 'Value'],
            ['hv', teruggaveHv]

        ]);

        var options = {
            width: 200,
            height: 200,
            minorTicks: 20,
            max: 1000,
            min: 0,
            majorTicks: ['0', '1000']
        };

        chartteruggaveHv = new google.visualization.Gauge(document.getElementById('teruggave-div-hoog'));
        chartteruggaveHv.draw(data, options);
    }
</script>