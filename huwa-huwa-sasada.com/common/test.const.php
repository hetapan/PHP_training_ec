<?php
$birth_year = [];
$birth_month = [];
$birth_day = [];
for ($i = 1900; $i <= 2021; $i++) {
    $birth_year += array($i => $i);
}
for ($i = 1; $i <= 12; $i++) {
    $birth_month += array($i => $i);
}
for ($i = 1; $i <= 31; $i++) {
    $birth_day += array($i => $i);
}

// フォーム定義
define('BIRTH_YEAR', $birth_year);
define('BIRTH_MONTH', $birth_month);
define('BIRTH_DAY', $birth_day);
define('GENDER', ['1' => '男性', '2' => '女性', '99' => '未回答']);
define('PREF', [
    '1' => '北海道', '2' => '青森県', '3' => '岩手県', '4' => '宮城県', '5' => '秋田県', '6' => '山形県', '7' => '福島県', '8' => '茨城県', '9' => '栃木県', '10' => '群馬県',
    '11' => '埼玉県', '12' => '千葉県', '13' => '東京都', '14' => '神奈川県', '15' => '山梨県', '16' => '新潟県', '17' => '富山県', '18' => '石川県', '19' => '福井県', '20' => '長野県',
    '21' => '岐阜県', '22' => '静岡県', '23' => '愛知県', '24' => '三重県', '25' => '滋賀県', '26' => '京都府', '27' => '大阪府', '28' => '兵庫県', '29' => '奈良県', '30' => '和歌山県',
    '31' => '鳥取県', '32' => '島根県', '33' => '岡山県', '34' => '広島県', '35' => '山口県', '36' => '徳島県', '37' => '香川県', '38' => '愛媛県', '39' => '高知県', '40' => '福岡県',
    '41' => '佐賀県', '42' => '長崎県', '43' => '熊本県', '44' => '大分県', '45' => '宮崎県', '46' => '鹿児島県', '47' => '沖縄県'
]);

// メール定義
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_PROTOCOL', 'tls');
define('GMAIL_SITE', 'l.piro.masa.0111@gmail.com');
define('GMAIL_APPPASS', 'giuswqmrczuxyscp');
define('MAIL_FROM', ['info@gmail.com' => 'HuwaHuwa']);
define('MAIL_TITLE', '[HuwaHuwa]会員登録完了のお知らせ');
