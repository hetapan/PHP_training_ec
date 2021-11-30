<?php
require_once('class/Model.php');
require_once('common/util.inc.php');

session_start();

//Authクラスのインスタンス生成をしておく
$auth = new Auth;

if (!empty($_SESSION['authenticated'])) { // 認証済みならば
    // Authクラスのログアウト処理を行う
    $auth->logout();
}

try {
    if (!empty($_POST['login_btn'])) {  //フォームからログインボタンが押下されれば
        if (($_POST['login_id'] === '' || mb_ereg_match('^(\s|　)+$', $_POST['login_id']))
            || ($_POST['login_pass'] === '' || mb_ereg_match('^(\s|　)+$', $_POST['login_pass']))
        ) {
            $error = 'IDかパスワードが入力されていません';
        } else { //バリデーションチェックに問題がなければ
            // Authクラスのログイン処理を行い、エラーがあれば受け取る
            $error = $auth->login($_POST['login_id'], $_POST['login_pass']);
        }
    }
} catch (PDOException $e) {
    $error = "システムエラーが発生しました。もう一度お試しいただくか、03-5912-6155にお問い合わせください";
}
?>
<?php require_once('header.php') ?>
<section class="login_form">
    <h2 class="login_title">ログイン画面</h2>
    <p class="login_txt">ログインIDとパスワードを入力してログインしてください。</p>
    <div class="login_form_panel">
        <?php if (isset($error)) : ?>
            <p class="error"><?=$error?></p>
        <?php endif; ?>
        <form action="" method="post">
            <label class="login_form_label">ログインID</label>
            <input type="text" class="login_form_item" name="login_id" value="<?=!empty($_POST['login_id']) ? h($_POST['login_id']) : '';?>">
            <label class="login_form_label">パスワード</label>
            <input type="password" class="login_form_item" name="login_pass">
            <input type="hidden" name="product_id" value="<?=!empty($_GET['product_id']) ? h($_GET['product_id']) : '';?>">
            <p class="login_form_submit"><input type="submit" name="login_btn" value="ログイン"></p>
        </form>
    </div>
</section>
<?php require_once('footer.php') ?>
