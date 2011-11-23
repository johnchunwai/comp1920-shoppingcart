// Initialize Ajax settings.
function ajaxSetup() {
	$.ajaxSetup({
		 cache: false,
		 error: function(x, e) {
		 	if (x.status == 0) {
		 		alert('You are offline! Please check you network.');
		 	}
		 	else if (x.status == 404) {
		 		alert('Requested URL not found.');
		 	}
		 	else if (x.status == 500) {
		 		alert('Internel server error.');
		 	}
		 	else if (e == 'parseerror') {
		 		alert('Error parsing JSON request.');
		 	}
		 	else if (e == 'timeout') {
		 		alert('Request timed out.');
		 	}
		 	else {
		 		alert('Unknown error: ' + x.responseText);
		 	}
		 }
	});
}
	
// Retrieve the product lists. Must do this synchronously to make sure products is set.
// This is okay since it is only called once (guarded by the window.products global).
function updateProductCatalogue() {
	if (!window.products) {
		$.ajax({
			url: './storefront.php?method=getProducts',
			async: false,
			dataType: 'json',
			success: function(resp) {
				window.products = resp;
			}
		});
	}
}