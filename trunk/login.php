<?php
	/* Author: Bill Yu, Chun Wai Chan, Patrick Kowalski, Sahra Dilmaghanyan, Sami Jashromi
	 * Date: Nov 17, 2011
	 * File name: login.php
	 * File description: Handles login and logout of users.
	 */
	require_once('common.php');
	
	session_start();
	
	initDb();
	
	/*
	 * Authenticates user. Returns succeed/failure, error_msg. eg. { "succeed":true } or { "succeed":false, "msg":"error message" }.
	 */
	function login($loginname, $password) {
		$succeed = false;
		$msg = "";
		if (USE_DB) {
			$loginname = mysql_real_escape_string($loginname);
			$password = mysql_real_escape_string($password);
			$q = "SELECT cus_id, cus_name FROM tbl_customer WHERE cus_loginname = '$loginname' AND cus_password = '$password'";
			if ($rs = mysql_query($q)) {
				if ($r = mysql_fetch_assoc($rs)) {
					$succeed = true;
					$_SESSION['login'] = true;
					$_SESSION['loginname'] = $loginname;
					$_SESSION['cus_name'] = $r['cus_name'];
					$_SESSION['cus_id'] = $r['cus_id'];
				}
				else {
					$msg = "Invalid credential.";
				}
			}
			else {
				$msg = mysql_error();
			}
		}
		else {
			if ($loginname == "comp1920" && $password == "comp1920") {
				$succeed = true;
				$_SESSION['login'] = true;
				$_SESSION['loginname'] = $loginname;
				$_SESSION['cus_name'] = 'John Chan';
				$_SESSION['cus_id'] = 1;
			}
		}
		return array('succeed' => $succeed, 'msg' => $msg);
	}
	
	/*
	 * Returns the user login info. eg. { "login":true, "loginname":"johnchan" "cus_name": "John Chan"} or { "login":false }.
	 */
	function getLoginInfo() {
		if (isset($_SESSION['login']) && isset($_SESSION['loginname']) && isset($_SESSION['cus_name'])) {
			$result['login'] = $_SESSION['login'];
			$result['loginname'] = $_SESSION['loginname'];
			$result['cus_name'] = $_SESSION['cus_name'];
		}
		else {
			$result['login'] = false;
			$result['loginname'] = null;
			$result['cus_name'] = null;
		}
		return $result;
	}
	
	/*
	 * Logout the current user.
	 */
	function logout() {
		// Unset all of the session variables.
		$_SESSION = array();
		
		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get("session.use_cookies")) {
		    $params = session_get_cookie_params();
		    setcookie(session_name(), '', time() - 42000,
		        $params["path"], $params["domain"],
		        $params["secure"], $params["httponly"]
		    );
		}
		
		// Finally, destroy the session.
		session_destroy();
	}
	
	/*
	 * Script to invoke methods.
	 */
	$method = $_REQUEST['method'];
	if ($method == 'login') {
		echo json_encode(login($_REQUEST['loginname'], $_REQUEST['password']));
	}
	else if ($method == 'getLoginInfo') {
		echo json_encode(getLoginInfo());
	}
	else if ($method == 'logout') {
		logout();
	}
?>