<?php
// namespace billiesworks
// priceDB.php

require_once('lib/mylib.php');

const INDEX_TABLE = 'list';

class PriceDB {
    private $db;
    public $asin, $title, $price;
//	public $loginName;

	// $loginName を引数にとることで、データベース名を
	// <ログイン名>.db としている。
    function __construct($loginName) {
        $dbname = 'sqlite:db/' . $loginName . '.db';
        $this->db = new PDO($dbname);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	    // listテーブルがなければ作る
		try {
			$query = "create table if not exists " . INDEX_TABLE . " ( "
				. "id integer primary key, asin text, table_name text, title text )";
			$stmt = $this->db->query($query);
        } catch (PDOException $e) {
            putErrorLog($e);
//            $this->db = null;
			return FALSE;
        }
		return TRUE;
    }

	private function getTableName($asin) {
		$tablename = 'db_' . $asin;
        return $tablename;
    }

    /**
     * db_index
     * 
     * Summary: どのテーブルに何が入っているかの一覧を作成する。
     *          各テーブルが作成された時に1回だけ呼ばれる。.
     *
     * @param: $db -- データベース・オブジェクト
     *         $asin -- アマゾンのASIN番号
     *         $dbtable -- 作成したテーブル名
     *         $title -- タイトル.
     *
     * @return: $db.
     *
     * ----------------------------------------------------------------------------
     * id          asin        table_name     title                                
     * ----------  ----------  -------------  -------------------------------------
     * 1           4063528650  db_4063528650  長濱ねる1st写真集 ここから
     * 2           4873117763  db_4873117763  Docker                               
     * 3           4873116864  db_4873116864  Web API: The Good Parts              
     * 4           B01HM6KK6S  db_B01HM6KK6S  (フルグロウ) Full Glow トート
     * 5           4798151645  db_4798151645  PHPの絵本 第2版 Webアプリ作
     * ----------------------------------------------------------------------------
     */
    function db_index($asin, $dbtable, $title) {
		try {
			// データを入れる。新規テーブル（ウォッチ商品）を記録する。
			$query = "insert into " . INDEX_TABLE . " ( asin, table_name, title ) values (?, ?, ?)";
			
			$stmt = $this->db->prepare($query);
			$stmt->bindValue(1, $asin, PDO::PARAM_STR);
			$stmt->bindValue(2, $dbtable, PDO::PARAM_STR);
			$stmt->bindValue(3, $title, PDO::PARAM_STR);
			$stmt->execute();
        } catch (PDOException $e) {
            putErrorLog($e);
//            $this->db = null;
			return FALSE;
        }
		return TRUE;
    }

    /**
     * Summary: db_XXXXXXXXX というテーブル名にデータを登録する。XXXXXXXX は ASIN コード.
     *
     * @param:
     *   $db -- データベース・オブジェクト
     *   $asin -- ASINコード
     *   $newAmazonPrice -- ['official_p', 'new_p', 'used=p', 'collectible_p']
     *       offisial_p    -- アマゾン価格
     *       new_p         -- 新品価格
     *       used_p        -- 中古品価格
     *       collectible_p -- コレクション価格.
     *
     * @return: TRUE.
     */
    function db_addPrice ($asin, $newAmazonPrice) {
		
	    $tablename = 'db_' . $asin;
	    $date = date("Y-m-d H:i");
	    try {
			$query = "insert into $tablename (asin, official_p, new_p, used_p, collectible_p, date) " 
				. " values (?, ?, ?, ?, ?, ?)";
			var_dump($newAmazonPrice);
			$stmt = $this->db->prepare($query);
			$stmt->bindValue(1, $asin, PDO::PARAM_STR);
			$stmt->bindValue(2, $newAmazonPrice['official_p'], PDO::PARAM_INT);
			$stmt->bindValue(3, $newAmazonPrice['new_p'], PDO::PARAM_INT);
			$stmt->bindValue(4, $newAmazonPrice['used_p'], PDO::PARAM_INT);
			$stmt->bindValue(5, $newAmazonPrice['collectible_p'], PDO::PARAM_INT);
			$stmt->bindValue(6, $date, PDO::PARAM_STR);
			$stmt->execute();
        } catch (PDOException $e) {
            putErrorLog($e);
//            $this->db = null;
			return FALSE;
        }
	    return TRUE;
    }

