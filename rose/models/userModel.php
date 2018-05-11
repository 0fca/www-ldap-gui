<?php
    class UserModel implements Serializable{
       private $userName = "";
       private $passwordHash = "";
       private $sn = "";
       private $cn = "";
       private $mail = "";

        public function __construct($username, $passhash, $sn, $cn, $mail){
                $this->userName = $username;
                $this->passwordHash = $passhash;
                $this->sn = $sn;
                $this->cn = $cn;
                $this->mail = $mail;
        }


        public function getUserName(){
            return $this->userName;
        }

        public function getPasswordHash(){
            return $this->passwordHash;
        }
        
        public function getSn(){
            return $this->sn;
        }

        public function getCn(){
            return $this->cn;
        }

        public function getMail(){
            return $this->mail;
        }

        public function setPassHash($hashSeq){
            $this->passwordHash = $hashSeq;
        }

       public function serialize() {
            return serialize([
                $this->userName,
                $this->passwordHash,
                $this->sn,
                $this->cn,
                $this->mail,
            ]);
       }

        public function unserialize($data) {
            list(
                $this->userName,
                $this->passwordHash,
                $this->sn,
                $this->cn,
                $this->mail,
            ) = unserialize($data);
        }
    }
?>