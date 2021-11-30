// 初期処理
window.onload = function() {
    modifyUserInfo();
}
/**
 * 住所検索処理
 *
 * @returns void
 */
function modifyUserInfo() {
    // 要素を取得
    var elements = document.getElementsByName("modify");

    // 選択状態の値を取得
    for (var a = "", i = elements.length; i--; ) {
        if (elements[i].checked) {
            if (elements[i].value == 2) {
                document.getElementById("purchase_edit_panel_target").style.display = "block";
                break;
            }
            document.getElementById("purchase_edit_panel_target").style.display = "none";
        }
    }
}
