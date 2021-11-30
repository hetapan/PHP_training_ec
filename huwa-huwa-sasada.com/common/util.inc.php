<?php
require_once(__DIR__ . '/const.php');

/**
 * XSS対策の参照名省略
 *
 * @param string string
 * @return string
 */
function h(?string $string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * ページボタン振り分け処理
 *
 * @return string
 */
function getPage()
{
    $func = [
        'product' => '商品管理'
    ];

    $view = [
        'list' => 'リスト',
        'edit' => '',
        'conf' => '確認',
        'done' => '完了'
    ];

    // 対象のURL
    $url = basename($_SERVER['SCRIPT_NAME'], '.php');
    // 対象のURLを分割
    $url_count = mb_strrpos($url, '_');
    $url_part[] = mb_substr($url, 0, $url_count);
    $url_part[] = mb_substr($url, $url_count + 1);

    echo '<div class="page_btn">'
        . '<p>' . $func[$url_part[0]] . (!empty($_GET['type']) ? TYPE[$_GET['type']] : '') . $view[$url_part[1]] . '</p>'
        . '</div>'
    ;
}