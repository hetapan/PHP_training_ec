<?php
require_once('../class/Model.php');
require_once('../common/util.inc.php');

session_start();
// ログインセッションの確認を行う
$adminAuth = new AdminAuth;
$adminAuth->checkLoginSession();

// Productクラスを生成しておく
$product = new Product;

// 空の配列を用意しておく
$item = [];

try {
    if ($_GET['type'] == 'edit') { //商品編集ならば
        // 編集商品の取得を行う
        $item = $product->getProduct($_GET['id']);
    }
        // 配列をマージする
        $item = $_POST + $item;

    if (!empty($_FILES['file'])) { //ファイル送信があれば
        // 画像のフォルダ格納と商品名のデータベース保存を行う
        $file_success = $product->uploadProductFile($_FILES['file'], $_GET['id']);
        $item['img'] = $file_success['name'];
    };

} catch (PDOException $e) {
    $db_error = $e->getMessage();
} catch (Exception $e) {
    $file_error = $e->getMessage();
}
?>
<?php require_once('header.php') ?>
<section class="admin_panel">
    <?php getPage(); ?>
    <?php if (isset($db_error)) : ?>
        <p class="error"><?=$db_error?></p>
    <?php else : ?>
        <form action="product_conf.php?type=<?=h($_GET['type'])?><?=!empty($_GET['id']) ? '&id=' . h($_GET['id']) : '';?>" method="post">
            <table class="admin_table_edit">
                <tr>
                    <th>商品名</th>
                    <td>
                        <input type="text" name="name" class="" value="<?=empty($item['name']) ?  '' : h($item['name']);?>">
                    </td>
                </tr>
                <tr>
                    <th>値段</th>
                    <td>
                        <input type="text" name="price" value="<?=empty($item['price']) ?  '' : h($item['price']);?>">
                    </td>
                </tr>
                <tr>
                    <th>表示順</th>
                    <td>
                        <input type="text" name="turn" value="<?=empty($item['turn']) ?  '' : h($item['turn']);?>">
                    </td>
                </tr>
                <tr>
                    <th>説明文</th>
                    <td>
                        <textarea name="description"><?=empty($item['description']) ?  '' : h($item['description']);?></textarea>
                    </td>
                </tr>
            </table>
            <p class="admin_edit_form_submit"><input type="submit" name="edit_submit" value="確認画面へ"></p>
        </form>
        <?php if ($_GET['type'] == 'edit') : ?>
            <hr class="table_divide"></hr>
            <form action="" method="post" enctype="multipart/form-data">
                <?php if (!empty($file_success['message'])) : ?>
                    <p class="file_success"><?=$file_success['message']?></p>
                <?php elseif (!empty($file_error)) : ?>
                    <p class="file_error error"><?=$file_error?></p>
                <?php endif; ?>
                <table class="admin_table_edit_file">
                    <tr>
                        <th>サムネイル</th>
                        <td><input type="file" name="file"></td>
                    </tr>
                    <tr>
                        <th>トップページサムネイル</th>
                        <td>
                            <?php if (!empty($item['img'])) : ?>
                                <img src="<?=IMGS_PATH . h($item['img'])?>">
                                <p><?=h($item['img'])?></p>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
                <p class="admin_edti_form_notes">半角英数字のファイルのみアップ可能です。</p>
                <p class="admin_edit_form_submit"><input type="submit" name="upload" value="アップロード" onclick="return uploadConfirm()"></p>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</section>
<?php require_once('footer.php') ?>