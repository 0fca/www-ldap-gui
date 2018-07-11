<?php
    include_once('constants.php');
    include_once('messages.php');
    include_once('userListController.php');
    include_once('editHelper.php');

    final class AccountUserController{
        static $con = NULL;
        const ldaprdn  = "cn=admin,".dc;

        private function __construct(){}
        
        //gets UserModel object by CN parameter
        static public function getUserByCn($cn){
            self::prepareConnection();
            $users = UserListController::listUsers();
            foreach($users as $user){
                if($user->getCn() == $cn){
                    return $user;
                }
            }
            self::closeConnection();
        }
        
        //it should get dn, but there is some sort of shitty thing with it, left just to next release.
        static public function getUserDn($cn){
            self::prepareConnection();
            $users = UserListController::listUsers();
            foreach($users as $user){
                //echo var_dump($user);
                $ucn = $user->getCn();
                if($ucn == $cn){
                    self::closeConnection();
                    return $ucn.",".dn;
                }
            }
        }
        
        //Adds user to the LDAP server.
        static public function addUser($model){
            $message = "Coś się zjebało w trakcie łączenia.";
            self::prepareConnection();
            $info = array();
            $info["cn"] = $model->getCn();
            $info["sn"] = $model->getSn();
            $info["givenname"] = $model->getUserName();
            $info["objectclass"] = array("organizationalPerson","person","inetOrgPerson","top");
            $info['userpassword'] = $model->getPasswordHash();
            $info['mail'] = $model->getMail();

            $res = ldap_add(self::$con, "sn=".$model->getSn().",".dn, $info);
            self::closeConnection();
            $message = $res ? A0 : '<p class="errMsg">'.A200.'</p>';
            return $message;
        }

        //Deletes user from the server
        static public function deleteUser($deleteModel){
            $message = "Coś się zjebało w trakcie łączenia.";
            self::prepareConnection();
            $dn = "sn=".$deleteModel->getSn().",".dn;
            $res = ldap_delete(self::$con, $dn);
            self::closeConnection();
            $message = $res ? D0 : '<p class="errMsg">'.D200.'</p>';
            return $message;
        }

        //Edits data of the user.
        static public function editUserData($userModel, $oldModel){
            self::prepareConnection();
            $message = "Coś totalnie zjebało sprawę.";
            $attributes = array();
            
            $attributes["objectclass"] = array("organizationalPerson","person","inetOrgPerson","top");
            $attributes['cn'] = $userModel->getCn();
            $attributes['sn'] = $userModel->getSn();
            $attributes['givenname'] = $userModel->getUserName();
            $attributes['userpassword'] = $userModel->getPasswordHash();
            $attributes['mail'] = $userModel->getMail();

            $res = ldap_modify(self::$con, "sn=".$oldModel->getSn().",".dn, $attributes);              
            $message = $res ? ED0 : '<p class="errMsg">'.ED200.'</p>';

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

        static public function publicChangePassword($user,$oldPassword,$newPassword,$newPasswordCnf){
            $message = "-1";
            
            $dn = dn;
            
            if ($user == adm_name)
                $ldaprdn  = "sn=$user,".dc;     // ldap rdn or dn
            else{
                $ldaprdn  = "sn=$user,".dn;
            }
              
              //error_reporting(0);
              ldap_connect(serv_name);
              
              $con=ldap_connect(serv_name);
            
              ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3);
            
              $sr = ldap_search($con,$dn,"(sn=*)");
              $records = ldap_get_entries($con, $sr);
            #$message[] = "Pass: " . $oldPassword;
            #$message[] = "nPass: " .$newPassword;
            
              // echo "<pre>";print_r($records);
              /* error if found more than one user */
             /*
             if ($records["count"] != "1") {
                $message[] = "Brugere fundet:" . $records["count"];
                $message[] = "Error E100 - Wrong user.";
                return false; 
              }else {
                $message[] = "Found user <b>".$records[0]["cn"][0]."</b>.";
              }
            */
            
              if (ldap_bind($con, $ldaprdn, $oldPassword) === false) {
                $message = E104;
                return $message;
              }
              
              if ($newPassword != $newPasswordCnf) {
                $message = E101;
                return $message;
              }
              if (strlen($newPassword) < 5 ) {
                $message = E102;
                return $message;
              }
            
              if (!preg_match("/[0-9]/",$newPassword)) {
                $message = E103a;
                return $message;
              }
              if (!preg_match("/[a-zA-Z]/",$newPassword)) {
                $message = E103b;
                return $message;
              }

              /* change the password finally */
              $entry = array();
              $entry["userPassword"] = EditHelper::hashPassword($newPassword);
              //echo var_dump('Hsh: '.$entry["userPassword"]);
            
              if (ldap_modify($con,$ldaprdn,$entry) === false){
                $message = E100;
                return $message;
              }else { 
                $message = E0;
                return $message;
                //mail($records[0]["mail"][0],"Password change notice : ".$user,"Your password has just been changed."); 
              } 
        } 
    }
?>