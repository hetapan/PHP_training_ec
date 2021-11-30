<?php
class Auth extends Model
{
    /**
     * ログイン処理
     *
     * @param string $login_id
     * @param string $pass
     * @return string $error
     */
    public function login(string $login_id, string $login_pass)
    {
        try {
            // データベースから該当ユーザを検索
            $this->connect();
            $sql = 'SELECT * FROM user '
                . 'WHERE login_id = :login_id '
                . 'AND status = 1'
            ;
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':login_id', $login_id, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && $login_pass === $result['login_pass']) { //パスワードが一致すれば
                //ログイン状態をセッションに保持
                $_SESSION['authenticated'] = 1;
                $_SESSION['name'] = $result['name'];
                $_SESSION['user_id'] = $result['id'];
                //mypageに遷移
                if (!empty($_POST['product_id'])) {
                    header('location:cart.php', true, 307);
                    exit;
                }
                header('location:my_page.php');
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
        unset($_SESSION['authenticated']);
        unset($_SESSION['name']);
        unset($_SESSION['user_id']);
    }

    /**
     * ログインセッションの確認
     *
     * @return void
     */
    public function checkLoginSession()
    {
        if (empty($_SESSION['authenticated'])) { // 認証済みでなければ
            // ログインページにリダイレクト
            if (!empty($_POST['add_cart_btn'])) { //購入するから遷移していれば
                //GETパラーメータを渡す
                header('Location: login.php?product_id=' . $_POST['product_id']);
                exit;
            }
            header('Location: login.php');
            exit;
        }
    }
}
