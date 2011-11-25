<?php
	/* Author: Bill Yu, Chun Wai Chan, Patrick Kowalski, Sahra Dilmaghanyan, Sami Jashromi
	 * Date: Nov 24, 2011
	 * File name: common.php
	 * File description: Contains variables or functions that are shared by different php files.
	 */
	
	//
	// Settings
	//
	define('DB_HOSTNAME', '1');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'r');
	define('DB_NAME', 'test');
	define('MAX_COUNT_PER_ITEM_IN_CART', 100);	// Only allow a max of 100 of each item in the cart.
	
	define('USE_DB', false);	// flag to turn on/off using DB (set to false when DB is not ready yet).
	
	// Initialize mysql.
	function initDb() {
		if (USE_DB) {
			mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
			mysql_select_db(DB_NAME) or die(mysql_error());
		}
	}
	
	// Sanity checks for logged in users.
	function validateSession() {
		isset($_SESSION['user_id']) or die("Failed to get user_id");
		isset($_SESSION['cus_id']) or die("Failed to get cus_id");
	}
?>