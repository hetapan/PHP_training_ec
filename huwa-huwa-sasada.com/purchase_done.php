<?php
require_once('class/Model.php');
require_once('common/util.inc.php');

session_start();

// Authクラスのインスタンスを生成をしておく
$user = new User;

// トークンのチェックを行う
$purchase = new Purchase;
$purchase->checkPurchaseToken();
unset($_SESSION['token']);

try {
    // 商品の購入処理を行う
    $purchase->purchaseProduct($_POST);

} catch (PDOException $e) {
    $db_error = '商品の購入に失敗しました。<br>もう一度お試しいただくか、03-5912-6155にお問い合わせください。';
} catch (Exception $e) {
    $mail_error = 'メールの送信に失敗しましたが、商品の購入は完了しました。<br>メールの再送を希望する方は、03-5912-6155にお問い合わせください。';
}
?>
<?php require_once('header.php') ?>
<section class="purchase_done">
    <div class="purchase_done_panel">
        <?php if (!empty($db_error)) : ?>
            <h3 class="purchase_done_error"><?=$db_error?></h3>
            <a href="purchase_edit.php" class="purchase_done_btn">ご注文情報入力画面に戻る</a>
        <?php else : ?>
            <h2 class="purchase_done_title">購入完了</h2>
            <h3 class="purchase_done_txt1">商品の購入が完了しました。<br>ご利用ありがとうございました。</h3>
            <?php if (!empty($mail_error)) : ?>
                <h3 class="purchase_done_error"><?=$mail_error?></h3>
            <?php else : ?>
                <p class="purchase_done_txt2">ご登録のメールアドレスへご確認のメールをお送り致しました。<br>ご注文内容が正しいかご確認ください。</p>
                <p class="purchase_done_txt2">※万が一メールが届かない場合は03-5912-6155にお問い合わせください</p>
            <?php endif; ?>
            <a href="./index.php" class="purchase_done_btn">トップページへ</a>
        <?php endif; ?>
    </div>
</section>
<?php require_once('footer.php') ?>