<?php
  include_once('constants.php');
?>
<div class="container">
<form name="passwordChange" method="post">
  <table>
    <caption>Formularz zmiany hasła usługi LDAP </caption>
    <tr><th>Imię i nazwisko:</th><td><input name="username" placeholder="Jan Kowalski" type="text" size="20" autocomplete="off" /></td></tr>
    <tr><th>Aktualne hasło:</th><td><input name="oldPassword" size="20" type="password" /></td></tr>
    <tr><th>Nowe hasło:</th><td><input name="newPassword1" size="20" type="password" /></td></tr>
    <tr><th>Nowe hasło (weryfikacja):</th><td><input name="newPassword2" size="20" type="password" /></td></tr>
    <tr><td colspan="2" style="text-align: center;" >
        <input name="submitted" type="submit" value="OK"/></td></tr>
  </table>
</form>
<!DOCTYPE html>
<html>
        <head>
            <meta charset="UTF-8"/>
            <link rel="stylesheet" type="text/css" href="style.css">
        </head>
        <body>
          <div class="msg">
          <?php
            if (isset($_POST["submitted"])) {
              changePassword($_POST['username'],$_POST['oldPassword'],$_POST['newPassword1'],$_POST['newPassword2']);
              foreach ( $message as $one ) { 
                  echo "<p style='color: red;'>$one</p>"; 
              }
            } 
          ?>
          </div>
          <p><a href="/data/psi.pdf" target="_blank">Konfiguracja drugiego konta PSI</a><br/><p>
<!--<a href="/data/logins.pdf" target="okno">Lista loginów</a><br/>-->
        </body>
</html>