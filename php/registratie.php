<?php
$content = new TemplatePower("html/registratie.html");
$content->prepare();

if (!empty($_POST["voornaam"]) and !empty($_POST["achternaam"]) and !empty($_POST["email"]) and !empty($_POST["wachtwoord"]) and !empty($_POST["wachtwoord2"])) {
    if ($_POST["wachtwoord"] == $_POST["wachtwoord2"]) {
        $options = [
            'cost' => 12,
        ];
        $wachtwoord = password_hash($_POST["wachtwoord"], PASSWORD_BCRYPT, $options);

        try {
            $insert = $verbinding->prepare("INSERT INTO gebruikers SET voornaam = :voornaam, achternaam = :achternaam, email = :email, wachtwoord = :wachtwoord , role_idrole = 1");
            $insert->bindParam(":voornaam", $_POST['voornaam']);
            $insert->bindParam(":achternaam", $_POST['achternaam']);
            $insert->bindParam(":email", $_POST['email']);
            $insert->bindParam(":wachtwoord", $wachtwoord);

            $insert->execute();
        } catch (PDOException $error) {
            $content->newBlock("ERROR");
            $content->assign("ERROR", "Kan de gebruiker niet aanmaken");
        }
        $content->newBlock("SUCCES");
        $content->assign("SUCCES", "Registratie voltooid");
    } else {
        $content->newBlock("ERROR");
        $content->assign("ERROR", "De wachtwoorden zijn niet hetzelfde");
    }
} else {
    $content->newBlock("FORMULIER");
}