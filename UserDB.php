<?php
// UserDB.php
require_once('conf/db_conf.php');
require_once('lib/mylib.php');

class UserDB {
	private $db;

	function __construct() {
		$this->db = new PDO(DB_NAME);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	    // USER_TABLEテーブルがなければ作る
	    $query = "create table if not exists " . USER_TABLE . " ( "
		       . "id integer primary key, loginId text, fullName text, password text, email text )";
	    $stmt = $this->db->query($query);

	}

    /**
     * Summary: ユーザーを登録する
     *
     * @params: array $member -- 連想配列 'loginId', 'name', 'passwd', 'email', 'firstPW'
     *
     * @return: boolean TRUE
     */
	public function registUser($member) {
        try {
            $query = "insert into " . USER_TABLE . " (loginId, fullName, password, email, firstPW) "
                   . "values ( ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $member['loginId'], PDO::PARAM_STR);
            $stmt->bindValue(2, $member['name'], PDO::PARAM_STR);
            $stmt->bindValue(3, $member['passwd'], PDO::PARAM_STR);
            $stmt->bindValue(4, $member['email'], PDO::PARAM_STR);
            $stmt->bindValue(5, $member['firstPW'], PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            putErrorLog($e);
			return FALSE;
        }
        return TRUE;
        
	}

	/**
     * ログイン処理で使う
     *
     * Summary: ユーザーを見つける。
     *          この関数は、ユーザー名とパスワードの有効を調べるために作った。
     *
     * @params: string $kw -- loginId か fullName, password, email を指定。
     *          string $elem -- ログインID, なまえ、パスワード、メルアドを指定。
     *
     * @return: boolean TRUE or FALSE
     */
	public function findUser($kw, $elem) {
        $flag = FALSE;
		try {
			$query = "select * from " . USER_TABLE . " where {$kw} = ?";
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
            putErrorLog($e);
			return FALSE;
        }
        return $flag;
    }

    /**
     * Summary: ログインIDが存在するか確認する.
     * 
     * @params: string $loginId -- ログインID.
     * 
     * @return: boolean TRUE.
     */
    public function existLoginId($loginId) {
        $query = "select count(id) from " . USER_TABLE . " where loginId = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $loginId, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        $cnt = (int)$count['count(id)'];
        if ($cnt > 0)
            return TRUE;
        else
            return FALSE;
    }
    
    /**
     * Summary: Eメールアドレスが存在するか確認する.
     * 
     * @params: string $email -- Eメール
     * 
     * @return: boolean TRUE.
     */
    public function existEmail($email) {
        $query = "select count(id) from " . USER_TABLE . " where email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $email, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        $cnt = (int)$count['count(id)'];
        if ($cnt > 0)
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Summary: メールアドレスを取得する
     *
     * @params: string $loginId -- ログイン名
     *
     * @return: string $email -- メールアドレス
     */
    public function getMailAddress($loginId) {
        try {
            $query = "select email from " . USER_TABLE . " where loginId = ? limit 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $loginId, PDO::PARAM_STR);
            $stmt->execute();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $email = $row['email'];
            }
        } catch (PDOException $e) {
            putErrorLog($e);
			return FALSE;
        }
        return $email;
    }

    /**
     * Summary: パスワードを取得する
     * 
     * @params: string $loginId -- ログイン名
     * 
     * @return: string $passwd -- パスワード
     */
	public function getPasswd($loginId) {
		try {
            $query = "select password from " . USER_TABLE . " where loginId = ? limit 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $loginId, PDO::PARAM_STR);
            $stmt->execute();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $passwd = $row['password'];
            }
        } catch (PDOException $e) {
            putErrorLog($e);
			return FALSE;
        }
        return $passwd;
    }

