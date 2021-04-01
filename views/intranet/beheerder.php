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
                            <h2>Medewerker toevoegen</h2>
                        </div>
                        <div class="card-body vebruiksmeter-cardbody-intranet">
                            <button class="manager-intranet-button" onclick="openCreateModal()">Medewerker aanmaken</button>
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

<!--change modal-->
<div class="modal fade" id="changeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Account aanpassen</h5>
                <button type="button" class="btn btn-close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                <form id="changeForm">
                    <input id="oldUID" name="oldUID" type="text" hidden>
                    <input id="oldGroup" name="oldGroup" type="text" hidden>
                    <div class="form-group">
                        <label for="changeUID">UID</label>
                        <input id="changeUID" class="form-control" type="text" name="uid">
                    </div>
                    <div class="form-group">
                        <label for="changeCN">Voornaam</label>
                        <input id="changeCN" class="form-control" type="text" name="cn">
                    </div>
                    <div class="form-group">
                        <label for="changeSN">Achternaam</label>
                        <input id="changeSN" class="form-control" type="text" name="sn">
                    </div>
                    <div class="form-group">
                        <label for="changeGroup">Groep</label>
                        <select class="form-control changeGroup" name="group">

                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="change()">Opslaan</button>
            </div>
        </div>
    </div>
</div>

<!--create modal-->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Account aanmaken</h5>
                <button type="button" class="btn btn-close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                <form id="createForm">
                    <div class="form-group">
                        <label for="uid">UID</label>
                        <input id="uid" class="form-control" type="text" name="uid">
                    </div>
                    <div class="form-group">
                        <label for="cn">Voornaam</label>
                        <input id="cn" class="form-control" type="text" name="cn">
                    </div>
                    <div class="form-group">
                        <label for="sn">Achternaam</label>
                        <input id="sn" class="form-control" type="text" name="sn">
                    </div>
                    <div class="form-group">
                        <label for="password">Wachtwoord</label>
                        <input id="password" class="form-control" type="password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="passwordConfirm">Herhaal wachtwoord</label>
                        <input id="passwordConfirm" class="form-control" type="password" name="passwordConfirm">
                    </div>
                    <div class="form-group">
                        <label for="changeGroup">Groep</label>
                        <select class="form-control changeGroup" name="group">

                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="create()">Opslaan</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(()=>{
        load_employees()
        load_groups()
    })

    function load_groups(){
        $.ajax({
            url:"/intranet/get_groups",
            method:"post",
            dataType:"json",
            success:(response)=>{
                if(response.status){
                    response.result.shift();
                    response.result.forEach((element)=>{
                        $("select.changeGroup").append(`<option value="${element}">${element}</option>`)
                    })
                }else{
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
            }
        })
    }

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
                                '<td><i class="fa fa-pencil pr-1 clickable-employee"></i><i class="fas fa-scroll pr-1 clickable-employee"></i><i'+
                                    ' class="fas fa-times clickable-employee"></i></td>'+
                            '</tr>')
                    })
                    //$.off("click", "i.clickable-employee")
                    let clickables = $(".clickable-employee")
                    for(let clickable of clickables){
                        $(clickable).off("click")
                    }
                    //console.log($(".clickable-employee"))
                    // let elements = document.getElementsByClassName(".clickable-employee")
                    // for(let element of elements){
                    //     element.removeEventListener("click")
                    // }

                    $("table#employee_table").on("click", "i.fa-pencil", (event)=>{
                        loadChangeModal($(event.target).parent().parent());
                    })
                    $("table#employee_table").on("click", "i.fa-times",(event)=>{
                        delete_employee(event.target)
                    })

                }else{
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
            }
        })
    }



    function openCreateModal(){
        $("div#createModal").modal('toggle');
    }

    function loadChangeModal(row){
        //Load Data
        let uid, sn, cn, group;
        uid = row.find("th:eq(0)").text();
        cn = row.find("td:eq(0)").text();
        sn = row.find("td:eq(1)").text();
        group = row.find("td:eq(2)").text();
        $("input#oldUID").val(uid);
        $("input#changeUID").val(uid);
        $("input#changeCN").val(cn);
        $("input#changeSN").val(sn);
        $("input#changeGroup").val(group);
        $("input#oldGroup").val(group);

        $("div#changeModal").modal('toggle');

    }

    function clearTable(){
        //console.log($("table#employee_table > tbody"))
        $("table#employee_table > tbody").empty();
    }

    function reloadEmployees(){
        clearTable()
        console.log($("tbody"))
        load_employees()
    }

    //emploYEET
    function delete_employee(child){
        hideError()
        let uid = $(child).parent().parent().find("th").text();
        $.ajax({
            url:"/intranet/delete_employee",
            method: "post",
            data:{uid:uid},
            dataType: "json",
            success: (response)=>{
                if(response.status){
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-success");
                    reloadEmployees()
                }else{
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                    reloadEmployees()
                }

            }

        })

    }

    function change(){
        $.ajax({
            url:"/intranet/change_employee",
            method: "post",
            data: $("form#changeForm").serialize(),
            dataType: "json",
            success: (response)=>{
                if(response.status){
                    $("#changeModal").modal("toggle")
                }else{
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
            }
        })
    }

    function create(){
        $.ajax({
            url:"/intranet/create_employee",
            method: "post",
            data: $("form#createForm").serialize(),
            dataType: "json",
            success: (response)=>{
                if(response.status){
                    $("#createModal").modal("toggle")
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-success");
                }else{
                    $("div#error-box > p").text(response.message);
                    $("div#error-box").css("display", 'block');
                    $("div#error-box").addClass("alert-danger");
                }
            }
        })
    }
</script>


