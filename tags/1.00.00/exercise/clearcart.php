<?php
	/* Author: Bill Yu, Chun Wai Chan, Patrick Kowalski, Sahra Dilmaghanyan, Sami Jashromi
	 * Date: Nov 28, 2011
	 * File name: clearcart.php
	 * File description: Remove all items from the shopping cart.
	 */
	
	session_start();
	// Initialize the shopping cart.
	if (isset($_SESSION['cart'])) {
		$_SESSION['cart'] = array();
		header('Location: ./simplecart.php');
	}
?>