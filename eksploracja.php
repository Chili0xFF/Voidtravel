<?php
    include_once 'istota.php'; 
    include_once 'uzytkownik.php';
    include_once 'przeciwnik.php';
    include_once 'connect.php';
    include_once 'functions.php';
    session_start();   //NAJPIERW KLASY. POTEM SESJA. ZAPAMIĘTAĆ.
    if(!isset($_SESSION['gracz'])){
        header("Location: index.php");
        exit();
    }
    ?>
<html>
<head>
    <title>Voidtravel</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css" type="text/css">
    </head>
    <body>
<div id='container'>
    <div class="panel" id="gorny">
        <div id="main-baner"><img src="img/logo.png"></div>
    </div>
    <div class="panel" id="lewy">
        <div id="menu">
            <table>
                <tr><td><a href='main.php'>Miasto</a></td></tr>
                <tr><td><a href='postac.php'>Postać</a></td></tr>
                <tr><td><a href='eksploracja.php'>Eksploracja</a></td></tr>
                <tr><td><a href='ranking.php'>Ranking</a></td></tr>
                <tr><td><a href='pomoc.php'>Pomoc</a></td></tr>
                <tr><td><a href='logout.php'>Wyloguj</a></td></tr>
            </table>
        </div>
    </div>
    <div class="panel" id="srodkowy">
        <div class="title"><h1>Eksploracja</h1></div>
        <?php if(isset($_SESSION['error']))echo "<div style='color: red'>".$_SESSION['error']."</div>";?>
        <div id="enemyGallery">
        <?php 
        $query = "SELECT * FROM przeciwnik WHERE ID<=".$_SESSION['gracz']->getEtap().";";
        $result = $db_connect->query($query);
        $i=1;
        while($row=$result->fetch_assoc()){
            echo "<div class='enemy' id='enemy$i'";
            if($i%5==0){
                echo " style='clear: both;'";
            }
            else{
                echo "style='float: left;'";
            }
            echo ">";
            $przeciwnik = new przeciwnik($row);
            echo $przeciwnik->getNazwa();
            echo "<img src='img/".$przeciwnik->getHref()."'><a href='walka.php?przeciwnik=".$przeciwnik->getId()."'>Walcz!</a>";
            $i++;
            echo "</div>";
        }
        
        
        ?>
        </div>
    </div>
    <div class="panel" id="prawy">

    </div>
    <div class="panel" id="dolny">
    Projekt wykonany przez: Artur Szulist, s23049. <a href="https://github.com/Chili0xFF">GitHub</a>
    </div>
</div>
<?php 
    resetError();
?>
    </body>
</html>