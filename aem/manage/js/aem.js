jQuery( document ).ready(function( $ ) {
    var clipboard = new Clipboard('.btnCopy');

    clipboard.on('success', function(e) {
        // alert('Form copied to clipboard.');
        jQuery('body').find('.copy_success').show();
    });
    
    var $_GET = {};

    document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
        function decode(s) {
            return decodeURIComponent(s.split("+").join(" "));
        }

        $_GET[decode(arguments[1])] = decode(arguments[2]);
    });

    if(typeof $_GET["action"] != "undefined") {
    	$('body').find('li[id="menu_'+$_GET["action"]+'"]').addClass("active");
    } else {
    	$('body').find('li[id="menu_dashboard"]').addClass("active");	
    }
});