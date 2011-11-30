<?php
	/* Author: Bill Yu, Chun Wai Chan, Patrick Kowalski, Sahra Dilmaghanyan, Sami Jashromi
	 * Date: Nov 17, 2011
	 * File name: storefront.php
	 * File description: Store front to show available merchandise.
	 */

	require_once ('common.php');
	
	session_start();
	validateSession() or die();	// Prevent the script from running further if this fails.
	initDb();
	
	/*
	 * Function to list products available for purchase.
	 * Return a list of product IDs mapping to product details.
	 */
	function getProducts() {
		if (USE_DB) {
			$succeed = true;
			$msg = null;
			$products = null;
			
			// read from product table
			$q = "SELECT * FROM tbl_product";
			if ($result = mysql_query($q)) {
				while ($row = mysql_fetch_assoc($result)) {
					$products[] = array("prod_id" => $row['prod_id'], "prod_name" => $row['prod_name'], "prod_desc" => $row['prod_desc'], "prod_image" => $row['prod_image']);
				}
			}
			else {
				$succeed = false;
				$msg = mysql_error();
			}
			return array('succeed' => $succeed, 'msg' => $msg, 'products' => $products);
		} else {
			$resp = array("succeed" => true, "products" =>
				array("prod_id" => 1, "prod_name" => "item1", "prod_desc" => "item1 description", "prod_image" => "./img/radioactive_blue.jpg"),
				array("prod_id" => 2, "prod_name" => "item2", "prod_desc" => "item2 description", "prod_image" => "./img/dry_cow_dung.jpg"),
				array("prod_id" => 3, "prod_name" => "item3", "prod_desc" => "item3 description", "prod_image" => "./img/bill.jpg")
			);
			return $resp;
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