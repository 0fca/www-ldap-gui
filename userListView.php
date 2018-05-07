<?php
    //$params = urldecode();
    
    //include_once('constants.php');
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
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>Panel administratora</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<form action="<?php print $_SERVER['PHP_SELF']; ?>" name="listForm" method="post">

            <div class="container">  
                <table>
                    <thead>
                        <tr>
                            <th>
                                Name
                            </th>
                            <th>
                                Opcje
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <caption>Lista użyszkodników dostępnych na serwerze LDAP</caption>
            <?php
                
                $users = getUsers();
                //$editViewController = new EditUserController();
                
                if(sizeof($users) > 0){
                    
                    $userArray = array();
                    $i = 0;
                    
                    foreach($users as $userData){
                        echo "<tr>";
                        $display = $userData->getUserName();
                        $userArray[$display] = $userData;
                        
                        echo "<td>$display</td>
                        <td><button type='submit' formaction='admin.php?view=editUserView&userid=$i&mode=edit' name='$i' class='standardbutton'>Edytuj</button>  |
                        <button class='standardbutton' formaction='/admin.php?view=deleteUserView&userid=$i'>Usuń</button></td>"."\n";

                        $s = serialize($userArray[$display]);
                        file_put_contents($i, $s);
                        
                        $i++;
                        echo "</tr>";
                    }
                    echo "<tr>";
                    echo viewMessage(UserListController::getMessage());
                    echo "</tr>";
                    echo "<tr>";
                    echo "<a href='/admin.php?view=editUserView&mode=add'>Dodaj nowego</a>";
                    echo "</tr>";
                }
            ?>
                    </tbody>
            </table>
            </div>
</from>
</body>
</html>