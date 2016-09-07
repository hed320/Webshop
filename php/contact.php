<?php
$content = new TemplatePower("include/contact.include");
$content->prepare();

if (isset($_POST["submit"])) {
    $to      = "";
    $subject = "Contact";
    $message = $_POST["message"];
    $headers = 'Reply-To: '. $_POST["email"] . "\r\n";
    mail($to, $subject, $message, $headers);
}