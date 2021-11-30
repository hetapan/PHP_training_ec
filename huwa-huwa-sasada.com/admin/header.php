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
<body class="admin_html">
    <div class="container">
        <?php if (!empty($_SESSION['admin_authenticated'])) : ?>
            <header class="admin_header">
                <div class="admin_header_top">
                    <div class="admin_header_left">
                        <p>ログイン名[<?=$_SESSION['admin_name']?>]さん、ご機嫌いかがですか？</p>
                    </div>
                    <div class="admin_header_right">
                        <p><a href="logout.php" class="admin_header_link">ログアウトする</a></p>
                    </div>
                </div>
                <h2 class="admin_header_title">HuwaHuwaのカシミアセーター　管理画面</h2>
                <nav class="admin_nav">
                    <ul>
                        <li><a href="top.php">top</a></li>
                        <li><a href="product_list.php">商品管理</a></li>
                    </ul>
                </nav>
            </header>
        <?php endif; ?>