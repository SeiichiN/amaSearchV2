<?php
//  namespace billiesworks;

// IdLookup.php
//
// 参考サイト
// https://manablog.org/amazon-api-library-apai-io/
// https://github.com/Exeu/apai-io

require_once('vendor/autoload.php');

use ApaiIO\Operations\Lookup;

require_once('mylib.php');

require_once('AmazonSearch.php');

/**
 * IdLookup:
 * Summary: アマゾンをASIN番号（文字列）で検索する.
 * 
 * SuperClass: AmazonSearch
 *
 * @param: string $id -- '4063528650'.
 *
 * @return: array $data.
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
 * $lookupData = new IdLookup('4063528650');
 *
 * foreach ($lookupData->getData() as $onedata) {
 *  	echo $onedata['title'];
 *  	echo "<br>\n";
 * }
 * --------------------------------------------------
 */
class IdLookup extends AmazonSearch {
    public $id = null;
    private $data;
    
	public function getData(string $id) {
		$this->id = $id;

		$lookup = new Lookup();
		$lookup->setItemId($this->id);
		$lookup->setResponseGroup(array('ItemAttributes', 'OfferSummary', 'Offers', 'Images'));

        $this->data = parent::mysearch($lookup);
        
		return $this->data;
    }
	
}

/* $myobj = new IdLookup();
 * $lookupData = $myobj->getData('4063528650');
 * 
 * // print_r($lookupData);
 * foreach ($lookupData as $onedata) {
 *     echo $onedata['id'], "<br>\n";
 *     echo $onedata['title'], "<br>\n";
 *     echo $onedata['author'], "<br>\n";
 *     echo $onedata['pubdate'], "<br>\n";
 *     echo $onedata['pub'], "<br>\n";
 *     echo $onedata['url'], "<br>\n";
 *     echo $onedata['image'], "<br>\n";
 *     echo $onedata['newPrice'], "<br>\n";
 *     echo $onedata['usedPrice'], "<br>\n";
 *     echo $onedata['collectiblePrice'], "<br>\n";
 *     echo $onedata['officialPrice'], "<br>\n";
 * }*/


