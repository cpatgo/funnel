jQuery( document ).ready(function( $ ) {
    var clipboard = new Clipboard('.btnCopy');

    clipboard.on('success', function(e) {
        alert('Form copied to clipboard.');
    });
});