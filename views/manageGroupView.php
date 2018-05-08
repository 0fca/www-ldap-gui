<?php
    include_once('groupController.php');
    include_once('models/groupModel.php');
    include_once('messages.php');

    class ManageGroupView{
        private $model;

        public function __construct(){
            $s = file_get_contents($_SESSION['userid']);
            $this->model = unserialize($s);
        }

        public function getGroupModel(){
            return $this->model;
        }

        public function getMode(){
            return $_SESSION["mode"];
        }
    }

    $editView = new ManageGroupView();
    $model = $editView->getGroupModel();
    $mode = $editView->getMode();
    $text = $mode == "edit" ? "Edytuj" : "Dodaj";
    if($mode == "add"){
        $model = new GroupModel("","","");
    }
?>

<div class="container">
<form name="manageGroupForm" method="post">
  <table style="width: 400px; margin: 0 auto;">
    <tr><th>Nazwa grupy:</th><td><input placeholder="NazwaGrupy" name="cn" type="text" size="20" autocomplete="off" value="<?php echo $model->getName() ?>"/></td></tr>
    <tr><th>Opis:</th><td><textarea name="description" rows="4" cols="25"><?php echo $model->getDesc() ?></textarea></td></tr>
    <tr><th>Members:</th><td>Hir byndzie lista.</td></tr>
    <tr><td colspan="2" style="text-align: center;" >
        <button class="standardbutton" name="submitted" type="submit" value=""><?php echo $text; ?></button>
        <button class="standardbutton" name="cancel" formaction="admin.php?view=groupListView" type="cancel">Anuluj</button></td></tr>
  </table>
</form>
</div>
<div class ="msg">
    <?php
        if(isset($_POST["submitted"])){
                if(!empty($_POST['cn']) && !empty($_POST['description'])){
                    $newModel = new GroupModel($_POST['cn'], $_POST['description'],"");
                    if($mode == "edit"){
                        echo GroupController::editGroup($model,$newModel);
                    }else{
                        echo GroupController::addGroup($newModel);
                    }
                    
                }else{
                    echo '<p class="errMsg">'.I100.'</p>';
                }
                echo "<p><a href='admin.php?view=groupListView'>Wróć</a></p>";
        }
    ?>
</div>