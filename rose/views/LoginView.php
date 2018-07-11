<?php 
    include_once('constants.php');
    include_once('authController.php');
    include_once('models/userModel.php');
    include_once('messages.php');   
    include_once('router.php');
    include_once('hashGenerator.php');

    $systemUser = $_COOKIE['userHash'];
    if($systemUser !== NULL){
      $systemUser = NULL;
      setcookie('userHash', NULL);
      Router::redirect('/rose');
    }
?>
<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8"/>
    <title>Panel administratora</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet"> 
    <link rel="icon" href="data/favicon-sml-blu.png">
    <script src="js/script.js"></script>
</head>
<body>    
<div class="container">
    <form name="authUserForm" method="post">
        
    <table>
    <caption>Logowanie do sekcji administracyjnej</caption>
    <tr><th>Login:</th><td><input name="username" type="text" size="20" autocomplete="off" required/></td></tr>
    <tr><th>Hasło:</th><td><input name="password" size="20" type="password" required/></td></tr>
    <tr>
      <td colspan="2" style="text-align: center;" >
        <button class="actionbutton" name="submitted" type="submit" >OK</button>
      </td>
    </tr>
  </table>
</form>
          <div class="infoMsg">
          <?php
            if (isset($_POST["submitted"])) {
              $result = AuthController::authorize($_POST['password'], $_POST['username']);
              
              if($result['result']){
                $cookie_xpire = cookie_xpire;
                $userHash = HashGenerator::generateHash(16);
                $_SESSION["userHash"] = $userHash;

                if($cookie_xpire !== NULL){
                  setcookie("userHash", $userHash , time()+$cookie_xpire);
                }else{
                  setcookie("userHash", $userHash);
                }
                
                Router::redirect("/rose?view=userListView");
              }else{
                echo "<p style='color: red;'>".$result["message"]."</p>"; 
              }
            } 
          ?>
          </div>
</div>
</body>
</html>