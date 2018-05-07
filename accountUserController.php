<?php
    include_once('constants.php');
    include_once('errors.php');

    final class AccountUserController{
        static $con = NULL;
        const ldaprdn  = "cn=admin,".dc;

        private function __counstruct(){}
        
        //Adds user to the LDAP server.
        static public function addUser($model){
            $message = "Coś się zjebało w trakcie łączenia.";
            self::prepareConnection();
            $info = array();
            $info["cn"] = $model->getCn();
            $info["sn"] = $model->getSn();
            $info["displayname"] = $model->getUserName();
            $info["objectclass"] = array("organizationalPerson","person","inetOrgPerson","top");
            $info['userpassword'] = $model->getPasswordHash();
            $info['mail'] = $model->getMail();
            $res = ldap_add(self::$con, "cn=".$model->getCn().",".dn, $info);
            self::closeConnection();
            $message = $res ? A0 : A200;
            return $message;
        }

        static public function deleteUser($deleteModel){
            $message = "Coś się zjebało w trakcie łączenia.";
            self::prepareConnection();
            $dn = "cn=".$deleteModel->getCn().",".dn;
            $res = ldap_delete(self::$con, $dn);
            self::closeConnection();
            $message = $res ? D0 : D200;
            return $message;
        }

        //Well, sth is pretty fucked up with hashing algorithms, cant sort out what?
        //This should edit data of the user.
        static public function editUserData($userModel){
            self::prepareConnection();
            $message = "Coś totalnie zjebało sprawę.";
            $attributes = array();
            
            $attributes["objectclass"] = array("organizationalPerson","person","inetOrgPerson","top");
            $attributes['cn'] = $userModel->getCn();
            $attributes['sn'] = $userModel->getSn();
            $attributes['displayname'] = $userModel->getUserName();
            $attributes['userpassword'] = $userModel->getPasswordHash();
            $attributes['mail'] = $userModel->getMail();

            $res = ldap_modify(self::$con, "cn=".$userModel->getCn().",".dn, $attributes);              
            $message = $res ? ED0 : ED200;

            self::closeConnection();
            return $message;
        }

        //Opens connection to the LDAP server if there isnt no opened already.
        static private function prepareConnection(){
            if(self::$con === NULL){
                ldap_connect(serv_name);
                self::$con=ldap_connect(serv_name);
                ldap_set_option(self::$con, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_bind(self::$con, self::ldaprdn, adm_pass);
            }
        }
        //Closes connection to the LDAP server.
        static private function closeConnection(){
            if(self::$con !== NULL){
                ldap_unbind(self::$con);
                self::$con = NULL;
            }
        }
    }
?>