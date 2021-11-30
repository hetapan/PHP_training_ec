<?php
require_once('class/Model.php');
require_once('common/const.php');
require_once('common/util.inc.php');

session_start();

// Userクラスのインスタンス生成をしておく
$user = new User;

// トークンのチェックを行う
$purchase = new Purchase;
$purchase->checkPurchaseToken();

// バリデーションチェック処理を行う
$validation = new Validation;
$error = $validation->purchaseValidate();

try {
    // ユーザー情報を取得する
    $user = $user->getUser($_SESSION['user_id']);

    // カート内の合計を計算する
    $cart_total = $purchase->calculateCart();

    // カート内の商品リストを取得する
    $cart_products = $purchase->getCartList();

} catch (PDOException $e) {
    $db_error = 'システムエラーのため商品の購入ができません<br>もう一度お試しいただくか、03-5912-6155にお問い合わせください';
}

if (!empty($error) || !empty($db_error)) {
    require_once('purchase_edit.php');
    exit;
}
?>
<?php require_once('header.php') ?>
<section class="purchase_edit">
    <div class="purchase_edit_panel_1">
        <h2 class="purchase_title">ご注文内容確認</h2>
        <div class="purchase_edit_panel_2">
            <h3 class="purchase_subtitle">ご注文商品</h3>
            <div class="purchase_edit_panel_3">
                <table class="cart_conf_table">
                    <tr>
                        <th>商品画像</th>
                        <th>商品名/サイズ</th>
                        <th>個数</th>
                        <th>単価</th>
                        <th>小計</th>
                    </tr>
                    <?php foreach ($cart_products as $cart_product) : ?>
                        <tr>
                            <td>
                                <img class="item_img" src="<?=IMGS_PATH_TOP?><?=!empty($cart_product['img']) ? h($cart_product['img']) : 'no_image.jpg';?>">
                            </td>
                            <td><?=$cart_product['name']?></td>
                            <td><?=h($cart_product['num'])?>個</td>
                            <td><?=number_format($cart_product['price'])?>円</td>
                            <td><?=number_format($cart_product['total'])?>円</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <th colspan="2">小計</th>
                        <td><?=$cart_total['total_num']?>個</td>
                        <td></td>
                        <td><?=number_format($cart_total['subtotal_price'])?>円</td>
                    </tr>
                    <tr>
                        <th colspan="4">送料</th>
                        <td><?=number_format($cart_total['cost'])?>円</td>
                    </tr>
                    <tr>
                        <th colspan="4">総合計</th>
                        <td><?=number_format($cart_total['total_price'])?>円</td>
                    </tr>
                </table>
            </div>
            <h3 class="purchase_subtitle">送付先情報</h3>
            <div class="purchase_edit_panel_3">
                <?php if ($_POST['modify'] == 2) : ?>
                    <table>
                        <tr>
                            <th>郵便番号</th>
                            <td>
                                <?=h($_POST['postal_code1'])?> - <?=h($_POST['postal_code2'])?>
                            </td>
                        </tr>
                        <tr>
                            <th>住所</th>
                            <td>
                                <?=PREF[$_POST['pref']]?> <?=h($_POST['city'])?> <?=h($_POST['address'])?> <?=h($_POST['other'])?>
                            </td>
                        </tr>
                        <tr>
                            <th>電話番号</th>
                            <td>
                                <?=h($_POST['tel1'])?> - <?=h($_POST['tel2'])?> - <?=h($_POST['tel3'])?>
                            </td>
                        </tr>
                        <tr>
                            <th>お名前</th>
                            <td>
                                <?=h($_POST['name'])?>
                            </td>
                        </tr>
                        <tr>
                            <th>お名前(カナ)</th>
                            <td>
                                <?=h($_POST['name_kana'])?>
                            </td>
                        </tr>
                    </table>
                <?php else : ?>
                    <p class="purchase_edit_panel_text">※請求先と同じ</p>
                <?php endif; ?>
            </div>
            <h3 class="purchase_subtitle">請求先情報</h3>
            <div class="purchase_edit_panel_3">
                <table>
                    <tr>
                        <th>郵便番号</th>
                        <td><?=h($user['postal_code1'])?> - <?=h($user['postal_code2'])?>
                        </td>
                    </tr>
                    <tr>
                        <th>住所</th>
                        <td><?=PREF[$user['pref']]?> <?=h($user['city'])?> <?=h($user['address'])?> <?=h($user['other'])?></td>
                    </tr>
                    <tr>
                        <th>電話番号</th>
                        <td><?=h($user['tel1'])?> - <?=h($user['tel2'])?> - <?=h($user['tel3'])?></td>
                    </tr>
                    <tr>
                        <th>メールアドレス</th>
                        <td><?=h($user['mail'])?></td>
                    </tr>
                    <tr>
                        <th>お名前</th>
                        <td><?=h($user['name'])?></td>
                    </tr>
                    <tr>
                        <th>お名前(カナ)</th>
                        <td><?=h($user['name_kana'])?></td>
                    </tr>
                </table>
            </div>
            <h3 class="purchase_subtitle">支払方法</h3>
            <div class="purchase_edit_panel_3">
                <table>
                    <tr>
                        <th>支払方法</th>
                        <td>
                            <?=PAYMENT[$_POST['payment']]?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <form action="purchase_done.php" method="post">
            <p class="purchase_edit_submit"><input type="submit" value="購入する"></p>
            <input type="hidden" name="modify" value="<?=h($_POST['modify'])?>">
            <input type="hidden" name="postal_code1" value="<?=h($_POST['postal_code1'])?>">
            <input type="hidden" name="postal_code2" value="<?=h($_POST['postal_code2'])?>">
            <input type="hidden" name="pref" value="<?=h($_POST['pref'])?>">
            <input type="hidden" name="city" value="<?=h($_POST['city'])?>">
            <input type="hidden" name="address" value="<?=h($_POST['address'])?>">
            <input type="hidden" name="other" value="<?=h($_POST['other'])?>">
            <input type="hidden" name="tel1" value="<?=h($_POST['tel1'])?>">
            <input type="hidden" name="tel2" value="<?=h($_POST['tel2'])?>">
            <input type="hidden" name="tel3" value="<?=h($_POST['tel3'])?>">
            <input type="hidden" name="name" value="<?=h($_POST['name'])?>">
            <input type="hidden" name="name_kana" value="<?=h($_POST['name_kana'])?>">
            <input type="hidden" name="payment" value="<?=h($_POST['payment'])?>">
            <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
        </form>
        <form action="purchase_edit.php" method="post">
            <p class="purchase_edit_cancel"><input type="submit" value="修正する"></p>
            <input type="hidden" name="modify" value="<?=h($_POST['modify'])?>">
            <input type="hidden" name="postal_code1" value="<?=h($_POST['postal_code1'])?>">
            <input type="hidden" name="postal_code2" value="<?=h($_POST['postal_code2'])?>">
            <input type="hidden" name="pref" value="<?=h($_POST['pref'])?>">
            <input type="hidden" name="city" value="<?=h($_POST['city'])?>">
            <input type="hidden" name="address" value="<?=h($_POST['address'])?>">
            <input type="hidden" name="other" value="<?=h($_POST['other'])?>">
            <input type="hidden" name="tel1" value="<?=h($_POST['tel1'])?>">
            <input type="hidden" name="tel2" value="<?=h($_POST['tel2'])?>">
            <input type="hidden" name="tel3" value="<?=h($_POST['tel3'])?>">
            <input type="hidden" name="name" value="<?=h($_POST['name'])?>">
            <input type="hidden" name="name_kana" value="<?=h($_POST['name_kana'])?>">
            <input type="hidden" name="payment" value="<?=h($_POST['payment'])?>">
        </form>
    </div>
</section>
<?php require_once('footer.php') ?>
