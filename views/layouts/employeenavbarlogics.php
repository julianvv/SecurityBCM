<?php
$role = \core\Application::$app->session->get('employee_group');

//TODO: Navbars voor verschillende rollen toevoegen.

switch ($role) {
    case 'beheerder':
        $nav_buttons = "<li class='nav-item mr-2'>
                    <a class='btn btn-greentheme' href='/intranet/accounts'>Accounts</a>
                </li>
                <li class='nav-item'>
                    <a class='btn btn-greentheme' href='/intranet/logs'>Logs</a>
                </li>
                <li class='right-button'>
                    <button class='btn btn-greentheme' type=\"button\" onclick=\"logout()\">Uitloggen</button>
                </li>";
        $nav_icons = "<li class='nav-item mr-2'>
                    <i class=\"fas fa-users fa-2x clickable\" onclick=\"window.location.href = '/intranet/accounts'\"></i>
                </li>
                <li class='nav-item'>
                    <i class=\"fas fa-file-alt fa-2x clickable\" onclick=\"window.location.href = '/intranet/logs'\"></i>
                </li>
                <li class='right-button'>
                    <i class=\"fas fa-sign-out-alt fa-2x clickable\" onclick=\"logout()\"></i>
                </li>";
        break;

    case 'manager':
        $nav_buttons = "<li class='nav-item mr-2'>
                    <a class='btn btn-greentheme' href='/intranet/accounts'>Accounts</a>
                </li>
                <li class='nav-item'>
                    <a class='btn btn-greentheme' href='/intranet/logs'>Logs</a>
                </li>
                <li class='right-button'>
                    <button class='btn btn-greentheme' type=\"button\" onclick=\"logout()\">Uitloggen</button>
                </li>";
        $nav_icons = "<li class='nav-item mr-2'>
                    <i class=\"fas fa-users fa-2x clickable\" onclick=\"window.location.href = '/intranet/accounts'\"></i>
                </li>
                <li class='nav-item'>
                    <i class=\"fas fa-file-alt fa-2x clickable\" onclick=\"window.location.href = '/intranet/logs'\"></i>
                </li>
                <li class='right-button'>
                    <i class=\"fas fa-sign-out-alt fa-2x clickable\" onclick=\"logout()\"></i>
                </li>";
        break;

    case 'klantenservice':
        $nav_buttons = "<li class='nav-item mr-2'>
                    <a class='btn btn-greentheme' href='/intranet/accounts'>Accounts</a>
                </li>
                <li class='nav-item'>
                    <a class='btn btn-greentheme' href='/intranet/logs'>Logs</a>
                </li>
                <li class='right-button'>
                    <button class='btn btn-greentheme' type=\"button\" onclick=\"logout()\">Uitloggen</button>
                </li>";
        $nav_icons = "<li class='nav-item mr-2'>
                    <i class=\"fas fa-users fa-2x clickable\" onclick=\"window.location.href = '/intranet/accounts'\"></i>
                </li>
                <li class='nav-item'>
                    <i class=\"fas fa-file-alt fa-2x clickable\" onclick=\"window.location.href = '/intranet/logs'\"></i>
                </li>
                <li class='right-button'>
                    <i class=\"fas fa-sign-out-alt fa-2x clickable\" onclick=\"logout()\"></i>
                </li>";
        break;

    case 'marketing':
        $nav_buttons = "<li class='nav-item mr-2'>
                    <a class='btn btn-greentheme' href='/intranet/accounts'>Accounts</a>
                </li>
                <li class='nav-item'>
                    <a class='btn btn-greentheme' href='/intranet/logs'>Logs</a>
                </li>
                <li class='right-button'>
                    <button class='btn btn-greentheme' type=\"button\" onclick=\"logout()\">Uitloggen</button>
                </li>";
        $nav_icons = "<li class='nav-item mr-2'>
                    <i class=\"fas fa-users fa-2x clickable\" onclick=\"window.location.href = '/intranet/accounts'\"></i>
                </li>
                <li class='nav-item'>
                    <i class=\"fas fa-file-alt fa-2x clickable\" onclick=\"window.location.href = '/intranet/logs'\"></i>
                </li>
                <li class='right-button'>
                    <i class=\"fas fa-sign-out-alt fa-2x clickable\" onclick=\"logout()\"></i>
                </li>";
        break;

    default:
        $nav_buttons = "<li class='nav-item mr-2'><p>U heeft geen geldige rechten.</p></li>
                <li class='right-button'>
                    <i class=\"fas fa-sign-out-alt fa-2x clickable\" onclick=\"logout()\"></i>
                </li>";
        $nav_icons = "<li class='nav-item mr-2'><p>U heeft geen geldige rechten.</p></li>
                <li class='right-button'>
                    <i class=\"fas fa-sign-out-alt fa-2x clickable\" onclick=\"logout()\"></i>
                </li>";
        break;
}