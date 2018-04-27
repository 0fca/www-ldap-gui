<?php 
     include_once('constants.php');
     include_once('userModel.php');
     include_once('accountUserController.php');
    
    class UserListController{
        static $message = "-1";

        static public function listUsers(){
            return self::loadUsersFromLDAP();
        }

        static private function loadUsersFromLDAP(){
            $ldap_password = adm_pass;
            $ldap_username = adm_name;
            $ldaprdn  = "cn=$ldap_username,".dc;
            $ldap_connection = ldap_connect(serv_name);
 
            if (false === $ldap_connection){
                self::$message = "Sth fucked up with the connection.";
                return array();
            }

            self::$message = "";

            // We have to set this option for the version of LDAP we are using.
            ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
            ldap_set_option($ldap_connection, LDAP_OPT_REFERRALS, 0); // We need this for doing an LDAP search.
            $bindRet = ldap_bind($ldap_connection, $ldaprdn, $ldap_password);//Binding to the admin account on server.
         
 
            self::$message .= ($bindRet === false ? "Nie można zalogować się jako administrator serwera.\n" : "Zalogowano jako admin.\n");
        if (true === $bindRet){
            $search_filter = "sn=*";
            $result = ldap_search($ldap_connection, dc, $search_filter);
 
            self::$message .= $result === false ? "Wyszukiwanie użytkowników bazie danych zakończone niepowodzeniem.\n" : "\n";
 
            if (false !== $result){
                $entries = ldap_get_entries($ldap_connection, $result);

                for ($x=0; $x<count($entries); $x++){
                    if (!empty($entries[$x]["cn"][0]) &&
                        !empty($entries[$x]["sn"][0]) && !empty($entries[$x]["displayname"][0]) && !empty($entries[$x]["mail"][0])){
                        $user = new UserModel($entries[$x]["displayname"][0],$entries[$x]["userpassword"][0],$entries[$x]["sn"][0],$entries[$x]["cn"][0], $entries[$x]["mail"][0]);
                        $ad_users[$entries[$x]["displayname"][0]] = $user;
                    }
                }
            }
            ldap_unbind($ldap_connection); // Clean up after ourselves.
        }
 
            self::$message .= count($ad_users) > 0 ? "Znaleziono ". count($ad_users) ." użytkowników serwera LDAP.\n" : "Nie wyszukano żadnych użyszkodników.";
            return $ad_users;
        }   

        static public function getMessage(){
            return self::$message;
        }
    }
?>