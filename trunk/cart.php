<?php
	/* Author: Bill Yu, Chun Wai Chan, Patrick Kowalski, Sahra Dilmaghanyan, Sami Jashromi
	 * Date: Nov 17, 2011
	 * File name: cart.php
	 * File description: Provides functionalities for a shopping cart.
	 */
	
	/*
	 * Adds a certain quantity of an item to the cart.
	 * Return succeed/failure, error message pair. Eg. {"succeed":true} or {"succeed":false,"msg":"cart add error"};
	 */
	function addToCart($prodId, $count) {
		$result = array("succeed" => true);
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
		return json_encode($cartContent);
	}
	
	/*
	 * Purchase all items in the cart.
	 * Return succeed/failure, error message pair. Eg. {"succeed":true} or {"succeed":false "msg":"Failed to purchase"};
	 */
	function makePurchase() {
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
	else if ($method == 'getCartContent2') {
		echo getCartContent();
	}
	else if ($method == 'makePurchase') {
		echo makePurchase();
	}
?>	