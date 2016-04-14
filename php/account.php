<?php
$content = new TemplatePower("html/account.html");
$content->prepare();

if (isset($_SESSION["userid"]) and isset($_SESSION["role"])) {
    if ($_SESSION["role"] == 1) {
        try {
            $getinfo = $verbinding->prepare("SELECT * FROM gebruikers WHERE idgebruikers = :id");
            $getinfo->bindParam(':id', $_SESSION['userid']);
            $getinfo->execute();
        } catch (PDOException $error) {
            $content->newBlock("ERROR");
            $content->assign("ERROR", "Kan uw gegevens niet laden");
        }
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
        $options = [
            'cost' => 12,
        ];
        $wachtwoord = password_hash($_POST["wachtwoord"], PASSWORD_BCRYPT, $options);
        try {
            $wijzigen = $verbinding->prepare("UPDATE gebruikers SET 
				voornaam= :voornaam, 
				achternaam= :achternaam,
				email= :email,
				wachtwoord= :wachtwoord,
				adres= :adres,
				woonplaats= :woonplaats,
				postcode= :postcode,
				telefoonnummer= :telefoonnummer
				WHERE idgebruikers= :id AND email = :email");

            $wijzigen->bindParam(':voornaam', $_POST['voornaam']);
            $wijzigen->bindParam(':achternaam', $_POST['achternaam']);
            $wijzigen->bindParam(':email', $_POST['email']);
            $wijzigen->bindParam(':wachtwoord', $wachtwoord);
            $wijzigen->bindParam(':adres', $_POST['adres']);
            $wijzigen->bindParam(':woonplaats', $_POST['woonplaats']);
            $wijzigen->bindParam(':postcode', $_POST['postcode']);
            $wijzigen->bindParam(':telefoonnummer', $_POST['telefoon']);
            $wijzigen->bindParam(':id', $_SESSION['userid']);

            $wijzigen->execute();

            $content->newBlock("SUCCES");
            $content->assign("SUCCES", "De wijzigingen zijn succesvol opgeslagen");
        } catch (PDOException $error) {
            $content->newBlock("ERROR");
            $content->assign("ERROR", "Kan de wijzigigen niet opslaan");
        }
    } else {
        try {
            $wijzigen = $verbinding->prepare("UPDATE gebruikers SET 
				voornaam= :voornaam, 
				achternaam= :achternaam,
				email= :email,
				adres= :adres,
				woonplaats= :woonplaats,
				postcode= :postcode,
				telefoonnummer= :telefoonnummer
				WHERE idgebruikers= :id AND email = :email");

            $wijzigen->bindParam(':voornaam', $_POST['voornaam']);
            $wijzigen->bindParam(':achternaam', $_POST['achternaam']);
            $wijzigen->bindParam(':email', $_POST['email']);
            $wijzigen->bindParam(':adres', $_POST['adres']);
            $wijzigen->bindParam(':woonplaats', $_POST['woonplaats']);
            $wijzigen->bindParam(':postcode', $_POST['postcode']);
            $wijzigen->bindParam(':telefoonnummer', $_POST['telefoon']);
            $wijzigen->bindParam(':id', $_SESSION['userid']);

            $wijzigen->execute();

            $content->newBlock("SUCCES");
            $content->assign("SUCCES", "De wijzigingen zijn succesvol opgeslagen");
        } catch (PDOException $error) {
            $content->newBlock("ERROR");
            $content->assign("ERROR", "Kan de wijzigigen niet opslaan");
        }
    }
}