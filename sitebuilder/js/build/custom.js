(function () {
	"use strict";

	var clipboard = new Clipboard('.btnCopy');
    clipboard.on('success', function(e) {
        window.top.close();
    });
}());