<?php
require_once('common/const.php');
require_once('common/util.inc.php');

//重複呼び出し防止
if (empty($_SESSION['token'])) {
    session_start();
};

// トークンを発行
$_SESSION['token'] = hash('sha256', md5(uniqid(mt_rand(), true)));
?>
<?php require_once('header.php') ?>
<section class="register">
    <h2 class="register_title">新規会員登録 入力画面</h2>
    <?php if (!empty($error)) : ?>
        <p class="register_txt error">エラー内容を確認してください。</p>
    <?php elseif(!empty($db_error)) : ?>
        <p class="register_txt error"><?=$db_error?></p>
    <?php else : ?>
        <p class="register_txt">お客様情報を入力して会員登録を完了してください。</p>
    <?php endif; ?>
    <div class="register_form_panel">
        <h2 class="register_form_title">会員情報入力</h2>
        <form action="./new_account_confirm.php" method="post">
            <table>
                <tr>
                    <th>ログインID<span>必須</span></th>
                    <td>
                        <input type="text" name="login_id" value="<?=!empty($_POST['login_id']) ? h($_POST['login_id']) : '';?>" placeholder="例： je28v75c">
                        <?php if (!empty($error['login_id'])) : ?>
                            <span><?=$error['login_id']?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>ログインパスワード<span>必須</span></th>
                    <td>
                        <input type="password" name="login_pass" value="<?=!empty($_POST['login_pass']) ? h($_POST['login_pass']) : '';?>" placeholder="例： Ac53oxbd3">
                        <?php if (!empty($error['login_pass'])) : ?>
                            <span><?=$error['login_pass']?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>ユーザ名<span>必須</span></th>
                    <td>
                        <input type="text" name="name" value="<?=!empty($_POST['name']) ? h($_POST['name']) : '';?>" placeholder="例： 田中太郎">
                        <?php if (!empty($error['name'])) : ?>
                            <span><?=$error['name']?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>ユーザ名カナ<span>必須</span></th>
                    <td>
                        <input type="text" name="name_kana" value="<?=!empty($_POST['name_kana']) ? h($_POST['name_kana']) : '';?>" placeholder="例： タナカタロウ">
                        <?php if (!empty($error['name_kana'])) : ?>
                            <span><?=$error['name_kana']?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>誕生日</th>
                    <td>
                        <select name="birth_year">
                            <option value="">----</option>
                            <?php foreach (BIRTH_YEAR as  $key => $birth_year) : ?>
                                <option value="<?=$key?>"<?=!empty($_POST['birth_year']) && $_POST['birth_year'] == $key ? ' selected'  : '';?>>
                                    <?=$birth_year?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select name="birth_month">
                            <option value="">--</option>
                            <?php foreach (BIRTH_MONTH as $key => $birth_month) : ?>
                                <option value="<?=$key?>"<?=!empty($_POST['birth_month']) && $_POST['birth_month'] == $key ? ' selected' : '';?>>
                                    <?=$birth_month?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select name="birth_day">
                            <option value="">--</option>
                            <?php foreach (BIRTH_DAY as $key => $birth_day) : ?>
                                <option value="<?=$key?>"<?=!empty($_POST['birth_day']) && $_POST['birth_day'] == $key ? ' selected' : '';?>>
                                    <?=$birth_day?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>性別<span>必須</span></th>
                    <td>
                        <?php foreach (GENDER as $key => $gender) : ?>
                            <label>
                                <input type="radio" name="gender" value=<?=$key?>
                                <?=(!empty($_POST['gender']) && $_POST['gender'] == $key ) || (empty($_POST['gender']) && $key == 1) ? ' checked' : '';?>>
                                <?=$gender?>
                            </label>
                        <?php endforeach; ?>
                        <?php if (!empty($error['gender'])) : ?>
                            <span><?=$error['gender']?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>メールアドレス<span>必須</span></th>
                    <td>
                        <input type="text" name="mail" value="<?=!empty($_POST['mail']) ? h($_POST['mail']) : '';?>" placeholder="例： xxx@xxx.com">
                        <?php if (!empty($error['mail'])) : ?>
                            <span><?=$error['mail']?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>メールアドレス確認<span>必須</span></th>
                    <td>
                        <input type="text" name="mail_confirm" value="" placeholder="例： xxx@xxx.com">
                        <?php if (!empty($error['mail_confirm'])) : ?>
                            <span><?=$error['mail_confirm']?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>電話番号<span>必須</span></th>
                    <td>
                        <p id="register_form_tel1">
                            <input type="text" name="tel1" value="<?=!empty($_POST['tel1']) ? h($_POST['tel1']) : '';?>"  maxlength="5" placeholder="000"> -
                            <input type="text" name="tel2" value="<?=!empty($_POST['tel2']) ? h($_POST['tel2']) : '';?>"  maxlength="5" placeholder="0000"> -
                            <input type="text" name="tel3" value="<?=!empty($_POST['tel3']) ? h($_POST['tel3']) : '';?>"  maxlength="5" placeholder="0000">
                        </p>
                        <?php if (!empty($error['tel'])) : ?>
                            <span><?=$error['tel']?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>郵便番号<span>必須</span></th>
                    <td>
                        <p id="register_form_post1">
                            <input type="text" name="postal_code1" value="<?=!empty($_POST['postal_code1']) ? h($_POST['postal_code1']) : '';?>" maxlength="3" placeholder="000"> -
                            <input type="text" name="postal_code2" value="<?=!empty($_POST['postal_code2']) ? h($_POST['postal_code2']) : '';?>" maxlength="4" placeholder="0000">
                        </p>
                        <?php if (!empty($error['postal_code'])) : ?>
                            <span><?=$error['postal_code']?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>都道府県<span>必須</span></th>
                    <td>
                        <select name="pref">
                            <?php foreach (PREF as $key => $pref) : ?>
                                <option value="<?=$key?>"<?=!empty($_POST['pref']) && $_POST['pref'] == $key ? ' selected' : '';?>>
                                    <?=$pref?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($error['pref'])) : ?>
                            <span><?=$error['pref']?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>市区町村<span>必須</span></th>
                    <td>
                        <input type="text" name="city" class="address" value="<?=!empty($_POST['city']) ? h($_POST['city']) : '';?>" placeholder="例： 千代田区">
                        <?php if (!empty($error['city'])) : ?>
                            <span><?=$error['city']?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>番地<span>必須</span></th>
                    <td>
                        <input type="text" name="address" class="address" value="<?=!empty($_POST['address']) ? h($_POST['address']) : '';?>" placeholder="例： 五番町〇-〇">
                        <?php if (!empty($error['address'])) : ?>
                            <span><?=$error['address']?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>マンション名等</th>
                    <td><input type="text" name="other" class="address" value="<?=!empty($_POST['other']) ? h($_POST['other']) : '';?>" placeholder="例： 〇〇マンション101号室"></td>
                </tr>
                <tr>
                    <th>備考</th>
                    <td>
                        <textarea name="memo" placeholder="他にお伝えしたい内容がございましたらご入力ください。"><?=!empty($_POST['memo']) ? h($_POST['memo']) : '';?></textarea>
                        <?php if (!empty($error['memo'])) : ?>
                            <span><?=$error['memo']?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
            <p class="register_form_submit"><input type="submit" name="confirm_btn" value="確認する"></p>
            <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
        </form>
    </div>
</section>
<?php require_once('footer.php') ?>