    /**
     * db_mkitem
     *
     * Summary: データが新規である場合、関数が呼ばれる。(watchPrice.phpから)
     *          新規データなら、まずテーブル一覧（list）に今から
     *          作成するテーブル名とタイトルを登録し、それからテーブルを作成する。
     *          そして、最初のデータ（価格）を記録する。
     *          データが追加である場合は、この関数は呼ばれないと思うんだがなあ。
     *          （データが追加である場合、それはデータに変動があったからである。
     *          　この場合は、テーブル一覧（list）にはすでに登録されてあるから、お
     *          　こなわれることは、すでに存在するテーブルに変動した価格データを追
     *          　加するだけである。）
     *          （データが新規あるいは追加であるかのチェックはここではしない。この
     *          　関数が呼ばれるのは、新規あるいは追加だからである。）
     *
     * @params: $asin, string  -- アマゾン商品番号
     *          $title, string  -- その商品のタイトル
     *          $price, array  -- 記録する価格。配列になっている。
     *
     * @return: TRUE, boolean
     */
    public function db_mkitem($asin, $title, $price) {
        try {
            $tablename = 'db_' . $asin;

            // テーブルを作成する前に、テーブル一覧(list)にテーブル名を追加。
            // 
            // listの中の項目を調べるのではなく、ここでは、テーブルの存在を調べて、
            // listの中にその項目が存在するかどうかをみている。
			// テーブルがない -- listにその項目がない
            // テーブルがある -- listにその項目がある、あるいは、テーブルの削除失敗。
		    $aru = 0;
            $query = "select count (*) from sqlite_master where type='table' and name='" . $tablename . "'";
            $stmt = $this->db->query($query);
		    while ($row = $stmt->fetch(PDO::FETCH_BOTH)) {
				// もしテーブルがあれば、$row[0]は1になっているはず。
			    $aru = $row[0];
		    }

		    // テーブルがあるということは、テーブル一覧(list)に登録されているってこと。
            // いや、それはわからん。list から削除できても、テーブルが残っていることも
            // ありうる。
            // listに登録されてないからこのメソッドが呼ばれたのである。
            // listに登録されてないのに、テーブルがあるということは、削除失敗。
            // テーブルを削除したうえで、登録作業をする。
            if ($aru) {
                $this->deleteTable($asin);
                $msg = "この商品の過去の消し忘れデータがあったので、削除しました。\n";
            }
            $this->db_index($asin, $tablename, $title);
            $msg =  $msg . "この商品を登録しました。";

			$_SESSION['msg'] = $msg;
		    
            // テーブルがなければ作成 -- タイトルはここには入れない。listに入れてある。
		    // テーブルがすでにあれば、なにもしない。
            $query = "create table if not exists $tablename ( "
                   . "id integer primary key, asin text, official_p integer, new_p integer, "
                   . "used_p integer, collectible_p integer, date text )";
            $stmt = $this->db->query($query);

            // データの追加
		    // もし、このテーブルにすでにデータがあれば、同じ内容を追加してもしかたがない。
		    // この処理は、データ（価格）に変化があったから、それを記録するものである。
		    // だから、この処理をする前に、新規データ、あるいは修正データであるかを調べることが必要。
		    $this->db_addPrice($asin, $price);

        } catch (PDOException $e) {
            putErrorLog($e);
//            $this->db = null;
			return FALSE;
        }

//        $this->db = null;
	    
	    return TRUE;
    }

	public function connectDB() {
		return $this->db;
	}
    public function closeDB() {
        $this->db = null;
        return TRUE;
    }

    // 各テーブルの最新データにタイトルも付け加えて返す
    public function lastData() {
        $lastData = [];
		try {
			// テーブル list から、各テーブル名を取得する。
			$query = "select * from " . INDEX_TABLE;
			$stmt = $this->db->query($query);
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				
				// 各テーブルの最新データと結合した表を取得する。
				$query2 = "select * from " . INDEX_TABLE . " inner join {$row['table_name']} on "
                    . INDEX_TABLE . ".asin = {$row['table_name']}.asin order by id desc limit 1";
				
				$stmt2 = $this->db->query($query2);
				while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
				
					// 結合した最新データの配列を作成。
					array_push($lastData, $row2);
				}
			}
        } catch (PDOException $e) {
            putErrorLog($e);
            return FALSE;
        }
//		$this->db = null;

        return $lastData;
    }

	/**
	 * Summary: 各商品の価格変動の一覧を返す
	 *          'officialPrice', 'newPrice', 'usedPrice', 'collectiblePrice', 'date'
	 *
	 * @params: string $asin -- 商品番号
	 *
	 * @return: array $transPrice
     *
     * id          asin        official_p  new_p       used_p      collectible_p  date
	 * ----------  ----------  ----------  ----------  ----------  -------------  ----------------
     * 1           4873117763  3888        3888        3599        0              2018-04-22 21:57
	 * 2           4873117763  3888        3888        3599        -1             2018-04-22 21:57
	 * 3           4873117763  3888        3888        3265        -1             2018-04-23 03:36
	 * 4           4873117763  3888        3888        3007        -1             2018-04-30 18:29
	 */
	function transPrice($asin) {
		$trPrice = [];
		$tablename = 'db_' . $asin;
		
        // テーブル db_$asin から、データを取得。
        $query = "select * from $tablename";
        $stmt = $this->db->query($query);
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            // 配列をそのままぶちこむ。
            array_push($trPrice, $row);

		}
        return $trPrice;
    }

	/**
	 * Summary: アイテムを削除する.
	 *
	 * @params: string $asin.
	 *
	 * @return: boolean TRUE or FALSE.
	 */
	function deleteTable($asin) {
		// そのアイテムの追跡をしないのだから、テーブルを削除。
		$tablename = $this->getTableName($asin);
        try {
//            $this->db->beginTransaction();
            $query = "drop table if exists " . $tablename;
            $stmt = $this->db->query($query);
            $stmt->execute();
//            $this->db->commit();
        } catch (PDOException $e) {
//            $this->db->rollBack();
            putErrorLog($e);
            return FALSE;
        }
        return TRUE;
	}

    /**
     * Summary: テーブル list から、Itemのエントリを削除する。
     *          ウォッチする Item は、このリストにエントリされている.
     *
     * @params: string $asin
     *
     * @return: boolen
     */
    function deleteFromList($asin) {
        $query = "delete from ". INDEX_TABLE . " where asin = '" . $asin . "'";
        try {
            $stmt = $this->db->query($query);
            $stmt->execute();
        } catch (PDOException $e) {
            putErrorLog($e);
            return FALSE;
        }
        return TRUE;
    }    
	
}
?>
