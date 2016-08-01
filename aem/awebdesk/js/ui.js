// ui.js

var adesk_ui_prompt_width 	= "300px";
var adesk_ui_prompt_top	= "0px";
var adesk_ui_prompt_left	= "0px";

// Create an input box for our "prompt".  Label is what you want the box to say above the
// input element.  Cb is the callback function.
//
// Builder has some problems with parsing functions or javascript for its events.
// Particularly, I've noticed, it has some problems with strings in cb.  The safest method
// I've experienced is make cb look like "func()", where func() is the actual callback.

function adesk_ui_prompt_make(label, vbl) {
	// need to clear out vbl

	if (!adesk_ui_prompt_echeck(vbl))
		return;

	eval(sprintf("%s = null;", vbl));

	var elab = Builder._text(label);
	var einp = Builder.node("input", { style: "border: 1px solid black; font-size: 10px", id: "adesk_ui_prompt_input" });
	var esub = Builder.node("input", { type: "button", onclick: sprintf("%s = $('adesk_ui_prompt_input').value; adesk_ui_prompt_free()", vbl), value: "Submit", style: "font-size: 10px" });

	var ediv = Builder.node("div", { id: "adesk_ui_prompt_div", style: sprintf("font-family: Verdana, San-Serif; text-align: center; border: 1px solid #cccccc; background: #eeeeee; padding: 5px; font-size: 10px; position: absolute; width: %s; top: %s; left: %s", adesk_ui_prompt_width, adesk_ui_prompt_top, adesk_ui_prompt_left) });

	ediv.appendChild(elab);
	ediv.appendChild(Builder.node("br"));
	ediv.appendChild(einp);
	ediv.appendChild(Builder._text(" "));
	ediv.appendChild(esub);

	document.body.appendChild(ediv);
}

function adesk_ui_prompt_free() {
	var elem = $("adesk_ui_prompt_div");

	if (elem !== null)
		document.body.removeChild(elem);
}

function adesk_ui_prompt_echeck(str) {
	return str.match(/^[a-zA-Z_][a-zA-Z0-9_]*$/);
}

function adesk_ui_prompt(label, vbl, waitfor) {
	var val;

	if (waitfor == "" || !adesk_ui_prompt_echeck(waitfor))
		val = "ok";		// anything will do
	else
		val = eval(waitfor);

	if (val !== null)
		adesk_ui_prompt_make(label, vbl);
	else {
		window.setTimeout(function() { adesk_ui_prompt(label, vbl, waitfor); }, 500);
	}
}

function adesk_ui_prompt_waitdo(vars, func) {
	for (var i = 0; i < vars.length; i++) {
		if (!adesk_ui_prompt_echeck(vars[i]))
			return;

		var val = eval(vars[i]);

		if (val === null) {
			window.setTimeout(function() { adesk_ui_prompt_waitdo(vars, func); }, 500);
			return;
		}
	}

	func();
}

/*
var _a = null;
var _b = null;
var _c = null;

adesk_ui_prompt("a", "_a", "");
adesk_ui_prompt("b", "_b", "_a");
adesk_ui_prompt("c", "_c", "_b");
adesk_ui_prompt_waitdo(["_a", "_b", "_c"], function() { alert("done!"); });
*/


/*
	ANCHORS
*/

function adesk_ui_anchor_set(newAnchor, data) {
	adesk_anchor_old = newAnchor;
	window.location.hash = newAnchor;
}
function adesk_ui_anchor_get() {
	return window.location.hash.substr(1);
}
function adesk_ui_anchor_changed() {
	var newAnchor = adesk_ui_anchor_get();
	if ( newAnchor != adesk_anchor_old ) {
		if ( typeof(runPage) == 'function' )
			runPage();
	}
}
function adesk_ui_anchor_init() {
	historyTimer = setInterval(adesk_ui_anchor_changed, 200);
}
var adesk_anchor_old = adesk_ui_anchor_get();
var historyTimer = null;



/*
	Functions that trigger session ping method
	Usages:
	- admin  side: adesk_ui_session_ping_admin();  // every 10 minutes
	- public side: adesk_ui_session_ping_public(); // every 10 minutes
*/
// set session ping
function adesk_ui_session_ping_admin() {
	setInterval(
		function() {
			adesk_ajax_call_cb('../awebdesk/api/public/ping.php', 'sessionping', adesk_ui_session_ping_cb);
		},
		10 * 60 * 1000 // every 10 minutes
	);
}

function adesk_ui_session_ping_cb(xml) {
	// do nothing
}

// set session ping
function adesk_ui_session_ping_public() {
	setInterval(
		function() {
			adesk_ajax_call_cb('awebdesk/api/public/ping.php', 'sessionping', adesk_ui_session_ping_cb);
		},
		10 * 60 * 1000 // every 10 minutes
	);
}


