<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>HuwaHuwaのカシミアセーター</title>
    <meta name="description" content="重量感たっぷりの本物のミンクとカシミアの毛を使用!!フワフワだけど高級感のあるミンクとカシミアのセーター">
    <meta name="keywords" content="カシミア,セーター,ミンク,ふわふわ,高級カシミア,ミンクカシミア">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="header_left">
                <a href="./index.php"><img src="./images/logo.png" class="loginform_logo"></a>
            </div>
            <div class="header_right">
                <?php if (!empty($_SESSION['authenticated'])) : ?>
                    <p class="header_txt">ユーザー：<?=h($_SESSION['name'])?></p>
                        <a href="cart.php" class="header_link header_link_3">カートをみる</a>
                    <a href="logout.php" class="header_link header_link_2">ログアウト</a>
                <?php else : ?>
                    <a href="./login.php" class="header_link header_link_1">ログイン</a>
                    <a href="./new_account_edit.php" class="header_link header_link_2">会員登録</a>
                <?php endif; ?>
            </div>
        </header>