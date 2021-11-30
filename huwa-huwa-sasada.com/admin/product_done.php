<?php
require_once('../class/Model.php');
require_once('../common/util.inc.php');

session_start();
// ログインセッションの確認を行う
$adminAuth = new AdminAuth;
$adminAuth->checkLoginSession();

// Productクラスのインスタンス生成をしておく
$product = new Product;

try {
    if ($_GET['type'] == 'edit') { //商品編集ならば
        // 商品編集処理を行う
        $product->editProduct($_POST, $_GET['id']);
    } elseif ($_GET['type'] == 'new') { //新規商品登録ならば
        // 商品登録処理を行う
        $product->registerProduct($_POST);
    }
} catch (PDOException $e) {
    $error = "システムエラーが発生しました。";
}
?>
<?php require_once('header.php') ?>
<section class="admin_panel">
    <?php getPage(); ?>
    <?php if (!empty($error)) : ?>
        <p class="admin_form_done_error"><?=$error?></p>
    <?php else : ?>
        <p class="admin_form_done_text"><?=TYPE[$_GET['type']]?>完了しました。</p>
    <?php endif; ?>
</section>
<?php require_once('footer.php') ?>