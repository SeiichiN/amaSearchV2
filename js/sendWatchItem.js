/**
 * send Request
 * 「ウォッチする」ボタンをクリックしたときにおこなう処理。
 * その商品の各項目をHTMLから読み取り、watchPrice.php に送る。
 * その返答を受け取り、画面に表示する。
 * 通常は、amazonFind.php -> watchPrice.php -> amazonFind.php という遷移になるが、
 * このsendWatchItem.js のおかげで、画面遷移せずに watchPrice.php にデータを送り、
 * その結果を取得することができている。（非同期処理）
 *
 * @param: Integer idx -- キーワード検索画面の「ウォッチする」ボタンをクリックした
 *                      ときの、インデックス番号
 */
function sendRequest(idx) {
    var xmlhttp = new XMLHttpRequest();

    // console.log(idx);
    
    var asin = document.getElementsByName('asin')[idx].value;
    var title = document.getElementsByName('title')[idx].value;
    var url = document.getElementsByName('url')[idx].value;
    var officialPrice = document.getElementsByName('officialPrice')[idx].value;
    var newPrice = document.getElementsByName('newPrice')[idx].value;
    var usedPrice = document.getElementsByName('usedPrice')[idx].value;
    var collectiblePrice = document.getElementsByName('collectiblePrice')[idx].value;

    /* console.log(asin + " " + title);
     * console.log(url);
     * console.log(officialPrice + " " + newPrice + " " + usedPrice + " " + collectiblePrice);*/

    var sendData = "";
    var data = new FormData();
    data.append('asin', asin);
    data.append('title', title);
    data.append('url', url);
    data.append('officialPrice', officialPrice);
    data.append('newPrice', newPrice);
    data.append('usedPrice', usedPrice);
    data.append('collectiblePrice', collectiblePrice);
    /* for (let [key, val] of data) {
     *     sendData = sendData + key + "=" + val + "&";
     * }
     * sendData = sendData.slice(0, -1);  // 末尾の「&」を削除*/

    // formDataを使うときは、
    //   xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // の記述はいらない。
    // sendメソッドが自動的に付加してくれる。
    
    if (xmlhttp !== null) {
        xmlhttp.open("POST", "./watchPrice.php", false);
        xmlhttp.send(data);
        var res = xmlhttp.responseText;
        document.getElementsByClassName("notice")[0].textContent = res;
        alert(res);
    }
}

window.onload = function() {
    var elements = document.getElementsByClassName('sendWatchItemBtn');

    /* for (var ele of elements) {
     *     ele.onclick = function(e) {
     *         console.log(e);
     *     }
     * }*/
    

    for (var i = 0; i < elements.length; i++) {
        elements[i].onclick = (function (a) {
            return function() {
                sendRequest(a);
            };
        })(i);
    };
};
