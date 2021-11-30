<?php
class Validation extends Model
{
    /**
     * 会員登録画面のバリデーションチェック
     *
     * @return array|string
     */
    public function registerValidate()
    {
        try {
            if ($_POST['login_id'] === '' || mb_ereg_match('^(\s|　)+$', $_POST['login_id'])) {
                $error['login_id'] = '※入力が必須の項目です';
            } elseif ($this->checkLoginIdExist()) {
                $error['login_id'] = '※このログインIDは既に使用されています';
            }
            if ($_POST['login_pass'] === '' || mb_ereg_match('^(\s|　)+$', $_POST['login_pass'])) {
                $error['login_pass'] = '※入力が必須の項目です';
            }
            if ($_POST['name'] === '' || mb_ereg_match('^(\s|　)+$', $_POST['name'])) {
                $error['name'] = '※入力が必須の項目です';
            }
            if ($_POST['name_kana'] === '' || mb_ereg_match('^(\s|　)+$', $_POST['name_kana'])) {
                $error['name_kana'] = '※入力が必須の項目です';
            } elseif (!preg_match('/^[ァ-ヶー]+$/u', $_POST['name_kana'])) {
                $error['name_kana'] = '※全角カタカナで入力してください';
            }
            if ($_POST['mail'] === '' || mb_ereg_match('^(\s|　)+$', $_POST['mail'])) {
                $error['mail'] = '※入力が必須の項目です';
            } elseif (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
                $error['mail'] = '※不正な形式のメールアドレスです';
            } elseif ($this->checkMailExist()) {
                $error['mail'] = '※このメールアドレスは既に使用されています';
            }
            if (empty($_POST['gender'])) {
                $error['gender'] = '※入力が必須の項目です';
            }
            if ($_POST['mail_confirm'] === '' || mb_ereg_match('^(\s|　)+$', $_POST['mail_confirm'])) {
                $error['mail_confirm'] = '※入力が必須の項目です';
            } elseif ($_POST['mail'] !== $_POST['mail_confirm']) {
                $error['mail_confirm'] = '※メールアドレスが一致していません';
            }
            if (($_POST['tel1'] === '' || mb_ereg_match('^(\s|　)+$', $_POST['tel1']))
                || ($_POST['tel2'] === '' || mb_ereg_match('^(\s|　)+$', $_POST['tel2']))
                || ($_POST['tel3'] === '' || mb_ereg_match('^(\s|　)+$', $_POST['tel3']))
            ) {
                $error['tel'] = '※入力が必須の項目です';
            }
            if (($_POST['postal_code1'] === '' || mb_ereg_match('^(\s|　)+$', $_POST['postal_code1']))
                || ($_POST['postal_code2'] === '' || mb_ereg_match('^(\s|　)+$', $_POST['postal_code2']))
            ) {
                $error['postal_code'] = '※入力が必須の項目です';
            }
            if ($_POST['pref'] === '') {
                $error['pref'] = '※入力が必須の項目です';
            }
            if ($_POST['city'] === '' || mb_ereg_match('^(\s|　)+$', $_POST['city'])) {
                $error['city'] = '※入力が必須の項目です';
            }
            if ($_POST['address'] === '' || mb_ereg_match('^(\s|　)+$', $_POST['address'])) {
                $error['address'] = '※入力が必須の項目です';
            }
            if ($_POST['memo'] !== '' && mb_strlen($_POST['memo'], 'utf8') < 20) {
                $error['memo'] = '※入力する場合、20文字以上入力してください';
            }
            return !empty($error) ? $error : '';
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * ログインIDの重複チェック
     *
     * @return array|boolean
     */
    public function checkLoginIdExist()
    {
        try {
            $this->connect();
            $sql = 'SELECT * FROM user Where login_id = :login_id';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':login_id', $_POST['login_id'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                return TRUE;
            }
            return FALSE;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * メールアドレスの重複チェック
     *
     * @return array|boolean
     */
    public function checkMailExist()
    {
        try {
            $this->connect();
            $sql = 'SELECT * FROM user Where mail = :mail';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':mail', $_POST['mail'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                return TRUE;
            }
            return FALSE;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }
    /**
     * 商品購入画面のバリデーションチェック
     *
     * @return array|string
     */
    public function purchaseValidate()
    {
        if (($_POST['postal_code1'] === '' || preg_match('/^(\s|　)+$/', $_POST['postal_code1']))
            || ($_POST['postal_code2'] === '' || preg_match('/^(\s|　)+$/', $_POST['postal_code2']))
        ) {
            $error['postal_code'] = '※入力が必須の項目です';
        } elseif (!preg_match('/^[0-9]+$/', $_POST['postal_code1']) || !preg_match('/^[0-9]+$/', $_POST['postal_code2'])) {
            $error['postal_code'] = '※半角数字で入力してください';
        } elseif (mb_strlen($_POST['postal_code1'], 'utf8') > 3 || mb_strlen($_POST['postal_code2'], 'utf8') > 4) {
            $error['postal_code'] = '※[3文字]-[4文字]以内で入力してください';
        }

        if (($_POST['city'] === '' || preg_match('/^(\s|　)+$/', $_POST['city']))
            || ($_POST['address'] === '' || preg_match('/^(\s|　)+$/', $_POST['address']))
        ) {
            $error['address'] = '※建物名以外は入力が必須の項目です';
        } elseif (mb_strlen($_POST['city'], 'utf8') > 15 || mb_strlen($_POST['address'], 'utf8') > 100 || mb_strlen($_POST['other'], 'utf8') > 100) {
            $error['address'] = '※市区町村は15文字、番地以降は100文字以内で入力してください';
        }

        if (($_POST['tel1'] === '' || preg_match('/^(\s|　)+$/', $_POST['tel1']))
            || ($_POST['tel2'] === '' || preg_match('/^(\s|　)+$/', $_POST['tel2']))
            || ($_POST['tel3'] === '' || preg_match('/^(\s|　)+$/', $_POST['tel3']))
        ) {
            $error['tel'] = '※入力が必須の項目です';
        } elseif (!preg_match('/^[0-9]+$/', $_POST['tel1']) || !preg_match('/^[0-9]+$/', $_POST['tel2']) || !preg_match('/^[0-9]+$/', $_POST['tel3'])) {
            $error['tel'] = '※半角数字で入力してください';
        } elseif (mb_strlen($_POST['tel1'], 'utf8') > 5 || mb_strlen($_POST['tel2'], 'utf8') > 5 || mb_strlen($_POST['tel3'], 'utf8') > 5) {
            $error['tel'] = '※それぞれ5文字以内で入力してください';
        }

        if ($_POST['name'] === '' || preg_match('/^(\s|　)+$/', $_POST['name'])) {
            $error['name'] = '※入力が必須の項目です';
        } elseif (mb_strlen($_POST['name'], 'utf8') > 15) {
            $error['name'] = '※15文字以内で入力してください';
        }

        if ($_POST['name_kana'] === '' || preg_match('/^(\s|　)+$/', $_POST['name_kana'])) {
            $error['name_kana'] = '※入力が必須の項目です';
        } elseif (!preg_match('/^[ァ-ヶー]+$/u', $_POST['name_kana'])) {
            $error['name_kana'] = '※全角カタカナで入力してください';
        } elseif (mb_strlen($_POST['name_kana'], 'utf8') > 20) {
            $error['name_kana'] = '※20文字以内で入力してください';
        }

        return !empty($error) ? $error : '';
    }
}
