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
		
            mysql_connect("localhost","root","") or die("error connecting");
            mysql_select_db("shoppingcart") or die("databse doesnt exist");
            $q = "SELECT * FROM tbl_product";
            $result = mysql_query($q) or die("cant run query");
        
        
       
            $row1 = mysql_fetch_row($result);
            
         
            $row2 = mysql_fetch_row($result);
         
            $row3 = mysql_fetch_row($result);
			//
			// IMPLEMENT THIS!!!
			//
			$products = array();
			$products = array(
				array("prod_id" => $row1[0], "prod_name" => $row1[1], "prod_desc" => $row1[2], "prod_image" => $row1[3]),
				array("prod_id" => $row2[0], "prod_name" => $row2[1], "prod_desc" => $row2[2], "prod_image" => $row2[3]),
				array("prod_id" => $row3[0], "prod_name" => $row3[1], "prod_desc" => $row3[2], "prod_image" => $row3[3])
			);
			// do a sql select from tbl_product. For each product, add it like this:
			// $products[] = array(product details...);
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