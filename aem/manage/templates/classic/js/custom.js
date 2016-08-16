/*custom JS here*/
jQuery(document).ready(function(){
// 	// var form = $("#example-advanced-form").show();

// 	jQuery("#create-campaign-landingpage").steps({
// 	    headerTag: "h3",
// 	    bodyTag: "section",
// 	    transitionEffect: "slideLeft",
// 	    // stepsOrientation: "vertical"
// 	});

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
		    else
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
	    		jQuery('body').find('select').attr('disabled', true);
	    		jQuery('body').find('input').attr('disabled', true);
	    		jQuery('body').find('textarea').attr('disabled', true);
	    	}
	        form.validate().settings.ignore = ":disabled,:hidden";
	        return form.valid();
	    },
	    onFinishing: function (event, currentIndex)
	    {
	        form.validate().settings.ignore = ":disabled";
	        return form.valid();
	    },
	    onFinished: function (event, currentIndex)
	    {
	        alert("Submitted!");
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
		            jQuery('body').find('#funnel_link').append(result.link)
		        },
		        error: function(errorThrown){
		            console.log(errorThrown);
		        }
		    });
		}
	};

	//GET LISTS
	aem_functions.get_lists();

	//REFRESH LIST
    jQuery('body').on('click', '#refresh_list_list', function(e){
    	e.preventDefault();
    	aem_functions.get_lists();
    });

	//GET FORMS
	// aem_functions.get_forms();

    // jQuery('body').on('click', '#refresh_form_list', function(e){
    // 	e.preventDefault();
    // 	aem_functions.get_forms();
    // });
});