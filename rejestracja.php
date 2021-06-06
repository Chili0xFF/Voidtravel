<?php
    include_once 'connect.php';
    include_once 'functions.php';
    
    session_start();
    if(isset($_POST["email"]))
    {
        $flag=true;
        $nick=$_POST['nickname'];
        $email=$_POST['email'];
        $emailSanitized=filter_var($email,FILTER_SANITIZE_EMAIL);
        $pass1=$_POST['pass1'];
        $pass2=$_POST['pass2'];
        if((strlen($nick)<3)||(strlen($nick)>20))
        {
            $flag=false;
            $_SESSION['e_nick']="Nick musi posiadać od 3 do 20 znaków";
        }
        if(ctype_alnum($nick)==false)
        {
            $flag=false;
            $_SESSION['e_nick']="Tylko alfanumeryczne";
        }
        if((filter_var($emailSanitized,FILTER_VALIDATE_EMAIL)==false)||($email!=$emailSanitized))
        {
            $flag=false;
            $_SESSION['e_email']="Niepoprawny email";
        }
        if((strlen($pass1)<3)||(strlen($pass1)>20))
        {
            $flag=false;
            $_SESSION['e_pass']="Niepoprawne haslo";
        }
        else if($pass1!=$pass2)
        {
            $flag=false;
            $_SESSION['e_pass']="Hasła nie są identyczne";
        }
        if($flag==true)
        {
            register($nick,$pass1,$emailSanitized);
            header("Location: main.php");
            exit();
        }
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
    <div class="panel" id="lewy" style="background-color: RGBA(0,0,0,0);">
        <div id="menu">
        </div>
    </div>
    <div class="panel" id="srodkowy" style="background-color: RGBA(0,0,0,0);">
        <div id="tekst_fabularny">
            Świat jaki znamy, upadł. Około dekadę temu, ziemię pokryła ciemna i gęsta jak atrament mgła, a z samego centrum tej mgły zaczęły wypełzać istoty rodem z koszmarów. Oblężone miasta były odcinane od siebie nawzajem, tylko po to aby potem upaść pod naporem wrogiej inwazji. Teraz, zostało już tylko kilka niewielkich siedlisk ludzi którzy nie mają ze sobą żadnego kontaktu. 
            <br><br>
            Narodziła się jednak nikła nadzieja. Kolejne pokolenie, jak gdyby przesiąknięte już mroczną siłą wykazuje zdolności do przezwyciężania potworności. Jedni, zwani Latarniami nauczyli się czerpać z mocy, i utrzymują mrok za murami, dając szansę ludzkości na normalne życie. Inni, nazywani Skrybami posiedli moc przekuwania energi w wyspecjalizowany ekwipunek, zdolny zranić i na dobre zniszczyć istoty pustki. Ostatnia, najrzadsza garstka ludzi, widzi w tej gęstej mgle, i są zdolni do równej walki z przeciwnikiem na ich terenie. Ci ostatni, nazywani są Kroczącymi w Mroku i darzeni są wielkim szacunkiem, bo to na nich spoczywa odpowiedzialność odbicia świata i zniszczenia Źródła.
            <br><br>
            Czy masz odwagę podjąć się wędrówki w nieznane? Czy myślisz że masz to, co potrzebne będzie aby raz na zawsze położyć kres temu szaleństwu? Przekonajmy się...
        </div>
        <div id="zaloguj">
            <form method="post">
                Email: <input type="email" name="email"><?php if(isset($_SESSION['e_email']))echo'<div class="error">'.$_SESSION["e_email"].'</div>';unset($_SESSION['e_email']);?><br>
                Login: <input type="text" name="nickname"> <?php if(isset($_SESSION['e_nick']))echo'<div class="error">'.$_SESSION["e_nick"].'</div>';unset($_SESSION['e_nick']);?><br>
                Hasło: <input type="password" name="pass1"><?php if(isset($_SESSION['e_pass']))echo'<div class="error">'.$_SESSION["e_pass"].'</div>';unset($_SESSION['e_pass']);?><br>
                Powtorz Hasło: <input type="password" name="pass2"><br>
            <label><input type="checkbox" name="reg" required>Akceptuje regulamin</label><br>
            <br/>
            <input type="submit" value="Rejestruj"><br>
            Masz już konto? <a href="index.php" style="color: white;">Zaloguj się</a>!
            </form>
        </div>
    </div>
    <div class="panel" id="prawy"style="background-color: RGBA(0,0,0,0);">

    </div>
    <div class="panel" id="dolny">
        Projekt wykonany przez: Artur Szulist, s23049. <a href="https://github.com/Chili0xFF">GitHub</a>
    </div>
</div>
    </body>
</html>