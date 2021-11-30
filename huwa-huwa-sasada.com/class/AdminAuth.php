<?php
class AdminAuth extends Model
{
    /**
     * ログイン処理
     *
     * @param string $login_id
     * @param string $pass
     * @return void
     */
    public function login(string $login_id, string $login_pass)
    {
        try {
            // データベースから該当ユーザを検索
            $this->connect();
            $sql = 'SELECT * FROM admin_user WHERE login_id = :login_id';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':login_id', $login_id, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && $login_pass === $result['login_pass']) { //パスワードが一致すれば
                //ログイン状態をセッションに保持
                $_SESSION['admin_authenticated'] = 1;
                $_SESSION['admin_name'] = $result['name'];
                $_SESSION['admin_id'] = $result['id'];
                //topに遷移
                header('location:top.php');
                exit;
            }
            // エラー内容を返却
            return 'IDかパスワードが間違っています';

        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * ログアウト処理
     *
     * @return void
     */
    public function logout()
    {
        //セッションの削除処理
        unset($_SESSION['admin_authenticated']);
        unset($_SESSION['admin_name']);
    }

    /**
     * ログインセッションの確認
     *
     * @return void
     */
    public function checkloginSession()
    {
        if (empty($_SESSION['admin_authenticated'])) { // 認証済みでなければ
            // ログインページにリダイレクト
            header('Location: login.php');
            exit;
        }
    }
}
