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
	 * Return a list of user information.
	 */
	function getUserInfo($userId) {
		$userInfo = array("name" => "John Chan");
		return $userInfo;
	}
	
	/*
	 * Get the inventory for a user.
	 * Return list of inventory for a user as item name, quantity pair.
	 */
	function getUserInventory($userId) {
		$inventory = array("item1" => 5, "item2" => 100, "item5" => 1000);
		return $inventory;
	}
	
	/*
	 * Get a list of purchase history for a user.
	 * Return a sorted list of order history as date, order ID.
	 */
	function getUserOrderHistory($userId) {
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
		echo json_encode(getUserInfo($_SESSION['user_id']));
	}
	else if ($method == 'getUserInventory') {
		echo json_encode(getUserInventory($_SESSION['user_id']));
	}
	else if ($method == 'getUserOrderHistory') {
		echo json_encode(getUserOrderHistory($_SESSION['user_id']));
	}
	else if ($method == 'getOrderDetails') {
		echo json_encode(getOrderDetails($_REQUEST['orderId']));
	}
?>	