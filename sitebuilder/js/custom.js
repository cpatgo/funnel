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
	
    
}());