<?php
<<<<<<< HEAD
    include_once("authController.php");
    $isAuthorized = $_COOKIE["userHash"] != $_SESSION["userHash"];
        
    
?>

=======
    error_reporting(0);
    include_once('constants.php');
    include_once('accountUserController.php');
    session_start();
    function decodeUrl($phrase){
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $parts = parse_url($actual_link);
        parse_str($parts['query'], $query);
        if(array_key_exists($phrase,$query)){
            $retVal = $query[$phrase];
            return $retVal; 
        }
        return NULL;
    }
?>
>>>>>>> 4fe1b298cc9e27c28c576c60d67da566b556a4fc
<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8"/>
<<<<<<< HEAD
    <title>LDAP - Panel administracyjny</title>
=======
    <title>Panel administratora</title>
>>>>>>> 4fe1b298cc9e27c28c576c60d67da566b556a4fc
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet"> 
    <link rel="icon" href="data/favicon-sml-blu.png">
<<<<<<< HEAD
    <script src="js/main.js"></script>
=======
>>>>>>> 4fe1b298cc9e27c28c576c60d67da566b556a4fc
</head>
    
    <form method="get">
        <header>
            <div id="navbar">
            <nav class="navigation">
                <button class="navbutton" type="submit">
<<<<<<< HEAD
                    <img src="data/logooftheyear2018.png"/>
                </button>
                <ul class="navigation__list">
                    <li>
                        <button class="navbutton" type="submit" name="view" value="changePassView">Zmień hasło</button>
                    </li>
                    <?php 
                            if($isAuthorized){
                                echo "<li>
                                <button class='navbutton' type='submit' name='view' value='userListView'>Zarządzaj użyszkodnikami</button>
                                </li>
                                <li>
                                  <button class='navbutton' type='submit' name='view' value='groupListView'>Zarządzaj grupami</button>
                                </li>";
                            }
                    ?>
                    <li>
                        <button class="navbutton" type="submit" name="view" value="aboutView">O aplikacji</button>
                    </li>
                    
                </ul>
                <p class="userText"><?php echo $model === NULL ? "" : "Zalogowano jako ".$model->getUserName();?></p>
                <button class="navbutton" type="submit" name="view" value="LoginView"><?php echo !$isAuthorized ? "Zaloguj" : "Wyloguj"; ?></button>
=======
                    <img src="data/logooftheyear2018.png" />
                    <button class="navbutton" type="submit" name="view" value="userListView">Zarządzaj użyszkodnikami</button>
                    <button class="navbutton" type="submit" name="view" value="groupListView">Zarządzaj grupami</button>
                    <button class="navbutton" type="submit" name="view" value="aboutView">O aplikacji</button>
>>>>>>> 4fe1b298cc9e27c28c576c60d67da566b556a4fc
            </nav>
            </div>
        </header>
    </form>
    <body>
<<<<<<< HEAD

    <?php
        include_once('router.php');

        Router::route($_GET['view']);
    ?>
    </body>
</html>
=======
        <?php
            $userid = decodeUrl("userid");
            $mode = decodeUrl("mode");
            $viewtype = decodeUrl("viewtype");


            $filename = 'views/'.$_GET["view"].".php";
            if($userid != NULL){
                $_SESSION['userid'] = $userid;
            }

            if($mode != NULL){
                $_SESSION['mode'] = $mode;
            }    

            if(file_exists($filename)){
                include($filename);
            }else{
                if($_GET["view"] == NULL){
                    include("views/admin.content.html");
                }else{
                    $filename = 'views/'.$_GET["view"].".html";
                    include($filename);
                }
            }    
            
        ?>
    </body>
</html>
>>>>>>> 4fe1b298cc9e27c28c576c60d67da566b556a4fc
