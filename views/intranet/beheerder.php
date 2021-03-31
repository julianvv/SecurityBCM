<div class="d-flex flex-column justify-content-center main-col scrollable invis-scrollbar">
    <div class="d-flex justify-content-center main-row">
        <div class="card opacity-background opacity-employee">
            <div class="row helpdesk-main-row">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="card card-manager">
                        <div class="card-body" style="background-color: #4792d2">
                            <h3 style="font-weight: bolder">Werknemer:</h3>
                            <p style="font-weight: bolder">{{voornaam}} {{achternaam}} - {{group}}</p>
                        </div>
                    </div>
                    <div class="card card-verbruik">
                        <div class="card-header verbruiksmeter-header-intranet">
                            <h2>Medewerker Verwijderen</h2>
                        </div>
                        <div class="card-body vebruiksmeter-cardbody-intranet">
                            <p>Medewerker Selecteren</p>
                            <select class="" id="medewerkeslijst">
                                <option value="medewerker">
                                    Medewerker
                                </option>
                            </select>
                            <button class="manager-intranet-button" id="eVerwijderen">Verwijderen</button>
                        </div>
                    </div>
                    <div class="card card-verbruik">
                        <div class="card-header verbruiksmeter-header-intranet">
                            <h2>Logs Medewerker</h2>
                        </div>
                        <div class="card-body vebruiksmeter-cardbody-intranet">
                            <p>Medewerker Selecteren</p>
                            <select class="" id="medewerkeslijst">
                                <option value="medewerker">
                                    Medewerker
                                </option>
                            </select>
                            <button class="manager-intranet-button" id="eLogs">Logs Opvragen</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="card card-manager">
                        <table class="table table-striped" id="employee_table">
                            <thead class="table-dark">
                            <tr>
                                <th scope="col">UID</th>
                                <th scope="col">Voornaam</th>
                                <th scope="col">Achternaam</th>
                                <th scope="col">Rol</th>
                                <th scope="col">Wijzigen</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(()=>{
        load_employees()
    })
    function load_employees(){
        hideError();
        $.ajax({
            url:"/intranet/get_employees",
            method:"post",
            data:{type:"employees"},
            dataType:"json",
            success:(response)=>{
                if(response.status){
                    response.result.shift();
                    response.result.forEach((element)=>{
                        $("table#employee_table > tbody").append('<tr>'+
                            `<th scope="row">${element.uid[0]}</th>`+
                                `<td>${element.cn[0]}</td>`+
                                `<td>${element.sn[0]}</td>`+
                                `<td>${element.group}</td>`+
                                '<td><i class="fa fa-pencil pr-1"></i><i class="fas fa-scroll pr-1"></i><i'+
                                    ' class="fas fa-times"></i></td>'+
                            '</tr>')
                    })
                    $("table#employee_table").on("click", "i.fa-pencil", (event)=>{
                        change(event.target)
                    })
                }else{
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
            }
        })
    }

    function change(child){
        let row = $(child).parent().parent()
        let tds = row.find("td");
        console.log(tds)
        tds.each((index, element)=>{
            console.log($(element).text())
        })
        //console.log($(row).parent().parent())

        // let data = $(row).parent().parent().children()
        // data.forEach((element)=>{
        //     console.log(element)
        // })
    }
</script>


