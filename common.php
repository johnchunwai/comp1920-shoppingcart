<?php
	/* Author: Bill Yu, Chun Wai Chan, Patrick Kowalski, Sahra Dilmaghanyan, Sami Jashromi
	 * Date: Nov 24, 2011
	 * File name: common.php
	 * File description: Contains variables or functions that are shared by different php files.
	 */
	
	//
	// Settings
	//
	define('DB_HOSTNAME', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'root');
	define('DB_NAME', 'shoppingcart');
	define('MAX_COUNT_PER_ITEM_IN_CART', 100);	// Only allow a max of 100 of each item in the cart.
	
	define('USE_DB', true);	// flag to turn on/off using DB (set to false when DB is not ready yet).
	
	// Common initialize routines.
	session_start();
	// This avoids $_COOKIE to mess with $_REQUEST.
	$_REQUEST = array_merge($_GET, $_POST);
	if (USE_DB) {
		mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
		mysql_select_db(DB_NAME) or die(mysql_error());
	}
	
	// Sanity checks for logged in users.
	function validateSession() {
		return (isset($_SESSION['login']) && isset($_SESSION['loginname'])
				&& isset($_SESSION['cus_name']) && isset($_SESSION['cus_id']));
	}
?>