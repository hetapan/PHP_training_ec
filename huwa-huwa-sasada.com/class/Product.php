<?php
class Product extends Model
{
    /**
     * ソート＋商品一覧の取得
     *
     * @param string $sort
     * @param string $order
     * @return array $stmt
     */
    public function getProductList(string $sort, string $order)
    {
        try {
            $this->connect();
            $sql = 'SELECT * FROM product WHERE delete_flg = FALSE ';
            if (!empty($sort)) { //ソートボタンが押されたら
                $sql .= 'ORDER BY '
                    . $sort . ' IS NULL ASC, '
                    . $sort . ' ' . $order . ', id DESC'
                ;
            } else {
                $sql .= 'ORDER BY id DESC';
            }
            $stmt = $this->dbh->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * 新規商品登録処理
     *
     * @param array $product
     * @return void
     */
    public function registerProduct(array $post_product)
    {
        try{
            $this->connect();
            $sql = 'INSERT INTO product '
                . '('
                    . 'name, '
                    . 'description, '
                    . 'price, '
                    . 'turn, '
                    . 'create_user'
                . ')VALUES('
                    . ':name, '
                    . ':description, '
                    . ':price, '
                    . ':turn, '
                    . ':create_user'
                . ')'
            ;
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':name', !empty($post_product['name']) ? $post_product['name'] : NULL, PDO::PARAM_STR);
            $stmt->bindValue(':description', !empty($post_product['description']) ? $post_product['description'] : NULL, PDO::PARAM_STR);
            $stmt->bindValue(':price', !empty($post_product['price']) ? $post_product['price'] : NULL, PDO::PARAM_INT);
            $stmt->bindValue(':turn', !empty($post_product['turn']) ? $post_product['turn'] : NULL, PDO::PARAM_INT);
            $stmt->bindValue(':create_user', $_SESSION['admin_name'], PDO::PARAM_STR);
            $stmt->execute();

        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * 商品編集処理
     *
     * @param array $product
     * @param int $id
     * @return void
     */
    public function editProduct(array $post_product, int $id)
    {
        try{
            $this->connect();
            $sql = 'UPDATE product '
                . 'SET '
                    . 'name=:name, '
                    . 'description=:description, '
                    . 'price=:price, '
                    . 'turn=:turn, '
                    . 'update_user=:update_user, '
                    . 'updated_at=current_timestamp(6)'
                . 'WHERE '
                    . 'id = :id '
            ;
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':name', !empty($post_product['name']) ? $post_product['name'] : NULL, PDO::PARAM_STR);
            $stmt->bindValue(':description', !empty($post_product['description']) ? $post_product['description'] : NULL, PDO::PARAM_STR);
            $stmt->bindValue(':price', !empty($post_product['price']) ? $post_product['price'] : NULL, PDO::PARAM_INT);
            $stmt->bindValue(':turn', !empty($post_product['turn']) ? $post_product['turn'] : NULL, PDO::PARAM_INT);
            $stmt->bindValue(':update_user', $_SESSION['admin_name'], PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * 編集する商品情報の取得
     *
     * @param int $id
     * @return array $stmt
     */
    public function getProduct(int $id)
    {
        try{
            $this->connect();
            $sql = 'SELECT * FROM product WHERE id = :id AND delete_flg = FALSE';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new PDOException('システムエラーが発生しました。');
        }
    }

    /**
     *商品の画像のフォルダ保存処理
     *
     * @param string $name
     * @param int $id
     * @return string
     */
    public function uploadProductFile(array $img, int $id)
    {
        error_reporting(0);
        try {
            if ($img['error'] == UPLOAD_ERR_NO_FILE) { //ファイルが選択されてなければ
                throw new Exception('ファイルが選択されていません。');
            }

            if ($img['error'] != UPLOAD_ERR_OK) { //エラーがあれば
                throw new Exception('ファイルのアップロードに失敗しました。');
            }
            //ディレクトリトラバーサル化してアップロードされたファイル名を取得
            $name = basename($img['name']);
            //tempファイルへの絶対パス(アップロードしたファイル)
            $temp = $img['tmp_name'];

            // ファイル名保存処理を行う
            $this->connect();
            $this->dbh->beginTransaction();
            $sql = 'UPDATE product SET img = :img WHERE id = :id';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':img', $name, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // パーミッションを777に変更
            chmod('../images/upload', 0777);
            if (!move_uploaded_file($temp, IMGS_PATH . $name)) { //ファイル移動にエラーがあれば
                // パーミッションを755に変更
                chmod('../images/upload', 0755);
                // 更新をキャンセルして、例外を投げる
                $this->dbh->rollback();
                throw new Exception('ファイルのアップロードに失敗しました。');
            }
            // パーミッションを755に変更
            chmod('../images/upload', 0755);
            // 更新を確定する
            $this->dbh->commit();
            return ['message' => 'ファイルのアップロードに成功しました。', 'name' => $name];

        } catch (PDOException $e) {
            throw new PDOException('システムエラーが発生しました。');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 商品の削除処理
     *
     * @param int $id
     * @return void
     */
    public function deleteProduct(int $id)
    {
        try{
            $this->connect();
            $sql = 'UPDATE product SET delete_flg = TRUE WHERE id = ?';
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([$id]);
            header('Location: product_list.php');
            exit;

        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }
}
