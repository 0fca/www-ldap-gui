<?php
    include_once("authController.php");
    $isAuthorized = $_COOKIE["userHash"] != $_SESSION["userHash"];
?>

<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8"/>
    <title>LDAP - Panel administracyjny</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet"> 
    <link rel="icon" href="data/favicon-sml-blu.png">
    <script src="js/main.js"></script>
</head>
    
    <form method="get">
        <header>
            <div id="navbar">
            <nav class="navigation">
                <button class="navbutton" type="submit">
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
                    <li>
                        <button class="navbutton" type="submit" name="view" value="helpView">Pomoc</button>
                    </li>
                </ul>
                <p class="userText"><?php echo $model === NULL ? "" : "Zalogowano jako ".$model->getUserName();?></p>
                <button class="navbutton" type="submit" name="view" value="LoginView"><?php echo !$isAuthorized ? "Zaloguj" : "Wyloguj"; ?></button>
            </nav>
            </div>
        </header>
    </form>
    <body>

    <?php
        include_once('router.php');

        Router::route($_GET['view']);
    ?>
    <footer>
        Rose - Panel administracyjny serwera LDAP                     
    </footer>
    </body>
</html>
