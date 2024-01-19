<?php
require($_SERVER['DOCUMENT_ROOT']."/auth/DBHandler.php");
require($_SERVER['DOCUMENT_ROOT'].'/auth/Bcrypt.php');
class Auth {

    function isNicknameEmpty($username) {
        if(empty($username) || $username == "") {
            return true;
        } else {
            return false;
        }
    }

    function isPasswordEmpty($password) {
        if(empty($password) || $password = "") {
            return true;
        } else {
            return false;
        }
    }

    function isPasswordValid($username, $password) {
        $db_handle = new DBHandler();
        $query = "SELECT * FROM `AUTH` WHERE `NICKNAME` = '" . $username . "'";
        $row = $db_handle->runQuery($query);
        $userdata = $row["HASH"];
        $isPasswordRight = Bcrypt::checkPassword($password, $userdata);
        if ($isPasswordRight) {
            $_SESSION["userid"] = $row["UUID"];
            $_SESSION["user"] = $row["NICKNAME"];
            return true;
        } else {
            return false;
        };
    }

    function getVars($username) {
        $db_handle = new DBHandler();
        $query = "SELECT * FROM `AUTH` WHERE `NICKNAME` = '" . $username . "'";
        $row = $db_handle->runQuery($query);
        return $row;
    }

    function isNicknameValid($username) {
        $db_handle = new DBHandler();
        $query = "SELECT * FROM `registered_users` WHERE `player` = '" . $username . "'";
        $result = $db_handle->runBaseQuery($query);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    function getMemberByUsername($username) {
        $db_handle = new DBHandler();
        $query = "Select * from members where member_name = ?";
        $result = $db_handle->runBaseQuery($query);
        return $result;
    }

    function getTokenByUsername($username,$expired) {
	    $db_handle = new DBHandler();
	    $query = "SELECT * FROM `tbl_token_auth` WHERE `username` = '".$username."' AND `is_expired` = ".$expired;
	    $result = $db_handle->runQuery($query);
	    return $result;
    }
    
    function markAsExpired($tokenId) {
        $db_handle = new DBHandler();
        $query = "UPDATE `tbl_token_auth` SET `is_expired` = ? WHERE id = ?";
        $expired = 1;
        $result = $db_handle->update($query, 'ii', array($expired, $tokenId));
        return $result;
    }
    
    function insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date) {
        $db_handle = new DBHandler();
        $query = "INSERT INTO tbl_token_auth (username, password_hash, selector_hash, expiry_date) values (?, ?, ?, ?)";
        $result = $db_handle->insert($query, 'ssss', array($username, $random_password_hash, $random_selector_hash, $expiry_date));
        return $result;
    }

    function update($query) {
        mysqli_query($this->conn,$query);
    }

    function clearCookies() {
        setcookie("member_login", "", time() - 86400, "/");
        setcookie("random_password", "", time() - 86400, "/");
        setcookie("random_selector", "", time() - 86400, "/");
    }
}
?>