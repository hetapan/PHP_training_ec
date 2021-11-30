<?php
require_once('../class/Model.php');
require_once('../common/util.inc.php');

session_start();
// ログインセッションの確認を行う
$adminAuth = new AdminAuth;
$adminAuth->checkLoginSession();
?>
<?php require_once('header.php') ?>
<section class="admin_panel">
</section>
<?php require_once('footer.php') ?>
