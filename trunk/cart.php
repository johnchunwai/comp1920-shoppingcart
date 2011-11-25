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
		$cart = $_SESSION['cart'];
		if (!isset($_SESSION['cart'])) {
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
		return json_encode($result);
	}
	
	/*
	 * Update the quantity of a certain item in the cart.  
	 * Return succeed/failure, new quantity pair. Eg. { true, "qty":34 } or { false, "qty":56 };
	 */
	function updateCartItemCount($prodId, $count) {
		return json_encode(array("succeed" => false, "qty" => 34));
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
		return json_encode($cartContent);
	}
	
	/*
	 * Purchase all items in the cart.
	 * Return succeed/failure, error message pair. Eg. {"succeed":true} or {"succeed":false "msg":"Failed to purchase"};
	 */
	function makePurchase() {
		// Grab the list of items from the cart and add them to user inventory.
		// Then, clear the cart.
		$succeeded = true;
		if (USE_DB) {
			foreach ($_SESSION['cart'] as $prodId => $qty) {
				// Try insert first, then try update.
				$q = "INSERT INTO tbl_cus_inventory (prod_id, cus_id, qty) VALUES ('$prodId', '$_SESSION[cus_id]', '$qty')";
				//die($q);
				//mysql_query($q) or die(mysql_error());
				if (!mysql_query($q)) {
					// insert failed. the entry probably already exists. Do an update instead.
					$q = "UPDATE tbl_cus_inventory SET qty = qty+'$qty' WHERE prod_id = '$prodId' and cus_id = '$_SESSION[cus_id]'";
					// die($q);
					// mysql_query($q) or die(mysql_error());
					if (!mysql_query($q)) {
						return json_encode(array("succeed" => false, mysql_error()));
					}
				}
			}
		}
		if ($succeeded) {
			// If purchase successful, clear the cart contents.
			$_SESSION['cart'] = array();
		}
		
		return json_encode(array("succeed" => true));
	}
	
	
	/*
	 * Script to invoke methods.
	 */
	$method = $_REQUEST['method'];
	if ($method == 'addToCart') {
		echo addToCart($_REQUEST['prod_id'], $_REQUEST['count']);
	}
	else if ($method == 'updateCartItemCount') {
		echo updateCartItemCount($_REQUEST['prodId'], $_REQUEST['count']);
	}
	else if ($method == 'getCartContent') {
		echo getCartContent();
	}
	else if ($method == 'makePurchase') {
		echo makePurchase();
	}
?>	