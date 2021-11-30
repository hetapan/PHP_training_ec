<?php
require_once(__DIR__ . '/../common/dbconfig.php');
require_once(__DIR__ . '/Auth.php');
require_once(__DIR__ . '/User.php');
require_once(__DIR__ . '/Validation.php');
require_once(__DIR__ . '/AdminAuth.php');
require_once(__DIR__ . '/Product.php');
require_once(__DIR__ . '/Purchase.php');

class Model
{
    public $dbh;

    /**
     * DB接続
     *
     * @return void
     */
    public function connect()
    {
        try {
            $this->dbh = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, DBPASS);
            $this->dbh->exec('set names utf8');
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }
}
