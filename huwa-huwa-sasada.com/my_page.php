<?php
require_once('class/Model.php');
require_once('common/util.inc.php');

session_start();

// Authクラスのログインセッションの確認を行う
$auth = new Auth;
$auth->checkLoginSession();

$user = new User;
?>
<?php require_once('header.php') ?>
<section class="main">
    <?php
    echo '<pre>';
    var_dump($user->getUser($_SESSION['user_id']));
    echo '</pre>';
    ?>
</section>
<?php require_once('footer.php') ?>
