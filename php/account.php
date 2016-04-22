<?php
$content = new TemplatePower("html/account.html");
$content->prepare();

if (isset($_SESSION["userid"]) and isset($_SESSION["role"])) {
    $content->newBlock("ACCOUNT");
    $content->assign("OPTIE", "gegevens");
    $content->assign("OPTIENAAM", "Mijn Gegevens");
    $content->newBlock("ACCOUNT");
    $content->assign("OPTIE", "bestelling");
    $content->assign("OPTIENAAM", "Mijn bestellingen");
    if ($_SESSION["role"] == 2) {
        $content->newBlock("ACCOUNT");
        $content->assign("OPTIE", "abestelling");
        $content->assign("OPTIENAAM", "Alle bestellingen");
        $content->newBlock("ACCOUNT");
        $content->assign("OPTIE", "agebruikers");
        $content->assign("OPTIENAAM", "Alle gebruikers");
    }
    if (isset($_GET["optie"])) {
        if ($_GET["optie"] == "gegevens") {
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
            } else {
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
        } elseif ($_GET["optie"] == "bestelling") {
            try {
                $getuser = $verbinding->prepare("SELECT * FROM gebruikers WHERE idgebruikers = :idgebruiker");
                $getuser->bindParam(":idgebruiker", $_SESSION["userid"]);
                $getuser->execute();
            } catch (PDOException $error) {
                $content->newBlock("ERROR");
                $content->assign("ERROR", "Kan de gebruiker niet laden");
            }
            $user = $getuser->fetch(PDO::FETCH_ASSOC);
            try {
                $getorders = $verbinding->prepare("SELECT * FROM bestelling WHERE gebruikers_idgebruikers = :idgebruiker");
                $getorders->bindParam(":idgebruiker", $_SESSION["userid"]);
                $getorders->execute();
            } catch (PDOException $error) {
                $content->newBlock("ERROR");
                $content->assign("ERROR", "Kan niet alle bestellingen laden");
            }
                $orders = $getorders->fetchAll(PDO::FETCH_ASSOC);
            foreach ($orders as $value) {
                $content->newBlock("BESTELLING");
                $content->assign("VOORNAAM", $user["voornaam"]);
                $content->assign("ACHTERNAAM", $user["achternaam"]);
                try {
                    $getproducts = $verbinding->prepare("SELECT * FROM bestelregel WHERE bestelling_idbestelling = :idbestelling");
                    $getproducts->bindParam(":idbestelling", $value["idbestelling"]);
                    $getproducts->execute();
                } catch (PDOException $error) {
                    $content->newBlock("ERROR");
                    $content->assign("ERROR", "Kan niet alle bestellingen laden");
                }
                $products = $getproducts->fetchAll(PDO::FETCH_ASSOC);
                foreach ($products as $value) {
                    $content->newBlock("PRODUCT");
                    try {
                        $getproducts = $verbinding->prepare("SELECT * FROM producten WHERE idproducten = :idproduct");
                        $getproducts->bindParam(":idproduct", $value["producten_idproducten"]);
                        $getproducts->execute();
                    } catch (PDOException $error) {
                        $content->newBlock("ERROR");
                        $content->assign("ERROR", "Kan niet alle bestellingen laden");
                    }
                    $product = $getproducts->fetch(PDO::FETCH_ASSOC);
                    $content->assign("PRODUCTNAAM", $product["naam"]);
                    $content->assign("HOEVEELHEID", $value["aantal"]);
                }
            }
        } elseif ($_GET["optie"] == "abestelling" and $_SESSION["role"] == 2) {
            try {
                $getorders = $verbinding->prepare("SELECT * FROM bestelling");
                $getorders->execute();
            } catch (PDOException $error) {
                $content->newBlock("ERROR");
                $content->assign("ERROR", "Kan niet alle bestellingen laden");
            }
            $orders = $getorders->fetchAll(PDO::FETCH_ASSOC);
            foreach ($orders as $key=>$value) {
                try {
                    $getinfo = $verbinding->prepare("SELECT * FROM gebruikers WHERE idgebruikers = :idgebruiker");
                    $getinfo->bindParam(":idgebruiker", $value["gebruikers_idgebruikers"]);
                    $getinfo->execute();
                } catch (PDOException $error) {
                    $content->newBlock("ERROR");
                    $content->assign("ERROR", "Kan de gebruiker niet laden van de bestelling");
                }
                $info = $getinfo->fetch(PDO::FETCH_ASSOC);
                    $content->newBlock("BESTELLING");
                    $content->assign("VOORNAAM", $info["voornaam"]);
                    $content->assign("ACHTERNAAM", $info["achternaam"]);
                try {
                    $getproducts = $verbinding->prepare("SELECT * FROM bestelregel WHERE bestelling_idbestelling = :idbestelling");
                    $getproducts->bindParam(":idbestelling", $value["idbestelling"]);
                    $getproducts->execute();
                } catch (PDOException $error) {
                    $content->newBlock("ERROR");
                    $content->assign("ERROR", "Kan niet alle bestellingen laden");
                }
                $products = $getproducts->fetchAll(PDO::FETCH_ASSOC);
                foreach ($products as $key=>$value) {
                    $content->newBlock("PRODUCT");
                    try {
                        $getproduct = $verbinding->prepare("SELECT * FROM producten WHERE idproducten = :idproduct");
                        $getproduct->bindParam(":idproduct", $value["producten_idproducten"]);
                        $getproduct->execute();
                    } catch (PDOException $error) {
                        $content->newBlock("ERROR");
                        $content->assign("ERROR", "Kan niet alle bestellingen laden");
                    }
                    $product = $getproduct->fetch(PDO::FETCH_ASSOC);
                    $content->assign("PRODUCTNAAM" , $product["naam"]);
                    $content->assign("HOEVEELHEID", $value["aantal"]);
                }
            }
        } elseif ($_GET["optie"] == "agebruikers" and $_SESSION["role"] == 2) {
            if (isset($_GET["id"])) {
                try {
                    $getuser = $verbinding->prepare("SELECT * FROM gebruikers WHERE idgebruikers = :idgebruiker");
                    $getuser->bindParam(":idgebruiker", $_GET["id"]);
                    $getuser->execute();
                } catch (PDOException $error) {
                    $content->newBlock("ERROR");
                    $content->assign("ERROR", "Kan de gebruiker niet laden");
                }
                $users = $getuser->fetch(PDO::FETCH_ASSOC);
                try {
                    $getroles = $verbinding->prepare("SELECT * FROM role");
                    $getroles->execute();
                } catch (PDOException $error) {
                    $content->newBlock("ERROR");
                    $content->assign("ERROR", "Kan niet alle rollen laden");
                }
                $roles = $getroles->fetchAll(PDO::FETCH_ASSOC);
                $content->newBlock("GEBRUIKERINFO");
                $content->assign("ID", $_GET["id"]);
                $content->assign("VOORNAAM", $users["voornaam"]);
                $content->assign("ACHTERNAAM", $users["achternaam"]);
                $content->assign("EMAIL", $users["email"]);
                $content->assign("ADRES", $users["adres"]);
                $content->assign("WOONPLAATS", $users["woonplaats"]);
                $content->assign("POSTCODE", $users["postcode"]);
                $content->assign("TELEFOON", $users["telefoonnummer"]);
                $content->assign("ID", $_GET["id"]);
                foreach ($roles as $value) {
                    $content->newBlock("ROLE");
                    $content->assign("ID", $value["idrole"]);
                    if ($users["role_idrole"] == $value["idrole"]) {
                        $content->assign("SELECTED", "selected");
                    }
                    $content->assign("ROLENAAM", $value["naam"]);
                }
            } else {
                if (!empty($_POST["voornaam"]) and !empty($_POST["achternaam"])) {
                    try {
                        $wijzigen = $verbinding->prepare("UPDATE gebruikers SET 
                        voornaam= :voornaam, 
                        achternaam= :achternaam,
                        email= :email,
                        adres= :adres,
                        woonplaats= :woonplaats,
                        postcode= :postcode,
                        telefoonnummer = :telefoonnummer,
                        role_idrole = :idrole
                        WHERE idgebruikers= :id");
                        $wijzigen->bindParam(':voornaam', $_POST['voornaam']);
                        $wijzigen->bindParam(':achternaam', $_POST['achternaam']);
                        $wijzigen->bindParam(':email', $_POST['email']);
                        $wijzigen->bindParam(':adres', $_POST['adres']);
                        $wijzigen->bindParam(':woonplaats', $_POST['woonplaats']);
                        $wijzigen->bindParam(':postcode', $_POST['postcode']);
                        $wijzigen->bindParam(':telefoonnummer', $_POST['telefoon']);
                        $wijzigen->bindParam(":idrole", $_POST["role"]);
                        $wijzigen->bindParam(':id', $_POST["id"]);

                        $wijzigen->execute();

                        $content->newBlock("SUCCES");
                        $content->assign("SUCCES", "De wijzigingen zijn succesvol opgeslagen");
                        var_dump($_POST);
                    } catch (PDOException $error) {
                        $content->newBlock("ERROR");
                        $content->assign("ERROR", "Kan de wijzigigen niet opslaan");
                    }
                } else {
                    try {
                        $getusers = $verbinding->prepare("SELECT * FROM gebruikers");
                        $getusers->execute();
                    } catch (PDOException $error) {
                        $content->newBlock("ERROR");
                        $content->assign("ERROR", "Kan niet alle gebruikers laden");
                    }
                    $users = $getusers->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($users as $value) {
                        $content->newBlock("GEBRUIKER");
                        $content->assign("ID", $value["idgebruikers"]);
                        $content->assign("VOORNAAM", $value["voornaam"]);
                        $content->assign("ACHTERNAAM", $value["achternaam"]);
                    }
                }
            }
        }
    }
}