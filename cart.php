<?php
	/* Author: Bill Yu, Chun Wai Chan, Patrick Kowalski, Sahra Dilmaghanyan, Sami Jashromi
	 * Date: Nov 17, 2011
	 * File name: cart.php
	 * File description: Provides functionalities for a shopping cart.
	 */
	require_once('common.php');
	
	session_start();
	validateSession();
	initDb();
	
	
	// Initialize the shopping cart.
	if (!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = array();
	}
	
	/*
	 * Adds a certain quantity of an item to the cart.
	 * Return succeed/failure, error message pair. Eg. {"succeed":true} or {"succeed":false,"msg":"cart add error"};
	 */
	function addToCart($prodId, $count) {
		$succeeded = true;
		if (!isset($_SESSION['cart'][$prodId])) {
			// If the product is not added to cart, add it.
			$_SESSION['cart'][$prodId] = $count;
		}
		else {
			// The product is already in cart, check if it reaches the cap of 100 (constant defined in common.php).
			if ($_SESSION['cart'][$prodId] == MAX_COUNT_PER_ITEM_IN_CART) {
				$succeeded = false;
			}
			else {
				// It hasn't reached the cap yet, add it to cart.
				$_SESSION['cart'][$prodId] += $count;
				// Make sure we don't exceed our limit.
				if ($_SESSION['cart'][$prodId] > MAX_COUNT_PER_ITEM_IN_CART) {
					$_SESSION['cart'][$prodId] = MAX_COUNT_PER_ITEM_IN_CART;
				}
			}
		}
		if ($succeeded) {
			$result = array('succeed' => true, 'msg' => "Product ID: $prodId Total quantity: " . $_SESSION['cart'][$prodId]);
		}
		else {
			$result = array('succeed' => false, 'msg' => 'Reached the limited of ' . MAX_COUNT_PER_ITEM_IN_CART . ' items per product in cart.');
		}
		return $result;
	}
	
	/*
	 * Update the quantity of a certain item in the cart.  
	 * Return succeed/failure, new quantity pair. Eg. { true, "qty":34 } or { false, "qty":56 };
	 */
	function updateCartItemCount($prodId, $count) {
		return array("succeed" => false, "qty" => 34);
	}
	
	/*
	 * List the cart content.
	 * Return a list of product ID, names and quantities.
	 */
	function getCartContent() {
		$cartContent = array(
			1 => array("prod_name" => "item1", "prod_image" => "./data/img/prod_01.jpg", "qty" => 67),
			2 => array("prod_name" => "item2", "prod_image" => "./data/img/prod_02.jpg", "qty" => 13),
			3 => array("prod_name" => "item3", "prod_image" => "./data/img/prod_03.jpg", "qty" => 23)
		);
		// 1) get the cart from session 2) call getProducts in storefront
		// 3) loop through each item in cart, for each prodId, look for the corresponding prod_name and prod_image
		// from the return value from getProducts and put them into an array for return. 
		return $cartContent;
	}
	
	/*
	 * Purchase all items in the cart.
	 * Return succeed/failure, error message pair. Eg. {"succeed":true} or {"succeed":false "msg":"Failed to purchase"};
	 */
	function makePurchase() {
		// Grab the list of items from the cart and add them to user inventory.
		// Then, clear the cart.
		$succeeded = true;
		$purchasedProdIds = "";
		$failedProdIds = "";
		$sqlErrors = "";
		$msg = "";
		if (USE_DB) {
			$cusId = mysql_real_escape_string($_SESSION['cus_id']);
			foreach ($_SESSION['cart'] as $prodId => $qty) {
				// Try insert first, then try update.
				$productPurchased = true;
				$prodId = mysql_real_escape_string($prodId);
				$qty = mysql_real_escape_string($qty);
				$q = sprintf("INSERT INTO tbl_inventory (prod_id, cus_id, qty) VALUES ('%d', '%d', '%d')", $prodId, $cusId, $qty);
				//die($q);
				//mysql_query($q) or die(mysql_error());
				if (!mysql_query($q)) {
					// insert failed. the entry probably already exists. Do an update instead.
					$q = sprintf("UPDATE tbl_inventory SET qty = qty+'%d' WHERE prod_id = '%d' and cus_id = '%d'", $qty, $prodId, $cusId);
					// die($q);
					// mysql_query($q) or die(mysql_error());
					if (!mysql_query($q) || 1 != mysql_affected_rows()) {
						$productPurchased = false;
						$succeeded = false;
						$failedProdIds .= " $prodId ";
						$sqlErrors .= ' ' . mysql_error() . ' ';
					}
				}
				
				// If this product is purchase, remove it from the cart.
				if ($productPurchased) {
					unset($_SESSION['cart'][$prodId]);
					$purchasedProdIds .= " $prodId ";
				}
			}
		}

		if (!$succeeded) {
			$msg = ' Purchased product IDs (' . $purchasedProdIds
				. '). Failed to purchase product IDs (' . $failedProdIds
				. '). sql errors ( ' . $sqlErrors . ')';
		}
		
		return array("succeed" => $succeeded, "msg" => $msg);
	}
	
	
	/*
	 * Script to invoke methods.
	 */
	$method = $_REQUEST['method'];
	if ($method == 'addToCart') {
		echo json_encode(addToCart($_REQUEST['prod_id'], $_REQUEST['count']));
	}
	else if ($method == 'updateCartItemCount') {
		echo json_encode(updateCartItemCount($_REQUEST['prod_id'], $_REQUEST['count']));
	}
	else if ($method == 'getCartContent') {
		echo json_encode(getCartContent());
	}
	else if ($method == 'makePurchase') {
		echo json_encode(makePurchase());
	}
?>	