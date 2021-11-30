<?php
require_once('class/Model.php');
require_once('common/util.inc.php');

session_start();

// ログインセッションの確認を行う
$auth = new Auth;
$auth->checkLoginSession();

// Purchaseクラスのインスタンス生成をしておく
$purchase = new Purchase;

try {
    if (!empty($_POST['add_cart_btn']) || !empty($_POST['login_btn'])) { //購入するボタンが押下されたら
        //カートに商品追加処理をする
        $purchase->addCart($_POST['product_id']);
    }

    if (!empty($_POST['edit_cart_btn'])) { //個数変更ボタンが押下されたら
        if(!preg_match('/^[1-9]|[1-9][0-9]$/', $_POST['num'])){ //1以上99以下の数値でなければ
            $error = '個数は1～99の数値を入力してください。';
        } else {
            //商品個数の変更処理をする
            $purchase->editCart($_POST['id'], $_POST['num']);
        }
    }

    if (!empty($_POST['delete_cart_btn'])) { //削除ボタンが押下されたら
        //カートの商品の削除処理をする
        $purchase->deleteCart($_POST['id']);
    }

    //カート内の合計を計算する
    $cart_total = $purchase->calculateCart();

    //カート内の商品リストを取得する
    $cart_products = $purchase->getCartList();

} catch (PDOException $e) {
    $db_error = 'システムエラーが発生しました。<br>もう一度お試しいただくか、03-5912-6155にお問い合わせください';
}
?>
<?php require_once('header.php') ?>
<main class="cart">
    <aside class="cart_side">
        <?php if (!empty($cart_products) && empty($db_error)) : ?>
            <div class="cart_total_panel">
                <div class="cart_total_panel_2">
                    <table class="cart_total_table">
                        <tr>
                            <th colspan="2">合計金額</th>
                        </tr>
                        <tr>
                            <th>小計</th>
                            <td><?=number_format($cart_total['subtotal_price'])?>円</td>
                        </tr>
                        <tr>
                            <th>合計個数</th>
                            <td><?=$cart_total['total_num']?>個</td>
                        </tr>
                        <tr>
                            <th>送料</th>
                            <td><?=number_format($cart_total['cost'])?>円</td>
                        </tr>
                        <tr>
                            <th>合計</th>
                            <td><?=number_format($cart_total['total_price'])?>円</td>
                        </tr>
                    </table>
                </div>
                <form action="purchase_edit.php" method="post">
                    <p><input type="submit" name="cart_btn" class="cart_btn" value="レジに進む"></p>
                </form>
            </div>
        <?php endif; ?>
    </aside>
    <section class="cart_main">
        <div class="cart_panel">
            <h2 class="cart_title">カート</h2>
            <?php if (!empty($db_error)) : ?>
                <p class="error"><?=$db_error?></p>
            <?php elseif (!empty($cart_products)) : ?>
                <?php if (!empty($error)) : ?>
                    <p class="error"><?=$error?></p>
                <?php endif; ?>
                <table class="cart_table">
                    <tr>
                        <th>削除</th>
                        <th>商品画像</th>
                        <th>商品名/サイズ</th>
                        <th>個数</th>
                        <th>単価</th>
                        <th>小計</th>
                    </tr>
                    <?php foreach ($cart_products as $cart_product) : ?>
                        <tr>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="id" value="<?=$cart_product['id']?>">
                                    <p><input type="submit" name="delete_cart_btn" value="削除"></p>
                                </form>
                            </td>
                            <td>
                                <img class="item_img" src="<?=IMGS_PATH_TOP?><?=!empty($cart_product['img']) ? h($cart_product['img']) : 'no_image.jpg';?>">
                            </td>
                            <td><?=h($cart_product['name'])?></td>
                            <td>
                                <form action="" method="post">
                                    <input type="text" name="num" value="<?=h($cart_product['num'])?>">
                                    <input type="hidden" name="id" value="<?=$cart_product['id']?>">
                                    <p><input type="submit" name="edit_cart_btn" value="変更"></p>
                                </form>
                            </td>
                            <td><?=number_format($cart_product['price'])?>円</td>
                            <td><?=number_format($cart_product['total'])?>円</td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else : ?>
                <p>現在、カートの中身は空です。</p>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php require_once('footer.php') ?>