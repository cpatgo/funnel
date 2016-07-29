// validation.js
// insert jquery page validations here. 
// 

jQuery(document).ready(function($){

	$("form#referafriend").submit(function(){
		
		// check if fields are not empty
		if ( $.trim("#friendemail").length == 0 || $("#friendname").val() == "" ) {
			alert('All fields are mandatory!');
			e.preventDefault();
		}

	});



	function validateEmail(sEmail) {
		var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
		
		if (filter.test(sEmail)) {
			return true;
		}
		else {
			return false;
		}
	}
});


