<?php
    include_once('accountUserController.php');
    include_once('userModel.php');
    include_once('editHelper.php');

    class EditView{
        private $model;

        public function __construct(){
            $s = file_get_contents($_SESSION['userid']);
            $this->model = unserialize($s);
        }

        public function getUserModel(){
            return $this->model;
        }
    }
    //this is snippet for parsing URL
    /*$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $parts = parse_url($actual_link);
    parse_str($parts['query'], $query);
    
    $userName = $query['name']; 
    */
    $editView = new EditView();
    $model = $editView->getUserModel();
?>
<!DOCTYPE html>
<html>
    <head>  
        <meta charset="UTF-8"/>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
<body>
<div class="container">
<form name="editUserForm" method="post">
  <table style="width: 400px; margin: 0 auto;">
    <tr><th>Użyszkodnik:</th><td><input placeholder="(imie.nazwisko)" name="username" type="text" size="20" autocomplete="off" value="<?php echo $model->getUserName() ?>"/></td></tr>
    <tr><th>cn:</th><td><input name="cn" size="20" type="text" value="<?php echo $model->getCn() ?>"/></td></tr>
    <tr><th>sn:</th><td><input name="sn" size="20" type="text" value="<?php echo $model->getSn() ?>"/></td></tr>
    <tr><th>Mail:</th><td><input name="mail" size="20" type="mail" value="<?php echo $model->getMail() ?>"/></td></tr>
    <tr><th>Hasło:</th><td><input name="newPassword" size="20" type="password" value="<?php echo $model->getPasswordHash() ?>"/></td></tr>
    <tr><td colspan="2" style="text-align: center;" >
        <button class="standardbutton" name="submitted" type="submit" value="">Edytuj</button>
        <button class="standardbutton" name="cancel" type="cancel">Anuluj</button></td></tr>
  </table>
</form>
</div>
<div class ="msg">
    <?php
        if(isset($_POST["submitted"])){
            //echo var_dump(get_class_methods('EditUserController'));
            $hashed = "{SHA}".EditHelper::hashPassword($_POST['newPassword']);
            $model->setPassHash($hashed);
            //echo var_dump($hashed);
            AccountUserController::editUserData($model);
        }
    ?>
</div>
</body>
</html>