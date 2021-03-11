<?php


namespace controllers;


use core\Application;

class LdapController
{
    private $ldapConn;
    private $config;

    public function __construct()
    {
        $this->config = Application::$config;
        $this->ldapConn = ldap_connect($this->config["LDAP_URI"]);

        if($this->ldapConn){
            ldap_set_option($this->ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
        }
    }

    public function authenticate($username, $password) : bool
    {
        $username = "cn=".ldap_escape($username, LDAP_ESCAPE_DN);
        $pwd = $password;

        $baseDN = $this->config["LDAP_BASEDN"];

        $data = @ldap_bind($this->ldapConn, $username.$baseDN, $pwd);
        if($data)
        {
            return true;
        }
        return false;
    }


}