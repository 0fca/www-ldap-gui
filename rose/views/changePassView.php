<?php
  include_once('constants.php');
  include_once('accountUserController.php');

?>
<!DOCTYPE html>
<html>
        <head>
            <meta charset="UTF-8"/>
            <link rel="stylesheet" type="text/css" href="style.css">
            <link rel="icon" href="data/favicon-sml-blu.png">
        </head>
        <body>
<div class="container">
<form name="passwordChange" method="post">
  <table>
    <caption>Formularz zmiany hasła usługi LDAP </caption>
    <tr><th>Login:</th><td><input name="username" placeholder="jan.kowalski" type="text" size="20" autocomplete="off" /></td></tr>
    <tr><th>Aktualne hasło:</th><td><input name="oldPassword" size="20" type="password" /></td></tr>
    <tr><th>Nowe hasło:</th><td><input name="newPassword1" size="20" type="password" /></td></tr>
    <tr><th>Nowe hasło (weryfikacja):</th><td><input name="newPassword2" size="20" type="password" /></td></tr>
    <tr>
      <td colspan="2" style="text-align: center;" >
        <button class="actionbutton" name="submitted" type="submit" >OK</button>
      </td>
    </tr>
  </table>
</form>
          <div class="msg">
          <?php
            if (isset($_POST["submitted"])) {
              echo "Użyszkodnik: " .$_POST['username']."\n";
              $message = AccountUserController::publicChangePassword($_POST['username'],$_POST['oldPassword'],$_POST['newPassword1'],$_POST['newPassword2']);
              echo "<p style='color: red;'>$message</p>"; 
            } 
          ?>
          </div>
          <p><a href="rose/data/psi.pdf" target="_blank">Konfiguracja drugiego konta PSI</a><br/><p>
<!--<a href="/data/logins.pdf" target="okno">Lista loginów</a><br/>-->
        </body>
</html>