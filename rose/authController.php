<?php 
    include_once('messages.php');
    include_once('constants.php');
    include_once('models/userModel.php');

    final class AuthController{
        static $message = "";
        static $ldap_connection;

        static public function authorize($ldap_password, $ldap_username){
            $ldaprdn  = "cn=$ldap_username,".dc;
            self::$ldap_connection = ldap_connect(serv_name);
            ldap_set_option(self::$ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);
            $bind_result = ldap_bind(self::$ldap_connection, $ldaprdn, $ldap_password);

            $result = array();
            $model = NULL;

            $result["result"] = $bind_result;
            $result["message"] = $bind_result ? L0 : L100 ;
            return $result;
        }
    }
?>