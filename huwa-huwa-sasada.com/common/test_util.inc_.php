<?php

/**
 * XSS対策の参照名省略
 *
 * @param string string
 * @return string
 */
function h(?string $string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * メール送信処理
 *
 * @return void
 */
function mailSend()
{
    $birth_year = !empty($_POST['birth_year']) ? BIRTH_YEAR[$_POST['birth_year']] : '';
    $birth_month = !empty($_POST['birth_month']) ? BIRTH_MONTH[$_POST['birth_month']] : '';
    $birth_day = !empty($_POST['birth_day']) ? BIRTH_DAY[$_POST['birth_day']] : '';
    $gender = GENDER[$_POST['gender']];
    $pref = !empty($_POST['pref']) ? PREF[$_POST['pref']] : '';

    $transport = new Swift_SmtpTransport(
        SMTP_HOST,
        SMTP_PORT,
        SMTP_PROTOCOL
    );

    $transport->setUsername(GMAIL_SITE);
    $transport->setPassword(GMAIL_APPPASS);
    $mailer = new Swift_Mailer($transport);

    $message = new Swift_Message(MAIL_TITLE);
    $message->setFrom(MAIL_FROM);
    $message->setTo($_POST['mail']);

    $mailBody = <<<EOT
━━━━━━━━━━━━━━━━━━━━━━
会員登録完了のお知らせ
━━━━━━━━━━━━━━━━━━━━━━

この度はご登録いただき誠にありがとうございました。
送信内容を以下の内容で承りました。

------------------------------
ログインID：{$_POST['login_id']}
ユーザ名：{$_POST['name']}
ユーザ名カナ：{$_POST['name_kana']}
誕生年：{$birth_year}
誕生月：{$birth_month}
誕生日：{$birth_day}
性別：{$gender}
メールアドレス：{$_POST['mail']}
電話番号：{$_POST['tel1']} - {$_POST['tel2']} - {$_POST['tel3']}
郵便番号：{$_POST['postal_code1']} - {$_POST['postal_code1']}
都道府県：{$pref}
市区町村：{$_POST['city']}
番地：{$_POST['address']}
マンション名等：{$_POST['other']}
備考：
{$_POST['memo']}
-----------------------------


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
湘和株式会社　芹沢 瑠
〒176-0012 東京都練馬区豊玉北2-22-15
お問い合わせ：03-5912-6155（土日、祝祭日を除く平日10:00-17:00）
WEB：https://extremesites.tokyo/training/huwa-huwa-sasada.com/
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
EOT;
    $message->setBody($mailBody, 'text/html');
    $mailer->send($message);
}
