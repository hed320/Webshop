<?php
$content = new TemplatePower("html/contact.html");
$content->prepare();

if (isset($_POST["submit"])) {
    mail("", "Contact", $_POST["message"]);
}