(function () {
	"use strict";

	var form_added = 0;

	var clipboard = new Clipboard('.btnCopy');
    clipboard.on('success', function(e) {
    	$('#getLinkModal2').modal('hide');
        window.top.close();
    });

    var clipboard2 = new Clipboard('.btnCopy2');
    clipboard2.on('success', function(e) {
    	$('#getLinkModal2').modal('hide');
    });


    // attach new li manually to block container in builder.
	// var strbuilder;

	// strbuilder 	= 	'<li><a href="#" id="travel">Travel</a></li>';
	// strbuilder +=	'<li><a href="#" id="thank_you">Thank You</a></li>';

	// $('body').find('#elementCats > li:last-child').after(strbuilder);
	// 
	
	// add DIVIDER on specific <li> using before or after function
	$("ul#elementCats > li > a#footers").after('<hr>');
	
	// $('body').find('#thank_you').parent().html('</li></ul><h3><span class="fui-list menu_categ"> DIY</span></h3><ul id="elementCats">' + '<li><a href id="navigation">Navigation</a></li></ul>');
	// $('body').find("#elementCats").append('<h3 style="margin-top:30px;"><span class="fui-document"></span> Templates</h3><ul id="elementsCats" styles="margin-bottom: 30px;"><li><a href="#" id="travel">Travel</a></li></ul><hr>');

	$('body').on('mouseenter', '.menu', function(){
        $('body').find('#settings_icon').toggleClass("fui-arrow-right fui-arrow-left");
    });

    $('body').on('mouseleave', '.menu', function(){
        $('body').find('#settings_icon').toggleClass("fui-arrow-right fui-arrow-left");
    });

    $('body').on('click', '#back_to_emarketer', function(){
    	window.location.href = 'https://glchub.com/aem/manage';
    });
    
}());