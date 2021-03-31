<div class="d-flex flex-column justify-content-center main-col">
    <div class="d-flex justify-content-center main-row">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Gaat u akkoord?</h3>
            </div>
            <div class="card-body width-spacing">
                <p class="card-body">U bent nog niet akkoord gegaan met onze algemene voorwaarden. Zolang u niet akkoord gaat is het niet mogelijk om deze applicatie te gebruiken</p>
                <div class="custom-control custom-checkbox pb-3"  style="text-align: center">
                    <input type="checkbox" class="custom-control-input" id="privacy-statement" name="privacy-statement">
                    <label for="privacy-statement" class="custom-control-label">Ik ga akkoord met de <a href="/voorwaarden">voorwaarden</a>.</label>
                </div>
                <button style="display: block" class="btn btn-greentheme ml-auto mr-auto" type="button" onclick="akkoord()">Akkoord</button>
            </div>
        </div>
    </div>
</div>

<script>
    function akkoord(){
        let permission = $("input#privacy-statement").is(":checked") ? 1 : 0;
        $.ajax({
           method: 'post',
           url: '/akkoord',
           data: { privacy_statement: permission },
           dataType: 'json',
           success: function (response){
                if (response.status){
                    window.location.href = '/account';
                }else{
                    $("div#error-box > p").text(response.error);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
           }
        });
    }
</script>