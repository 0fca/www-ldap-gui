<?php
    include_once("accountUserController.php");
    include_once('models/userModel.php');

    class DeleteUserView{
        private $model;

        public function __construct(){
            $s = file_get_contents($_SESSION['userid']);
            $this->model = unserialize($s);
        }

        public function getModel(){
            return $this->model;
        }
    }

    $deleteView = new DeleteUserView();
    $deleteModel = $deleteView->getModel();
?>
<form method="post">
 <table>
    <caption><strong><p>Czy na pewno chcesz usunąć użytkownika o loginie <?php echo $deleteModel->getUserName();?>?</p></strong></caption>
    <tr><td colspan="2" style="text-align: center;" >
        <button class="standardbutton" name="submitted" type="submit">Usuń</button>
        <button class="standardbutton" name="cancel" type="cancel" formaction="admin.php?view=userListView">Anuluj</button></td></tr>
</table>
<div class="msg">
    <?php
        if(isset($_POST["submitted"])){
            echo AccountUserController::deleteUser($deleteModel); 
            echo "<p><a href='admin.php?view=userListView'>Wróć</a></p>";
        }
    ?>   
</div>
</form>