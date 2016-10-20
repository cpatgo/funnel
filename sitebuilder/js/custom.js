(function () {
	"use strict";

	var form_added = 0;

	var clipboard = new Clipboard('.btnCopy');
    clipboard.on('success', function(e) {
        window.top.close();
    });



    
	
	console.log('testing');

	// $('body').find('#thank_you').parent().html('</li></ul><h3><span class="fui-list menu_categ"> DIY</span></h3><ul id="elementCats">' + '<li><a href id="navigation">Navigation</a></li></ul>');
	// $('body').find("#elementCats").append('<h3 style="margin-top:30px;"><span class="fui-document"></span> Templates</h3><ul id="elementsCats" styles="margin-bottom: 30px;"><li><a href="#" id="travel">Travel</a></li></ul><hr>');
	$('body').find('#elementCats').after('<h3 style="margin-top:30px;"><span class="fui-document"></span> Templates</h3><ul id="elementsCats" styles="margin-bottom: 30px;"><li><a href="#" id="travel">Travel</a></li></ul>');
	
    
}());