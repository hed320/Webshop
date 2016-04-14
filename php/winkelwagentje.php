<?php
$content = new TemplatePower("html/winkelwagentje.html");
$content->prepare();

if (isset($_GET["action"])) {
    if ($_GET["action"] == "remove") {
        unset ($_SESSION["winkelwagentje"][$_GET["id"]]);
    }
}

if (isset($_GET["step"])) {
    if ($_GET["step"] == "overzicht" and !empty($_POST["voornaam"]) and !empty($_POST["achternaam"]) and !empty($_POST["email"]) and !empty($_POST["adres"]) and !empty($_POST["woonplaats"]) and !empty($_POST["postcode"])) {
        $product = "";
        foreach ($_SESSION["winkelwagentje"] as $key=>$value) {
            $getproducten = $verbinding->prepare("SELECT * FROM producten WHERE idproducten = :productid");
            $getproducten->bindParam(":productid", $key);
            $getproducten->execute();

            $producten = $getproducten->fetch(PDO::FETCH_ASSOC);
            $product = $product.$producten["naam"].", ";
        }

        $content->newBlock("OVERZICHT");
        $content->assign("VOORNAAM", $_POST["voornaam"]);
        $content->assign("ACHTERNAAM", $_POST["achternaam"]);
        $content->assign("EMAIL", $_POST["email"]);
        $content->assign("ADRES", $_POST["adres"]);
        $content->assign("WOONPLAATS", $_POST["woonplaats"]);
        $content->assign("POSTCODE", $_POST["postcode"]);
        $content->assign("TELEFOON", $_POST["telefoon"]);
        if (isset($_POST["telefoonnummer"])) {
            $content->assign("TELEFOON", $_POST["telefoonnummer"]);
        }
        $content->assign("PRODUCTEN", $product);
        $prijs = number_format($_SESSION["totaal"], 2, ",", ".");
        $content->assign("TOTAAL", $prijs);
    } elseif ($_GET["step"] == "gegevens") {
        if (isset($_SESSION["userid"]) and isset($_SESSION["role"])) {
            $getgebruiker = $verbinding->prepare("SELECT * FROM gebruikers WHERE idgebruikers = :id");
            $getgebruiker->bindParam(":id", $_SESSION["userid"]);
            $getgebruiker->execute();

            $gebruiker = $getgebruiker->fetch(PDO::FETCH_ASSOC);

            $content->newBlock("BESTELLEN");
            $content->assign("VOORNAAM", $gebruiker["voornaam"]);
            $content->assign("ACHTERNAAM", $gebruiker["achternaam"]);
            $content->assign("EMAIL", $gebruiker["email"]);
            $content->assign("ADRES", $gebruiker["adres"]);
            $content->assign("WOONPLAATS", $gebruiker["woonplaats"]);
            $content->assign("POSTCODE", $gebruiker["postcode"]);
            if (isset($gebruiker["telefoonnummer"])) {
                $content->assign("TELEFOON", $gebruiker["telefoonnummer"]);
            }
        } else {
            $content->newBlock("ERROR");
            $content->assign("ERROR", "U moet eerst inloggen");
        }
    } elseif ($_GET["step"] == "toegevoegd") {
        if (isset($_SESSION["winkelwagentje"])) {
            $winkelwagentje = $_SESSION["winkelwagentje"];
        }else {
            $winkelwagentje = array();
        }
        if (isset($_SESSION["winkelwagentje"][$_GET["id"]])) {
            $winkelwagentje = $_SESSION["winkelwagentje"];
            $winkelwagentje[$_GET["id"]] = $winkelwagentje[$_GET["id"]] + $_POST["hoeveelheid"];
        } else {
            $winkelwagentje[$_GET["id"]] = $_POST["hoeveelheid"];
        }
        $_SESSION["winkelwagentje"] = $winkelwagentje;
    } elseif ($_GET["step"] == "bestel") {
        
    }
} else {
    $content->newBlock("WINKELWAGENTJE");
    if (!empty($_SESSION["winkelwagentje"])) {
        $winkelwagentje = $_SESSION["winkelwagentje"];
        $totaal = 0.00;
        $verzendkosten = 6.50;
        foreach ($winkelwagentje as $key=>$value) {
            $getproduct = $verbinding->prepare("SELECT * FROM producten WHERE idproducten = :id");
            $getproduct->bindParam(":id", $key);
            $getproduct->execute();

            $product = $getproduct->fetch(PDO::FETCH_ASSOC);
            $content->newBlock("PRODUCT");
            $content->assign("ID", $key);
            $content->assign("CATEGORIE", $product["categorieen_idcategorieen"]);
            $content->assign("HOEVEELHEID", $value);
            $content->assign("NAAM", $product["naam"]);
            $content->assign("PRIJSPRODUCT", $product["prijs"]);
            $producttotaal = $product["prijs"] * $value;
            $totaal = $producttotaal + $totaal;
            $content->assign("PRIJSTOTAAL", number_format($producttotaal, 2, ",", "."));
        }
        $content->newBlock("TOTAAL");
        $content->assign("SUBTOTAAL",  number_format($totaal, 2, ",", "."));
        $content->assign("VERZENDKOSTEN", number_format($verzendkosten, 2, ",", "."));
        $totaal = $totaal + $verzendkosten;
        $content->assign("TOTAAL", number_format($totaal, 2, ",", "."));
        $_SESSION["totaal"] = $totaal;
    }
}