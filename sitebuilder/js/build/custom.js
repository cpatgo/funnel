(function () {
	"use strict";

	var form_added = 0;

	var clipboard = new Clipboard('.btnCopy');
    clipboard.on('success', function(e) {
        window.top.close();
    });
}());