<?php
$content = new TemplatePower("html/webshop.html");
$content->prepare();

$content->newBlock("PRODUCT");
$content->assign("PRODUCTID", 1);