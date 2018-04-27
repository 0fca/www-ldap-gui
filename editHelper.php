<?php
    include_once('constants.php');
    final class EditHelper{
        static public function hashPassword($sequence){
            return base64_encode( pack( "H*", md5( $newPassword ) ) );
            //return hash(hashAlgo1, $sequence);
        }
    }
?>