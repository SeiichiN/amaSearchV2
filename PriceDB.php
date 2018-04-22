<?php
// namespace billiesworks

// priceDB.php

// require_once('mylib.php');

const DBNAME = 'watchItem.db';
const INDEX_TABLE = 'list';
const USER_TABLE = 'user';

class PriceDB {
    private $db;
    public $asin, $title, $price;
    
    function __construct() {
        $dbname = 'sqlite:' . DBNAME;
        $this->db = new PDO($dbname);
        // $db = new PDO('sqlite:watchItem.db');
        
        /* $query = "select * from sample";
         * $stmt = $db->query($query);
         * foreach ($stmt as $row) {
         *     print_r($row);
         * }*/
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
     */
    function db_index($asin, $dbtable, $title) {

	    // listテーブルがなければ作る
	    // 最初のウォッチ指定のときにできるので、あとはスルー。
	    $query = "create table if not exists " . INDEX_TABLE . " ( "
		       . "id integer primary key, asin text, table_name text, title text )";
	    $stmt = $this->db->query($query);

	    // データを入れる。新規テーブル（ウォッチ商品）を記録する。
	    $query = "insert into " . INDEX_TABLE . " ( asin, table_name, title ) values (?, ?, ?)";

	    $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $asin, PDO::PARAM_STR);
        $stmt->bindValue(2, $dbtable, PDO::PARAM_STR);
        $stmt->bindValue(3, $title, PDO::PARAM_STR);
        $stmt->execute();
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
	    
	    $query = "insert into $tablename (asin, official_p, new_p, used_p, collectible_p, date) " 
		       . " values (?, ?, ?, ?, ?, ?)";
	    $stmt = $this->db->prepare($query);
	    $stmt->bindValue(1, $asin, PDO::PARAM_STR);
	    $stmt->bindValue(2, $newAmazonPrice['official_p'], PDO::PARAM_INT);
	    $stmt->bindValue(3, $newAmazonPrice['new_p'], PDO::PARAM_INT);
	    $stmt->bindValue(4, $newAmazonPrice['used_p'], PDO::PARAM_INT);
	    $stmt->bindValue(5, $newAmazonPrice['collectible_p'], PDO::PARAM_INT);
	    $stmt->bindValue(6, $date, PDO::PARAM_STR);
	    $stmt->execute();
	    
	    return TRUE;
    }

    /**
     * db_mkitem
     *
     * Summary: データが新規である場合、もしくは、データに変動があった場合、この
     *          関数が呼ばれる。新規データなら、まずテーブル一覧（list）に今から
     *          作成するテーブル名とタイトルを登録し、それからテーブルを作成する。
     *          そして、最初のデータ（価格）を記録する。
     *          データが追加である場合、それはデータに変動があったからである。
     *          この場合は、テーブル一覧（list）にはすでに登録されてあるから、お
     *          こなわれることは、すでに存在するテーブルに変動した価格データを追
     *          加するだけである。
     *          データが新規あるいは追加であるかのチェックはここではしない。この
     *          関数が呼ばれるのは、新規あるいは追加だからである。
     *
     * @params: $asin, string  -- アマゾン商品番号
     *          $title, string  -- その商品のタイトル
     *          $price, array  -- 記録する価格。配列になっている。
     *
     * @return: TRUE, boolean
     */
    public function db_mkitem($asin, $title, $price) {
        try {
            // $db = connectDB();
            $tablename = 'db_' . $asin;

            // テーブルを作成する前に、テーブル一覧(list)にテーブル名を追加。
		    $aru = 0;
            $query = "select count (*) from sqlite_master where type='table' and name='" . $tablename . "'";
            $stmt = $this->db->query($query);
		    while ($row = $stmt->fetch(PDO::FETCH_BOTH)) {
			    $aru = $row[0];
		    }

		    // テーブルがあるということは、テーブル一覧(list)に登録されているってこと。
            if ($aru) {
                echo "この商品はすでに登録されてますね。<br>\n";
            } else {
                echo "この商品を登録しますね。<br>\n";
                $this->db_index($asin, $tablename, $title);
            }
		    
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
            echo "エラー: ", $e->getMessage();
            echo "(File: ", $e->getFile(), ") ";
            echo "(Line: ", $e->getLine(), ")\n";
            die();
        }

        $this->db = null;
	    
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
        return $lastData;
    }

	public function registUser($member) {
        try {
            $query = "insert into " . USER_TABLE . " (loginId, fullName, password, email) "
                   . "values ( ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $member['loginId'], PDO::PARAM_STR);
            $stmt->bindValue(2, $member['name'], PDO::PARAM_STR);
            $stmt->bindValue(3, $member['passwd'], PDO::PARAM_STR);
            $stmt->bindValue(4, $member['email'], PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "エラー: ", $e->getMessage();
            echo "(File: ", $e->getFile(), ") ";
            echo "(Line: ", $e->getLine(), ")\n";
            die();
        }
        return TRUE;
        
	}

	public function findUser($kw, $elem) {
        $flag = FALSE;
		try {
			$query = "select * from user where {$kw} = ?";
			$stmt = $this->db->prepare($query);
			$stmt->bindValue(1, $elem, PDO::PARAM_STR);
			$stmt->execute();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $flag = TRUE;
                $member = [
                    'loginId' => $row['loginId'],
                    'name' => $row['fullName'],
                    'passwd' => $row['password'],
                    'email' => $row['email'],
                ];
            }
		} catch (PDOException $e) {
            echo "エラー: ", $e->getMessage();
            echo "(File: ", $e->getFile(), ") ";
            echo "(Line: ", $e->getLine(), ")\n";
            die();
        }
        return $flag;
    }
	
}
?>
