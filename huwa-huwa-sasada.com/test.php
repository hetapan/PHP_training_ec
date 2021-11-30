<?php
require_once('class/Model.php');
require_once('common/const.php');
require_once('common/util.inc.php');

$purchase = new Purchase;
echo '<pre>';
var_dump($purchase->test());
echo '</pre>';
