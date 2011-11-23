<?php
	/* Author: Bill Yu, Chun Wai Chan, Patrick Kowalski, Sahra Dilmaghanyan, Sami Jashromi
	 * Date: Nov 17, 2011
	 * File name: cart.php
	 * File description: Provides functionalities for a shopping cart.
	 */
	
	/*
	 * Adds a certain quantity of an item to the cart.
	 * Return succeed/failure, error message pair. Eg. {true} or {false,"cart add error"};
	 */
	function addToCart($prodId, $count) {
		$result = array(true);
		return json_encode($result);
	}
	
	/*
	 * Update the quantity of a certain item in the cart.  
	 * Return succeed/failure, error message pair.
	 */
	function updateCartItemCount($prodId, $count) {
		return json_encode(array(true, ""));
	}
	
	/*
	 * List the cart content.
	 * Return a list of product ID, names and quantities.
	 */
	function getCartContent() {
		$cartContent = array(1 => array("prod_name" => "item1", "prod_qty" => 3), 3 => array("prod_name" => "item3", "prod_qty" => 1));
		return json_encode($cartContent);
	}
	
	/*
	 * Purchase all items in the cart.
	 * Return succeed/failure, error message pair.
	 */
	function makePurchase() {
		return json_encode(array(true, ""));
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
	else if ($method == 'getCartContent2') {
		echo getCartContent();
	}
	else if ($method == 'makePurchase') {
		echo makePurchase();
	}
?>	