<?php
$content = new TemplatePower("html/webshop.html");
$content->prepare();

$getcat = $verbinding->prepare("SELECT * FROM categorieen");
$getcat->execute();

$categorieen = $getcat->fetchall(PDO::FETCH_ASSOC);
sort($categorieen);

foreach ($categorieen as $value) {
    $content->newBlock("CATEGORIE");
    $content->assign("CATEGORIE", $value["naam"]);
    $content->assign("CATID", $value["idcategorieen"]);
}

if (isset($_GET["cat"]) and isset($_GET["id"])) {
    $getproducten = $verbinding->prepare("SELECT * FROM producten WHERE categorieen_idcategorieen = :catid AND idproducten = :productid");
    $getproducten->bindParam(":catid", $_GET["cat"]);
    $getproducten->bindParam(":productid", $_GET["id"]);
    $getproducten->execute();

    $producten = $getproducten->fetchall(PDO::FETCH_ASSOC);
    sort($producten);

    foreach ($producten as $value) {
        $content->newBlock("DETAILS");
        $content->assign("PRODUCTID", $value["idproducten"]);
        $content->assign("CATID", $_GET["cat"]);
        $content->assign("NAAM", $value["naam"]);
        $content->assign("PRIJS", $value["prijs"]);
        $content->assign("KOMSCHRIJVING", $value["korteomschrijving"]);
        $content->assign("OMSCHRIJVING", $value["omschrijving"]);
    }
} elseif (isset($_GET["cat"])) {
    $getproducten = $verbinding->prepare("SELECT * FROM producten WHERE categorieen_idcategorieen = :catid");
    $getproducten->bindParam(":catid", $_GET["cat"]);
    $getproducten->execute();

    $producten = $getproducten->fetchall(PDO::FETCH_ASSOC);
    sort($producten);

    foreach ($producten as $value) {
        $content->newBlock("CAT");
        $content->assign("PRODUCTID", $value["idproducten"]);
        $content->assign("CATID", $_GET["cat"]);
        $content->assign("NAAM", $value["naam"]);
        $content->assign("PRIJS", $value["prijs"]);
        $content->assign("KOMSCHRIJVING", $value["korteomschrijving"]);
    }
}