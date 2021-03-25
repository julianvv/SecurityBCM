<div class="row">
    <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h3>Je moeder</h3>
            </div>
            <div class="card-body">
                <p>jemoeder2</p>
            </div>
        </div>
    </div>
    <div class="col-md-9 col-lg-9 col-sm-12 col-xs-12">

    </div>
</div>


<!--<canvas id="myChart" width="400" height="400" style="background: white;"></canvas>-->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
    let kaas;
    $.ajax({
        url: '/getSessionData',
        method: 'post',
        dataType: 'json',
        success: function (response) {
            console.log(response);
            kaas = response;
        }
    });

    $(document).ready(function () {
        let persoonlijkeChart = document.getElementById('eigenVerbruik').getContext('2d');
        let vergelijkChart = document.getElementById('vergelijken').getContext('2d');
        let myChart = new Chart(persoonlijkeChart, {
            type: 'bar',
            data: {
                labels: kaas.labels,
                datasets: [{
                    labels: ['# of Votes', '2e label'],
                    data: kaas.verbruik,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }],
            },
            options: {
                aspectRatio: 1,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        let vergelijken = new Chart(vergelijkChart, {
            type: 'bar',
            data: {
                labels: kaas.labels,
                datasets: [{
                    labels: ['# of Votes', '2e label'],
                    data: kaas.verbruik,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }],
            },
            options: {
                aspectRatio: 1,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    });
</script>