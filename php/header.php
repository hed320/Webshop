<?php
$header = new TemplatePower("html/header.html");
$header->prepare();

if (isset($_POST["uitloggen"])) {
    unset($_SESSION["userid"]);
    unset($_SESSION["role"]);
}

if (!empty($_POST["email"]) and !empty($_POST["wachtwoord"]) and isset($_POST["login"])) {
    $options = [
        'cost' => 12,
    ];
    $wachtwoord = password_hash($_POST["wachtwoord"], PASSWORD_BCRYPT, $options);

    try {
        $checkmail = $verbinding->prepare("SELECT count(*) FROM gebruikers WHERE email = :email");
        $checkmail->bindParam(":email", $_POST['email']);
        $checkmail->execute();

    } catch (PDOException $error) {
        $content->newBlock("ERROR");
        $content->assign("ERROR", "Kan geen gebruiker vinden");
    }

    if ($checkmail->fetchColumn() == 1) {
        try {
            $getinfo = $verbinding->prepare("SELECT * FROM gebruikers WHERE email = :email");
            $getinfo->bindParam(":email", $_POST['email']);
            $getinfo->execute();
        } catch (PDOException $error) {
            $content->newBlock("ERROR");
            $content->assign("ERROR", "Kan de gebruiker niet ophalen");
        }

        $info = $getinfo->fetch(PDO::FETCH_ASSOC);

        if (password_verify($_POST["wachtwoord"], $info["wachtwoord"])) {
            $_SESSION["userid"] = $info["idgebruikers"];
            $_SESSION["role"] = $info["role_idrole"];
            $header->newBlock("LOGINSUCCES");
            $header->assign("LOGINSUCCES", "Je bent ingelogd");
        } else {
            $header->newBlock("LOGINERROR");
            $header->assign("LOGINERROR", "Je login klopt niet");
        }
    }
}

if (isset($_SESSION["userid"]) and isset($_SESSION["role"])) {
    $header->newBlock("ACCOUNT");
    $header->newBlock("LOGGEDIN");
} else {
    $header->newBlock("LOGIN");
}

if (isset($_SESSION["winkelwagentje"])) {
    $header->newBlock("WINKELWAGENTJE");
    $winkelwagentje = $_SESSION["winkelwagentje"];
    $hoeveelheid = 0;
    foreach ($winkelwagentje as $key=>$value) {
        $hoeveelheid = $hoeveelheid + $value;
    }
    $header->assign("HOEVEELHEID", $hoeveelheid);
}