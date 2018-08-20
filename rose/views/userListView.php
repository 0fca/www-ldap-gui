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

    if($_COOKIE["userHash"] != $_SESSION["userHash"]){
        $_SESSION["returnUrl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        Router::redirect("/?view=LoginView");
    }
?>
<form name="listForm" method="post">
            <div class="container">  
            <input type='search' id='searchinput' oninput="filter();"/>
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
<script>
    document.getElementById("searchinput").addEventListener("keydown",function(e){
        if(e.keyCode == 8){
            //console.log(e.keyCode);
            filter();
        }
    });
</script> 
