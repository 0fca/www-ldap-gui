<?php
    include_once('groupController.php');
    include_once('models/groupModel.php');
    include_once('messages.php');
    include_once('userListController.php');
    include_once('accountUserController.php');
    include_once('constants.php');

    class ManageGroupView{
        private $model;

        public function __construct(){
            $s = file_get_contents('cache/g'.$_SESSION['userid']);
            $this->model = unserialize($s);
        }

        public function getGroupModel(){
            return $this->model;
        }

        public function setGroupModel($model){
            $this->model = $model;
        }

        public function getMode(){
            return $_SESSION["mode"];
        }

        public function printUsers(){
            if(!empty($this->model->getUserList())){ 
                $out = "";
                foreach($this->model->getUserList() as $user){
                    $out .= (ldap_explode_dn($user,5)[0]."\n");
                }
                return $out;
            }else{ 
                return "Brak użyszkodników.";
            }
        }
    }

    $editView = new ManageGroupView();
    $model = $editView->getGroupModel();
    $mode = $editView->getMode();
    $text = $mode == "edit" ? "Zastosuj" : "Dodaj";
    if($mode == "add"){
        $model = new GroupModel("","","");
        $editView->setGroupModel($model);
    }

    if($_COOKIE["userHash"] != $_SESSION["userHash"]){
        Router::redirect("/rose?view");
    }
?>

<div class="container">
<form name="manageGroupForm" method="post">
   <table>
  
    <tr><th>Nazwa grupy:</th><td><input placeholder="NazwaGrupy" name="ou"  type="text" size="25" autocomplete="off" value="<?php echo $model->getName() ?>"/></td></tr>
    <tr><th>Opis:</th><td><textarea name="description" rows="4" cols="25"><?php echo $model->getDesc() ?></textarea></td></tr>
        <th>Wybierz użyszkodnika</th>
        <td colspan="2" style="justify-content: flex-start;align-items: flex-start;">
                <input list="users" id="userChoice" name="userChoice" autocomplete="off" size="25"/>
                <span>
                    <p class="clickableParagraph" onclick="OnAddUserActionHandler();">Dodaj</p>
                    <p class='clickableParagraph' onclick='OnDeleteUserActionHandler();'>Usuń</p>
                </span>
        </td>
    </tr>   
    <tr>
        <th>Członkowie:</th>
        <td>
            <textarea id="usersToAdd" name="userstoadd" rows="4" cols="25" readonly><?php echo $editView->printUsers()?></textarea>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;" >
            <button class="actionbutton" name="submitted" type="submit" value=""><?php echo $text; ?></button>
            <button class="actionbutton" name="cancel" formaction="index.php?view=groupListView" type="cancel">Anuluj</button>
        </td>
    </tr>
    </table>
    <datalist id="users">
            <?php
            $users = UserListController::listUsers();

            if($mode == "edit"){
                
                $members = $model->getUserList();
                $resultArray = array();
                $i = 0;
                foreach($users as $user){
                    //echo var_dump($members);
                    if(!in_array('cn='.$user->getCn().','.dn, $members, false)){
                        $resultArray[$i] = ldap_explode_dn('cn='.$user->getCn().','.dn, 5)[0];
                        $i++;
                    } 
                }
                
                foreach($resultArray as $item){;
                    echo "<option value='$item'>\n";
                }
            }else{
                foreach($users as $user){
                    $name = $user->getCn();
                    echo "<option value='$name'>\n";
                }
            }
            ?>
    </datalist> 
</form>
            
</div>
<div class ="msg">
    <?php
        if(isset($_POST["submitted"])){
                if(!empty($_POST['ou']) && !empty($_POST['description']) && !empty($_POST['userstoadd'])){
                    $preparedStr = str_replace("\n",",",trim($_POST['userstoadd']));
                    $cns = explode(',',$preparedStr);
                    $userArray = array();
                    $i = 0;
                    foreach($cns as $cn){
                        $userArray[$i] = "sn=".$cn.','.dn;
                        $i++;
                    }

                    $newModel = new GroupModel($_POST['ou'], $_POST['description'],$userArray);
                    if($mode == "edit"){
                        echo GroupController::editGroup($model,$newModel);
                    }else{
                        echo GroupController::addGroup($newModel);
                    }
                    
                }else{
                    echo '<p class="errMsg">'.I100.'</p>';
                }
                echo "<p><a href='index.php?view=groupListView'>Wróć</a></p>";
        }
    ?>
</div>