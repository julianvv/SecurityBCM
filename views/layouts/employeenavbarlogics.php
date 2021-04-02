<?php

//TODO: Navbars voor verschillende rollen toevoegen.
switch (\core\Application::$app->session->get('employee_group')){
    case "beheerder":
        $nav_buttons = "<li class='nav-item mr-2'>
                           <a class='btn btn-greentheme' href='/intranet'>Home</a>
                        </li>
                        <li class='nav-item mr-2'>
                            <a class='btn btn-greentheme' href='/intranet/account'>Account</a>
                        </li>
                        <li class='nav-item mr-2'>
                            <a class='btn btn-greentheme' href='/intranet/rollen'>Rollen</a>
                        </li>
                        <li class='right-button'>
                            <button class='btn btn-greentheme' type=\"button\" onclick=\"logout()\">Uitloggen</button>
                        </li>";
        $nav_icons = "<li class='nav-item mr-2'>
                           <i class='fas fa-home' onclick=\"window.location.href = '/intranet'\">Home</i>
                      </li>
                <li class='nav-item mr-2'>
                    <i class=\"fas fa-users fa-2x clickable\" onclick=\"window.location.href = '/intranet/account'\"></i>
                </li>
                <li class='right-button'>
                    <i class=\"fas fa-sign-out-alt fa-2x clickable\" onclick=\"logout()\"></i>
                </li>";
        break;
    default:
        $nav_buttons = "<li class='nav-item mr-2'>
                           <a class='btn btn-greentheme' href='/intranet'>Home</a>
                        </li>
                 <li class='nav-item mr-2'>
                    <a class='btn btn-greentheme' href='/intranet/account'>Account</a>
                </li>
                <li class='right-button'>
                    <button class='btn btn-greentheme' type=\"button\" onclick=\"logout()\">Uitloggen</button>
                </li>";
        $nav_icons = "<li class='nav-item mr-2'>
                           <i class='fas fa-home' onclick=\"window.location.href = '/intranet'\">Home</i>
                      </li>
                <li class='nav-item mr-2'>
                    <i class=\"fas fa-users fa-2x clickable\" onclick=\"window.location.href = '/intranet/account'\"></i>
                </li>
                <li class='right-button'>
                    <i class=\"fas fa-sign-out-alt fa-2x clickable\" onclick=\"logout()\"></i>
                </li>";
        break;
}

