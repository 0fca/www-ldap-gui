<?php
    include_once('constants.php');
    final class EditHelper{
        static public function hashPassword($sequence){
            return "{SHA}" . base64_encode(pack("H*", sha1($sequence)));
            //return hash(hashAlgo1, $sequence);
        }
    }
?>