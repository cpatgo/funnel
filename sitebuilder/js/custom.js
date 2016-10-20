(function () {
	"use strict";

	var form_added = 0;

	var clipboard = new Clipboard('.btnCopy');
    clipboard.on('success', function(e) {
        window.top.close();
    });



    
	
	console.log('testing');

	$('body').find('#video').parent().html('<h3><span class="fui-list menu_categ"> DIY</span></h3>' + '<a href id="navigation">Navigation</a>');
	
    
}());