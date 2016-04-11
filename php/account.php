<?php
$content = new TemplatePower("html/account.html");
$content->prepare();

if (isset($_SESSION["userid"]) and isset($_SESSION["role"])) {
    if ($_SESSION["role"] == 1) {
        // getuser
        $getinfo = $verbinding->prepare("SELECT * FROM gebruikers WHERE idgebruikers = :id");
        $getinfo->bindParam(':id', $_SESSION['userid']);
        $getinfo->execute();

        $info = $getinfo->fetch(PDO::FETCH_ASSOC);

        $content->newBlock("FORMULIER");
        $content->assign("VOORNAAM", $info["voornaam"]);
        $content->assign("ACHTERNAAM", $info["achternaam"]);
        $content->assign("EMAIL", $info["email"]);
        $content->assign("ADRES", $info["adres"]);
        $content->assign("WOONPLAATS", $info["woonplaats"]);
        $content->assign("POSTCODE", $info["postcode"]);
        $content->assign("TELEFOON", $info["telefoonnummer"]);
    }
}

if (!empty($_POST["voornaam"]) and !empty($_POST["achternaam"])) {
    if (!empty($_POST["wachtwoord"]) and !empty($_POST["wachtwoord2"]) and $_POST["wachtwoord"] == $_POST["wachtwoord2"]) {
        //sql met wachtwoord update
    } else {
        //sql zonder wachtwoord update
    }
}