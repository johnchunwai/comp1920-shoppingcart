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
	 * Authenticates user. Returns succeed/failure, error_msg. eg. { true } or { false }.
	 */
	function login($userId, $password) {
		$result = false;
		if ($userId == "comp1920" && $password == "comp1920") {
			$result = true;
			$_SESSION['login'] = true;
			$_SESSION['user_id'] = $userId;
			$_SESSION['cus_id'] = 1;
		}
		return $result;
	}
	
	/*
	 * Returns the user login info. eg. { "login":true, "user_id":"johnchan" } or { "login":false, "user_id":""}.
	 */
	function getLoginInfo() {
		$result['login'] = $_SESSION['login'];
		$result['user_id'] = $_SESSION['user_id'];
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
		echo json_encode(login($_REQUEST['user_id'], $_REQUEST['password']));
	}
	else if ($method == 'getLoginInfo') {
		echo json_encode(getLoginInfo());
	}
	else if ($method == 'logout') {
		logout();
	}
?>