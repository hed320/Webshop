<?php
$content = new TemplatePower("html/webshop.html");
$content->prepare();

$getcat = $verbinding->prepare("SELECT * FROM categorieen");
$getcat->execute();

$categorieen = $getcat->fetch(PDO::FETCH_ASSOC);

var_dump($categorieen);

$content->newBlock("CATEGORIE");
$content->assign("CATEGORIE", "CPU");
$content->assign("CATID", 1);