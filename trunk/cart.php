<?php
	/* Author: Bill Yu, Chun Wai Chan, Patrick Kowalski, Sahra Dilmaghanyan, Sami Jashromi
	 * Date: Nov 17, 2011
	 * File name: cart.php
	 * File description: Provides functionalities for a shopping cart.
	 */
	require_once('common.php');
	require_once('storefront.php');
	
	//
	// The followings are done by storefront.php already.
	//
	//session_start();
	//if (!validateSession()) {
	//	return null;
	//}
	//initDb();
	
	
	// Initialize the shopping cart.
	if (!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = array();
	}
	
	/*
	 * Adds a certain quantity of an item to the cart.
	 * Return succeed/failure, error message pair. Eg. {"succeed":true} or {"succeed":false,"msg":"cart add error"};
	 */
	function addToCart($prodId, $count) 
	{
		$succeeded = true;
		$msg = null;
		if ($count < 0) {
			$succeeded = false;
			$msg = 'Unable to add negative amount for a product.';
		}
		if (!isset($_SESSION['cart'][$prodId]))
		{
			// If the product is not added to cart, add it.
			$_SESSION['cart'][$prodId] = $count;
		}
		else
		{
			// The product is already in cart, check if it reaches the cap of 100 (constant defined in common.php).
			if ($_SESSION['cart'][$prodId] == MAX_COUNT_PER_ITEM_IN_CART)
			{
				$succeeded = false;
				$msg = 'Reached the limited of ' . MAX_COUNT_PER_ITEM_IN_CART . ' items per product in cart.';
			}
			else
			{
				// It hasn't reached the cap yet, add it to cart.
				$_SESSION['cart'][$prodId] += $count;
				// Make sure we don't exceed our limit.
				if ($_SESSION['cart'][$prodId] > MAX_COUNT_PER_ITEM_IN_CART)
				{
					$_SESSION['cart'][$prodId] = MAX_COUNT_PER_ITEM_IN_CART;
				}
			}
		}
		if ($succeeded)
		{
			$msg = "Product ID: $prodId Total quantity: " . $_SESSION['cart'][$prodId];
		}
		return array('succeed' => $succeeded, 'msg' => $msg);
	}
	
	/*
	 * Update the quantity of a certain item in the cart.  
	 * Return succeed/failure, new quantity pair. Eg. { true, "qty":34 } or { false, "qty":56 };
	 */
	function updateCartItemCount($prodId, $count) {
		$succeeded = true;
		$qty = 0;
		$msg = null;
		if (!isset($_SESSION['cart'][$prodId])) {
			$succeeded = false;
			$msg = 'Product ID: ' . $prodId . ' is not in the cart.';
		} else {
			if ($count == 0) {
				unset($_SESSION['cart'][$prodId]);
			} else {
				if ($count > MAX_COUNT_PER_ITEM_IN_CART || $count < 0) {
					$succeeded = false;
					$msg = 'Failed to change quantity: quantity is either negative or greater than max limit of ' . MAX_COUNT_PER_ITEM_IN_CART;
				}
				else {
					$_SESSION['cart'][$prodId] = $count;
				}
				$qty = $_SESSION['cart'][$prodId];
			}
		}
		return array('succeed' => $succeeded, 'msg' => $msg, 'qty' => $qty);
	}
	
	/*
	 * List the cart content.
	 * Return a list of product ID, names and quantities.
	 */
	function getCartContent() {
		if (USE_DB) {
			$cartContent = array();
			$productsResp = getProducts();
			$products = $productsResp['products'];
			foreach ($_SESSION['cart'] as $prodId => $qty) {
				$foundProduct = null;
				foreach ($products as $product) {
					if ($product['prod_id'] == $prodId) {
						$foundProduct = $product;
						break;
					}
				}
				if ($foundProduct) {
					$cartContent[] = array('prod_id' => $prodId, 'prod_name' => $foundProduct['prod_name'],
						'prod_image' => $foundProduct['prod_image'], 'qty' => $qty);
				}
				else {
					// if the product is not found, remove it from the cart.
					unset($_SESSION['cart'][$prodId]);
				}
			}
			return $cartContent;			
		}
		else {
			$cartContent = array(
				array("prod_id" => 1, "prod_name" => "item1", "prod_image" => "./img/radioactive_blue.jpg", "qty" => 67),
				array("prod_id" => 2, "prod_name" => "item2", "prod_image" => "./img/dry_cow_dung.jpg", "qty" => 13),
				array("prod_id" => 3, "prod_name" => "item3", "prod_image" => "./img/bill.jpg", "qty" => 23)
			);
			return $cartContent;
		}
	}
	
	/*
	 * Purchase all items in the cart.
	 * Return succeed/failure, error message pair. Eg. {"succeed":true} or {"succeed":false "msg":"Failed to purchase"};
	 */
	function makePurchase($cusId) {
		// Grab the list of items from the cart and add them to user inventory.
		// Then, clear the cart.
		$succeeded = true;
		$purchasedProdIds = "";
		$failedProdIds = "";
		$sqlErrors = "";
		$msg = "";
		if (USE_DB) {
			$cusId = mysql_real_escape_string($cusId);
			$firstItem = true;
			$orderId = null;
			foreach ($_SESSION['cart'] as $prodId => $qty) {
				// Add the order history first.
				if ($firstItem) {
					$q = sprintf("INSERT INTO tbl_order (cus_id, ord_time) VALUES ('%d', '%d')", $cusId, time());
					if (!mysql_query($q)) {
						// If this fails, do nothing.
						$productPurchased = false;
						$succeeded = false;
						$sqlErrors .= ' ' . mysql_error() . ' ';
						break;
					}
					else {
						$orderId = mysql_insert_id();
						$msg = " Your order ID is $orderId";
					}
					$firstItem = false;
				}
				
				//
				// Start transaction.
				//
				
				mysql_query('START TRANSACTION');
				
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
					}
				}
				
				// Update order history
				if ($productPurchased) {
					$q = sprintf("INSERT INTO tbl_order_item (ord_id, prod_id, ord_qty) VALUES ('%d', '%d', '%d')",
						$orderId, $prodId, $qty);
					if (!mysql_query($q)) {
						$productPurchased = false;
					}
				}
								
				// If this product is purchase, remove it from the cart.
				if ($productPurchased) {
					mysql_query('COMMIT');
					unset($_SESSION['cart'][$prodId]);
					$purchasedProdIds .= " $prodId ";
				}
				else {
					mysql_query('ROLLBACK');
					$succeeded = false;
					$failedProdIds .= " $prodId ";
					$sqlErrors .= ' ' . mysql_error() . ' ';
				}

				//
				// End transaction.
				//
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
		echo json_encode(makePurchase($_SESSION['cus_id']));
	}
?>	