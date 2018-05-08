<?php
    require_once('constants.php');
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
<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8"/>
    <title>Panel administratora</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet"> 
    <link rel="icon" href="data/favicon-sml-blu.png">
</head>
    
    <form method="get">
        <header>
            <div id="navbar">
            <nav class="navigation">
                <button class="navbutton" type="submit">
                    <img src="/data/logooftheyear2018.png"/>
                </button>
                <ul class="navigation__list">
                    <li>
                        <button class="navbutton" type="submit" name="view" value="changePassView">Zmień hasło</button>
                    </li>
                    <li>
                        <button class="navbutton" type="submit" name="view" value="userListView">Zarządzaj użyszkodnikami</button>
                    </li>
                    <li>
                        <button class="navbutton" type="submit" name="view" value="groupListView">Zarządzaj grupami</button>
                    </li>
                </ul>
            </nav>
            </div>
        </header>
    </form>
    <body>
        <?php
            $userid = decodeUrl("userid");
            $mode = decodeUrl("mode");

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
