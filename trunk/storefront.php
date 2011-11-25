<?php
	/* Author: Bill Yu, Chun Wai Chan, Patrick Kowalski, Sahra Dilmaghanyan, Sami Jashromi
	 * Date: Nov 17, 2011
	 * File name: storefront.php
	 * File description: Store front to show available merchandise.
	 */
	require_once('common.php');
	
	session_start();
	validateSession();
	 	
	/*
	 * Function to list products available for purchase.
	 * Return a list of product IDs mapping to product details.
	 */
	function getProducts() {
		$products = array(
			1 => array("prod_name" => "item1", "prod_desc" => "item1 description", "prod_image" => "./data/img/prod_01.jpg"),
			2 => array("prod_name" => "item2", "prod_desc" => "item2 description", "prod_image" => "./data/img/prod_02.jpg"),
			3 => array("prod_name" => "item3", "prod_desc" => "item3 description", "prod_image" => "./data/img/prod_03.jpg")
		);
		return $products;
	}
	
	/*
	 * Script to invoke methods.
	 */
	$method = $_REQUEST['method'];
	if ($method == 'getProducts') {
		echo json_encode(getProducts());
	}
?>	