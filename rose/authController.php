<?php 
    include_once('messages.php');
    include_once('constants.php');
    include_once('models/userModel.php');

    final class AuthController{
        static $message = "";
        static $ldap_connection;

        static public function authorize($ldap_password, $ldap_username){
            $ldaprdn  = "sn=$ldap_username,".dn;
            self::$ldap_connection = ldap_connect(serv_name);
            ldap_set_option(self::$ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);
            $bind_result = ldap_bind(self::$ldap_connection, $ldaprdn, $ldap_password);

            $result = array();
            if($bind_result){
                $sr = ldap_search(self::$ldap_connection, $ldaprdn,"(|(sn=$ldap_username))");
                $records = ldap_get_entries(self::$ldap_connection, $sr);

                if($records[0]["title"][0] === "roseAdmin"){
                    $result["result"] = true;
                    $result["message"] = L0;
                }else{
                    $result["result"] = false;
                    $result["message"] = L1;
                }
            }else{
                $result["result"] = $bind_result;
                $result["message"] = L100;
            }
            return $result;
        }
    }
?>