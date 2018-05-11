<?php    
    include_once('userListController.php');
    function getUsers(){
        $ad_users = UserListController::listUsers();
        
        return $ad_users;
    }


    function viewMessage($message){
        echo "
        <div class='msg'>
            $message
        </div>";
    }

?>
<form action="<?php print $_SERVER['PHP_SELF']; ?>" name="listForm" method="post">
            <div class="container">  
                <table id="userTable">
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
                        <caption>Lista użyszkodników dostępnych na serwerze LDAP</caption>
                        <tr>
                            <td>
                                <input type='search' id='searchinput'/>
                            </td>
                            <td>
                                <p class='clickableParagraph' name='searchbutton' onclick='SearchInUserDataList();'>Wyszukaj</p></span>
                            </td>
                        </tr>
            <?php
                
                function view($users){
                    if(sizeof($users) > 0){
                        $userArray = array();
                        $i = 0;
                        
                        foreach($users as $userData){
                            echo "<tr>";
                            $display = $userData->getUserName();
                            $userArray[$display] = $userData;
                        
                            echo "<td>$display</td>
                            <td><button class='actionbutton' type='submit' formaction='index.php?view=manageUserView&userid=$i&mode=edit' name='$i' class='standardbutton'>Edytuj</button>  |
                            <button class='actionbutton' formaction='index.php?view=deleteUserView&userid=$i'>Usuń</button></td>"."\n";

                            $s = serialize($userArray[$display]);
                            file_put_contents('cache/u'.$i, $s);
                            
                            echo "";
                            $i++;
                            echo "</tr>";
                        }
                        
                    }   
                }
                view(getUsers());
                echo "<tr>";
                echo viewMessage(UserListController::getMessage());
                echo "</tr>";
                echo "<tr>";
                echo "<a href='index.php?view=manageUserView&mode=add'>Dodaj nowego</a>";
                echo "</tr>";
            ?>
            </tbody>
            </table>
            </div>
</form>