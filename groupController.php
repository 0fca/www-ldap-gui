<?php
include_once('constants.php');
include_once('exceptions.php');
include_once('messages.php');
include_once('models/groupModel.php');
include_once('models/userModel.php');

    final class GroupController{
        private static $message;
        private static $ldap_connection;

        static private function prepareConnection(){
            $ldap_password = adm_pass;
            $ldap_username = adm_name;
            $ldaprdn  = "cn=$ldap_username,".dc;
            self::$ldap_connection = ldap_connect(serv_name);
 
            if (false === self::$ldap_connection){
                self::$message = "Sth fucked up with the connection.";
                return false;
            }

            self::$message = "";
            try{
                self::setUpOpts();
            }catch(UnsupportedOptionException $e){
                echo "<p class='errMsg'>".$e->getMessage()."</p>";
            }
            
            $bindRet = ldap_bind(self::$ldap_connection, $ldaprdn, $ldap_password);
         
            self::$message = ($bindRet === false ? "Nie można zalogować się jako administrator serwera.\n" : "Zalogowano jako admin.\n");
            return $bindRet;
        }
        
        static public function listGroups(){
            $bindRet = self::prepareConnection();
            $ad_groups = array();
           

            if (true === $bindRet){
                $search_filter = "ou=*";
                $result = ldap_search(self::$ldap_connection, dc, $search_filter);
                self::$message = $result === false ? "Wyszukiwanie grup w bazie danych zakończone niepowodzeniem.\n" : "\n";

                if (false !== $result){
                    $entries = ldap_get_entries(self::$ldap_connection, $result);
    
                    for ($x=0; $x<count($entries); $x++){
                        if (!empty($entries[$x]["cn"][0]) &&
                            !empty($entries[$x]["description"][0]) && !empty($entries[$x]["member"])){

                            $group = new GroupModel($entries[$x]["cn"][0], $entries[$x]["description"][0], $entries[$x]["member"]);
                            $ad_groups[$entries[$x]["cn"][0]] = $group;
                        }
                    }
                }
            }
            self::closeConnection();
            self::$message = count($ad_groups) > 0 ? "Znaleziono ". (count($ad_groups) > 1 ? count($ad_groups)." grup" : " grupę") ." serwera LDAP.\n" : "Nie wyszukano żadnych grup.";
            return $ad_groups;
        }

        static public function addGroup($model){
            self::prepareConnection();
            $entry = self::prepareEntries($model);
            $retVal = ldap_add(self::$ldap_connection, 'cn='.$model->getName().','.gdn, $entry) ? A100: '<p class="errMsg">'.A400.'</p>';
            self::closeConnection();
            return $retVal;
        }

        static public function editGroup($oldModel, $model){
            self::prepareConnection();
            $entry = self::prepareEntries($model);
            $retVal = ldap_mod_replace(self::$ldap_connection, 'cn='.$oldModel->getName().','.gdn, $entry) ? A101: '<p class="errMsg">'.A400.'</p>';
            self::closeConnection();
            return $retVal;
        }

        static public function deleteGroup($deleteModel){
            self::prepareConnection();
            $dn = "cn=".$deleteModel->getName().",".gdn;
            $res = ldap_delete(self::$ldap_connection, $dn);
            self::closeConnection();
            $message = $res ? D0 : '<p class="errMsg">'.D200.'</p>';
            return $message;
        }

        static private function closeConnection(){
            if(self::$ldap_connection !== NULL){
                ldap_unbind(self::$ldap_connection); 
                self::$ldap_connection = NULL;
            }
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
        static public function prepareEntries($model){
            $entry = array();
            $entry['cn'] = $model->getName();
            $entry['ou'] = $model->getName();
            $entry['member'] = $model->getUserList();
            $entry['objectClass'] = array("groupOfNames", "top");
            $entry['description'] = $model->getDesc();
            return $entry;
        }
    }
?>