<form action="ldap-test" method="post">
    <div class="form-group">
        <label for="ldapUser">Ldap Username</label>
        <input type="text" class="form-control" name="ldapUser" id="ldapUser" aria-describedby="ldapHelp" placeholder="Enter ldap user">
    </div>
    <div class="form-group">
        <label for="email">Ldap email</label>
        <input type="email" class="form-control" name="email" id="email" aria-describedby="ldapHelp" placeholder="email">
    </div>
    <div class="form-group">
        <label for="ldapPass">Ldap password</label>
        <input type="password" class="form-control" name="ldapPass" id="ldapPass" aria-describedby="ldapHelp" placeholder="password">
    </div>
    <button class="btn btn-primary" type="submit">Test</button>
</form>