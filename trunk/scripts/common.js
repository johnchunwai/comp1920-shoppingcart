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

// Check if the user has logged in. If not, redirect user back to login page.
function checkLogin() {
	// Check whether the user already logged in and redirect to login page if not.
	$.getJSON("./login.php?method=getLoginInfo", function(resp) {
		if (!resp.login) {
			$("body").html("User needs to login first. Redirecting to login page in 2 seconds.");
			setTimeout(function() {
				window.location = "login.html";
			}, 2000);
		}
	});
}

// Set the user_id class span to the userId.
function updateUserId() {
	$.getJSON("./customerinfo.php?method=getUserInfo", function(resp) {
		$(".cus_name").html(resp.cus_name);
	});

}

// Helper function to create a HTML select with the specified id,
// starting at startIndex till endIndex, selected index is optional.
function createSelectForQuantity(id, startIndex, endIndex, selectedIndex) {
	if (selectedIndex == undefined) {
		selectedIndex = startIndex - 1;
	}
	content = '<select id="' + id + '">';
	for (i = startIndex; i <= endIndex; i++) {
		content += '<option value="' + i + '"';
		if (i == selectedIndex) {
			content += ' selected="selected"';
		}
		content	+= '>' + i + '</option>';
	}
	content += '</select>';
	return content;
}