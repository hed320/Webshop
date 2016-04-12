<?php
$header = new TemplatePower("html/header.html");
$header->prepare();

if (!empty($_POST["email"]) and !empty($_POST["wachtwoord"])) {
    $options = [
        'cost' => 12,
    ];
    $wachtwoord = password_hash($_POST["wachtwoord"], PASSWORD_BCRYPT, $options);

    $checkmail = $verbinding->prepare("SELECT count(*) FROM gebruikers WHERE email = :email");
    $checkmail->bindParam(":email", $_POST['email']);
    $checkmail->execute();

    if ($checkmail->fetchColumn() == 1) {
        $getinfo = $verbinding->prepare("SELECT * FROM gebruikers WHERE email = :email");
        $getinfo->bindParam(":email", $_POST['email']);
        $getinfo->execute();

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
}