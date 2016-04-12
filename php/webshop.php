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