/*
	REAL SIMPLE HISTORY
*/
var adesk_rsh = null;
var adesk_rsh_enabled = true;

function adesk_rsh_listener(newLocation, historyData) {
	// do something
	var msg = 'A history change has occurred!\n\n\nNew Location:\n' + newLocation + '\n\nHistory Data:\n' + historyData;
	alert(msg);
	//adesk_loader_show(nl2br(msg));
}

function adesk_ui_rsh_listenwrapper(func) {
	return function(loc, hist) {
		if (adesk_rsh_enabled)
			func(loc, hist);
	};
}

function adesk_ui_rsh_init(listenerFunction, firstTimeRun) {
	// initialize rsh
	adesk_rsh = window.dhtmlHistory.create(
		{
			toJSON: function(o) {
				return Object.toJSON(o);
			},
			fromJSON: function(s) {
				return s.evalJSON();
			}
		}
	);
	// set fallback function in case function ain't provided
	if ( typeof(listenerFunction) != 'function' ) {
		listenerFunction = adesk_rsh_listener;
	}

	listenerFunction = adesk_ui_rsh_listenwrapper(listenerFunction);

	// prototype-style adding envent observers
	Event.observe(
		window,
		'load',
		function() {
			dhtmlHistory.initialize();
			dhtmlHistory.addListener(listenerFunction);
			if ( firstTimeRun && dhtmlHistory.isFirstLoad() ) {
				listenerFunction(dhtmlHistory.currentLocation, null);
			}
		}
	);
}

function adesk_ui_rsh_stop() {
	adesk_rsh_enabled = false;
}

function adesk_ui_rsh_save(newLocation, historyData) {
	dhtmlHistory.add(newLocation, historyData);
}


/*
	AJAX API CALLS SUPPORTING FUNCTIONS
	(needs standardization, naming at least)
*/

// define default english strings if translatables are not provided
var jsAreYouSure = 'Are You Sure?';
var jsAPIfailed = 'Server call failed for unknown reason. Please try your action again...';
var jsLoading = 'Loading...';
var jsResult = 'Changes Saved.';
var jsResult = 'Error Occurred!';

// define vars used
var resultTimer = false; // used in API call functions
var processingDelay = 60; // seconds! used in API call functions (how long to wait?)
var printAPIerrors = false; // if false, will do alert(!), if {} it will discard, if function it will pass message as param or true DOM ref to print there (innerHTML)

// this function notifies about droppedd api call (after time interval has passed)
// it will stop the loading bar and print out the error if listed
function adesk_ui_api_stop() {
	if ( resultTimer ) {
		window.clearTimeout(resultTimer); // we don't need this, done elsewhere
		resultTimer = false;
	} else {
		return;
	}
	if ( typeof(printAPIerrors) == 'function' ) {
		printAPIerrors(jsAPIfailed);
	} else if ( typeof(printAPIerrors) == 'object' ) {
		printAPIerrors.innerHTML = jsAPIfailed;
	} else {
		alert(jsAPIfailed);
	}
	adesk_loader_hide();
}

// this function should be called right prior to adesk_ajax_*
function adesk_ui_api_call(customMessage, delay) {
	if ( !delay && typeof(delay) != 'number' ) delay = processingDelay;
	if ( delay == 0 ) delay = 60 * 60 * 24; // 24hrs in seconds
	resultTimer = window.setTimeout(adesk_ui_api_stop, delay * 1000);
	adesk_loader_show(customMessage);
}

// this function should be called right at the end of ajax callback function
function adesk_ui_api_callback() {
	// reset the timer
	if ( resultTimer ) {
		window.clearTimeout(resultTimer);
		resultTimer = false;
	}
	// if processing is shown, hide it, since we got our response back
	adesk_loader_hide();
}



/*
	RESULT/ERROR MESSAGES (THE SAME AS LOADER BAR FROM loader.js)
*/

function adesk_result_show(txt) {
	// cleanup previous
	if ( adesk_loader_visible() ) adesk_loader_hide();
	if ( adesk_error_visible() ) adesk_error_hide();
	if ( txt == '' ) {
		if ( adesk_result_visible() ) adesk_result_hide();
		return;
	} else if ( !txt ) {
		$('adesk_result_text').innerHTML = nl2br(jsResult);
	} else {
		$('adesk_result_text').innerHTML = nl2br(txt);
	}
	$('adesk_result_bar').className = 'adesk_block';
	window.setTimeout(adesk_result_hide, 6 * 1000);
}

function adesk_result_hide() {
	$('adesk_result_bar').className = 'adesk_hidden';
}

function adesk_result_visible() {
	return $('adesk_result_bar').className == 'adesk_block';
}

