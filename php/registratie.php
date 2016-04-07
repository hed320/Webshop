<?php
$content = new TemplatePower("html/registratie.html");
$content->prepare();

if (!empty($_POST["voornaam"]) and !empty($_POST["achternaam"]) and !empty($_POST["email"]) and !empty($_POST["wachtwoord"]) and !empty($_POST["wachtwoord2"])) {

} else {
    $content->newBlock("FORMULIER");
}