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
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="card card-manager">
                        <div class="card-header verbruiksmeter-header-intranet">
                            <h2>Medewerker Aanpassen of Toevoegen</h2>
                        </div>
                        <div class="card-body vebruiksmeter-cardbody-intranet">
                            <div class="card-header verbruiksmeter-header-intranet">
                                <h4>Medewerker Toevoegen</h4>
                            </div>
                            <br>
                            <p>Voornaam: <input type="text" class="med-voornaam"></p>
                            <p>Achternaam: <input type="text" class="med-achternaam"></p>
                            <p>Rol: <select class="rollen-lijst" name="rollenlijst" id="rollenlijst">
                                    <option value="helpdeskmedewerker">
                                        Helpdeskmedewerker
                                    </option>
                                </select>
                            </p>
                            <button class="manager-intranet-button" id="eToevoegen">Toevoegen</button>
                            <div class="card-header verbruiksmeter-header-intranet">
                                <h4>Medewerker Aanpassen</h4>
                            </div>
                            <br>
                            <p>Voornaam: <input type="text" class="med-voornaam"></p>
                            <p>Achternaam: <input type="text" class="med-achternaam"></p>
                            <p>Rol: <select class="rollen-lijst" name="rollenlijst" id="rollenlijst">
                                    <option value="helpdeskmedewerker">
                                        Helpdeskmedewerker
                                    </option>
                                </select>
                            </p>
                            <button class="manager-intranet-button" id="eAanpassen">Aanpassen</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


