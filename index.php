<?php
include_once("include/class.TemplatePower.inc.php");

print "dasdsa";

include_once("php/header.php");
include_once("php/content.php");
include_once("php/aside.php");
include_once("php/footer.php");

$header->printToScreen();
$content->printToScreen();
$aside->printToScreen();
$footer->printToScreen();