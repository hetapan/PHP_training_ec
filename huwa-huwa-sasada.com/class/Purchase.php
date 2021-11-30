<?php
class Purchase extends Model
{
    /**
     * カートに商品追加処理
     *
     * @param stirng $product_id
     * @return void
     */
    public function addCart(string $product_id)
    {
        try {
            $this->connect();

            // 選択した商品が既にカート内に入っているか確認する
            $sql = 'SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) { //選択した商品が既にカート内にあれば
                //個数を1つ加算する
                $sql = 'UPDATE cart SET num = :num WHERE id = :id';
                $stmt = $this->dbh->prepare($sql);
                $stmt->bindValue(':num', $result['num'] + 1, PDO::PARAM_INT);
                $stmt->bindValue(':id', $result['id'], PDO::PARAM_INT);
                $stmt->execute();
                return;
            }
            //カートに商品を追加する
            $sql = 'INSERT INTO cart (user_id, product_id, num) VALUES (:user_id, :product_id, 1)';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();

        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * カート内の商品を取得
     *
     * @return array|void
     */
    public function getCartList()
    {
        try {
            $this->connect();
            $sql = 'SELECT '
                    . 'cart.id, '
                    . 'cart.user_id, '
                    . 'product_id, '
                    . 'img, '
                    . 'name, '
                    . 'num, '
                    . 'price, '
                    . 'num * price AS total '
                . 'FROM '
                    . 'cart '
                . 'INNER JOIN '
                    . 'product '
                . 'ON '
                    . 'cart.product_id = product.id '
                . 'WHERE '
                    . 'user_id = :user_id'
            ;
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * カート内の商品の存在チェック
     *
     * @return bool|void
     */
    public function checkCart()
    {
        try {
            $this->connect();
            $sql = 'SELECT * FROM cart WHERE user_id = :user_id';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchAll(PDO::FETCH_ASSOC)) {
                return TRUE;
            }
            return FALSE;

        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * カートの商品個数変更処理
     *
     * @param int $id
     * @param int $num
     * @return void
     */
    public function editCart(int $id, int $num)
    {
        try {
            $this->connect();
            $sql = 'UPDATE cart SET num = :num WHERE id = :id';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':num', $num, PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * カートの商品削除処理
     *
     * @param int $id
     * @return void
     */
    public function deleteCart(int $id)
    {
        try {
            $this->connect();
            $sql = 'DELETE FROM cart WHERE id = :id';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * カートの商品合計の取得処理
     *
     * @return array|void
     */
    public function calculateCart()
    {
        try {
            $this->connect();
            $sql = 'SELECT '
                    . 'sum(num * price) AS subtotal_price, '
                    . 'sum(num) AS total_num '
                . 'FROM '
                    . 'cart '
                . 'INNER JOIN '
                    . 'product '
                . 'ON '
                    . 'cart.product_id = product.id '
                . 'WHERE '
                    . 'user_id = :user_id'
            ;
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['subtotal_price'] >= 10000) {
                $result += ['cost' => 0];
            } else {
                $result += ['cost' => 1000];
            }
            $result += ['total_price' => $result['subtotal_price'] + $result['cost']];
            return $result;

        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * 商品購入処理
     *
     * @param array $post_customer_info
     * @return void
     */
    public function purchaseProduct(array $post_customer_info)
    {
        try {
            // 購入処理をするための情報を取得
            $user = new User;
            $user_info = $user->getUser($_SESSION['user_id']);
            $total = $this->calculateCart();
            $products = $this->getCartList();

            $this->connect();

            //トランザクションの開始
            $this->dbh->beginTransaction();

            // カート商品の購入処理
            $sql = 'INSERT INTO purchase '
                . '('
                    . 'name, '
                    . 'name_kana, '
                    . 'tel1, '
                    . 'tel2, '
                    . 'tel3, '
                    . 'postal_code1, '
                    . 'postal_code2, '
                    . 'pref, '
                    . 'city, '
                    . 'address, '
                    . 'other, '
                    . 'billing_name, '
                    . 'billing_name_kana, '
                    . 'billing_mail, '
                    . 'billing_tel1, '
                    . 'billing_tel2, '
                    . 'billing_tel3, '
                    . 'payment_id, '
                    . 'sub_price, '
                    . 'shipping_price, '
                    . 'total_price'
                . ') VALUES ('
                    . ':name, '
                    . ':name_kana, '
                    . ':tel1, '
                    . ':tel2, '
                    . ':tel3, '
                    . ':postal_code1, '
                    . ':postal_code2, '
                    . ':pref, '
                    . ':city, '
                    . ':address, '
                    . ':other, '
                    . ':billing_name, '
                    . ':billing_name_kana, '
                    . ':billing_mail, '
                    . ':billing_tel1, '
                    . ':billing_tel2, '
                    . ':billing_tel3, '
                    . ':payment_id, '
                    . ':sub_price, '
                    . ':shipping_price, '
                    . ':total_price'
                . ')'
            ;
            $stmt = $this->dbh->prepare($sql);

            if ($post_customer_info['modify'] == 1){
                $mailing_info = $user_info;
            } else {
                $mailing_info = $post_customer_info;
            }
            $stmt->bindValue(':name', $mailing_info['name'], PDO::PARAM_STR);
            $stmt->bindValue(':name_kana', $mailing_info['name_kana'], PDO::PARAM_STR);
            $stmt->bindValue(':tel1', $mailing_info['tel1'], PDO::PARAM_STR);
            $stmt->bindValue(':tel2', $mailing_info['tel2'], PDO::PARAM_STR);
            $stmt->bindValue(':tel3', $mailing_info['tel3'], PDO::PARAM_STR);
            $stmt->bindValue(':postal_code1', $mailing_info['postal_code1'], PDO::PARAM_STR);
            $stmt->bindValue(':postal_code2', $mailing_info['postal_code2'], PDO::PARAM_STR);
            $stmt->bindValue(':pref', $mailing_info['pref'], PDO::PARAM_INT);
            $stmt->bindValue(':city', $mailing_info['city'], PDO::PARAM_STR);
            $stmt->bindValue(':address', $mailing_info['address'], PDO::PARAM_STR);
            $stmt->bindValue(':other', (!empty($mailing_info['other']) ? $mailing_info['other'] : NULL), PDO::PARAM_STR);
            $stmt->bindValue(':billing_name', $user_info['name'], PDO::PARAM_STR);
            $stmt->bindValue(':billing_name_kana', $user_info['name_kana'], PDO::PARAM_STR);
            $stmt->bindValue(':billing_mail', $user_info['mail'], PDO::PARAM_STR);
            $stmt->bindValue(':billing_tel1', $user_info['tel1'], PDO::PARAM_STR);
            $stmt->bindValue(':billing_tel2', $user_info['tel2'], PDO::PARAM_STR);
            $stmt->bindValue(':billing_tel3', $user_info['tel3'], PDO::PARAM_STR);
            $stmt->bindValue(':payment_id', $post_customer_info['payment'], PDO::PARAM_INT);
            $stmt->bindValue(':sub_price', $total['subtotal_price'], PDO::PARAM_INT);
            $stmt->bindValue(':shipping_price', $total['cost'], PDO::PARAM_INT);
            $stmt->bindValue(':total_price', $total['total_price'], PDO::PARAM_INT);
            $stmt->execute();

            // 最後に登録したpurchase_idを取得する
            $last_insert_id = $this->dbh->lastInsertId();

            // カート商品の購入詳細の登録
            $sql = 'INSERT INTO purchase_detail '
                . '('
                    . 'purchase_id, '
                    . 'product_id, '
                    . 'name, '
                    . 'price, '
                    . 'num '
                . ') VALUES ('
                    . ':purchase_id, '
                    . ':product_id, '
                    . ':name, '
                    . ':price, '
                    . ':num '
                . ')'
            ;
            foreach ($products as $product) {
                $stmt = $this->dbh->prepare($sql);
                $stmt->bindValue(':purchase_id', $last_insert_id, PDO::PARAM_INT);
                $stmt->bindValue(':product_id', $product['product_id'], PDO::PARAM_INT);
                $stmt->bindValue(':name', $product['name'], PDO::PARAM_STR);
                $stmt->bindValue(':price', $product['price'], PDO::PARAM_INT);
                $stmt->bindValue(':num', $product['num'], PDO::PARAM_INT);
                $stmt->execute();
            }

            // カート商品の削除処理
            $sql = 'DELETE FROM cart WHERE user_id = :user_id';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();

            // 更新を確定
            $this->dbh->commit();

            // メールの送信処理
            $this->sendPurchaseMail($user_info, $products, $total, $last_insert_id, $post_customer_info);

        } catch (PDOException $e) {
            // 更新のキャンセル
            $this->dbh->rollback();
            throw new PDOException($e);

        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    /**
     * メール送信
     *
     * @param array $user_info
     * @param array $products
     * @param array $total
     * @param string $last_insert_id
     * @param array $porst_customer_info
     * @return void
     */
    public function sendPurchaseMail(array $user_info, array $products, array $total, string $last_insert_id, array $post_customer_info)
    {
        try {
            $product = '';
            foreach ($products as $value) {
                $product .= '商品名：' . h($value['name']) . "\r\n"
                    . '価格：' . h(number_format($value['price'])) . '円' . "\r\n"
                    . '個数：' . h(number_format($value['num'])) . '個' . "\r\n"
                    . '****************************' . "\r\n";
            }

            $send_address = '';
            if($post_customer_info['modify'] == 1){
                $send_address .= '送付先：' . h($user_info['name']) . ' 様' . "\r\n"
                . '〒' . h($user_info['postal_code1']) . ' - ' . h($user_info['postal_code2']) . "\r\n"
                . PREF[$user_info['pref']] . h($user_info['city']) . h($user_info['address']) . ' ' . h($user_info['other']) . "\r\n"
                . h($user_info['tel1']) . '-' . h($user_info['tel2']) . '-' . h($user_info['tel3']) . "\r\n";
            } else {
                $send_address .= '送付先：' . h($post_customer_info['name']) . ' 様' . "\r\n"
                . '〒' . h($post_customer_info['postal_code1']) . ' - ' . h($post_customer_info['postal_code2']) . "\r\n"
                . PREF[$post_customer_info['pref']] . h($post_customer_info['city']) . h($post_customer_info['address']) . ' ' . h($post_customer_info['other']) . "\r\n"
                . h($post_customer_info['tel1']) . '-' . h($post_customer_info['tel2']) . '-' . h($post_customer_info['tel3']) . "\r\n";
            }

            $mail_content =
                '━━━━━━━━━━━━━━━━━━━━━━' . "\r\n"
                . '商品購入完了のお知らせ' . "\r\n"
                . '━━━━━━━━━━━━━━━━━━━━━━' . "\r\n\r\n"
                . 'HuwaHuwaのカシミアセーターオンラインショップです。' . "\r\n\r\n"
                . 'この度はご注文いただき誠にありがとうございました。' . "\r\n"
                . 'ご注文内容を以下の内容で承りました。' . "\r\n\r\n"
                . '------------------------------' . "\r\n"
                . '注文ID：' . $last_insert_id . "\r\n"
                . 'ご注文者名：' . $user_info['name'] . ' 様' . "\r\n"
                . $send_address
                . 'お支払い方法：' . PAYMENT[$post_customer_info['payment']] . "\r\n\r\n"
                . 'ご注文商品：' . "\r\n"
                . '****************************' . "\r\n"
                . $product
                . '****************************' . "\r\n"
                . '小計：' . number_format($total['subtotal_price']) . '円' . "\r\n"
                . '合計個数：' . number_format($total['total_num']) . '個' . "\r\n"
                . '送料：' . number_format($total['cost']) . '円' . "\r\n"
                . '合計金額：' . number_format($total['total_price']) . '円' . "\r\n"
                . '****************************' . "\r\n"
                . '-----------------------------' . "\r\n\r\n"
                . '※ご注意' . "\r\n"
                . 'このメールはHuwaHuwaのカシミアセーターオンラインショップをご利用いただいた方に自動送信しております。' . "\r\n"
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

            if (!mb_send_mail($user_info['mail'], MAIL_TITLE_PURCHASE, mb_convert_encoding($mail_content, 'ISO-2022-JP-MS'), MAIL_HEADER)) {
                throw new Exception();
            }

        } catch (Exception $e) {
            throw new Exception();
        }
    }

    /**
     * 商品購入フォームのトークンチェック
     *
     * @return void
     */
    public function checkPurchaseToken()
    {
        if (empty($_POST['token']) || empty($_SESSION['token']) || $_POST['token'] != $_SESSION['token']) { //トークンが未発行、一致しなければ
            header('location:purchase_edit.php');
            exit;
        }
    }
}