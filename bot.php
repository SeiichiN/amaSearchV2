<?php
// namespace billiesworks

// amazonListAll.php

// require_once 'vendor/autoload.php';

require_once('mylib.php'); 
require_once('PriceDB.php');
require_once('IdLookup.php');
require_once('mymail.php');

function mkMailMsg($priceBit, $oldAmazonPrice, $newAmazonPrice) {
	$msg = '';
	if (($priceBit & 1) === 1) {
		$msg = $msg . "アマゾン価格が {$oldAmazonPrice['official_p']} から {$newAmazonPrice['official_p']} に変動しました。\n";
	}
	if(($priceBit & 2) === 2) {
		$msg = $msg . "新品価格が {$oldAmazonPrice['new_p']} から {$newAmazonPrice['new_p']} に変動しました。\n";
	}
	if(($priceBit & 4) === 4) {
		$msg = $msg . "中古品価格が {$oldAmazonPrice['used_p']} から {$newAmazonPrice['used_p']} に変動しました。\n";
	}
	if(($priceBit & 8) === 8) {
		$msg = $msg . "コレクション価格が {$oldAmazonPrice['collectible_p']} から {$newAmazonPrice['collectible_p']} に変動しました。\n";
	}
	return $msg;
}

/**
 * Summary: $newdata と 記録されたデータを比べて、価格に変動があればチェック。.
 *
 *     ビット演算子を使えば、楽にチェックできるかも。
 *     変化なし                   ... 0000 ... 0
 *     officialPriceに変化あり    ... 0001 ... 1
 *     newPriceに変化あり         ... 0010 ... 2
 *     usedPriceに変化あり        ... 0100 ... 4
 *     collectiblePriceに変化あり ... 1000 ... 8
 *     こうしておけば、たとえば、officialPriceとnewPriceに変化があれば、
 *     0011 ... 3
 *      ということになる。
 *
 * @params: array $db -- データベースから取得したデータ。各テーブルの最新レコードを取得。
 *          array $newdata -- Amazonから ASIN をキーにして取得した現在のデータ。
 *
 * @return: int $priceBit -- 10進数。ビット演算の結果が入っている。
 */
function setPriceBit($db, $newdata) {
    $priceBit = 0;

    if ($db['official_p'] != $newdata['officialPrice']) {
        $priceBit = $priceBit | 1;
    }
    if ($db['new_p'] != $newdata['newPrice']) {
        $priceBit = $priceBit | 2;
    }
    if ($db['used_p'] != $newdata['usedPrice']) {
        $priceBit = $priceBit | 4;
    }
    if ($db['collectible_p'] != $newdata['collectiblePrice']) {
        $priceBit = $priceBit | 8;
    }
    return $priceBit;
}

function chkPrice(&$mail_msg) {
    $doMail = FALSE;

    $mydb = new PriceDB();
    $lastDBdata = $mydb->lastData();

    // var_dump($lastDBdata);
    // die();

    $mylookup = new IdLookup();
    foreach ($lastDBdata as $row) {
        
 	    $officialPriceBit = 0;
	    $newPriceBit = 0;
	    $usedPriceBit = 0;
	    $collectiblePriceBit = 0;
	    $priceBit = 0;
		
        // アマゾンから現在データを取得
        $mygetdata = $mylookup->getData($row['asin']);
        
        // mygetdataを文字列から数値に変換する。空白は-1。
        $newdata = array_map("arrChange", $mygetdata[0]);
        
        // $newdata と 記録されたデータを比べて、価格に変動があればチェック。
        $priceBit = setPriceBit($row, $newdata);
        echo $row['asin'], " ", $row['title'], " BIT= ", $priceBit, "<br>\n";
        
	    // もし、0でなければ価格に変動があったということなので、
	    // その価格を記録する。また、メールでの報告文を作成。
	    if ($priceBit != 0) {
            
	        $oldAmazonPrice = [
		        'official_p'    => $row['official_p'],
		        'new_p'         => $row['new_p'],
		        'used_p'        => $row['used_p'],
		        'collectible_p' => $row['collectible_p']
	        ];
	        $newAmazonPrice = [
		        'official_p'    => $newdata['officialPrice'],
		        'new_p'         => $newdata['newPrice'],
		        'used_p'        => $newdata['usedPrice'],
		        'collectible_p' => $newdata['collectiblePrice']
	        ];
            // データベースに記録
		    $mydb->db_addPrice($row['asin'], $newAmazonPrice);
            
		    // ユーザーにメールして知らせるためのメッセージを作成。
		    $msg = mkMailMsg($priceBit, $oldAmazonPrice, $newAmazonPrice);
		    $mail_msg = $mail_msg . "ASIN:{$row['asin']}\nタイトル:{$row['title']}\n" . $msg . "\n";
		    $doMail = TRUE;
	    }
	    // アマゾンに負担をかけないように配慮
	    sleep(1);
        
    }
    return $doMail;
}

$mail_msg = "アマゾン価格に変動は以下のとおりです。\n";
$subject = 'アマゾン価格に変動がありました。';
$to = 'billie175@gmail.com';

$doMail = chkPrice($mail_msg);
if ($doMail) {
	if (!gmail($subject, $mail_msg, $to)) {
		echo "メール送信に失敗しました。\n";
	} else {
		echo "メール送信しました。\n";
	}
} else {
	echo "価格に変動はありませんでした。\n";
}


    
    
