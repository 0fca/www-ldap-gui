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
    if($_COOKIE["userHash"] != $_SESSION["userHash"]){
        Router::redirect("/?view");
    }
?>
<form action="<?php print $_SERVER['PHP_SELF']; ?>" name="groupListForm" method="post">
            <div class="container">  
            <input type='search' id='searchinput' oninput="filter();"/>
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
                        <td><button class='actionbutton' type='submit' formaction='index.php?view=manageGroupView&userid=$i&mode=edit' name='$i'>Edytuj</button>  |
                        <button class='actionbutton' formaction='index.php?view=deleteGroupView&userid=$i'>Usuń</button></td>"."\n";
                        $s = serialize($groupArray[$display]);
                        file_put_contents('cache/g'.$i, $s);
                        
                        $i++;
                        echo "</tr>";
                    }
                    echo "<tr>";
                    echo $glv->viewMessage(GroupController::getMessage());
                    echo "</tr>";
                    echo "<tr>";
                    echo "<a href='index.php?view=manageGroupView&mode=add'>Dodaj nową grupę</a>";
                    echo "</tr>";
                }
            ?>
            </tbody>
            </table>
            </div>
</form>
<script>
    document.getElementById("searchinput").addEventListener("keydown",function(e){
        if(e.keyCode == 8){
            console.log(e.keyCode);
            filter();
        }
    });
</script> 
