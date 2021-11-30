<?php
require_once('../class/Model.php');
require_once('../common/util.inc.php');

session_start();

//AdminAuthクラスのインスタンス生成をしておく
$adminAuth = new AdminAuth;

if (!empty($_SESSION['admin_authenticated'])) { // 認証済みならば
    // ログアウト処理を行う
    $adminAuth->logout();
}

try {
    if (!empty($_POST['login_btn'])) {  //フォームからログインボタンが押下されれば
        if (($_POST['login_id'] === '' || mb_ereg_match('^(\s|　)+$', $_POST['login_id']))
            || ($_POST['login_pass'] === '' || mb_ereg_match('^(\s|　)+$', $_POST['login_pass']))
        ) {
            $error = 'IDかパスワードが入力されていません';
        } else { //バリデーションチェックに問題がなければ
            // ログイン処理を行い、エラーがあれば受け取る
            $error = $adminAuth->login($_POST['login_id'], $_POST['login_pass']);
        }
    }
} catch (PDOException $e) {
    $error = 'システムエラーが発生しました。';
}
?>
<?php require_once('header.php') ?>
<section class="admin_login_form">
    <h2 class="admin_login_title">HuwaHuwaのカシミアセーター　管理ログイン画面</h2>
    <?php if (isset($error)) : ?>
        <p class="admin_login_error"><?=$error?></p>
    <?php endif; ?>
    <div class="admin_login_form_panel">
        <form action="" method="post">
            <table>
                <tr>
                    <th>ログインID：</th>
                    <td><input type="text" class="admin_login_form_item" name="login_id" value="<?=!empty($_POST['login_id']) ? h($_POST['login_id']) : '';?>"></td>
                </tr>
                <tr>
                    <th>パスワード：</th>
                    <td><input type="password" class="admin_login_form_item" name="login_pass"></td>
                </tr>
            </table>
            <p class="admin_login_form_submit"><input type="submit" name="login_btn" value="認証"></p>
        </form>
    </div>
</section>
<?php require_once('footer.php') ?>