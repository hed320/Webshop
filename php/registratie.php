<?php
$content = new TemplatePower("html/registratie.html");
$content->prepare();

if (!empty($_POST["voornaam"]) and !empty($_POST["achternaam"]) and !empty($_POST["email"]) and !empty($_POST["wachtwoord"]) and !empty($_POST["wachtwoord"])) {

} else {
    $content->newBlock("FORMULIER");
}