<?php
require_once('../class/Model.php');
require_once('../common/util.inc.php');

session_start();
// AdminAuthクラスのログインセッションの確認を行う
$adminAuth = new AdminAuth;
$adminAuth->checkLoginSession();

// Productクラスを生成しておく
$product = new Product;

try {
    if (!empty($_POST['delete'])) { //削除ボタンが押されたら
        // 商品の削除処理を行う
        $product->deleteProduct($_POST['id']);
    }

    // 商品の一覧を取得する
    $sort = !empty($_GET['sort']) ? $_GET['sort'] : '';
    $order = !empty($_GET['order']) ? $_GET['order'] : '';
    $products = $product->getProductList($sort, $order);

} catch (PDOException $e) {
    $error = 'システムエラーが発生しました。';
}
?>
<?php require_once('header.php') ?>
<section class="admin_panel">
    <?php getPage(); ?>
    <?php if(isset($error)) : ?>
        <p class="error"><?=$error?></p>
    <?php else : ?>
        <table class="admin_table_list">
            <form action="" method="post">
                <tr class="admin_table_list_header">
                    <th><a href="product_list.php?sort=id&order=ASC">▲</a>ID<a href="product_list.php?sort=id&order=DESC">▼</a></th>
                    <th>画像</th>
                    <th><a href="product_list.php?sort=name&order=ASC">▲</a>商品名<a href="product_list.php?sort=name&order=DESC">▼</a></th>
                    <th>表示順</th>
                    <th>登録日時</th>
                    <th><a href="product_list.php?sort=updated_at&order=ASC">▲</a>更新日時<a href="product_list.php?sort=updated_at&order=DESC">▼</a></th>
                    <th><a href="product_edit.php?type=new" class="admin_btn">新規登録</a></th>
                </tr>
            </form>
            <?php if (empty($products)) : ?>
                <tr>
                    <td colspan="7" class="product_list_txt">商品の登録がありません。</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($products as $product) : ?>
                <tr>
                    <td><?=$product['id']?></td>
                    <td><?=!empty($product['img']) ? '<img src="' . IMGS_PATH . h($product['img']) . '"' : '';?></td>
                    <td><?=h($product['name'])?></td>
                    <td><?=h($product['turn'])?></td>
                    <td><?=date('Y-m-d H:i:s', strtotime($product['created_at']));?></td>
                    <td><?=!empty($product['updated_at']) ? date('Y-m-d H:i:s', strtotime($product['updated_at'])) : '';?></td>
                    <td>
                        <a href="product_edit.php?type=edit&id=<?=$product['id']?>" class="admin_btn edit_btn">編集</a>
                        <form action="" method="post" class="delete_form">
                            <input type="hidden" name="id" value="<?=$product['id']?>">
                            <p><input type="submit" class="admin_btn" name="delete" value="削除" onclick="return deleteConfirm()"></p>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>
<?php require_once('footer.php') ?>