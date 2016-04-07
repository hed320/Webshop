<?php
session_start();
include_once("php/include/class.TemplatePower.inc.php");

include_once("php/include/database.php");

if (isset($_GET['page'])) {
    if (file_exists("php/" . $_GET['page'] . ".php")) {
        include_once("php/" . $_GET['page'] . ".php");
    } else {
        include_once("php/content.php");
    }
} else {
    include_once("php/content.php");
}

include_once("php/header.php");
include_once("php/content.php");
include_once("php/aside.php");
include_once("php/footer.php");

$header->printToScreen();
$content->printToScreen();
$aside->printToScreen();
$footer->printToScreen();