<?php
$message = array();             
include_once('errors.php');
include_once('constants.php');
include_once('editHelper.php');

function changePassword($user,$oldPassword,$newPassword,$newPasswordCnf){

global $message;

$dn = dn;

if ($user == adm_name)
    $ldaprdn  = "cn=$user,".dc;     // ldap rdn or dn
else{
    $ldaprdn  = "sn=$user,".dn;
}
  
#$user = $user . "@615SQN.DK";

  error_reporting(0);
  ldap_connect(serv_name);
  
  $con=ldap_connect(serv_name);

  ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3);


 // $findWhat = array("sAMAccountName", "cn");
 // $findWhere = $dn;
 // $findFilter = "(|(cn=$user*))";

  #bind anon and find user by uid
  $sr = ldap_search($con,$dn,"(sn=*)");
  $records = ldap_get_entries($con, $sr);

  $message[] = "Użyszkodnik: " .$user;
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
    $message[] = E104;
    return false;
  }
  
  if ($newPassword != $newPasswordCnf) {
    $message[] = E101;
    return false;
  }
  if (strlen($newPassword) < 5 ) {
    $message[] = E102;
    return false;
  }

  if (!preg_match("/[0-9]/",$newPassword)) {
    $message[] = E103a;
    return false;
  }
  if (!preg_match("/[a-zA-Z]/",$newPassword)) {
    $message[] = E103b;
    return false;
  }
  
  /* change the password finally */
  $entry = array();
  $entry["userPassword"] = "{MD5}".EditHelper::hashPassword($newPassword);
  echo $entry["userPassword"];

  if (ldap_modify($con,$ldaprdn,$entry) === false){
    $message[] = E200;
    return false;
  }else { 
    $message[] = E0;
    return true;
    //mail($records[0]["mail"][0],"Password change notice : ".$user,"Your password has just been changed."); 
  } 
}  
include('changePassView.php');
?>

