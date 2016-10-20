(function () {
	"use strict";

	var form_added = 0;

	var clipboard = new Clipboard('.btnCopy');
    clipboard.on('success', function(e) {
        window.top.close();
    });

    // attach new li manually to block container in builder.
	var strbuilder;

	strbuilder = '<li><a href="#" id="travel">Travel</a></li>';
	strbuilder +=	'<li><a href="#" id="thank_you">Thank You</a></li>';

	$('body').find('#elementCats > li:last-child').after(strbuilder);
	
	// $('body').find('#thank_you').parent().html('</li></ul><h3><span class="fui-list menu_categ"> DIY</span></h3><ul id="elementCats">' + '<li><a href id="navigation">Navigation</a></li></ul>');
	// $('body').find("#elementCats").append('<h3 style="margin-top:30px;"><span class="fui-document"></span> Templates</h3><ul id="elementsCats" styles="margin-bottom: 30px;"><li><a href="#" id="travel">Travel</a></li></ul><hr>');

	
    
}());