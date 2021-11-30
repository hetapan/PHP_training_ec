<?php
require_once('../class/Model.php');
session_start();

// ログアウト処理を行う
$adminAuth = new AdminAuth;
$adminAuth->logout();

header('Location: login.php');
exit;