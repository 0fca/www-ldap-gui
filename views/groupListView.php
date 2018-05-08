<?php 
    include_once('groupController.php');

    class GroupListView{
        public function __construct(){

        }

        function getGroups(){
            $ad_groups = GroupController::listGroups();
            return $ad_groups;
        }

        function viewMessage($message){
            echo "
            <div class='msg'>
                $message
            </div>";
        }
    }
    $glv = new GroupListView();
?>
<form action="<?php print $_SERVER['PHP_SELF']; ?>" name="groupListForm" method="post">
            <div class="container">  
                <table>
                    <thead>
                        <tr>
                            <th>
                                Nazwa
                            </th>
                            <th>
                                Opcje
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <caption>Lista grup dostępnych na serwerze LDAP</caption>
            <?php
                
                $groups = $glv->getGroups();

                if(sizeof($groups) > 0){
                    $groupArray = array();
                    $i = 0;
                    
                    foreach($groups as $groupData){
                        echo "<tr>";
                        $display = $groupData->getName();
                        $groupArray[$display] = $groupData;
                        
                        echo "<td>$display</td>
                        <td><button type='submit' formaction='admin.php?view=manageGroupView&userid=$i&mode=edit' name='$i' class='standardbutton'>Edytuj</button>  |
                        <button class='standardbutton' formaction='/admin.php?view=deleteGroupView&userid=$i'>Usuń</button></td>"."\n";

                        $s = serialize($groupArray[$display]);
                        file_put_contents($i, $s);
                        
                        $i++;
                        echo "</tr>";
                    }
                    echo "<tr>";
                    echo $glv->viewMessage(GroupController::getMessage());
                    echo "</tr>";
                    echo "<tr>";
                    echo "<a href='/admin.php?view=manageGroupView&mode=add'>Dodaj nową grupę</a>";
                    echo "</tr>";
                }
            ?>
            </tbody>
            </table>
            </div>
</form>

