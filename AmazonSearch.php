<?php
//  namespace billiesworks;

// kw-search.php
//
// 参考サイト
// https://manablog.org/amazon-api-library-apai-io/
// https://github.com/Exeu/apai-io

require_once('vendor/autoload.php');

use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;
use ApaiIO\ApaiIO;

require_once('conf/aws_conf.php');

require_once('mylib.php');

require_once('mkGuzzleRequest.php');

/**
 * amazonSearch: アマゾンを検索する
 * 
 * Param:
 *   $category -- カテゴリ ex,'Books'
 *   $keyword  -- キーワード ex, '神崎かなえ'
 *
 * Return:
 *   $data  -- 2次元の連想配列
 *       $data[]['id']      -- ASIN
 *       $data[]['title']   -- タイトル
 *       $data[]['author']  -- 著者・作者
 *       $data[]['pubdate'] -- 出版日
 *       $data[]['pub']     -- 出版社
 *       $data[]['url']     -- 商品紹介ページ
 *       $data[]['image']   -- 商品画像
 *   $data[0]['id'=>ASIN, 'title'=>タイトル, 'author'=>著者...
 *
 * Usage:
 * --------------------------------------------------
 * $searchData = amazonSearch('Books', '神崎かなえ');
 *
 * foreach ($searchData as $onedata) {
 *  	echo $onedata['title'];
 *  	echo "<br>\n";
 * }
 * --------------------------------------------------
 */
class AmazonSearch {
	public $method;
	
	public function mysearch($method) {
		$request = mkGuzzleRequest();
	
		$conf = new GenericConfiguration();
		$conf
			->setCountry('co.jp')
			->setAccessKey(AWS_API_KEY)
			->setSecretKey(AWS_API_SECRET_KEY)
			->setAssociateTag(AWS_ASSOCIATE_TAG)
			->setRequest($request)
			->setResponseTransformer(new \ApaiIO\ResponseTransformer\XmlToSimpleXmlObject());
		
		$apaiIO = new ApaiIO($conf);
		
		// 503エラー（サーバーエラー）が時々おこる
		try {
			// $formattedResponse = $apaiIO->runOperation($search);
			// $results = simplexml_load_string($formattedResponse);
			$results = $apaiIO->runOperation($method);
            
			// print_r($results);
			// die();
			
			$data = [];
			foreach($results->Items->Item as $item) {
				$item_id = $item->ASIN;
				$item_title = $item->ItemAttributes->Title;
				$item_author = $item->ItemAttributes->Author;
				$item_publicationdate = $item->ItemAttributes->PublicationDate;
				$item_publisher = $item->ItemAttributes->Publisher;
				$item_url = $item->DetailPageURL;
				$item_image = $item->LargeImage->URL;
				// 電子書籍だと値段情報がない
				if ($item->OfferSummary) {
					$item_newPrice = $item->OfferSummary->LowestNewPrice->Amount;
					$item_usedPrice = $item->OfferSummary->LowestUsedPrice->Amount;
					$item_collectiblePrice = $item->OfferSummary->LowestCollectiblePrice->Amount;
				} else {
					$item_newPrice = -1;
					$item_usedPrice = -1;
					$item_collectiblePrice = -1;
				}
				if ($item->Offers->Offer)
					$item_officialPrice = $item->Offers->Offer->OfferListing->Price->Amount;
				else
					$item_officialPrice = -1;
				
				array_push($data, [
							   'id' => $item_id,
							   'title' => $item_title,
							   'author' => $item_author,
							   'pubdate' => $item_publicationdate,
							   'pub' => $item_publisher,
							   'url' => $item_url,
							   'image' => $item_image,
							   'newPrice' => $item_newPrice,
							   'usedPrice' => $item_usedPrice,
							   'collectiblePrice' => $item_collectiblePrice,
							   'officialPrice' => $item_officialPrice
							   ]);
			}
			
		} catch (ErrorException $e) {
			echo "エラー：{$e->getMessage()}\n";
			echo "(File: {$e->getFile()})\n";
			echo "(Line: {$e->getLine()})\n";
			die();
		}
		return $data;
	}
}

// $search = new amazonSearch();
// $searchData = $search->kwSearch('Books', '長濱ねる');

// // var_dump($searchData);
// foreach ($searchData as $onedata) {
//  	echo $onedata['id'], "<br>\n";
//  	echo $onedata['title'], "<br>\n";
//  	echo $onedata['author'], "<br>\n";
//  	echo $onedata['pubdate'], "<br>\n";
//  	echo $onedata['pub'], "<br>\n";
//  	echo $onedata['url'], "<br>\n";
//  	echo $onedata['image'], "<br>\n";
//  	echo $onedata['newPrice'], "<br>\n";
//  	echo $onedata['usedPrice'], "<br>\n";
//  	echo $onedata['collectiblePrice'], "<br>\n";
//  	echo $onedata['officialPrice'], "<br>\n";
// }


