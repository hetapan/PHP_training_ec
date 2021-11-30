/**
 * 住所検索処理
 *
 * @returns void
 */
function getAddress() {
    let api = "https://zipcloud.ibsnet.co.jp/api/search?zipcode=";
    let input1 = document.getElementById("input1").value;
    let input2 = document.getElementById("input2").value;
    let url = api + input1 + input2;

    fetch(url)
        .then(function (data) {
      // 読み込むデータをJSONに設定
            return data.json();
        })
        .then(function (json) {
            document.getElementById("address1").selectedIndex = json.results[0].prefcode - 1;
            document.getElementById("address2").value = json.results[0].address2;
            document.getElementById("address3").value = json.results[0].address3;
        });
}
