<div class="d-flex flex-column justify-content-center main-col">
    <div class="d-flex justify-content-center main-row">
        <div class="container">
            <div class="card width-spacing scrollable">
                <div class="card-header">
                    <h3 class="card-title">Verifieer uw adres</h3>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        Voordat u gebruik kunt maken van deze applicatie dient uw account geverifieerd te worden.
                        Dit zodat wij zeker weten dat u bent wie u zegt dat u bent.
                        Hiermee verleent u ook expliciet toegang aan YouthEnergy om uw energiemeter op regelmatige basis uit te laten lezen.
                    </p>
                    <form>
                        <div class="form-group">
                            <label for=""></label>
                            <input type="number" class="form-control" placeholder="Verificatie code" id="verify-code">
                        </div>
                        <button style="display: block" class="btn btn-greentheme ml-auto mr-auto" type="button" id="verify-account">Verifieer Account</button>
                    </form>
                </div>
                <div class="float-right">
                    Ter demonstratie: <a href="/letter" target="_blank" rel="noreferrer noopener">Brief</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("button#verify-account").click(function (){
       $.ajax({
           url: '/verify',
           method: 'post',
           dataType: 'json',
           data: { verificatieCode: $("input#verify-code").val() },
           success: function (response){
                if(response.status){
                    window.location.href = "/verbruiksmeter";
                }else{
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
           }
       });
    });
</script>