<?php
    include_once('accountUserController.php');
    include_once('models/userModel.php');
    include_once('editHelper.php');

    class ManageUserView{
        private $model;

        public function __construct(){
            $s = file_get_contents($_SESSION['userid']);
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
?>
<div class="container">
<form name="editUserForm" method="post">
  <table style="width: 400px; margin: 0 auto;">
    <tr><th>Imię i nazwisko:</th><td><input placeholder="Jan Kowalski" name="username" type="text" size="20" autocomplete="off" value="<?php echo $model->getUserName() ?>"/></td></tr>
    <tr><th>Login:</th><td><input name="cn" size="20" type="text" placeholder="(jan.kowalski)" value="<?php echo $model->getCn() ?>"/></td></tr>
    <tr><th>Mail:</th><td><input name="mail" size="20" type="mail" placeholder="jan.kowalski@infinite.pl" value="<?php echo $model->getMail() ?>"/></td></tr>
    <tr><th>Hasło:</th><td><input name="newPassword" size="20" type="password" value="<?php echo $model->getPasswordHash() ?>"/></td></tr>
    <tr><td colspan="2" style="text-align: center;" >
        <button class="standardbutton" name="submitted" type="submit" value=""><?php echo $text; ?></button>
        <button class="standardbutton" name="cancel" formaction="admin.php?view=userListView" type="cancel">Anuluj</button></td></tr>
  </table>
</form>
</div>
<div class ="msg">
    <?php
        if(isset($_POST["submitted"])){
            
            $hashed = EditHelper::hashPassword($_POST['newPassword']);
            if($model->getPasswordHash($hashed) == $_POST['newPassword']){
                $hashed = $_POST['newPassword'];
            }
            $model->setPassHash($hashed);
            if(!empty($_POST['username']) && !empty($_POST['cn'] && !empty($_POST['cn']) && $_POST['mail'])){
                $model = new UserModel($_POST['username'], $hashed, $_POST['cn'], $_POST['cn'], $_POST['mail']);
                if($mode == "edit"){
                    echo AccountUserController::editUserData($model);
                }else{
                    echo AccountUserController::addUser($model);
                }
            }else{
                echo '<p class="errMsg">'.I100.'</p>';
            }
            echo "<p><a href='admin.php?view=userListView'>Wróć</a></p>";
        }
    ?>
</div>