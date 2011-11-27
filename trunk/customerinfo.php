<?php
	/* Author: Bill Yu, Chun Wai Chan, Patrick Kowalski, Sahra Dilmaghanyan, Sami Jashromi
	 * Date: Nov 17, 2011
	 * File name: customerinfo.php
	 * File description: Displays customer information, inventory, and purchase history.
	 */
	require_once('common.php');
	
	session_start();
	validateSession();
	
	/*
	 * Get detail information about a user.
	 * Return an array of user information.
	 */
	function getUserInfo($cusId) {
		$userInfo = array("cus_name" => "John Chan", "loginname" => 'cchan', 'email' => 'cchan331@my.bc.it.ca');
		return $userInfo;
	}
	
	/*
	 * Get the inventory for a user.
	 * Return list of inventory for a user as item name, quantity pair.
	 */
	function getUserInventory($cusId) {
		$inventory = array(
			array("prod_name" => "item1", "prod_image" => "./img/radioactive_blue.jpg", "qty" => 67),
			array("prod_name" => "item2", "prod_image" => "./img/dry_cow_dung.jpg", "qty" => 13),
			array("prod_name" => "item3", "prod_image" => "./img/bill.jpg", "qty" => 23)
		);
		return $inventory;
	}
	
	/*
	 * Get a list of purchase history for a user.
	 * Return a sorted list of order history as date, order ID.
	 */
	function getUserOrderHistory($cusId) {
		$orderHistory = array("Jan 13, 2011 01:15 AM" => 1, "Jan 02, 2011 11:15 PM" => 2);
		return $orderHistory;
	}
	
	/*
	 * Get the details of an order.
	 * Return a list of product name, quantity pairs.
	 */
	function getOrderDetails($orderId) {
		$order = array("item1" => 1, "item2" => 10);
		return $order;
	}
	
	/*
	 * Script to invoke methods.
	 */
	$method = $_REQUEST['method'];
	if ($method == 'getUserInfo') {
		echo json_encode(getUserInfo($_SESSION['cus_id']));
	}
	else if ($method == 'getUserInventory') {
		echo json_encode(getUserInventory($_SESSION['cus_id']));
	}
	else if ($method == 'getUserOrderHistory') {
		echo json_encode(getUserOrderHistory($_SESSION['cus_id']));
	}
	else if ($method == 'getOrderDetails') {
		echo json_encode(getOrderDetails($_REQUEST['orderId']));
	}
?>	