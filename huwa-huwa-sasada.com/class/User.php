<?php
class User extends Model
{
    /**
     * 新規会員登録処理
     *
     * @return void
     */
    public function registerUser()
    {
        try {
            $this->connect();
            $sql = 'INSERT INTO user '
                . '('
                    . 'login_id, '
                    . 'login_pass, '
                    . 'name, '
                    . 'name_kana, '
                    . 'birth_year, '
                    . 'birth_month, '
                    . 'birth_day, '
                    . 'gender, '
                    . 'mail, '
                    . 'tel1, '
                    . 'tel2, '
                    . 'tel3, '
                    . 'postal_code1, '
                    . 'postal_code2, '
                    . 'pref, '
                    . 'city, '
                    . 'address, '
                    . 'other, '
                    . 'memo, '
                    . 'status'
                . ')VALUES('
                    . ':login_id, '
                    . ':login_pass, '
                    . ':name, '
                    . ':name_kana, '
                    . ':birth_year, '
                    . ':birth_month, '
                    . ':birth_day, '
                    . ':gender, '
                    . ':mail, '
                    . ':tel1, '
                    . ':tel2, '
                    . ':tel3, '
                    . ':postal_code1, '
                    . ':postal_code2, '
                    . ':pref, '
                    . ':city, '
                    . ':address, '
                    . ':other, '
                    . ':memo, '
                    . '1'
                . ')'
            ;
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':login_id', $_POST['login_id'], PDO::PARAM_STR);
            $stmt->bindValue(':login_pass', $_POST['login_pass'], PDO::PARAM_STR);
            $stmt->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
            $stmt->bindValue(':name_kana', $_POST['name_kana'], PDO::PARAM_STR);
            $stmt->bindValue(':birth_year', (!empty($_POST['birth_year']) ? $_POST['birth_year'] : NULL), PDO::PARAM_STR);
            $stmt->bindValue(':birth_month', (!empty($_POST['birth_month']) ? $_POST['birth_month'] : NULL), PDO::PARAM_STR);
            $stmt->bindValue(':birth_day', (!empty($_POST['birth_day']) ? $_POST['birth_day'] : NULL), PDO::PARAM_STR);
            $stmt->bindValue(':gender', $_POST['gender'], PDO::PARAM_INT);
            $stmt->bindValue(':mail', $_POST['mail'], PDO::PARAM_STR);
            $stmt->bindValue(':tel1', $_POST['tel1'], PDO::PARAM_STR);
            $stmt->bindValue(':tel2', $_POST['tel2'], PDO::PARAM_STR);
            $stmt->bindValue(':tel3', $_POST['tel3'], PDO::PARAM_STR);
            $stmt->bindValue(':postal_code1', $_POST['postal_code1'], PDO::PARAM_STR);
            $stmt->bindValue(':postal_code2', $_POST['postal_code2'], PDO::PARAM_STR);
            $stmt->bindValue(':pref', $_POST['pref'], PDO::PARAM_INT);
            $stmt->bindValue(':city', $_POST['city'], PDO::PARAM_STR);
            $stmt->bindValue(':address', $_POST['address'], PDO::PARAM_STR);
            $stmt->bindValue(':other', (!empty($_POST['other']) ? $_POST['other'] : NULL), PDO::PARAM_STR);
            $stmt->bindValue(':memo', (!empty($_POST['memo']) ? $_POST['memo'] : NULL), PDO::PARAM_STR);
            $stmt->execute();

            //ログイン状態をセッションに保持
            $_SESSION['authenticated'] = 1;
            $_SESSION['name'] = $_POST['name'];
            $_SESSION['user_id'] = $this->dbh->lastInsertId();

        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * 新規登録フォームのトークンチェック
     *
     * @return void
     */
    public function checkRegisterToken()
    {
        if (empty($_POST['token']) || empty($_SESSION['token']) || $_POST['token'] != $_SESSION['token']) { //トークンが未発行、一致しなければ
            header('location:new_account_edit.php');
            exit;
        }
    }

    /**
     * メール送信処理
     *
     * @return bool
     */
    function sendRegisterMail()
    {
        try {
            $mail_content =
                '━━━━━━━━━━━━━━━━━━━━━━' . "\r\n"
                . '会員登録完了のお知らせ' . "\r\n"
                . '━━━━━━━━━━━━━━━━━━━━━━' . "\r\n\r\n"
                . 'HuwaHuwaのカシミアセーターオンラインショップです。' . "\r\n\r\n"
                . 'この度はご登録いただき誠にありがとうございました。' . "\r\n"
                . '送信内容を以下の内容で承りました。' . "\r\n\r\n"
                . '------------------------------' . "\r\n"
                . 'ログインID：' . h($_POST['login_id']) . "\r\n"
                . 'ユーザ名：' . h($_POST['name']) . "\r\n"
                . 'ユーザ名カナ：' . h($_POST['name_kana']) . "\r\n"
                . '誕生日：' . (empty($_POST['birth_year']) ? '' : BIRTH_YEAR[$_POST['birth_year']]) . ' / '
                . (empty($_POST['birth_month']) ? '' : BIRTH_MONTH[$_POST['birth_month']]) . ' / '
                . (empty($_POST['birth_day']) ? '' : BIRTH_DAY[$_POST['birth_day']]) . "\r\n"
                . '性別：' . GENDER[$_POST['gender']] . "\r\n"
                . 'メールアドレス：' . h($_POST['mail']) . "\r\n"
                . '電話番号：' . h($_POST['tel1']) . ' - ' . h($_POST['tel2']) . ' - ' . h($_POST['tel3']) . "\r\n"
                . '郵便番号：' . h($_POST['postal_code1']) . ' - ' . h($_POST['postal_code2'])  . "\r\n"
                . '都道府県：' . PREF[$_POST['pref']] . "\r\n"
                . '市区町村：' . h($_POST['city']) . "\r\n"
                . '番地：' . h($_POST['address']) . "\r\n"
                . 'マンション名等：' . h($_POST['other']) . "\r\n"
                . '備考：' . "\r\n"
                . h($_POST['memo']) . "\r\n"
                . '-----------------------------' . "\r\n\r\n"
                . '※ご注意' . "\r\n"
                . 'このメールはHuwaHuwaのカシミアセーターオンラインショップへ登録いただいた方に自動送信しております。' . "\r\n"
                . '本メールに心当たりがない場合は、誠に恐れ入りますが、' . "\r\n"
                . '03-5912-6155までお問い合わせくださいますようお願い申し上げます。' . "\r\n\r\n\r\n"
                . '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━' . "\r\n"
                . '湘和株式会社　芹沢 瑠' . "\r\n"
                . '〒176-0012 東京都練馬区豊玉北2-22-15' . "\r\n"
                . 'お問い合わせ：03-5912-6155（土日、祝祭日を除く平日10:00-17:00）' . "\r\n"
                . 'WEB：https://extremesites.tokyo/training/huwa-huwa-sasada.com/' . "\r\n"
                . '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━'
            ;
            error_reporting(0);
            mb_language('Japanese');
            mb_internal_encoding('UTF-8');

            if(!mb_send_mail($_POST['mail'], MAIL_TITLE, mb_convert_encoding($mail_content, 'ISO-2022-JP-MS'), MAIL_HEADER)){
                throw new Exception();
            }
        } catch (Exception $e) {
            throw new Exception();
        }
    }

        /**
     * ユーザー情報を取得
     *
     * @param int $id
     * @return array
     */
    public function getUser(int $id)
    {
        try {
            $this->connect();
            $sql = 'SELECT * FROM user WHERE id = :id ';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }
}
