<?php
// ManageUser.php
require_once('mylib.php');
require_once('mymail.php');
require_once('UserDB.php');

class ManageUser {

    /**
     * Summary: 新規登録があったことを管理人に知らせる
     *
     * @params: array $member -- 連想配列。'loginId','name','passwd','email'
     *
     * @return: boolean TRUE -- gmailに送ると、成功した場合 TRUE が帰ってくる。
     */
	public function inform($member, $to) {
		$reply = $member['email'];
		$subject = '新規登録のお知らせ';
		$body = "AmazonSeeach にて、{$member['loginId']}さま"
			  . "（実名：{$member['name']}さま）が登録されました。>\n"
			  . "初期パスワードは「{$member['passwd']} 」です。>\n"
			  . "http://" . SITE_URL . "tellPasswd.php?id={$member['loginId']} 返事を送る";
		return gmail($subject, $body, $to, $reply);
	}

    /**
     * Summary: address.jsonに登録されているかどうかを調べる。
     *          
     * @params: string $loginId.
     * @return: boolean TRUE or FALSE.
     */
    public function checkAdList($loginId) {

        $jsonUrl = ADDRESS_LIST;

        if (file_exists($jsonUrl)) {
	        $json = file_get_contents($jsonUrl);
	        $json = mb_convert_encoding($json, 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
	        $arr = json_decode($json, true);
        } else {
            return FALSE;
        }

        if (array_key_exists($loginId, $arr))
            return TRUE;
        else
            return FALSE;
        
    }
    

    /**
     * Summary: ログインIDとメールアドレスの json形式リストに
     *          アドレスを追加する。
     *          { 'ログインID': 'アドレス', ... }
     * @params: string $loginId.
     * @return: boolean TRUE or FALSE.
     */
    public function addAddressList($loginId) {
        $myobj = new UserDB();
        $address = $myobj->getMailAddress($loginId);

        $jsonUrl = ADDRESS_LIST;

        if (file_exists($jsonUrl)) {
	        $json = file_get_contents($jsonUrl);
	        $json = mb_convert_encoding($json, 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
	        $arr = json_decode($json, true);
        } else {
            return FALSE;
        }

        /* var_dump($arr);
         * echo "<br>\n";*/

        $arr[$loginId] =  $address;

        // var_dump($arr);

        // アドレスの配列をjsonオブジェクトにして保存
        $arr = json_encode($arr, JSON_PRETTY_PRINT);
        file_put_contents(ADDRESS_LIST, $arr);

        return TRUE;
    }

    /**
     * Summary: json形式リストからアドレスを削除する。
     *
     * @params: string $loginId.
     * @return: boolean TRUE or FALSE.
     */
    public function delAddressList($loginId) {

        $jsonUrl = ADDRESS_LIST;

        if (file_exists($jsonUrl)) {
	        $json = file_get_contents($jsonUrl);
	        $json = mb_convert_encoding($json, 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
	        $arr = json_decode($json, true);
        } else {
            return FALSE;
        }

        if (array_key_exists($loginId, $arr)) {
            unset($arr[$loginId]);  // 隙間があく

            // アドレスの配列をjsonオブジェクトにして保存
            $arr = json_encode($arr, JSON_PRETTY_PRINT);
            file_put_contents(ADDRESS_LIST, $arr);

            return TRUE;
        } else {
            return FALSE;
        }
        
    }

    /**
     * Summary: メールアドレスの json形式リストの
     *          アドレスを変更する。
     *          
     * @params: string $loginId, string $newEmail.
     * @return: boolean TRUE or FALSE.
     */
    public function changeAdListEmail($loginId, $newEmail) {

        $jsonUrl = ADDRESS_LIST;

        if (file_exists($jsonUrl)) {
	        $json = file_get_contents($jsonUrl);
	        $json = mb_convert_encoding($json, 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
	        $arr = json_decode($json, true);
        } else {
            return FALSE;
        }

        /* var_dump($arr);
         * echo "<br>\n";*/

        if (array_key_exists($loginId, $arr)) {
            $arr[$loginId] =  $newEmail;
        } else {
            return FALSE;
        }

        // var_dump($arr);

        // アドレスの配列をjsonオブジェクトにして保存
        $arr = json_encode($arr, JSON_PRETTY_PRINT);
        file_put_contents(ADDRESS_LIST, $arr);

        return TRUE;
    }
    
}

/* $myobj = new ManageUser();
 * var_dump($myobj->delAddressList('yukiko'));*/
