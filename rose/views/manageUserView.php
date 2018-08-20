<?php
    include_once('accountUserController.php');
    include_once('models/userModel.php');
    include_once('editHelper.php');

    class ManageUserView{
        private $model;

        public function __construct(){
            $s = file_get_contents('cache/u'.$_SESSION['userid']);
            $this->model = unserialize($s);
        }

        public function getUserModel(){
            return $this->model;
        }

        public function getMode(){
            return $_SESSION["mode"];
        }
    }

    $editView = new ManageUserView();
    $model = $editView->getUserModel();
    $mode = $editView->getMode();
    $text = $mode == "edit" ? "Edytuj" : "Dodaj";
    if($mode == "add"){
        $model = new UserModel("","","","","");
    }

    if($_COOKIE["userHash"] != $_SESSION["userHash"]){
        $_SESSION["returnUrl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        Router::redirect("/?view=LoginView");
    }
?>
<div class="container">
<form name="editUserForm" method="post">
  <table style="width: 400px; margin: 0 auto;">
    <tr><th>Imię i nazwisko:</th><td><input placeholder="Jan Kowalski" name="username" type="text" size="20" autocomplete="off" value="<?php echo $model->getUserName() ?>"/></td></tr>
    <tr><th>Login:</th><td><input name="cn" size="20" type="text" placeholder="(jan.kowalski)" value="<?php echo $model->getCn() ?>"/></td></tr>
    <tr><th>Mail:</th><td><input name="mail" size="20" type="mail" placeholder="jan.kowalski@infinite.pl" value="<?php echo $model->getMail() ?>"/></td></tr>
    <tr><th>Hasło:</th><td><input name="newPassword" size="20" type="password" value="<?php echo $model->getPasswordHash() ?>"/></td></tr>
    <tr><td colspan="2" style="text-align: center;" >
        <button class="actionbutton" name="submitted" type="submit" value=""><?php echo $text; ?></button>
        <button class="actionbutton" name="cancel" formaction="index.php?view=userListView" type="cancel">Anuluj</button></td></tr>
  </table>
</form>
</div>
<div class ="msg">
    <?php
        if(isset($_POST["submitted"])){
            if(!empty($_POST['username']) && !empty($_POST['cn'] && !empty($_POST['cn']) && $_POST['mail'])){
                $newModel = new UserModel($_POST['username'], $_POST['newPassword'], $_POST['cn'], $_POST['cn'], $_POST['mail']);

                if($mode == "edit"){
                    $hashed = EditHelper::hashPassword($_POST['newPassword']);
                    if($model->getPasswordHash() != $hashed){
                        $newModel->setPassHash($hashed);
                    }
                    echo AccountUserController::editUserData($newModel, $model);
                }else{
                    $hashed = EditHelper::hashPassword($_POST['newPassword']);
                    $newModel->setPassHash($hashed);
                    echo AccountUserController::addUser($newModel);
                }
                Router::redirect("/?view=userListView");
            }else{
                echo '<p class="errMsg">'.I100.'</p>';
            }
            
        }
    ?>
</div>