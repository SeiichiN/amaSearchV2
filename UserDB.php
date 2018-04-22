<?php
// UserDB.php

const USER_TABLE = 'user';

class UserDB {
	private $db;

	function __construct() {
		$this->db = new PDO('sqlite:user.db');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	    // USER_TABLEテーブルがなければ作る
	    $query = "create table if not exists " . USER_TABLE . " ( "
		       . "id integer primary key, loginId text, fullName text, password text, email text )";
	    $stmt = $this->db->query($query);

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
