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
        $winkelwagentje = array($_GET["id"]=>$_POST["hoeveelheid"]);
        $_SESSION["winkelwagentje"] = $winkelwagentje;
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
            $producttotaal = number_format($product["prijs"] * $value, 2, ",", ".");
            $totaal = $product["prijs"] * $key + $totaal;
            $content->assign("PRIJSTOTAAL", $producttotaal);

            var_dump($product);
        }
        $totaal = number_format($totaal, 2, ",", ".");
        $content->newBlock("TOTAAL");
        $content->assign("SUBTOTAAL", $totaal);
        $content->assign("VERZENDKOSTEN", number_format($verzendkosten, 2, ",", "."));
        $content->assign("TOTAAL", number_format($totaal + $verzendkosten, 2, ",", "."));
        var_dump($totaal);
    }
}

var_dump($winkelwagentje);