/**
 * 商品の削除確認ダイアログ
 *
 * @returns boolean
 */
function deleteConfirm() {
    if(confirm("本当に削除しますか？")) {
        return true;
      } else {
        return false;
      }
}

/**
 * 商品のアップロード確認ダイアログ
 *
 * @returns boolean
 */
 function uploadConfirm() {
    if(confirm("本当に画像をアップロードしますか？")) {
        return true;
      } else {
        return false;
      }
}