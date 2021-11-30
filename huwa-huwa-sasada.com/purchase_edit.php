<?php
require_once('class/Model.php');
require_once('common/const.php');
require_once('common/util.inc.php');

// 重複呼び出し防止
if (empty($_SESSION['token'])) {
    session_start();
}

// ログインセッションの確認を行う
$auth = new Auth;
$auth->checkLoginSession();

try {
    // カート商品の存在確認を行う
    $purchase = new Purchase;
    if (!$purchase->checkCart()) { // カートに商品がなければ
        // カート画面に遷移
        header('location:cart.php');
        exit;
    }

    //ユーザー情報を取得する
    $user = new User;
    $user = $user->getUser($_SESSION['user_id']);
    // 配列をマージする
    $user_marge = $_POST + $user;

} catch (PDOException $e) {
    $db_error = 'システムエラーのため商品の購入ができません<br>もう一度お試しいただくか、03-5912-6155にお問い合わせください';
}

// トークンを発行
$_SESSION['token'] = hash('sha256', md5(uniqid(mt_rand(), true)));
?>
<?php require_once('header.php') ?>
<section class="purchase_edit">
    <?php if (!empty($db_error)) : ?>
        <p class="purchase_txt error"><?=$db_error?></p>
        <p class="purchase_edit_btn"><a href="./cart.php">カートに戻る</a></p>
    <?php else : ?>
        <?php if (!empty($error)) : ?>
            <p class="purchase_txt error">エラー内容を確認してください。</p>
        <?php endif; ?>
        <div class="purchase_edit_panel_1">
            <h2 class="purchase_title">ご注文情報入力</h2>
            <form action="purchase_conf.php" method="post">
                <div class="purchase_edit_panel_2">
                    <h3 class="purchase_subtitle purchase_subtitle_1">送付先情報</h3>
                    <?php foreach (MODIFY as $key => $modify) : ?>
                        <label class="modify_radio">
                            <input type="radio" name="modify" value="<?=$key?>" onchange="modifyUserInfo()"
                            <?=(isset($user_marge['modify']) && $user_marge['modify'] == $key) || (!isset($user_marge['modify']) && $key == 1) ? ' checked' : '';?>>
                            <?=$modify?>
                        </label>
                    <?php endforeach; ?>
                    <div id="purchase_edit_panel_target" class="purchase_edit_panel_3">
                        <table>
                            <tr>
                                <th>郵便番号<span>必須</span></th>
                                <td>
                                    <p id="purchase_edit_form_post1">
                                        <input type="text" id="input1" name="postal_code1" value="<?=h($user_marge['postal_code1'])?>" maxlength="3" placeholder="000"> -
                                        <input type="text" id="input2" name="postal_code2" value="<?=h($user_marge['postal_code2'])?>" maxlength="4" placeholder="0000">
                                    </p>
                                    <p class="address_btn"><input type="button" name="address_btn" value="住所検索" onclick="getAddress()"></p>
                                    <?php if (!empty($error['postal_code'])) : ?>
                                        <span><?=$error['postal_code']?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>住所<span>必須</span></th>
                                <td>
                                    <select id="address1" name="pref">
                                        <?php foreach (PREF as $key => $pref) : ?>
                                            <option value="<?=$key?>" <?=$key == $user_marge['pref'] ? ' selected' : '';?>>
                                                <?=$pref?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" id="address2" name="city" class="city" value="<?=h($user_marge['city'])?>" maxlength="15" placeholder="市区町村">
                                    <input type="text" id="address3" name="address" class="address" value="<?=h($user_marge['address'])?>" maxlength="100" placeholder="番地">
                                    <input type="text" name="other" class="other" value="<?=h($user_marge['other'])?>" maxlength="100" placeholder="建物名  ※空白可">
                                    <?php if (!empty($error['address'])) : ?>
                                        <span><?=$error['address']?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>電話番号<span>必須</span></th>
                                <td>
                                    <p id="purchase_edit_form_tel1">
                                        <input type="text" name="tel1" value="<?=h($user_marge['tel1'])?>" maxlength="5" placeholder="000"> -
                                        <input type="text" name="tel2" value="<?=h($user_marge['tel2'])?>" maxlength="5" placeholder="0000"> -
                                        <input type="text" name="tel3" value="<?=h($user_marge['tel3'])?>" maxlength="5" placeholder="0000">
                                    </p>
                                    <?php if (!empty($error['tel'])) : ?>
                                        <span><?=$error['tel']?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>お名前<span>必須</span></th>
                                <td>
                                    <input type="text" name="name" value="<?=h($user_marge['name']);?>" maxlength="15" placeholder="例： 田中太郎">
                                    <?php if (!empty($error['name'])) : ?>
                                        <span><?=$error['name']?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>お名前(カナ)<span>必須</span></th>
                                <td>
                                    <input type="text" name="name_kana" value="<?=h($user_marge['name_kana']);?>" maxlength="20" placeholder="例： タナカタロウ">
                                    <?php if (!empty($error['name_kana'])) : ?>
                                        <span><?=$error['name_kana']?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
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
                                <td><?=h($user['tel1'])?></td>
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
                                    <?php foreach (PAYMENT as $key => $payment) : ?>
                                        <label>
                                            <input type="radio" name="payment" value="<?=$key?>"
                                            <?=(isset($user_marge['payment']) && $user_marge['payment'] == $key) || (!isset($user_marge['payment']) && $key == 1) ? ' checked' : '';?>>
                                            <?=$payment?>
                                        </label>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <p class="purchase_edit_submit"><input type="submit" value="購入する"></p>
                <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
            </form>
        </div>
    <?php endif; ?>
</section>
<?php require_once('footer.php') ?>