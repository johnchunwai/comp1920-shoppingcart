<?php
	/* Author: Bill Yu, Chun Wai Chan, Patrick Kowalski, Sahra Dilmaghanyan, Sami Jashromi
	 * Date: Nov 17, 2011
	 * File name: storefront.php
	 * File description: Store front to show available merchandise.
	 */
	require_once('common.php');
	
	session_start();
	if (!validateSession()) {
		return null;
	}
	 	
	/*
	 * Function to list products available for purchase.
	 * Return a list of product IDs mapping to product details.
	 */
	function getProducts() {
		if (USE_DB) {
			//
			// IMPLEMENT THIS!!!
			//
			$products = array();
			$products = array(
				array("prod_id" => 1, "prod_name" => "item1", "prod_desc" => "item1 description", "prod_image" => "./img/radioactive_blue.jpg"),
				array("prod_id" => 2, "prod_name" => "item2", "prod_desc" => "item2 description", "prod_image" => "./img/dry_cow_dung.jpg"),
				array("prod_id" => 3, "prod_name" => "item3", "prod_desc" => "item3 description", "prod_image" => "./img/bill.jpg")
			);
			// do a sql select from tbl_product. For each product, add it like this:
			// $products[] = array(product details...);
			return $products;
		}
		else {
			$products = array(
				array("prod_id" => 1, "prod_name" => "item1", "prod_desc" => "item1 description", "prod_image" => "./img/radioactive_blue.jpg"),
				array("prod_id" => 2, "prod_name" => "item2", "prod_desc" => "item2 description", "prod_image" => "./img/dry_cow_dung.jpg"),
				array("prod_id" => 3, "prod_name" => "item3", "prod_desc" => "item3 description", "prod_image" => "./img/bill.jpg")
			);
			return $products;
		}
	}
	
	/*
	 * Script to invoke methods.
	 */
	$method = $_REQUEST['method'];
	if ($method == 'getProducts') {
		echo json_encode(getProducts());
	}
?>	