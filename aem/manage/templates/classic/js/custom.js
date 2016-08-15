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
	    errorPlacement: function errorPlacement(error, element) { element.before(error); },
	    rules: {
	        confirm: {
	            equalTo: "#password"
	        }
	    }
	});
	form.steps({
	    headerTag: "h3",
	    bodyTag: "section",
	    transitionEffect: "slideLeft",
	    onStepChanging: function (event, currentIndex, newIndex)
	    {
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

	//GET LISTS
	jQuery.ajax({
        method: "post",
        url: "../manage/templates/classic/ajax/custom.php",
        data: {
            'action':'get_lists'
        },
        dataType: 'json',
        success:function(result) {
            console.log(result);
            jQuery('body').find('#list_block').append(result);
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
});