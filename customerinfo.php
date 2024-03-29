<?php
	/* Author: Bill Yu, Chun Wai Chan, Patrick Kowalski, Sahra Dilmaghanyan, Sami Jashromi
	 * Date: Nov 17, 2011
	 * File name: customerinfo.php
	 * File description: Displays customer information, inventory, and purchase history.
	 */
	require_once('common.php');
	
	validateSession() or die();	// Prevent the script from running further if this fails.
	
	/*
	 * Get detail information about a user.
	 * Return an array of user information.
	 */
	function getUserInfo($cusId) {
		$email = 'NA';
		if (USE_DB) {
			// Fetch the email for this customer from tbl_customer. The other info are already in session.
			$q = "SELECT cus_email from tbl_customer WHERE cus_id='$cusId' LIMIT 1";
			if ($rs = mysql_query($q)) {
				if ($r = mysql_fetch_assoc($rs)) {
					$email = $r['cus_email'];	
				}
			}
			else {
				$email = mysql_error();
			}
		}
		else {
			$email = 'cchan@my.bc.it.ca';
		}
		$userInfo = array("cus_name" => $_SESSION['cus_name'], "loginname" => $_SESSION['loginname'], 'email' => $email);
		return $userInfo;
	}
	
	/*
	 * Get the inventory for a user.
	 * Return list of inventory for a user as item name, quantity pair.
	 */
	function getUserInventory($cusId) {
		$succeed = true;
		$msg = null;
		$inventory = null;
		if (USE_DB) {
			// Retrieve the inventory for this customer.
			$q = "SELECT prod.prod_image, prod.prod_name, inv.qty FROM tbl_inventory as inv, tbl_product as prod WHERE cus_id='$cusId' AND inv.prod_id=prod.prod_id";
			if ($rs = mysql_query($q)) {
				while ($r = mysql_fetch_assoc($rs)) {
					$inventory[] = array('prod_image' => $r['prod_image'], 'prod_name' => $r['prod_name'], 'qty' => $r['qty']);
				}
			}
			else {
				$succeed = false;
				$msg = 'Failed to retrieve inventory for customer: ' . mysql_error();
			}
		}
		else {
			$succeed = true;
			$inventory = array(
				array("prod_name" => "item1", "prod_image" => "./img/radioactive_blue.jpg", "qty" => 67),
				array("prod_name" => "item2", "prod_image" => "./img/dry_cow_dung.jpg", "qty" => 13),
				array("prod_name" => "item3", "prod_image" => "./img/bill.jpg", "qty" => 23)
			);
		}
		return array('succeed' => $succeed, 'msg' => $msg, 'inventory' => $inventory);
	}
	
	/*
	 * Get a list of purchase history for a user.
	 * Return a sorted list of order history as date, order ID.
	 */
	function getUserOrderHistory($cusId) {
		if (USE_DB) {
			$succeed = true;
			$msg = null;
			$history = array();
			$cusId = mysql_real_escape_string($cusId);
			$q = sprintf("SELECT ord_id, ord_time FROM tbl_order WHERE cus_id='%d' ORDER BY ord_time DESC", $cusId);
			if ($rs = mysql_query($q)) {
				while ($r = mysql_fetch_assoc($rs)) {
					$order['ord_id'] = $r['ord_id'];
					$order['ord_time'] = date(DATE_COOKIE, $r['ord_time']);
					$history[] = $order;
				}
			}
			else {
				$succeed = false;
				$msg = 'Failed to retrieve order history: ' . mysql_error();
			}
			return array('succeed' => $succeed, 'msg' => $msg, 'history' => $history);
		}
		else {
			$orderHistory = array('succeed' => true, 'msg' => '', 'history' => array(
					array('ord_id' => 1, 'ord_time' => "Jan 13, 2011 01:15 AM"),
					array('ord_id' => 2, 'ord_time' => "Jan 02, 2011 11:15 PM")
				)
			);
			return $orderHistory;
		}
	}
	
	/*
	 * Get the details of an order.
	 * Return a list of product name, quantity pairs.
	 */
	function getOrderDetail($orderId) {
		if (USE_DB) {
			$succeed = true;
			$msg = null;
			$detail = array();
			$orderId = mysql_real_escape_string($orderId);
			$q = sprintf("SELECT p.prod_name, o.ord_qty FROM tbl_order_item AS o, tbl_product AS p WHERE o.ord_id='%d' AND o.prod_id=p.prod_id", $orderId);
			if ($rs = mysql_query($q)) {
				while ($r = mysql_fetch_assoc($rs)) {
					$item['prod_name'] = $r['prod_name'];
					$item['qty'] = $r['ord_qty'];
					$detail[] = $item;
				}
			}
			else {
				$succeed = false;
				$msg = 'Failed to retrieve order detail: ' . mysql_error();
			}
			return array('succeed' => $succeed, 'msg' => $msg, 'detail' => $detail);
		}
		else {
			$order = array('succeed' => true, 'msg' => '', 'detail' => array(
						array('prod_name' => 'item01', 'qty' => 10),
						array('prod_name' => 'item02', 'qty' => 20)
					)
				);
			return $order;
		}
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
	else if ($method == 'getOrderDetail') {
		echo json_encode(getOrderDetail($_REQUEST['ord_id']));
	}
?>	