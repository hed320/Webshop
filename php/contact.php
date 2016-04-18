<?php
$content = new TemplatePower("html/contact.html");
$content->prepare();

if (isset($_POST["submit"])) {
    $to      = "";
    $subject = "Contact";
    $message = $_POST["message"];
    $headers = 'Reply-To: '. $_POST["email"] . "\r\n";
    mail($to, $subject, $message, $headers);
}