<?php
require_once('class/Model.php');
session_start();

// Authクラスのログアウト処理を行う
$auth = new Auth;
$auth->logout();

header('Location: index.php');
exit;