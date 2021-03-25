<div class="container scrollable invis-scrollbar">
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12 verbruiksmeter-card">
            <div class="card card-verbruik">
                <div class="card-header verbruiksmeter-header">
                    <h2>Eigen Verbruik</h2>
                </div>
                <div class="card-body vebruiksmeter-cardbody">
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
        <div class="col-lg-4 col-md-12 col-sm-12 verbruiksmeter-card">
            <div class="card card-vergelijken">
                <div class="card-header verbruiksmeter-header">
                    <h2>Vergelijken</h2>
                </div>
                <div class="card-body vebruiksmeter-cardbody">
                    <img class="Locatie-Afbeelding" src="/assets/img/Blank_map_of_the_Netherlands.svg.png">
                    <button class="landelijk-button">Landelijk Vergelijken
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <button class="accordion-provinciaal" onclick="changeIcon(this)">Provinciaal Vergelijken
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <div class="panel">
                        <button class="provincie-button">Friesland</button>
                        <button class="provincie-button">Groningen</button>
                        <button class="provincie-button">Drenthe</button>
                        <button class="provincie-button">Overijssel</button>
                        <button class="provincie-button">Gelderland</button>
                        <button class="provincie-button">Noord-Holland</button>
                        <button class="provincie-button">Zuid-Holland</button>
                        <button class="provincie-button">Utrecht</button>
                        <button class="provincie-button">Noord-Brabant</button>
                        <button class="provincie-button">Zeeland</button>
                        <button class="provincie-button">Limburg</button>
                        <button class="provincie-button">Flevoland</button>
                    </div>
                    <button class="lokaal-button">Lokaal Vergelijken
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12 verbruiksmeter-card">
            <div class="card card-verbruik">
                <div class="card-header verbruiksmeter-header">
                    <h2>Gemiddeld Verbruik</h2>
                </div>
                <div class="card-body vebruiksmeter-cardbody">
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


<!--<canvas id="myChart" width="400" height="400" style="background: white;"></canvas>-->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
    var acc = document.getElementsByClassName("accordion-provinciaal");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        });
    }

    function changeIcon(button){
        $(button).find("i").toggleClass("fa-chevron-right fa-chevron-down");
    }
</script>