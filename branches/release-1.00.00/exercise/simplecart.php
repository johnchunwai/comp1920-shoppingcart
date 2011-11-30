<?php
	/* Author: Bill Yu, Chun Wai Chan, Patrick Kowalski, Sahra Dilmaghanyan, Sami Jashromi
	 * Date: Nov 17, 2011
	 * File name: simplecart.php
	 * File description: Provides functionalities for a shopping cart.
	 */
	
	/*
	 * Adds a certain quantity of an item to the cart.
	 */
	function addToCart($prodName, $count) {
		//
		// IMPLEMENT THIS!!!
		//
	}
	
	/*
	 * List the cart content.
	 * Return a list of product names and quantities.
	 */
	function getCartContent() {
		$content = '<ul>';
		foreach ($_SESSION['cart'] as $prodName => $count) {
			$content .= '<li>Product: ' . $prodName . ', Quantity: ' . $count . '</li>';
		}
		$content .= '</ul>';
		return $content;
	}
	
	
	session_start();	
	
	// Initialize the shopping cart.
	if (!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = array();
	}
	
	// Process adding item to cart.
	if (isset($_REQUEST['product']))
	{
		$prodName = $_REQUEST['product'];
		$count = $_REQUEST['quantity'];
		addToCart($prodName, $count);
	}

	echo '<h1>Product List</h1>';
	echo '<form action="simplecart.php" method="get">';
	echo 'Product: <select name="product">';
	echo '<option value="Product AAA">Product AAA</option>';
	echo '<option value="Product BBB">Product BBB</option>';
	echo '<option value="Product CCC">Product CCC</option>';
	echo '</select>';
	echo ' Quantity: <select name="quantity">';
	for ($i = 1; $i < 10; $i++) {
		echo '<option value="' . $i . '">' . $i . '</option>';
	}
	echo '</select>';
	echo ' <input type="submit" value="Add to Cart"/>';
	echo '</form>';
	echo '<h1>Cart Content</h1>';
	echo getCartContent();
	echo '<form action="clearcart.php" method="get">';
	echo '<input type="submit" value="Clear Cart Content"/>';
	echo '</form>';
?>	