<?php

    // function to login to system
    // Arguments:
    // 1. $user - email e.g example@essex.ac.uk
    // 2. $pass - user password


    function ldapLogin($user, $pass)
    {
        $ldap_server = "isswin205.essex.ac.uk";
        $ldapconn=ldap_connect($ldap_server);
        if (!$ldapconn) {
            $_SESSION['errormessage'] = "Login server connection failure. Please notify Admin.";
            return null;
        } else {
            $ldapbind=ldap_bind($ldapconn, $user, $pass);
            if (!$ldapbind) {
              ldap_close($ldapconn);
              $_SESSION['errormessage'] = "Login failed. Please try again.";

              return null;
            } else {
                return $ldapconn;
            }
        }
    }


    // function to get list of groups
    // Arguments:
    // 1. $ldap - ladp connection object from ldapLogin
    // 2. $accountname - username, first part of email
    
    function ldapGetGroups($ldap, $accountname)
    {
        // LDAP info
        $basedn_users = "ou=essex users,dc=essex,dc=ac,dc=uk";
        $basedn_groups = "ou=essex groups,dc=essex,dc=ac,dc=uk";
                
        $ldapsearch=ldap_search($ldap, $basedn_users, "(samAccountName=$accountname)");
        $info = ldap_get_entries($ldap, $ldapsearch);

        $groups = [];
        $possgroups = ['cesstaff-core-g', 'csee-app-admins-g', 'ces-tech-g', 'ces3dp-acl-m-g', 'csee-techdem-g', 'cesstaff-acad-g', 'ce301-g', 'ces-modules-g'];

        foreach ($possgroups as $group) {
            if (in_array("CN=$group,OU=Essex Groups,DC=essex,DC=ac,DC=uk", $info[0]['memberof'])) {
                $groups[] = $group;
            }
        }

        return $groups;
    }

    function checkValidityGroup($groups, $groupname){

        foreach ($groups as &$personGroups) {
            if ($personGroups == $groupname) {
                return true;
            }
        }

        return false;

    }

    function checkValidityGroups($groups, $groupsCheck = ["ces-tech-g", "cseeoperations-g"]){
        foreach ($groupsCheck as &$validGroup) {
            foreach ($groups as &$personGroups) {
                if ($personGroups == $validGroup) {
                    return true;
                }
            }
        }

        return false;

    }
?>
