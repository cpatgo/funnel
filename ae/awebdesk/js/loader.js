// loader.js

function adesk_loader_add(id, base) {
    var elem = document.getElementById(id);


    if (elem !== null) {
        var img = document.createElement("img");
        img.src = base + "media/loader.gif";
        img.id  = id + "_loader";

        adesk_dom_remove_children(elem);
        elem.appendChild(img);
    }
}

function adesk_loader_rem(id) {
    var elem = document.getElementById(id);
    var img  = document.getElementById(id + "_loader");

    if (elem !== null && img !== null) {
        elem.removeChild(img);
    }
}

function adesk_loader_show(txt) {
	// cleanup previous
	if ( adesk_error_visible() ) adesk_error_hide();
	if ( adesk_result_visible() ) adesk_result_hide();
	if ( txt == '' ) {
		if ( adesk_loader_visible() ) adesk_loader_hide();
		return;
	} else if ( !txt ) {
		$('adesk_loading_text').innerHTML = nl2br(jsLoading);
	} else {
		$('adesk_loading_text').innerHTML = nl2br(txt);
	}
	$('adesk_loading_bar').className = 'adesk_block';
	if ( typeof(ismobile) != "undefined" && ismobile ) {
		$('adesk_admin_container').style.display = 'none';
	}
}

function adesk_loader_hide() {
	$('adesk_loading_bar').className = 'adesk_hidden';
	if ( typeof(ismobile) != "undefined" && ismobile ) {
		$('adesk_admin_container').style.display = 'inline';
	}
}

function adesk_loader_visible() {
	return $('adesk_loading_bar').className == 'adesk_block';
}

function adesk_loader_flip() {
	adesk_dom_toggle_class('adesk_loading_bar', 'adesk_hidden', 'adesk_block');
}
