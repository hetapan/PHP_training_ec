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
    <?php getPage(); ?>
    <table class="admin_table_edit">
        <tr>
            <th>商品名</th>
            <td><?=h($_POST['name'])?></td>
        </tr>
        <tr>
            <th>値段</th>
            <td><?=h($_POST['price'])?></td>
        </tr>
        <tr>
            <th>表示順</th>
            <td><?=h($_POST['turn'])?></td>
        </tr>
        <tr>
            <th>説明文</th>
            <td><?=nl2br(h($_POST['description']))?></td>
        </tr>
    </table>
    <div class="admin_edit_box">
        <form action="product_edit.php?type=<?=h($_GET['type'])?><?=!empty($_GET['id']) ? '&id=' . h($_GET['id']) : '';?>" class="conf_form" method="post">
            <p class="admin_edit_form_submit admin_edit_form_submit_fix"><input type="submit" name="fix_btn" value="修正"></p>
            <input type="hidden" name="name" value="<?=h($_POST['name'])?>">
            <input type="hidden" name="price" value="<?=h($_POST['price'])?>">
            <input type="hidden" name="turn" value="<?=h($_POST['turn'])?>">
            <input type="hidden" name="description" value="<?=h($_POST['description'])?>">
        </form>
        <form action="product_done.php?type=<?=h($_GET['type'])?><?=!empty($_GET['id']) ? '&id=' . h($_GET['id']) : '';?>" class="conf_form" method="post">
            <p class="admin_edit_form_submit"><input type="submit" value="<?=TYPE[$_GET['type']]?>完了"></p>
            <input type="hidden" name="name" value="<?=h($_POST['name'])?>">
            <input type="hidden" name="price" value="<?=h($_POST['price'])?>">
            <input type="hidden" name="turn" value="<?=h($_POST['turn'])?>">
            <input type="hidden" name="description" value="<?=h($_POST['description'])?>">
        </form>
        <div class="admin_edit_box_2"></div>
    </div>
</section>
<?php require_once('footer.php') ?>