<!-- Verbruiksmeter Pagina (Check eerst of gebruiker al geverifieerd is) -->
<?php
    use \core\Application;
    $app = Application::$app;

    if($app->session->get('userdata')[0]['permission_granted'] === "0"){
        $app->session->setFlash('notification', ['type' => 'alert-danger', 'message' => 'Account niet geverifieerd. Doe dit via de account pagina.']);
        //Show dummy/no data
    }

?>

<div class="card">
    <div class="card-body">
        <div class="card-header">Lol</div>
    </div>
</div>


<canvas id="myChart" width="400" height="400" style="background: white;"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
    let kaas;
    $.ajax({
        url: '/getSessionData',
        method: 'post',
        dataType: 'json',
        success: function (response){
            console.log(response);
            kaas = response;
        }
    });

    $(document).ready(function () {
        let ctx = document.getElementById('myChart').getContext('2d');
        let myChart = new Chart(ctx, {
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
                }]
            },
            options: {
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