CREATE DATABASE m_sasada CHARACTER SET utf8;

CREATE TABLE user(
  id SERIAL PRIMARY KEY,
  login_id TEXT NOT NULL,
  login_pass TEXT NOT NULL,
  name TEXT NOT NULL,
  name_kana TEXT NOT NULL,
  birth_year varchar(4) DEFAULT NULL,
  birth_month varchar(2) DEFAULT NULL,
  birth_day varchar(2) DEFAULT NULL,
  gender TINYINT UNSIGNED NOT NULL,
  mail TEXT NOT NULL,
  tel1 varchar(5) NOT NULL,
  tel2 varchar(5) NOT NULL,
  tel3 varchar(5) NOT NULL,
  postal_code1 varchar(3) NOT NULL,
  postal_code2 varchar(4) NOT NULL,
  pref TINYINT UNSIGNED NOT NULL,
  city varchar(15) NOT NULL,
  address varchar(100) NOT NULL,
  other varchar(100) DEFAULT NULL,
  memo TEXT DEFAULT NULL,
  status TINYINT UNSIGNED NOT NULL,
  last_login_at DATETIME(6) DEFAULT NULL,
  created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
  updated_at DATETIME(6) DEFAULT NULL,
  delete_flg boolean DEFAULT FALSE
);

INSERT INTO users(
  login_id,
  login_pass,
  name,
  name_kana,
  birth_year,
  birth_month,
  birth_day,
  gender,
  mail,
  tel1,
  tel2,
  tel3,
  postal_code1,
  postal_code2,
  pref,
  city,
  address,
  other,
  memo,
  status
) VALUES (
  'ebaeba',
  'eba4649',
  '笹田雅大',
  'ササダマサヒロ',
  '1994',
  '1',
  '11',
  1,
  'test@gmail.com',
  '090',
  '9999',
  '9999',
  '352',
  '0022',
  11,
  'テスト市',
  '1-11-11',
  'テストマンション',
  'テスト備考',
  1
);

INSERT INTO user(
  login_id,
  login_pass,
  name,
  name_kana,
  birth_year,
  birth_month,
  birth_day,
  gender,
  mail,
  tel1,
  tel2,
  tel3,
  postal_code1,
  postal_code2,
  pref,
  city,
  address,
  other,
  memo,
  status
) VALUES (
  'test3',
  'testtest3',
  '笹田太郎',
  'ササダタロウ',
  '1995',
  '2',
  '22',
  2,
  'test2@gmail.com',
  '080',
  '8888',
  '8888',
  '351',
  '0021',
  22,
  'テスト2市',
  '2-22-22',
  'テストマンション2',
  'テスト2備考',
  1
);

CREATE TABLE admin_user(
    id SERIAL PRIMARY KEY,
    login_id TEXT NOT NULL,
    login_pass TEXT NOT NULL,
    name TEXT NOT NULL,
    created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) DEFAULT NULL,
    delete_flg BOOLEAN DEFAULT FALSE
);

CREATE TABLE product(
    id SERIAL PRIMARY KEY,
    name TEXT,
    description TEXT,
    img TEXT,
    price MEDIUMINT UNSIGNED,
    turn SMALLINT UNSIGNED,
    create_user BIGINT UNSIGNED NOT NULL,
    update_user BIGINT UNSIGNED DEFAULT NULL,
    created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) DEFAULT NULL,
    delete_flg BOOLEAN DEFAULT FALSE
);

INSERT INTO admin_user(login_id, login_pass, name) VALUES ('adminebaeba', 'admineba4649', 'admin1');

INSERT INTO product(name, description, price) VALUES('カシミアセーター01', '商品説明文商品説明文', '4980');


CREATE TABLE cart(
    id SERIAL PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    num SMALLINT UNSIGNED NOT NULL
);

CREATE TABLE purchase(
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    name_kana TEXT NOT NULL,
    tel1 VARCHAR(5) NOT NULL,
    tel2 VARCHAR(5) NOT NULL,
    tel3 VARCHAR(5) NOT NULL,
    postal_code1 VARCHAR(3) NOT NULL,
    postal_code2 VARCHAR(4) NOT NULL,
    pref TINYINT UNSIGNED NOT NULL,
    city VARCHAR(15) NOT NULL,
    address VARCHAR(100) NOT NULL,
    other VARCHAR(100) DEFAULT NULL,
    billing_name TEXT NOT NULL,
    billing_name_kana TEXT NOT NULL,
    billing_mail TEXT NOT NULL,
    billing_tel1 VARCHAR(5) NOT NULL,
    billing_tel2 VARCHAR(5) NOT NULL,
    billing_tel3 VARCHAR(5) NOT NULL,
    payment_id TINYINT UNSIGNED NOT NULL,
    sub_price MEDIUMINT UNSIGNED NOT NULL,
    shipping_price SMALLINT UNSIGNED NOT NULL,
    total_price MEDIUMINT UNSIGNED NOT NULL,
    created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) DEFAULT NULL,
    delete_flg BOOLEAN DEFAULT FALSE
);

CREATE TABLE purchase_detail(
    id SERIAL PRIMARY KEY,
    purchase_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    name TEXT NOT NULL,
    price MEDIUMINT UNSIGNED NOT NULL,
    num SMALLINT UNSIGNED NOT NULL
);