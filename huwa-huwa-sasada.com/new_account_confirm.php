<?php
require_once('class/Model.php');
require_once('common/const.php');
require_once('common/util.inc.php');

session_start();

// Userクラスのトークンチェックを行う
$user = new User;
$user->checkRegisterToken();

try {
    // Validationクラスのインスタンス生成、バリデーションチェック処理を行う
    $validation = new Validation;
    $error = $validation->registerValidate();

} catch (PDOException $e) {
    $db_error = 'システムエラーのため会員登録できません';
}

if (!empty($error) || !empty($db_error)){
    require_once('new_account_edit.php');
    exit;
}
?>
<?php require_once('header.php'); ?>
<section class="register">
    <h2 class="register_title">入力内容の確認</h2>
    <p class="register_txt">入力内容に問題なければ、送信するボタンを押してください。</p>
    <div class="register_form_panel">
        <h2 class="register_form_title">会員情報入力</h2>
        <table>
            <tr>
                <th>ログインID</th>
                <td><?=h($_POST['login_id'])?></td>
            </tr>
            <tr>
                <th>ログインパスワード</th>
                <td><?=str_repeat('*', mb_strlen($_POST['login_pass'], 'UTF8'))?></td>
            </tr>
            <tr>
                <th>ユーザ名</th>
                <td><?=h($_POST['name'])?></td>
            </tr>
            <tr>
                <th>ユーザ名カナ</th>
                <td><?=h($_POST['name_kana'])?></td>
            </tr>
            <tr>
                <th>誕生日</th>
                <td>
                    <?=$_POST['birth_year'] != '' ? BIRTH_YEAR[$_POST['birth_year']] : ' ';?> /
                    <?=$_POST['birth_month'] != '' ? BIRTH_MONTH[$_POST['birth_month']] : ' ';?> /
                    <?=$_POST['birth_day'] != '' ? BIRTH_DAY[$_POST['birth_day']] : ' ';?>
                </td>
            </tr>
            <tr>
                <th>性別</th>
                <td><?=GENDER[$_POST['gender']]?>
                </td>
            </tr>
            <tr>
                <th>メールアドレス</th>
                <td><?=h($_POST['mail'])?></td>
            </tr>
            <tr>
                <th>電話番号</th>
                <td>
                    <?=h($_POST['tel1'])?> -
                    <?=h($_POST['tel2'])?> -
                    <?=h($_POST['tel3'])?>
                </td>
            </tr>
            <tr>
                <th>郵便番号</th>
                <td>
                    <?=h($_POST['postal_code1'])?> -
                    <?=h($_POST['postal_code2'])?>
                </td>
            </tr>
            <tr>
                <th>都道府県</th>
                <td><?=$_POST['pref'] != '' ? PREF[$_POST['pref']] : '';?></td>
            </tr>
            <tr>
                <th>市区町村</th>
                <td><?=h($_POST['city'])?></td>
            </tr>
            <tr>
                <th>番地</th>
                <td><?=h($_POST['address'])?></td>
            </tr>
            <tr>
                <th>マンション名等</th>
                <td><?=h($_POST['other'])?></td>
            </tr>
            <tr>
                <th>備考</th>
                <td><?=nl2br(h($_POST['memo']))?></td>
            </tr>
        </table>
        <form action="./new_account_done.php" method="post">
            <p class="register_form_submit"><input type="submit" value="送信する"></p>
            <input type="hidden" name="login_id" value="<?=h($_POST['login_id'])?>">
            <input type="hidden" name="login_pass" value="<?=h($_POST['login_pass'])?>">
            <input type="hidden" name="name" value="<?=h($_POST['name'])?>">
            <input type="hidden" name="name_kana" value="<?=h($_POST['name_kana'])?>">
            <input type="hidden" name="birth_year" value="<?=h($_POST['birth_year'])?>">
            <input type="hidden" name="birth_month" value="<?=h($_POST['birth_month'])?>">
            <input type="hidden" name="birth_day" value="<?=h($_POST['birth_day'])?>">
            <input type="hidden" name="gender" value="<?=h($_POST['gender'])?>">
            <input type="hidden" name="mail" value="<?=h($_POST['mail'])?>">
            <input type="hidden" name="mail_confirm" value="<?=h($_POST['mail_confirm'])?>">
            <input type="hidden" name="tel1" value="<?=h($_POST['tel1'])?>">
            <input type="hidden" name="tel2" value="<?=h($_POST['tel2'])?>">
            <input type="hidden" name="tel3" value="<?=h($_POST['tel3'])?>">
            <input type="hidden" name="postal_code1" value="<?=h($_POST['postal_code1'])?>">
            <input type="hidden" name="postal_code2" value="<?=h($_POST['postal_code2'])?>">
            <input type="hidden" name="pref" value="<?=h($_POST['pref'])?>">
            <input type="hidden" name="city" value="<?=h($_POST['city'])?>">
            <input type="hidden" name="address" value="<?=h($_POST['address'])?>">
            <input type="hidden" name="other" value="<?=h($_POST['other'])?>">
            <input type="hidden" name="memo" value="<?=h($_POST['memo'])?>">
            <input type="hidden" name="token" value="<?=$_POST['token']?>">
        </form>
        <form action="./new_account_edit.php" method="post">
            <p class="register_form_submit cancel_btn"><input type="submit" value="修正する"></p>
            <input type="hidden" name="login_id" value="<?=h($_POST['login_id'])?>">
            <input type="hidden" name="login_pass" value="<?=h($_POST['login_pass'])?>">
            <input type="hidden" name="name" value="<?=h($_POST['name'])?>">
            <input type="hidden" name="name_kana" value="<?=h($_POST['name_kana'])?>">
            <input type="hidden" name="birth_year" value="<?=h($_POST['birth_year'])?>">
            <input type="hidden" name="birth_month" value="<?=h($_POST['birth_month'])?>">
            <input type="hidden" name="birth_day" value="<?=h($_POST['birth_day'])?>">
            <input type="hidden" name="gender" value="<?=h($_POST['gender'])?>">
            <input type="hidden" name="mail" value="<?=h($_POST['mail'])?>">
            <input type="hidden" name="mail_confirm" value="<?=h($_POST['mail_confirm'])?>">
            <input type="hidden" name="tel1" value="<?=h($_POST['tel1'])?>">
            <input type="hidden" name="tel2" value="<?=h($_POST['tel2'])?>">
            <input type="hidden" name="tel3" value="<?=h($_POST['tel3'])?>">
            <input type="hidden" name="postal_code1" value="<?=h($_POST['postal_code1'])?>">
            <input type="hidden" name="postal_code2" value="<?=h($_POST['postal_code2'])?>">
            <input type="hidden" name="pref" value="<?=h($_POST['pref'])?>">
            <input type="hidden" name="city" value="<?=h($_POST['city'])?>">
            <input type="hidden" name="address" value="<?=h($_POST['address'])?>">
            <input type="hidden" name="other" value="<?=h($_POST['other'])?>">
            <input type="hidden" name="memo" value="<?=h($_POST['memo'])?>">
        </form>
    </div>
</section>
<?php require_once('footer.php') ?>