	/**
     * Summary: 初期パスワードを取得する
     * 
     * @params: string $loginId.
     *
     * @return: string $firstPW.
     */
    public function getFirstPW($loginId) {
        $query = "select firstPW from " . USER_TABLE . " where loginId = ? limit 1";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $loginId, PDO::PARAM_STR);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $firstPasswd = $row['firstPW'];
            }
        } catch (PDOException $e) {
            putErrorLog($e);
			return FALSE;
        }
        return $firstPasswd;
    }
        
    /**
     * Summary: フルネームを取得する
     * 
     * @params: string $loginId -- ログイン名
     * 
     * @return: string $fullname -- フルネーム
     */
	public function getFullName($loginId) {
		try {
            $query = "select fullName from " . USER_TABLE . " where loginId = ? limit 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $loginId, PDO::PARAM_STR);
            $stmt->execute();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $fullName = $row['fullName'];
            }
        } catch (PDOException $e) {
            putErrorLog($e);
			return FALSE;
        }
        return $fullName;
    }

    

    /**
     * Summary: activibyカラムに 1 をセットする。
     *          デフォルトでは 0 がセットされている。
     *          ユーザからの登録申請に対して
     *          こちらから初期パスワードの連絡をした段階で
     *          このメソッドが呼ばれる
     *
     * @params: string $loginId -- ログイン名
     *
     * @return: boolean TRUE
     */
	public function setActivity($loginId) {
		try {
            $query = "update user set activity = 1 where loginId = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $loginId, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            putErrorLog($e);
			return FALSE;
        }
        return TRUE;
    }

    /**
     * Summary: パスワードを変更
     * 
     * @params: string $loginId -- ログイン名
     * 
     * @return: boolean TRUE;
     */
	public function changePasswd($loginId, $newPW) {
		try {
            $query = "update " . USER_TABLE . " set password = :newPW where loginId = :loginId";
            $stmt = $this->db->prepare($query);
			$stmt->bindValue(':newPW', $newPW, PDO::PARAM_STR);
            $stmt->bindValue(':loginId', $loginId, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            putErrorLog($e);
			return FALSE;
        }
        return TRUE;
    }

    /**
     * Summary: ログインIDを変更する
     * 
     * @params: string $loginId -- ログイン名
     * 
     * @return: boolean TRUE;
     */
	public function changeLoginId($loginId, $newLoginId) {
        $id = $this->getId($loginId);
		try {
            $query = "update " . USER_TABLE . " set loginId = '{$newLoginId}' where id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $id, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            putErrorLog($e);
			return FALSE;
        }
        return TRUE;
    }

    /**
     * Summary: メールアドレスを変更する
     * 
     * @params: string $loginId -- ログイン名
     *          string $newEmail -- 新しいメールアドレス
     * 
     * @return: boolean TRUE;
     */
	public function changeMailAddress($loginId, $newEmail) {
		try {
            $query = "update " . USER_TABLE . " set email = '{$newEmail}' where loginId = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $loginId, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            putErrorLog($e);
			return FALSE;
        }
        return TRUE;
    }

	/**
	 * Summary: sqlite3データベースでの id 値を調べる -> 使うのかなあ
	 */
    private function getId($loginId) {
        try {
            $query = "select id from " . USER_TABLE . " where loginId = ? limit 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $loginId, PDO::PARAM_STR);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'];
            }
        } catch (PDOException $e) {
            putErrorLog($e);
			return FALSE;
        }
        return $id;
    }

	public function getUserList() {
        $userList = [];
		$query = "select * from " . USER_TABLE;
        try {
            $stmt = $this->db->query($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($userList, $row);
            }
        } catch (PDOException $e) {
            putErrorLog($e);
			return FALSE;
        }
        return $userList;
    }

    public function deleteUser($loginId) {
        $query = "delete from " . USER_TABLE . " where loginId = :loginId";
        try {
            $stmt=$this->db->prepare($query);
            $stmt->bindValue(':loginId', $loginId, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            putErrorLog($e);
			return FALSE;
        }
        return TRUE;
    }
}

/* $myobj = new UserDB();
 * var_dump($myobj->getFullName('yukiko'));
 * */
