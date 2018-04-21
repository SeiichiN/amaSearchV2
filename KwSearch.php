<?php
//  namespace billiesworks;

// KwSearch.php
//
// 参考サイト
// https://manablog.org/amazon-api-library-apai-io/
// https://github.com/Exeu/apai-io

require_once('vendor/autoload.php');

use ApaiIO\Operations\Search;

require_once('mylib.php');

require_once('AmazonSearch.php');

/**
 * KwSearch:
 * Summary: アマゾンをキーワードで検索する
 *
 * SuperClass: AmazonSearch
 *
 * @param: string $category, string $keyword.
 *   $category -- カテゴリ ex,'Books'
 *   $keyword  -- キーワード ex, '神崎かなえ'
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
 * $searchData = new KwSearch('Books', '長濱ねる');
 *
 * foreach ($searchData->getData() as $onedata) {
 *  	echo $onedata['title'];
 *  	echo "<br>\n";
 * }
 * --------------------------------------------------
 */
class KwSearch extends AmazonSearch {
    public $category = null;
    public $keyword = null;
    private $data;
    
	public function getData(string $category, string $keyword) {
        $this->category = $category;
        $this->keyword = $keyword;
        
		$search = new Search();
		$search->setCategory($this->category);
		$search->setKeywords($this->keyword);
		$search->setResponseGroup(array('ItemAttributes', 'OfferSummary', 'Offers', 'Images'));

        $this->data = parent::mysearch($search);
		return $this->data;
    }
	
}

/* $myobj = new KwSearch();
 * $searchData = $myobj->getData('Books', '長濱ねる');
 * 
 * // var_dump($searchData);
 * foreach ($searchData as $onedata) {
 *    	echo $onedata['id'], "<br>\n";
 *    	echo $onedata['title'], "<br>\n";
 *    	echo $onedata['author'], "<br>\n";
 *    	echo $onedata['pubdate'], "<br>\n";
 *    	echo $onedata['pub'], "<br>\n";
 *    	echo $onedata['url'], "<br>\n";
 *    	echo $onedata['image'], "<br>\n";
 *    	echo $onedata['newPrice'], "<br>\n";
 *    	echo $onedata['usedPrice'], "<br>\n";
 *    	echo $onedata['collectiblePrice'], "<br>\n";
 *    	echo $onedata['officialPrice'], "<br>\n";
 * }*/


