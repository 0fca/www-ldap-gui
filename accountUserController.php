<?php
    include_once('constants.php');

    final class AccountUserController{
        static $con = NULL;
        const ldaprdn  = "cn=admin,".dc;

        private function __counstruct(){}

        static public function addUser($model){
            $message = "Coś się zjebało w trakcie łączenia.";
            self::prepareConnection();
            $info = array();
            $info["cn"] = $model->getCn();
            $info["sn"] = $model->getSn();
            $info["objectclass"] = "organizationalPerson\n
                                    person\n
                                    inetOrgPerson\n
                                    top";
            $info['userpassword'] = EditHelper::hashPassword($model->getPasswordHash());
            $info['mail'] = $model->getMail();

            $message = ldap_add(self::$con, $model->getCn().",".dn, $info);

            self::closeConnection();
        }

        static public function deleteUser(){
            //TODO: Implement deleteing user.
        }
        //Well, sth is pretty fucked up with hashing algorithms, cant sort out what?
        static public function editUserData($userModel){
            self::prepareConnection();
            $message = "Coś totalnie zjebało sprawę.";
            $attributes = array('displayName' => $userModel->getUserName(),
                                'cn' => $userModel->getCn(),
                                'sn' => $userModel->getSn().dn,
                                'userPassword' => $userModel->getPasswordHash(),
                                'mail' => $userModel->getMail(),);

            $sr = ldap_search(self::$con, dn, 'sn=*');
            $entries= ldap_get_entries(self::$con, $sr);
            for($i=0; $i<$entries['count']; $i++) { 
                //echo var_dump($entries[$i]['dn'])."\n";
                ldap_modify(self::$con, $entries[$i]['dn'], $attributes);
            }                    
            echo $message;

            self::closeConnection();
        }

        static private function prepareConnection(){
            if(self::$con === NULL){
                ldap_connect(serv_name);
                self::$con=ldap_connect(serv_name);
                ldap_set_option(self::$con, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_bind(self::$con, self::ldaprdn, adm_pass);
            }
        }

        static private function closeConnection(){
            if($con !== NULL){
                ldap_unbind($con);
                $con = NULL;
            }
        }
    }
?>