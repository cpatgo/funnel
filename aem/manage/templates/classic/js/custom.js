/*custom JS here*/
jQuery(document).ready(function(){
// 	// var form = $("#example-advanced-form").show();

// 	jQuery("#create-campaign-landingpage").steps({
// 	    headerTag: "h3",
// 	    bodyTag: "section",
// 	    transitionEffect: "slideLeft",
// 	    // stepsOrientation: "vertical"
// 	});

	var $body = jQuery('body');

	var form = jQuery("#create-funnel-campaign").show();
	form.validate({
	    errorPlacement: function errorPlacement(error, element) { 
	    	if (element.attr("name") == "landing-page-name" )
		        error.appendTo('#landing-page-name-error');
		    else if  (element.attr("name") == "landing-page-type" )
		        error.appendTo('#landing-page-type-error');
		    else if  (element.attr("name") == "landing-page-list-id" )
		        error.appendTo('#landing-page-list-id-error');
		    else if  (element.attr("name") == "landing-page-url" )
		        error.appendTo('#landing-page-url-error');
		    else if  (element.attr("name") == "list_name" )
		        error.appendTo('#list_name-error');
		    else if  (element.attr("name") == "subscriber_email" )
		        error.appendTo('#subscriber_email-error');
		    else if  (element.attr("name") == "form_name" )
		        error.appendTo('#form_name-error');
		    else if  (element.attr("name") == "sub2_redirect" )
		        error.appendTo('#sub2_redirect-error');
		    else if  (element.attr("name") == "sub3_redirect" )
		        error.appendTo('#sub3_redirect-error');
	    	element.before(error); 
	    },
	    rules: {
	        confirm: {
	            equalTo: "#password"
	        },
	        "landing-page-name": {
	        	required: true
	        },
	        "landing-page-type": {
	        	required: true
	        },
	        "landing-page-list-id": {
	        	required: true
	        },
	        "landing-page-url": {
	        	required: true
	        },
	        "list_name": {
	        	required: true
	        },
	        "subscriber_email": {
	        	required: true
	        },
	        "form_name": {
	        	required: true
	        },
	        "sub2_redirect": {
	        	required: true
	        },
	        "sub3_redirect": {
	        	required: true
	        }
	    }	
	});
	form.steps({
	    headerTag: "h3",
	    bodyTag: "section",
	    transitionEffect: "slideLeft",
	    onStepChanging: function (event, currentIndex, newIndex)
	    {
	    	var step4 = jQuery('#landing-page-url').val();
	    	if(step4.trim() && newIndex == 4) {
	    		//Save campaign
	    		aem_functions.save_funnel_campaign();
	    		//Disable fields
	    		$body.find('select').attr('disabled', true);
	    		$body.find('input').attr('disabled', true);
	    		$body.find('textarea').attr('disabled', true);
	    	}
	    	//Save List
	    	if(currentIndex == 1 && newIndex == 2) {
	    		var method = $body.find('#select_list_method').val();
	    		if(method == 'select_existing_list') {
	    			aem_functions.save_list_to_session();
	    		} else if(method == 'create_new_list') {
	    			aem_functions.add_new_list();
	    		}
	    	}
	    	//Save Form 
	    	if(currentIndex == 2 && newIndex == 3) {
	    		aem_functions.add_new_form();
	    	}
	    	if(currentIndex == 3) {
	    		aem_functions.get_form();
	    	}

	        form.validate().settings.ignore = ":disabled,:hidden";
	        return form.valid();
	    },
	    onFinishing: function (event, currentIndex)
	    {
	    	aem_functions.destroy_list_session();
	        form.validate().settings.ignore = ":disabled";
	        return form.valid();
	    },
	    onFinished: function (event, currentIndex)
	    {
	        window.location.href = "/aem/manage/desk.php?action=funnel_campaign";
	    }
	});

	//COLLECTION OF FUNCTIONS
	var aem_functions = {
		get_lists 	: 	function() {
			jQuery.ajax({
		        method: "post",
		        url: "../manage/templates/classic/ajax/custom.php",
		        data: {
		            'action':'get_lists'
		        },
		        dataType: 'json',
		        success:function(result) {
		            var select_list = jQuery("body").find("#landing-page-list-id");
					select_list.html('');
					select_list.append(jQuery("<option></option>").attr("value", "").text("-- SELECT --"));
					jQuery.each(result.data, function(key, value) {
					    select_list.append(jQuery("<option></option>").attr("value", value.id).text(value.name));
					});
		        },
		        error: function(errorThrown){
		            console.log(errorThrown);
		        }
		    });
		},
		get_forms	: 	function() {
			jQuery.ajax({
		        method: "post",
		        url: "../manage/templates/classic/ajax/custom.php",
		        data: {
		            'action':'get_forms'
		        },
		        dataType: 'json',
		        success:function(result) {
		            var select_form = jQuery("body").find("#landing-page-form-id");
					select_form.html('');
					jQuery.each(result.data, function(key, value) {
					    select_form.append(jQuery("<option></option>").attr("value", value.id).text(value.name));
					});
		        },
		        error: function(errorThrown){
		            console.log(errorThrown);
		        }
		    });
		},
		save_funnel_campaign 	: 	function() {
			var fields = jQuery('#create-funnel-campaign').serialize();
			jQuery.ajax({
		        method: "post",
		        url: "../manage/functions/funnel_campaign.php",
		        data: {
		            'action': 'list_insert_post',
		            'fields': fields
		        },
		        dataType: 'json',
		        success:function(result) {
		            $body.find('#funnel_link').append('<a href="'+result.link+'" target="_blank">'+result.link+'</a>');
		            $body.find('#fb_share').attr('href', "https://www.facebook.com/sharer/sharer.php?u="+result.link);
		            $body.find('#twitter_share').attr('href', "https://twitter.com/home?status="+result.link);
		            $body.find('#email_share').attr('href', "mailto:?body="+result.link);
		        },
		        error: function(errorThrown){
		            console.log(errorThrown);
		        }
		    });
		},
		save_list_to_session 	: 	function() {
			var list_id = jQuery('#landing-page-list-id').val();
			jQuery.ajax({
		        method: "post",
		        url: "../manage/functions/funnel_campaign.php",
		        data: {
		            'action': 'save_list_to_session',
		            'list_id': list_id
		        },
		        dataType: 'json',
		        success:function(result) {
		            
		        },
		        error: function(errorThrown){
		            console.log(errorThrown);
		        }
		    });
		},
		destroy_list_session 	: 	function() {
			jQuery.ajax({
		        method: "post",
		        url: "../manage/functions/funnel_campaign.php",
		        data: {
		            'action': 'destroy_list_session'
		        },
		        dataType: 'json',
		        success:function(result) {
		            
		        },
		        error: function(errorThrown){
		            console.log(errorThrown);
		        }
		    });
		},
		add_new_list 	: 	function(callback) {
			var fields = $body.find('#new_list_div :input').serialize();
			jQuery.ajax({
		        method: "post",
		        url: "../manage/templates/classic/ajax/api.php",
		        data: {
		            'action':'add_list',
		            fields : fields
		        },
		        dataType: 'json',
		        success:function(result) {
		        	if(result.type == 'success') {
		        		aem_functions.get_lists();
		        		aem_functions.add_new_subscriber();
		        	}
		        },
		        error: function(errorThrown){
		            console.log(errorThrown);
		        }
		    });
		},
		add_new_subscriber 	: 	function() {
			jQuery.ajax({
		        method: "post",
		        url: "../manage/templates/classic/ajax/api.php",
		        data: {
		            'action':'add_subscriber',
		            'email' : $body.find('#subscriber_email').val()
		        },
		        dataType: 'json',
		        success:function(result) {
		        	if(result.type == 'error') {
		        		alert(result.message);
		        	}
		        },
		        error: function(errorThrown){
		            console.log(errorThrown);
		        }
		    });
		},
		add_new_form	: 	function() {
			var fields = $body.find('#new_form_div :input').serialize();
			jQuery.ajax({
		        method: "post",
		        url: "../manage/templates/classic/ajax/api.php",
		        data: {
		            'action':'add_form',
		            fields : fields
		        },
		        dataType: 'json',
		        success:function(result) {
		        	if(result.type == 'error') {
		        		alert(result.message);
		        	}
		        },
		        error: function(errorThrown){
		            console.log(errorThrown);
		        }
		    });
		},
		get_form 		: 	function() {
			jQuery.ajax({
		        method: "post",
		        url: "../manage/templates/classic/ajax/api.php",
		        data: {
		            'action':'get_form'
		        },
		        dataType: 'json',
		        success:function(result) {
		        	if(result.type == 'success') {
		        		$body.find('#formcode').text(result.message.html);
		        	}
		        },
		        error: function(errorThrown){
		            console.log(errorThrown);
		        }
		    });
		},
		focus_on_element 	: 	function(element_name) {
			var elementOffset = $body.find(element_name).offset().top;
    		jQuery('html, body').animate({scrollTop: elementOffset}, 600);
		},
		reset_values 		: 	function(element_name) {
			jQuery(element_name).find('input:text').val('');
		}
	};

	//GET LISTS
	aem_functions.get_lists();

	//REFRESH LIST
    $body.on('click', '#refresh_list_list', function(e){
    	e.preventDefault();
    	aem_functions.get_lists();
    });

    $body.on('click', '.newlist_btn', function(e){
    	e.preventDefault();
    	$body.find('#new_list_div').show();
    	$body.find('#new_subscriber_div').show();
    	$body.find('#select_existing_list_div').hide();
    	$body.find('#select_list_method').val('create_new_list');
    	aem_functions.focus_on_element('#new_list_div');
    });

    $body.on('click', '.select_existing_list', function(e){
    	e.preventDefault();
    	$body.find('#select_existing_list_div').show();
    	$body.find('#new_list_div').hide();
    	$body.find('#new_subscriber_div').hide();
    	aem_functions.reset_values('#new_list_div');
    	aem_functions.reset_values('#new_subscriber_div');
    	$body.find('#select_list_method').val('select_existing_list');
    	aem_functions.focus_on_element('#select_existing_list_div');
    });

    $body.on('click', '#add_new_list_go', function(e){
    	e.preventDefault();
    	aem_functions.add_new_list();
    });

    $body.on('click', '#add_new_list_cancel', function(e){
    	e.preventDefault();
    	$body.find('#new_list_div').hide();
    })

    $body.on('click', '#add_new_subscriber', function(e){
    	e.preventDefault();
    	aem_functions.add_new_subscriber();
    });

    $body.on('click', '#add_new_form', function(e){
    	e.preventDefault();
    	aem_functions.add_new_form();
    });

	//GET FORMS
	// aem_functions.get_forms();

    // $body.on('click', '#refresh_form_list', function(e){
    // 	e.preventDefault();
    // 	aem_functions.get_forms();
    // });
});