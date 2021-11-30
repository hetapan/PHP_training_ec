<?php
require_once('class/Model.php');
require_once('common/util.inc.php');

session_start();

// Authクラスのインスタンス生成をしておく
$user = new User;

// トークンチェックを行う
$user->checkRegisterToken();
unset($_SESSION['token']);

try {
    // 会員登録処理を行う
    $user->registerUser();

    // メール送信処理
    $user->sendRegisterMail();

} catch (PDOException $e) {
    $db_error = 'システムエラーが発生しました。<br>もう一度お試しいただくか、03-5912-6155にお問い合わせください。';
} catch (Exception $e) {
    $mail_error = 'メールの送信に失敗しましたが、会員登録は完了しました。<br>メールの再送を希望する方は、03-5912-6155にお問い合わせください。';
}
?>
<?php require_once('header.php') ?>
<section class="register_conf">
    <div class="register_conf_panel">
        <?php if (!empty($db_error)) : ?>
            <h3 class="register_conf_error"><?=$db_error?></h3>
            <a href="./new_account_edit.php" class="register_conf_btn">入力画面に戻る</a>
        <?php else : ?>
            <h2 class="register_conf_title">会員登録完了</h2>
            <h3 class="register_conf_txt1">会員登録ありがとうございます。</h3>
            <?php if (!empty($mail_error)) : ?>
                <h3 class="register_conf_error"><?=$mail_error?></h3>
            <?php else : ?>
                <p class="register_conf_txt2">ご登録のメールアドレスへご確認のメールをお送り致しました。<br>ご登録内容が正しいかご確認ください。</p>
                <p class="register_conf_txt2">※万が一メールが届かない場合は03-5912-6155にお問い合わせください</p>
            <?php endif; ?>
            <a href="./my_page.php" class="register_conf_btn">マイページへ</a>
        <?php endif; ?>
    </div>
</section>
<?php require_once('footer.php') ?>