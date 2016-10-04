var form = $("#upgrade_form").show();

$(function(){
    $('body').find('.common_fields').hide();

    $('#upgrade_form').validate({
        errorPlacement: function errorPlacement(error, element) { 
            if (element.attr("name") == "reg_by" )
                error.appendTo('#errordiv');
            else if  (element.attr("name") == "acceptTerms1" )
                error.appendTo('#terms1_error_container');
            else if  (element.attr("name") == "acceptTerms2" )
                error.appendTo('#terms2_error_container');
            else if (element.attr("name") == "pay_method")
                error.appendTo('#errordiv4');
            else if (element.attr("name") == "email")
                error.appendTo('#email_error_container');
            else if (element.attr("name") == "username")
                error.appendTo('#username_error_container');
            else if (element.attr("name") == "password")
                error.appendTo('#password_error_container');
            else if (element.attr("name") == "re_password")
                error.appendTo('#re_password_error_container');
            else if (element.attr("name") == "payment_l_name")
                error.appendTo('#payment_l_name_error_container');
            else if (element.attr("name") == "payment_f_name")
                error.appendTo('#payment_f_name_error_container');
            else if (element.attr("name") == "country")
                error.appendTo('#country_error_container');
            else if (element.attr("name") == "f_name")
                error.appendTo('#f_name_error_container');
            else if (element.attr("name") == "l_name")
                error.appendTo('#l_name_error_container');
            else if (element.attr("name") == "address_1")
                error.appendTo('#address_1_error_container');
            else if (element.attr("name") == "city")
                error.appendTo('#city_error_container');
            else if (element.attr("name") == "us_state")
                error.appendTo('#us_state_error_container');
            else if (element.attr("name") == "zip")
                error.appendTo('#zip_error_container');
            else if (element.attr("name") == "cc_number")
                error.appendTo('#cc_number_error_container');
            else if (element.attr("name") == "expireMM")
                error.appendTo('#expireMM_error_container');
            else if (element.attr("name") == "expireYY")
                error.appendTo('#expireYY_error_container');
            else if (element.attr("name") == "cc_ccv")
                error.appendTo('#cc_ccv_error_container');
            else if (element.attr("name") == "phone")
                error.appendTo('#phone_error_container');
            else if (element.attr("name") == "checknum")
                error.appendTo('#checknum_error_container');
            else if (element.attr("name") == "routingnum")
                error.appendTo('#routingnum_error_container');
            else if (element.attr("name") == "accountnum")
                error.appendTo('#accountnum_error_container');
            else
                error.insertAfter(element);
        },
        rules: {
            phone: {
                required: true,
                numericRegex: true
            },
            pay_method: {
                required: true
            },
            cc_ccv: {
                required: true,
                minlength: 3
            },
            checknum: {
                required: true
            },
            routingnum: {
                required: true
            },
            accountnum: {
                required: true
            },

        },  
        messages: {
            phone: "Please input valid phone number. Valid phone number must contain the follow '( ) -' and numbers only.",
            pay_method: "Please choose a payment option.",
            cc_ccv: {
                required: "Field required.",
                minlength: "Please enter at least 3 characters."
            },
            routingnum: {
                required: "Field Required."
            },
            accountnum: {
                required: "Field Required."
            },
        }
    });

    
    // $('#upgrade_form').validate().settings.ignore = ":disabled,:hidden";
    

	$('body').on('submit', '#upgrade_form', function(e){
		e.preventDefault();
        $('.loader_processing').show();
        $('#submit_form').prop('disabled', true);
		var fields = $('body').find('#upgrade_form').serialize();
		$.ajax({
            method: "post",
            url: "../glc/admin/ajax/upgrade_membership.php",
            data: {
                'fields': fields,
            },
            dataType: 'json',
            success:function(result) {
                if(result.result == 'error'){
                    window.location.href = '/glc/index.php?page=upgrade_account&err='+result.message;
                } else {
                    window.location.href = '/glc/index.php?page=upgrade_account&msg='+result.message;
                }
            },
            error: function(errorThrown){
                $('#submit_form').prop('disabled', true);
                console.log(errorThrown);
            }
        });
	});

    $('body').on('submit', '#upgrade_form_outside_backoffice', function(e){
        e.preventDefault();
        $('.loader_processing').show();
        $('#submit_form').prop('disabled', true);
        var fields = $('body').find('#upgrade_form_outside_backoffice').serialize();
        $.ajax({
            method: "post",
            url: "../glc/admin/ajax/upgrade_membership.php",
            data: {
                'fields': fields,
            },
            dataType: 'json',
            success:function(result) {
                if(result.result == 'error'){
                    window.location.href = '/glc/upgrade.php?err='+result.message;
                } else {
                    window.location.href = '/glc/upgrade.php?msg='+result.message;
                }
            },
            error: function(errorThrown){
                $('#submit_form').prop('disabled', true);
                console.log(errorThrown);
            }
        });
    });

    $('body').on('submit', '#upgrade_form_outside_backoffice_vip', function(e){
        e.preventDefault();
        $('.loader_processing').show();
        $('#submit_form').prop('disabled', true);
        var fields = $('body').find('#upgrade_form_outside_backoffice_vip').serialize();
        $.ajax({
            method: "post",
            url: "../glc/admin/ajax/upgrade_membership.php",
            data: {
                'fields': fields,
            },
            dataType: 'json',
            success:function(result) {
                if(result.result == 'error'){
                    window.location.href = '/glc/vip_upgrade.php?err='+result.message;
                } else {
                    window.location.href = '/glc/vip_upgrade.php?msg='+result.message;
                }
            },
            error: function(errorThrown){
                $('#submit_form').prop('disabled', true);
                console.log(errorThrown);
            }
        });
    });

    $('body').on('submit', '#upgrade_form_special_membership', function(e){
        e.preventDefault();
        $('.loader_processing').show();
        $('#submit_form').prop('disabled', true);
        var fields = $('body').find('#upgrade_form_special_membership').serialize();
        $.ajax({
            method: "post",
            url: "../glc/admin/ajax/upgrade_special_membership.php",
            data: {
                'fields': fields,
            },
            dataType: 'json',
            success:function(result) {
                if(result.result == 'error'){
                    window.location.href = '/glc/upgrade.php?err='+result.message;
                } else {
                    window.location.href = '/glc/upgrade.php?msg='+result.message;
                }
            },
            error: function(errorThrown){
                $('#submit_form').prop('disabled', true);
                console.log(errorThrown);
            }
        });
    });

    $("input[name=upgrade_membership]").click(function(){
        $('body').find('#amount_text').remove();
        var current_amount = $('body').find('#current_membership_amount').val();
        var new_amount = $(this).data('amt');
        var balance = parseInt(new_amount) - parseInt(current_amount);
        if($(this).prop('checked', true)) {
            $(this).closest('label').append('<span id="amount_text" class="alert-danger" style="padding:5px;"> - Balance Due <b>$'+balance+'</b>. USD</span>');
        }
    });
    
         // input masks for jquery.inputmask
    $('#cc_number').inputmask("9999 9999 9999 999[9]");
    $('#cc_ccv').inputmask({
        'mask': "999[9]",
        'greedy': false
    });
    $
    $("#zip").inputmask({
        'mask': "****[***********]",
        'greedy': false
    });
    
    $('#phone').attr('disabled', 'disabled');

    $("#phone").inputmask({
        'mask': "(999[9])-999-9999[99999]",
        'greedy': false
    });     


    $('body').on('change', '#payment_method', function(){
        $('body').find('.common_fields').hide();
        $('body').find('.common_fields').show();
        if ($(this).val() == 'echeck') {
            $('.cc_form').find(':input').prop('disabled', true);
            $('.echeck_form').find(':input').prop('disabled', false);
            $('body').find('.cc_form').hide();
            $('body').find('.echeck_form').show();
        }
        else if ($(this).val() == 'creditcard') {
            $('.echeck_form').find(':input').prop('disabled', true);
            $('.cc_form').find(':input').prop('disabled', false);
            $('body').find('.echeck_form').hide();   
            $('body').find('.cc_form').show();
        }
    });
// custom validator functions
    $.validator.addMethod("numericRegex", function(value, element) {
        return this.optional(element) || /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/i.test(value);
    }, "Valid phone number must contain the follow '( ) -' numbers only.");

    $.validator.addMethod("usernameRegex", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9_]+((\.(-\.)*-?|-(\.-)*\.?)[a-zA-Z0-9_]+)*$/i.test(value);
    }, "Please use only letters (a-z), numbers, underscores and periods. No spaces allowed.");

    
});