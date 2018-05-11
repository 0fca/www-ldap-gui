<?php 
     include_once('constants.php');
     include_once('models/userModel.php');
     include_once('accountUserController.php');
     include_once('exceptions.php');
     include_once('messages.php');
    
    class UserListController{
        static $message = I-1;
        static $ldap_connection;

        static public function listUsers(){
            return self::loadUsersFromLDAP();
        }

        static private function loadUsersFromLDAP(){
            $ldap_password = adm_pass;
            $ldap_username = adm_name;
            $ldaprdn  = "cn=$ldap_username,".dc;
            self::$ldap_connection = ldap_connect(serv_name);
 
            if (false === self::$ldap_connection){
                self::$message = "Sth fucked up with the connection.";
                return array();
            }

            self::$message = "";

            // We have to set this option for the version of LDAP we are using.
            try{
                self::setUpOpts();
            }catch(UnsupportedOptionException $e){
                echo "<p class='errMsg'>".$e->getMessage()."</p>";
            }
            $bindRet = ldap_bind(self::$ldap_connection, $ldaprdn, $ldap_password); //Binding to the admin account on server.
         
 
            self::$message .= ($bindRet === false ? "Nie można zalogować się jako administrator serwera.\n" : "Zalogowano jako admin.\n");
        if (true === $bindRet){
            $search_filter = "sn=*";
            $result = ldap_search(self::$ldap_connection, dc, $search_filter);
 
            self::$message .= $result === false ? "Wyszukiwanie użytkowników bazie danych zakończone niepowodzeniem.\n" : "\n";
            $ad_users = array();
            if (false !== $result){
                $entries = ldap_get_entries(self::$ldap_connection, $result);

                for ($x=0; $x<count($entries); $x++){
                    if (!empty($entries[$x]["cn"][0]) &&
                        !empty($entries[$x]["sn"][0]) && !empty($entries[$x]["givenname"][0]) && !empty($entries[$x]["mail"][0])){
                        $user = new UserModel($entries[$x]["givenname"][0],$entries[$x]["userpassword"][0],$entries[$x]["sn"][0],$entries[$x]["cn"][0], $entries[$x]["mail"][0]);
                        $ad_users[$entries[$x]["givenname"][0]] = $user;
                    }
                }
            }
            ldap_unbind(self::$ldap_connection); // Clean up after ourselves.
        }
 
        self::$message = count($ad_users) > 0 ? "Znaleziono ". (count($ad_users) > 1 ? count($ad_users)." użytkowników" : "użytkownika") ." serwera LDAP.\n" : "Nie wyszukano żadnych użyszkodnikóœ.";
        return $ad_users;
        }   

        static public function getMessage(){
            return self::$message;
        }

        static private function setUpOpts(){
            if(!ldap_set_option(self::$ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3)){ 
                throw new UnsupportedOptionException('Couldnt set up LDAP version to v3.');
            }

            if(!ldap_set_option(self::$ldap_connection, LDAP_OPT_REFERRALS, 0)){  
                throw new UnsupportedOptionException('Couldnt set up refferals option.');
            }
        }
    }
?>