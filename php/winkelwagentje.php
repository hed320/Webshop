<?php
$content = new TemplatePower("html/winkelwagentje.html");
$content->prepare();

if (isset($_GET["step"])) {
    if ($_GET["step"] == "overzicht" and !empty($_POST["voornaam"]) and !empty($_POST["achternaam"]) and !empty($_POST["email"]) and !empty($_POST["adres"]) and !empty($_POST["woonplaats"]) and !empty($_POST["postcode"]) and !empty($_POST["producten"])) {
        $getproducten = $verbinding->prepare("SELECT * FROM producten WHERE idproducten = :productid");
        $getproducten->bindParam(":productid", $_GET["id"]);
        $getproducten->execute();

        $producten = $getproducten->fetchAll(PDO::FETCH_ASSOC);

        $content->newBlock("OVERZICHT");
        $content->assign("VOORNAAM", $_POST["voornaam"]);
        $content->assign("ACHTERNAAM", $_POST["achternaam"]);
        $content->assign("EMAIL", $_POST["email"]);
        $content->assign("ADRES", $_POST["adres"]);
        $content->assign("WOONPLAATS", $_POST["woonplaats"]);
        $content->assign("POSTCODE", $_POST["postcode"]);
        $content->assign("TELEFOON", $_POST["telefoon"]);
        $content->assign("PRODUCTID", $_GET["id"]);
        if (isset($_POST["telefoonnummer"])) {
            $content->assign("TELEFOON", $_POST["telefoonnummer"]);
        }
        foreach ($producten as $value) {
            $content->assign("PRODUCTEN", $value["naam"]);
        }
    } elseif ($_GET["step"] == "bestel") {

    } elseif ($_GET["step"] == "toegevoegd") {
        $winkelwagen = array("id"=>$_GET["id"],"hoeveelheid"=>$_POST["hoeveelheid"]);
        $_SESSION["winkelwagentje"] = $winkelwagen;
    }
} else {
    if (isset($_SESSION["userid"]) and isset($_SESSION["role"])) {
        if ($_SESSION["role"] == 1) {
            // getuser
            $getinfo = $verbinding->prepare("SELECT * FROM gebruikers WHERE idgebruikers = :id");
            $getinfo->bindParam(':id', $_SESSION['userid']);
            $getinfo->execute();

            $info = $getinfo->fetch(PDO::FETCH_ASSOC);

            $content->newBlock("BESTELLEN");
            $content->assign("VOORNAAM", $info["voornaam"]);
            $content->assign("ACHTERNAAM", $info["achternaam"]);
            $content->assign("EMAIL", $info["email"]);
            $content->assign("ADRES", $info["adres"]);
            $content->assign("WOONPLAATS", $info["woonplaats"]);
            $content->assign("POSTCODE", $info["postcode"]);
            $content->assign("TELEFOON", $info["telefoonnummer"]);
            $content->assign("PRODUCTID", $_GET["id"]);
        }
    }
}