function adesk_result_flip() {
	adesk_dom_toggle_class('adesk_result_bar', 'adesk_hidden', 'adesk_block');
}



function adesk_error_show(txt) {
	// cleanup previous
	if ( adesk_loader_visible() ) adesk_loader_hide();
	if ( adesk_result_visible() ) adesk_result_hide();
	if ( txt == '' ) {
		if ( adesk_error_visible() ) adesk_error_hide();
		return;
	} else if ( !txt ) {
		$('adesk_error_text').innerHTML = nl2br(jsError);
	} else {
		$('adesk_error_text').innerHTML = nl2br(txt);
	}
	$('adesk_error_bar').className = 'adesk_block';
	window.setTimeout(adesk_error_hide, 6 * 1000);
}

function adesk_error_hide() {
	$('adesk_error_bar').className = 'adesk_hidden';
}

function adesk_error_visible() {
	return $('adesk_error_bar').className == 'adesk_block';
}

function adesk_error_flip() {
	adesk_dom_toggle_class('adesk_error_bar', 'adesk_hidden', 'adesk_block');
}


// menu init
function adesk_ui_menu_init() {
	//if ( document.getElementsByClassName('trapperr').length == 0 )
		initjsDOMenu();
}


/* KEY STOPPERS */
// usage: $('inputID').onkeypress = adesk_ui_stopkey_enter;

function adesk_ui_stopkey_enter(evt) {
	var evt = ( evt ? evt : ( event ? event : null ) );
	if ( !evt ) return true;
	var node = ( evt.target ? evt.target : ( evt.srcElement ? evt.srcElement : null ) );
	// 13 == ENTER
	if ( evt.keyCode == 13 && node.type == "text" )  {
		// nope, don't submit
		return false;
	}
}



function adesk_ui_tab_reset(ul) {
	var rel = $(ul);
	if ( !rel ) return;
	var li = rel.getElementsByTagName('li');
	for ( var i = 0; i < li.length; i++ ) {
		if ( li[i].id && li[i].id.substr(0, 9) == 'main_tab_' ) {
			li[i].className = "othertab";
			var tabname = li[i].id.substr(9);
			var tab = $(tabname);
			if ( tab ) {
				tab.className = 'adesk_hidden';
			}
		}
	}
}

// can call it onkeyup
function adesk_ui_isnumber(obj) {
	return obj.value.match(/^\d+$/);
}
function adesk_ui_numbersonly(obj, allowBlank) {
	if ( obj.value == '' ) return allowBlank;
	// isn't a number
	if ( !adesk_ui_isnumber(obj) ) {
		// cutoff the last digit
		obj.value = obj.value.replace(/[^\d]/g, '');
		if ( obj.value == '' ) return allowBlank;
		return adesk_ui_isnumber(obj);
	}
	return true;
}

function adesk_ui_openwindow(url) {
	var rand = Math.floor(Math.random() * 1000.0);
	var winname = "adesk_ui_openwindow_" + rand.toString();
	var w = window.open(url, winname, "width=600,height=500,menubar=yes,toolbar=yes,scrollbars=yes,resizable=yes");
	if ( !w ) return false;
	if ( w.focus ) {
		w.focus();
	}
	return winname;
}

function adesk_ui_error_mailer(txt, modal2close) {
	adesk_ui_api_callback();
	if ( jsErrorMailerBarMessage != '' ) adesk_error_show(jsErrorMailerBarMessage);
	// first close the modal
	if ( modal2close ) adesk_dom_toggle_display(modal2close, 'block');

	var msg = '';
	// try trapperr error
	var matches = txt.match(/<i>Message:<\/i> <b>(.*)<\/b><br \/>/);
	if ( matches && matches[1] ) {
		var err = matches[1].split(/<br \/>/);
		if ( err && err[1] ) {
			// use err[1] to populate the error
			msg = err[1];
		}
	// try default php error
	} else if ( matches = txt.match(/<b>Fatal error<\/b>:  <br \/>(.*)/) ) {
		var err = matches[1].split(/<br \/>/);
		if ( err && err[0] ) {
			// use err[1] to populate the error
			msg = err[0];
		}
	// try other default php error
	} else if ( matches = txt.match(/Fatal error: <br \/>(.*)/) ) {
		var err = matches[1].split(/<br \/>/);
		if ( err && err[0] ) {
			// use err[1] to populate the error
			msg = err[0];
		}
	} else {
		msg = nl2br(txt);
	}

	$('error_mailer_message').innerHTML = msg;
	$('error_mailer_message_box').className = ( msg != '' ? 'adesk_block' : 'adesk_hidden' );

	// show the error screen
	adesk_dom_toggle_display('error_mailer', 'block');
	// now reset the text handler
	adesk_ajax_handle_text = null;
}
