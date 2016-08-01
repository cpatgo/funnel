// jsmodules.js
// Loading...

// ajax.js

var adesk_ajax_debug = true;

function adesk_ajax_request_object() {
    var hreq;

    try {
        hreq = new XMLHttpRequest();
    } catch (e) {
        try {
            hreq = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (__e) {
            hreq = null;
        }
    }

    if (hreq !== null) {
//      try {
//          hreq.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//      } catch (e) {}
    }

    return hreq;
}

function adesk_ajax_init() {
}

function adesk_ajax_call_url(url, post, cb) {
    var hreq = adesk_ajax_request_object();

    if (hreq !== null) {
        hreq.onreadystatechange = function() {
            try {
                adesk_ajax_handle(hreq, cb);
            } catch (e) {}
        };
	    var method = ( post === null ? 'GET' : 'POST' );
	    var postType = typeof(post);
	    if ( post !== null ) {
		    if ( postType == 'array' || postType == 'object' ) {
		    	var postArr = new Array();
		        for ( var i in post ) {
				    var postType = typeof(post[i]);
				    if ( postType == 'array' || postType == 'object' ) {
				        for ( var j in post[i] ) {
				    		if ( typeof(post[i][j]) != 'function' ) {
			            		postArr.push(i + '[' + ( j == 'undefined' ? '' : j ) + ']=' + encodeURIComponent(post[i][j]));
				    		}
				        }
				    } else if ( postType != 'function' ) {
		            	postArr.push(i + '=' + encodeURIComponent(post[i]));
				    }
			    }
			    post = postArr.join('&');
		    }
	    }
        hreq.open(method, url, true);
        if ( post !== null ) {
        	hreq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			//hreq.setRequestHeader("Content-length", post.length);		// IE doesn't like these
			//hreq.setRequestHeader("Connection", "close");
        }
        hreq.send(post);
    }
}

function adesk_ajax_proxy_call_url(base, url, post, cb) {
	if (post !== null)
		adesk_ajax_call_url(base + "/awebdesk/functions/ajax_proxy.php?post=1&url=" + adesk_b64_encode(url), post, cb);
	else
		adesk_ajax_call_url(base + "/awebdesk/functions/ajax_proxy.php?url=" + adesk_b64_encode(url), post, cb);
}

function adesk_ajax_proxy_call_cb(base, url, func, cb) {
    if (url.match(/\?/))
        url = url + "&f=" + func;
    else
        url = url + "?f=" + func;

    url += "&rand=" + adesk_b64_encode(Math.random().toString());

    if (arguments.length > 3) {
        for (var i = 4; i < arguments.length; i++)
            url += "&p[]=" + encodeURIComponent(arguments[i]);
    }

    adesk_ajax_proxy_call_url(base, url, null, cb);
}

function adesk_ajax_call(url, func) {
    if (arguments.length < 3)
        adesk_ajax_call_cb(adesk_str_url(url), func, null);
    else {
        adesk_ajax_call_cb(adesk_str_url(url), func, null, adesk_ary_last(arguments, 2));
    }
}

function adesk_ajax_call_cb(url, func, cb) {
    if (func) {
        if (url.match(/\?/))
            url = url + "&f=" + func;
        else
            url = url + "?f=" + func;
    }

    url += "&rand=" + adesk_b64_encode(Math.random().toString());

    if (arguments.length > 3) {
        for (var i = 3; i < arguments.length; i++)
            url += "&p[]=" + encodeURIComponent(arguments[i]);
    }

   if ( cb === null ) cb = function(){};
   adesk_ajax_call_url(url, null, cb);
}

function adesk_ajax_post_cb(url, func, cb, post) {
    if (func) {
        if (url.match(/\?/))
            url = url + "&f=" + func;
        else
            url = url + "?f=" + func;
        }

    url += "&rand=" + adesk_b64_encode(Math.random().toString());

   if ( cb === null ) cb = function(){};
   adesk_ajax_call_url(url, post, cb);
}

function adesk_ajax_handle(hreq, cb) {
    if (hreq !== null) {
        if (hreq.readyState == 4) {
            if (hreq.status == 200) {
                try {
                    var xml = hreq.responseXML;
					if (xml !== null && xml.documentElement !== null) {
						if (cb === null)
							cb = eval("cb_" + xml.documentElement.nodeName);

						if (typeof cb == "function")
							cb(xml.documentElement, hreq.responseText);
					} else {
						if ( hreq.responseText != '' ) {
							if (typeof adesk_ajax_handle_text == 'function')
								adesk_ajax_handle_text(hreq.responseText);
						}
					}
					/*
					var rootNode = ( xml !== null ? xml.documentElement : null );
					if (cb === null && rootNode) {
						cb = eval("cb_" + rootNode.nodeName);
					}
					if (typeof cb == "function")
						cb(rootNode, hreq.responseText);
					*/
                } catch (e) {
					alert(e);
                }
            }
        }
    }
}

function adesk_ajax_cb(cbf) {
	return function(xml, text) {
		window.t_xml  = xml;
		window.t_text = text;
		var ary       = adesk_dom_read_node(xml, null);
		window.t_ary  = ary;

		cbf(ary);
	}
}
// dom.js

function adesk_dom_showif(id, cond) {
	if (cond)
		$(id).show();
	else
		$(id).hide();
}

function adesk_dom_hideif(id, cond) {
	if (cond)
		$(id).hide();
	else
		$(id).show();
}

function adesk_dom_read(tag, filter) {
    return adesk_dom_read_node(document.getElementsByTagName(tag).Items(0), filter);
}

function adesk_dom_read_node(node, filter) {
	if (typeof(filter) != 'function') filter = null;
    var ary = new Array();
    var cnode = null;
    if ( !node ) return null;

    for (var i = 0; i < node.childNodes.length; i++) {
        cnode = node.childNodes[i];

        switch (cnode.nodeType) {
            case 3:     // TEXT_NODE
                ary["__text"] = cnode.nodeValue;
                break;
            case 4:     // CDATA_SECTION_NODE
                ary["__cdata"] = cnode.nodeValue;
                break;
            case 1:     // ELEMENT_NODE
                if (adesk_dom_isnull(cnode.firstChild)) {
					var idx = cnode.nodeName.toLowerCase();

					if (ary[idx] === undefined || (typeof(ary[idx]) != "string" && typeof(ary[idx]) != "array" && typeof(ary[idx]) != "object"))
						ary[idx] = "";
					else {
						if (typeof(ary[idx]) == "string") {
							var tmp = ary[idx];
							ary[idx] = new Array();
							ary[idx].push(tmp);
						}
						ary[idx].push("");
					}
				} else if (adesk_dom_istext(cnode.firstChild)) {
					var idx = cnode.nodeName.toLowerCase();
					var nodedata = ( cnode.textContent !== undefined ? cnode.textContent : cnode.firstChild.nodeValue );

					nodedata = nodedata.replace(/__--acenc:endcdata--__/, "]]>", nodedata);
					if (nodedata.match(/^-?[0-9]+$/) && !nodedata.match(/0+[0-9]+$/))
						nodedata = parseInt(nodedata, 10);

					if (ary[idx] === undefined || (typeof(ary[idx]) != "string" && typeof(ary[idx]) != "array" && typeof(ary[idx]) != "object"))
						ary[idx] = (filter === null) ? nodedata : filter(nodedata);
					else {
						if (typeof(ary[idx]) == "string") {
							var tmp = ary[idx];
							ary[idx] = new Array();
							ary[idx].push(tmp);
						} else if (typeof(ary[idx]) != "array" && typeof(ary[idx]) != "object") {
							alert(typeof(ary[idx]));
							//continue;
						}
						ary[idx].push((filter === null) ? nodedata : filter(nodedata));
					}
				} else {
                    var idx = cnode.nodeName.toLowerCase();

					if (ary[idx] === undefined || (typeof(ary[idx]) != "string" && typeof(ary[idx]) != "array" && typeof(ary[idx]) != "object")) {
                        ary[idx] = new Array();
					}

					ary[idx].push(adesk_dom_read_node(cnode, filter));
                }
                break;
            default:
                break;
        }
    }

    return ary;
}

function adesk_dom_istext(node) {
    return node.nodeType == 3 || node.nodeType == 4;    // TEXT_NODE || CDATA_SECTION_NODE
}

function adesk_dom_isnull(node) {
    return node === null;
}

function adesk_dom_toggle_display(id, val) {
	var disp = $(id).style.display;

	if (disp != "none")
		$(id).style.display = "none";
	else
		$(id).style.display = ( typeof val == "undefined" ? "" : val );
}

/*
function adesk_dom_toggle_display(id, val) {
    var node = document.getElementById(id);

    if (val.match(/table(-row|-cell)?/) && navigator.userAgent.match(/MSIE [567]/))
        val = "block";

    if (node !== null)
        node.style.display = (node.style.display == val) ? "none" : val;
}
*/

function adesk_dom_display_block(id) {
	$(id).style.display = "block";
}

function adesk_dom_display_none(id) {
	$(id).style.display = "none";
}

function adesk_dom_toggle_class(id, className1, className2) {
	var node = document.getElementById(id);
	if ( !node ) return;
	node.className = ( node.className == className1 ? className2 : className1 );
}

// We don't recurse to the child nodes here; this function itself is a
// shallow foreach.

function adesk_dom_foreach_node(node, fun) {
    while (node !== null) {
        fun(node);
        node = node.nextSibling;
    }
}

// The idea here is to take an HTML collection and walk through it, as
// opposed to an actual node.  (You would use foreach_item, for example,
// with the result of a call to document.getElementsByTagName().)

function adesk_dom_foreach_item(coll, fun) {
    for (var i = 0; i < coll.length; i++)
        fun(coll[i]);
}

function adesk_dom_foreach_child(obj, fun) {
	for (var i = 0; i < obj.childNodes.length; i++)
		fun(obj.childNodes[i]);
}

// Useful for removing all children at once, which isn't a standard
// DOM function but does come up from time to time.

function adesk_dom_remove_children(node) {
	var filter = null;

	if (arguments.length > 1) {
		// they passed a filter function
		filter = arguments[1];
	}

    for (var i = node.childNodes.length - 1; i >= 0; i--) {
		if (typeof filter != "function")
			node.removeChild(node.childNodes[i]);
		else if (filter(node.childNodes[i]))
			node.removeChild(node.childNodes[i]);
	}
}

function adesk_dom_append_childtext(node, text) {
    node.appendChild(document.createTextNode(text));
}

// Create a new <option> element.

function adesk_dom_new_option(val, label) {
    var opt = document.createElement("option");
    opt.value = val;
    opt.appendChild(document.createTextNode(label));
    return opt;
}

/*
try {
    function $(id) {
        if (typeof id == 'string')
            return document.getElementById(id);
        return id;
    }
} catch (e) {}
*/


// ASSIGN WINDOW.ONLOAD FUNCTIONS HERE
function adesk_dom_onload_hook(func) {
	var oldonload = window.onload;
	if ( typeof window.onload != 'function' ) {
		window.onload = func;
	} else {
		window.onload = function() {
			oldonload();
			func();
		}
	}
}

// ASSIGN WINDOW.UNLOAD FUNCTIONS HERE
function adesk_dom_unload_hook(func) {
	var oldunload = window.onbeforeunload;
	if ( typeof window.onbeforeunload != 'function' ) {
		window.onbeforeunload = func;
	} else {
		window.onbeforeunload = function() {
			oldunload();
			func();
		}
	}
}

// ASSIGN DOCUMENT.ONCLICK FUNCTIONS HERE
function adesk_dom_onclick_hook(func) {
	var oldonclick = document.onclick;
	if ( typeof document.onclick != 'function' ) {
		document.onclick = func;
	} else {
		document.onclick = function(e) {
			oldonclick(e);
			func(e);
		}
	}
}

function adesk_dom_hook(func1, func2) {
	//var oldfunc = func1;
	eval('var oldfunc = ' + func1 + ';');
	if ( typeof oldfunc != 'function' ) {
		eval(func1 + ' = func2;');
		//func1 = func2;
	} else {
		eval(func1 + ' = function() { oldfunc(); func2(); }');
		/*func1 = function() {
			oldfunc();
			func2();
		}*/
	}
}

function adesk_dom_find_posX(obj) {
	var curleft = 0;
	if ( obj.offsetParent )
		while ( 1 ) {
			curleft += obj.offsetLeft;
			if ( !obj.offsetParent ) break;
			obj = obj.offsetParent;
		}
	else if ( obj.x )
		curleft += obj.x;
	return curleft;
}

function adesk_dom_find_posY(obj) {
	var curtop = 0;
	if ( obj.offsetParent )
	while ( 1 ) {
		curtop += obj.offsetTop;
		if ( !obj.offsetParent ) break;
		obj = obj.offsetParent;
	}
	else if(obj.y)
		curtop += obj.y;
	return curtop;
}


// clones an element of a parent object
// has an option to clear out inputs
// (convenient for dynamic "add more" actions)
// usage: x($('tableID'), 'tr', false, 1) === in table #tableID clone second row ( tr[1] ), don't clean inputs
function adesk_dom_clone_node(node, elem, elementIndex, clearInputs) {
    if ( !elementIndex ) elementIndex = 0;
	var original = node.getElementsByTagName(elem)[elementIndex];
    var new_node = original.cloneNode(true);
    if ( clearInputs ) {
	    var newinput = new_node.getElementsByTagName('input');
	    for ( var i = 0; i < newinput.length; i++ ) {
	        if (newinput[i].type == 'text' || newinput[i].type == 'file') newinput[i].value = '';
	    }
	    var newarea = new_node.getElementsByTagName('textarea');
	    for ( var i = 0; i < newarea.length; i++ ) {
	        newarea[i].value = '';
	    }
    }
    node.appendChild(new_node);
    return new_node;
}

function adesk_dom_liveedit_toggle(div) {
	adesk_dom_toggle_display(div, 'inline');
	adesk_dom_toggle_display(div + "_contain", 'inline');
}

function adesk_dom_liveedit_showform(div) {
	$(div).style.display = "none";
	$(div + "_contain").style.display = "inline";
}

function adesk_dom_liveedit_showtext(div) {
	$(div).style.display = "inline";
	$(div + "_contain").style.display = "none";
}

function adesk_dom_unhighlight(id, cls) {
	var ctext = "";
	var spans = $(id).select("span." + cls);
	var par   = null;

	for (var i = 0; i < spans.length; i++) {
		ctext = spans[i].firstChild;
		par   = spans[i].parentNode;

		par.replaceChild(ctext, spans[i]);
	}
}

function adesk_dom_keypress_doif(e, ch, cb) {
	var kcode;

	if (window.event)
		kcode = window.event.keyCode;
	else
		kcode = e.keyCode;

	if (kcode == ch)
		cb();
}

function adesk_dom_keypress(e, cb) {
	var kcode;

	if (window.event)
		kcode = window.event.keyCode;
	else
		kcode = e.keyCode;

	cb(kcode);
}

// Examine each of the text subnodes in node for matches in the terms array.  If
// any are found, replace them with some fancy highlights.

function adesk_dom_highlight(node, terms, full) {
	switch (node.nodeType) {
		case 3:
		case 4:
			node.nodeValue = adesk_dom_highlight_text(node.nodeValue, terms, full);
			break;

		case 1:
			for (var i = 0; i < node.childNodes.length; i++) {
				if (node.nodeName != "SCRIPT" && node.nodeName != "TEXTAREA")	// to skip liveedit elements
					adesk_dom_highlight(node.childNodes[i], terms, full);
			}
			break;
	}
}

function adesk_dom_highlight_replace(node, terms, cls) {
	node.innerHTML = node.innerHTML.replace(/___:::([a-zA-Z0-9!_-]+):::___/gi, adesk_dom_highlight_replace_cb(cls));
}

function adesk_dom_highlight_cb(def) {
	if (def === null) {
		return function(m) {
			return sprintf("___:::%s:::___", adesk_b64_encode(m));
		}
	} else {
		return function(m) {
			return sprintf("___:::%s:::___", adesk_b64_encode(m + ",,," + def));
		}
	}
}

function adesk_dom_highlight_replace_cb(cls) {
	return function(full, m) {
		return sprintf("<span class='%s'>%s</span>", cls, adesk_b64_decode(m));
	}
}

function adesk_dom_highlight_definition_cb(cls) {
	return function(full, m) {
		m       = adesk_b64_decode(m);
		var ary = m.split(",,,");

		if (ary.length != 2)
			return m;

		return sprintf("<span class='%s' onmouseover='adesk_tooltip_show(\"%s\", 200, \"\", true)' onmouseout='adesk_tooltip_hide()'>%s</span>", cls, adesk_b64_encode(ary[1]), adesk_str_htmlescape(ary[0]));
	}
}

function adesk_dom_highlight_text(text, terms, full, sens) {
	for (var i in terms) {
		if (typeof terms[i] != "string")
			continue;

		if (full)
			text = text.replace(new RegExp(sprintf("\\b(%s)\\b", i), "gim"), adesk_dom_highlight_cb(terms[i]));
		else
			text = text.replace(new RegExp(sprintf("\\b(%s)\\b", i), "gim"), adesk_dom_highlight_cb(null));
	}
	for (var i in sens) {
		if (typeof sens[i] != "string")
			continue;

		if (full)
			text = text.replace(new RegExp(sprintf("\\b(%s)\\b", i), "gm"), adesk_dom_highlight_cb(terms[i]));
		else
			text = text.replace(new RegExp(sprintf("\\b(%s)\\b", i), "gm"), adesk_dom_highlight_cb(null));
	}
	return text;
}

function adesk_dom_highlight_definition(node, terms, cls) {
	node.innerHTML = node.innerHTML.replace(/___:::([a-zA-Z0-9!_-]+):::___/gi, adesk_dom_highlight_definition_cb(cls));
}

function adesk_dom_emptynode(node, props) {
	if ( !props ) props = { };
	var obj = Builder.node(node, props);
	obj.innerHTML = '&nbsp;';
	return obj;
}

function adesk_dom_textarea_insertatcursor(elem, str) {
	// Adapted from http://www.scottklarr.com/topic/425/how-to-insert-text-into-a-textarea-where-the-cursor-is/
	var epos = elem.scrollTop;
	var pos = 0;
	var br = ((elem.selectionStart || elem.selectionStart == '0') ? "ff" : (document.selection ? "ie" : false ));

	if (br == "ie") { elem.focus();
		var range = document.selection.createRange();
		range.moveStart ('character', -elem.value.length);
		pos = range.text.length;
	} else if (br == "ff") {
		pos = elem.selectionStart;
	}

	var front = (elem.value).substring(0,pos);
	var back = (elem.value).substring(pos, elem.value.length);
	elem.value = front + str + back;
	pos = pos + str.length;

	if (br == "ie") {
		elem.focus();
		var range = document.selection.createRange();
		range.moveStart ('character', -elem.value.length);
		range.moveStart ('character', pos);
		range.moveEnd ('character', 0);
		range.select();
	} else if (br == "ff") {
		elem.selectionStart = pos;
		elem.selectionEnd = pos;
		elem.focus();
	}

	elem.scrollTop = epos;
}


/*
	usage:
	document.onclick = adesk_dom_clickcheck;
*/
var adesk_dom_clickers = {};

function adesk_dom_clicker_add(divId, clickers) {
	if ( !clickers ) return;
	//if ( !clickers || !clickers.length ) return;
	adesk_dom_clickers[divId] = clickers;
}

function adesk_dom_clicker_remove(whichOne, runIt) {
	if ( !whichOne ) {
		if ( runIt ) {
			for ( var i in adesk_dom_clickers ) {
				var c = adesk_dom_clickers[i];
				// for every link that could be clicked to open this div
				var clkObj = $(i);
				if ( !clkObj ) continue;
				c();
			}
		}
		adesk_dom_clickers = {};
	} else {
		if ( typeof adesk_dom_clickers[whichOne] == 'undefined' ) return;
		var c = adesk_dom_clickers[whichOne];
		if ( runIt ) {
			for ( var j in c ) {
				// for every link that could be clicked to open this div
				var clkObj = $(whichOne);
				if ( !clkObj ) return;
				c[j]();
			}
		}
		delete adesk_dom_clickers[whichOne];
	}
}

function adesk_dom_clickcheck(e) {
	var target = ( e && e.target ) || ( event && event.srcElement );
	// loop through all available divs for hidding
	for ( var i in adesk_dom_clickers ) {
		// find the div object
		var domObj = $(i);
		if ( !domObj ) continue;
		// loop through all links that let you open it
		// (if they click one of these, it should exit)
		var shouldBremoved = true;
		var c = adesk_dom_clickers[i];
		for ( var j in c ) {
			// for every link that could be clicked to open this div
			var clkObj = $(j);
			if ( !clkObj ) continue;
			//if ( target == clkObj ) continue;
			// if clicked outside, run the function to hide it
			if ( !adesk_dom_parent_exists(clkObj, target) ) {
				c[j]();
			} else {
				var shouldBremoved = false;
			}
		}
		if ( shouldBremoved ) adesk_dom_clicker_remove(i, false);
	}
}

function adesk_dom_parent_exists(what, where) {
	if ( what == where ) return true;
	while ( where.parentNode ) {
		if ( where == what ) {
			return true;
		}
		where = where.parentNode;
	}
	return false;
}

function adesk_dom_radiochoice(classname) {
	var ary = $$("input." + classname);

	for (var i = 0; i < ary.length; i++) {
		if (ary[i].checked)
			return ary[i].value;
	}

	return null;
}

function adesk_dom_radiotitle(classname) {
	var ary = $$("input." + classname);

	for (var i = 0; i < ary.length; i++) {
		if (ary[i].checked)
			return ary[i].title;
	}

	return null;
}

function adesk_dom_radioset(classname, value) {
	var ary = $$("input." + classname);

	for (var i = 0; i < ary.length; i++) {
		if (ary[i].value == value) {
			ary[i].checked = true;
			return;
		}
	}
}

function adesk_dom_radioclear(classname) {
	var ary = $$("input." + classname);

	for (var i = 0; i < ary.length; i++) {
		ary[i].checked = false;
	}
}

function adesk_dom_boxchoice(classname) {
	var ary  = $$("input." + classname);
	var rval = [];

	for (var i = 0; i < ary.length; i++) {
		if (ary[i].checked)
			rval.push(ary[i].value);
	}

	return rval;
}

function adesk_dom_boxset(classname, values) {
	var ary  = $$("input." + classname);
	if (typeof values.length == 'undefined') values = adesk_array_values(values);

	for (var i = 0; i < ary.length; i++) {
		if (adesk_array_indexof(values, ary[i].value) >= 0)
			ary[i].checked = true;
	}
}

function adesk_dom_boxclear(classname) {
	var ary  = $$("input." + classname);

	for (var i = 0; i < ary.length; i++) {
		ary[i].checked = false;
	}
}

function adesk_dom_boxempty(classname) {
	var ary = adesk_dom_boxchoice(classname);
	return ary.length == 0;
}

/*
function adesk_dom_clickcheck_parent(what, where) {
	while ( where.parentNode ) {
		if ( where == what ) {
			return false;
		}
		where = where.parentNode;
	}
	return true;
}
*/// b64.js

var adesk_b64_dec = {
    'A':  0, 'B':  1, 'C':  2, 'D':  3, 'E':  4, 'F':  5, 'G':  6, 'H':  7,
    'I':  8, 'J':  9, 'K': 10, 'L': 11, 'M': 12, 'N': 13, 'O': 14, 'P': 15,
    'Q': 16, 'R': 17, 'S': 18, 'T': 19, 'U': 20, 'V': 21, 'W': 22, 'X': 23,
    'Y': 24, 'Z': 25, 'a': 26, 'b': 27, 'c': 28, 'd': 29, 'e': 30, 'f': 31,
    'g': 32, 'h': 33, 'i': 34, 'j': 35, 'k': 36, 'l': 37, 'm': 38, 'n': 39,
    'o': 40, 'p': 41, 'q': 42, 'r': 43, 's': 44, 't': 45, 'u': 46, 'v': 47,
    'w': 48, 'x': 49, 'y': 50, 'z': 51, '0': 52, '1': 53, '2': 54, '3': 55,
    '4': 56, '5': 57, '6': 58, '7': 59, '8': 60, '9': 61, '-': 62, '!': 63,
    '=': 0
};

var adesk_b64_enc = {
     0: 'A',  1: 'B',  2: 'C',  3: 'D',  4: 'E',  5: 'F',  6: 'G',  7: 'H',
     8: 'I',  9: 'J', 10: 'K', 11: 'L', 12: 'M', 13: 'N', 14: 'O', 15: 'P',
    16: 'Q', 17: 'R', 18: 'S', 19: 'T', 20: 'U', 21: 'V', 22: 'W', 23: 'X',
    24: 'Y', 25: 'Z', 26: 'a', 27: 'b', 28: 'c', 29: 'd', 30: 'e', 31: 'f',
    32: 'g', 33: 'h', 34: 'i', 35: 'j', 36: 'k', 37: 'l', 38: 'm', 39: 'n',
    40: 'o', 41: 'p', 42: 'q', 43: 'r', 44: 's', 45: 't', 46: 'u', 47: 'v',
    48: 'w', 49: 'x', 50: 'y', 51: 'z', 52: '0', 53: '1', 54: '2', 55: '3',
    56: '4', 57: '5', 58: '6', 59: '7', 60: '8', 61: '9', 62: '-', 63: '!'
};

function adesk_b64_elshift(m, i, sh) {
    return (m.charCodeAt(i) << sh) & 63;
}

function adesk_b64_ershift(m, i, sh) {
    return (m.charCodeAt(i) >> sh) & 63;
}

// Base-64 encode a string, essentially by taking a 3-character block
// and turning it into a 4-character block using the base-64 alphabet.
// If less than 3 characters exist in the last block, the equal sign is
// used as padding (2 equal signs if only 1 character, 1 equal sign if 2
// characters).

function adesk_b64_encode(message) {
    var out = "";
    var buf0;
    var buf1;
    var buf2;
    var buf3;
    var i;

    for (i = 0; i < message.length; i += 3) {
        buf0 = adesk_b64_enc[adesk_b64_ershift(message, i+0, 2)];
        buf2 = "_";
        buf3 = "_";

        if ((i+1) < message.length)
            buf1 = adesk_b64_enc[adesk_b64_elshift(message, i+0, 4) | adesk_b64_ershift(message, i+1, 4)];
        else
            buf1 = adesk_b64_enc[adesk_b64_elshift(message, i+0, 4)];

        if ((i+2) < message.length) {
            buf2 = adesk_b64_enc[adesk_b64_elshift(message, i+1, 2) | adesk_b64_ershift(message, i+2, 6)];
            buf3 = adesk_b64_enc[adesk_b64_elshift(message, i+2, 0)];
        } else if ((i+1) < message.length)
            buf2 = adesk_b64_enc[adesk_b64_elshift(message, i+1, 2)];

        out += buf0 + buf1 + buf2 + buf3;
    }

    return out;
}

function adesk_b64_dlshift(c, sh) {
    return (adesk_b64_dec[c] << sh) & 255;
}

function adesk_b64_drshift(c, sh) {
    return (adesk_b64_dec[c] >> sh) & 255;
}

function adesk_b64_decode(message) {
    var out = "";
    var i;

    // All base-64 blocks are multiples of four characters.  Try it:
    // encode a one-letter string.  You'll get four characters
    // in return.  If that's not the case with this message, then it's
    // not really base-64 encoded (or not encoded correctly).

    if ((message.length % 4) != 0)
        return message;

    // Each block of four encoded characters can be decoded to, at most,
    // three unencoded ones.  (Which makes sense: 4 * 6bits = 24bits,
    // and 3 * 8bits = 24bits.)  The bits in base-64 are encoded
    // left-to-right, that is, starting with the high-order bit and
    // moving to the low-order bit.  Each number we consider has a bit
    // mask of 255 applied, so only (low-order) 8 bits are considered at
    // any given moment.

    // The equal sign is considered "padding" in an encoded string, but
    // they also represent the end marker.  A block of four bytes with
    // two equal signs on the end is a signal that only one character is
    // encoded; with one equal sign, two characters encoded.  No equal
    // sign is necessary if the initial string's length was a multiple
    // of 3.

    for (i = 0; i < message.length; i += 4) {
        out += String.fromCharCode(adesk_b64_dlshift(message.charAt(i+0), 2) | adesk_b64_drshift(message.charAt(i+1), 4)); if (message.charAt(i+2) == '_') break;
        out += String.fromCharCode(adesk_b64_dlshift(message.charAt(i+1), 4) | adesk_b64_drshift(message.charAt(i+2), 2)); if (message.charAt(i+3) == '_') break;
        out += String.fromCharCode(adesk_b64_dlshift(message.charAt(i+2), 6) | adesk_b64_drshift(message.charAt(i+3), 0));
    }
    
    return out;
}
// str.js

/*
function adesk_str_trim(str) {
    return str.replace(/^\s*(\S+)\s*$/, "$1");
}
*/
function adesk_str_trim(str, chars) {
	return adesk_str_ltrim(adesk_str_rtrim(str, chars), chars);
}

function adesk_str_ltrim(str, chars) {
	str += '';
	chars = chars || "\\s";
	return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function adesk_str_rtrim(str, chars) {
	str += '';
	chars = chars || "\\s";
	return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}

function adesk_str_repeat(str, times) {
	var out = "";

	while (times--)
		out += str;

	return out;
}

function adesk_str_shorten(text, chars) {
	if ( !chars || chars == 0 ) return text;
    var textLength = text.length;
    text += ' ';
    text = text.substr(0, chars);
    var lastSpacePos = text.lastIndexOf(' ');
    if ( lastSpacePos != -1 )
        text = text.substr(0, lastSpacePos);
    if ( textLength > text.length )
        text += '...';

    return text;
}

function adesk_str_middleshorten(text, front_chars, back_chars) {
	if ( !front_chars || front_chars == 0 ) return text;
	if ( !back_chars || back_chars == 0 ) return text;
	if ( text.length < front_chars + back_chars ) return text;
    var front = text.substr(0, front_chars);
    var back  = text.substr(text.length - back_chars, back_chars);
    return front + '...' + back;
}


function adesk_str_array(str) {
    var ary = new Array();

    for (var i = 0; i < str.length; i++) {
        if (str[i] == '&') {
            var tmp = "";
            while (i < str.length) {
                tmp += str[i++];
                if (str[i-1] == ';')
                    break;
            }

            ary.push(tmp);
        } else {
            ary.push(str[i]);
        }
    }

    return ary;
}

function adesk_array_has(ary, val) {
    //for (var i = 0; i < ary.length; i++) {
    for ( var i in ary ) {
        if (ary[i] == val)
            return true;
    }

    return false;
}

function adesk_array_extract(str) {
    var ary = new Array();
    var tmp = str.split("||");

    for (var i = 0; i < tmp.length; i++) {
        var ent = tmp[i].split("=", 2);
        ary[ent[0]] = ent[1];
    }

    return ary;
}

function adesk_str_array_len(ary) {
    for (var i = 0, c = 0; i < ary.length; i++)
        c += ary[i].length;
    return c;
}

function adesk_str_array_substr(ary, off, len) {
    var tmp = "";
    for (var i = off; i < ary.length; i++) {
        if (i >= len)
            break;
        tmp += ary[i];
    }

    return tmp;
}

function adesk_str_url(rel) {
    var ary = rel.split("/");
    var url = window.location.href.replace(/\/[^\/]*$/, "");

    for (var i = 0; i < ary.length; i++) {
        if (ary[i] == "..")
            url = url.replace(/\/[^\/]*$/, "");
        else
            url += "/" + ary[i];
    }

    return url;
}

function adesk_ary_last(ary, begin) {
    var nary = new Array();

    for (var i = begin, j = 0; i < ary.length; i++, j++) {
        nary[j] = ary[i];
    }

    return nary;
}

function adesk_str_rand_password(len) {
	var out = "";

	while (len--) {
		out += adesk_str_rand_passchar();
	}

	return out;
}

function adesk_str_rand_passchar() {
	var floor = Math.floor(Math.random() * 10.0);
	var chr;

	if (floor > 6) {
		chr = Math.floor(Math.random() * 10.0);
		chr = chr.toString();
	} else {
		var off = Math.floor(Math.random() * 100.0) % 26;
		chr = "a".charCodeAt(0) + off;
		chr = String.fromCharCode(chr);
	}

	return chr;
}

function adesk_sprintf(fmt, args) {
    var out;
    var argi;

    out     = "";
    argi    = 0;

    for (var i = 0; i < fmt.length; i++) {
        var fmtc = fmt.charAt(i);
        switch (fmtc) {
            case "\\":
                i++;
                break;
            case "%":
                if (argi < args.length) {
                    fmtc = fmt.charAt(i+1);
                    out += adesk_sprintf_spec(fmtc, args[argi]);
                    i++;
                    argi++;
                } else {
                    out += fmtc;
                }
                break;
            default:
                out += fmtc;
                break;
        }
    }

    return out;
}

function adesk_sprintf_spec(ch, arg) {
    switch (ch) {
        case "d":
        case "f":
            return arg.toString();
        case "s":
        default:
            return arg;
    }

    return "";
}


// This code is in the public domain. Feel free to link back to http://jan.moesen.nu/
function sprintf() {
	if (!arguments || arguments.length < 1 || !RegExp)
	{
		return;
	}
	var str = arguments[0];
	var re = /([^%]*)%('.|0|\x20)?(-)?(\d+)?(\.\d+)?(%|b|c|d|u|f|o|s|x|X)(.*)/; // '
	var a = b = [], numSubstitutions = 0, numMatches = 0;
	while (a = re.exec(str))
	{
		var leftpart = a[1], pPad = a[2], pJustify = a[3], pMinLength = a[4];
		var pPrecision = a[5], pType = a[6], rightPart = a[7];

		//alert(a + '\n' + [a[0], leftpart, pPad, pJustify, pMinLength, pPrecision);

		numMatches++;
		if (pType == '%')
		{
			subst = '%';
		}
		else
		{
			numSubstitutions++;
			if (numSubstitutions >= arguments.length)
			{
				//alert('Error! Not enough function arguments (' + (arguments.length - 1) + ', excluding the string)\nfor the number of substitution parameters in string (' + numSubstitutions + ' so far).\n\nString in question:\n' + str);
				return;
			}
			var param = arguments[numSubstitutions];
			var pad = '';
			       if (pPad && pPad.substr(0,1) == "'") pad = leftpart.substr(1,1);
			  else if (pPad) pad = pPad;
			var justifyRight = true;
			       if (pJustify && pJustify === "-") justifyRight = false;
			var minLength = -1;
			       if (pMinLength) minLength = parseInt(pMinLength);
			var precision = -1;
			       if (pPrecision && pType == 'f') precision = parseInt(pPrecision.substring(1));
			var subst = param;
			       if (pType == 'b') subst = parseInt(param).toString(2);
			  else if (pType == 'c') subst = String.fromCharCode(parseInt(param));
			  else if (pType == 'd') subst = parseInt(param) ? parseInt(param) : 0;
			  else if (pType == 'u') subst = Math.abs(param);
			  else if (pType == 'f') subst = (precision > -1) ? Math.round(parseFloat(param) * Math.pow(10, precision)) / Math.pow(10, precision): parseFloat(param);
			  else if (pType == 'o') subst = parseInt(param).toString(8);
			  else if (pType == 's') subst = param;
			  else if (pType == 'x') subst = ('' + parseInt(param).toString(16)).toLowerCase();
			  else if (pType == 'X') subst = ('' + parseInt(param).toString(16)).toUpperCase();
		}
		str = leftpart + subst + rightPart;
	}
	return str;
}


/*
 * This is the function that actually highlights a text string by
 * adding HTML tags before and after all occurrences of the search
 * term. You can pass your own tags if you'd like, or if the
 * highlightStartTag or highlightEndTag parameters are omitted or
 * are empty strings then the default <font> tags will be used.
 */
function adesk_str_highlight(bodyText, searchTerm, highlightStartTag, highlightEndTag)
{
  // the highlightStartTag and highlightEndTag parameters are optional
  if ((!highlightStartTag) || (!highlightEndTag)) {
    highlightStartTag = "<font style='color:blue; background-color:yellow;'>";
    highlightEndTag = "</font>";
  }

  // find all occurences of the search term in the given text,
  // and add some "highlight" tags to them (we're not using a
  // regular expression search, because we want to filter out
  // matches that occur within HTML tags and script blocks, so
  // we have to do a little extra validation)
  var newText = "";
  var i = -1;
  var lcSearchTerm = searchTerm.toLowerCase();
  var lcBodyText = bodyText.toLowerCase();

  while (bodyText.length > 0) {
    i = lcBodyText.indexOf(lcSearchTerm, i+1);
    if (i < 0) {
      newText += bodyText;
      bodyText = "";
    } else {
      // skip anything inside an HTML tag
      if (bodyText.lastIndexOf(">", i) >= bodyText.lastIndexOf("<", i)) {
        if (
	      // skip anything inside a <script> block
          (lcBodyText.lastIndexOf("/script>", i) >= lcBodyText.lastIndexOf("<script", i))
        ||
    	  // skip anything inside a <style> block
          (lcBodyText.lastIndexOf("/style>", i) >= lcBodyText.lastIndexOf("<style", i))
        ) {
          newText += bodyText.substring(0, i) + highlightStartTag + bodyText.substr(i, searchTerm.length) + highlightEndTag;
          bodyText = bodyText.substr(i + searchTerm.length);
          lcBodyText = bodyText.toLowerCase();
          i = -1;
        }
      }
    }
  }

  return newText;
}


/*
 * This is sort of a wrapper function to the adesk_str_highlight function.
 * It takes the searchText that you pass, optionally splits it into
 * separate words, transforms the text and returns it.
 * Only the "bodyText" and "searchText" parameters are required; all other parameters
 * are optional and can be omitted.
 */
function adesk_str_highlight_phrase(bodyText, searchText, treatAsPhrase, customColorIndex)
{
  // if the treatAsPhrase parameter is true, then we should search for
  // the entire phrase that was entered; otherwise, we will split the
  // search string so that each word is searched for and highlighted
  // individually
  if (treatAsPhrase) {
    var searchArray = [searchText];
  } else {
    var searchArray = searchText.split(" ");
    if ( searchArray.length == 1 ) {
      var treatAsPhrase = true;
    }
  }

  var colors = [ 'yellow', '#99FF99', '#FFCCFF', '#CC99FF', '#99CCFF', '#FFCC99', '#CCCCFF', '#66CCFF' ];
  for (var i = 0; i < searchArray.length; i++) {
    // choose color
    if (!customColorIndex && customColorIndex != 0) {
      if (treatAsPhrase) {
        var colorIndex = 0;
      } else {
        var colorIndex = ( i % 7 ) + 1;
      }
    } else {
      colorIndex = customColorIndex;
    }
    var color = colors[colorIndex];
    highlightStartTag = '<font class="__highlight" style="background-color: ' + color + ';">';
    highlightEndTag = '</font>';
    bodyText = adesk_str_highlight(bodyText, searchArray[i], highlightStartTag, highlightEndTag);
  }

  return bodyText;
}



/*
var __adesk_highlight_tags = [];
var __adesk_highlight_tag = '';
var __adesk_highlight_i = 0;

function adesk_str_highlight(str, terms, tag) {
	if ( tag == null || tag == undefined )
		var tag = '<b style="color: #000; background-color: #%s;">%s</b>';
	var orig = str;
	var colors = [ 'ff0', '0ff', 'f0f' ];
	var i = 0;
	if ( terms.length == 0 || ( terms.length == 1 && adesk_str_trim(terms[0]) == '' ) ) return str;
	__adesk_highlight_tags = [];
	for ( var i = 0; i < terms.length; i++ ) {
		// choose color
		var colorIndex = i % 3;
		var color = colors[colorIndex];
		if ( terms[i].length > 1 ) {
			// escape term
			var q = preg_quote(terms[i]);
			// If there are tags, we need to stay outside them
			__adesk_highlight_tag = tag;
			__adesk_highlight_i = i;
			if ( !str.match(/<.+>/) ) {
				// text
				str = str.replace(
					/(\b + q + \b)/ig,
					function(m) {
						alert(m);return m;
						var found = sprintf(__adesk_highlight_tag, __adesk_highlight_i, m[1]);
						var r = adesk_b64_encode(found);
						__adesk_highlight_tags[r] = found;
						return r;
					}
				);
			} else {
				// html
				str = str.replace(
					/(?<=>)([^<]+)?(\b/ + q + /\b)/ig,
					function(m) {
						var found = m[1] + sprintf(__adesk_highlight_tag, __adesk_highlight_i, m[2]);
						var r = adesk_b64_encode(found);
						__adesk_highlight_tags[r] = found;
						return r;
					}
				);
			}
		}
	}
	// do final replacements
	if ( __adesk_highlight_tags.length > 0 ) {
		str = str.replace(adesk_array_keys(__adesk_highlight_tags), adesk_array_values(__adesk_highlight_tags));
	}
	return str;
}
*/

function nl2br(str) {
	if ( typeof(str) == "string" )
		return str.replace(/(\r\n)|(\n\r)|\r|\n/g,'<br />'); // '
	else
		return str;
}

function preg_quote( str ) {
	// Quote regular expression characters
	//
	// +    discuss at: http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_preg_quote/
	// +       version: 801.2320
	// +   original by: booeyOH
	// +   improved by: Ates Goral (http://magnetiq.com)
	// *     example 1: preg_quote("$40");
	// *     returns 1: "\\\$40"
	// *     example 2: preg_quote("*RRRING* Hello?");
	// *     returns 2: "\\*RRRING\\* Hello\\?"
	// *     example 3: preg_quote("\\.+*?[^]$(){}=!<>|:");
	// *     returns 3: "\\\\\\.\\+\\*\\?\\[\\^\\]\\$\\(\\)\\{\\}\\=\\!\\<\\>\\|\\:"

	return str.replace(/([\\\.\+\*\?\[\^\]\$\(\)\{\}\=\!\<\>\|\:])/g, "\\$1");
}

function adesk_str_urlsafe(str) {
	// strip all tags first
	str = strip_tags(str);
	// encode escaped octets
	str = str.replace(/%([a-fA-F0-9][a-fA-F0-9])/, '-=-$1-=-');
	// remove percent signs
	str = str.replace('%', '');
	// decode found octets
	str = str.replace(/-=-([a-fA-F0-9][a-fA-F0-9])-=-/g, '%$1');
	// do your best to mask all weird chars
	str = adesk_str_remove_accents(str);
	// if string is in utf8
	if ( adesk_utf_check(str) ) {
		// encode string for usage in url
		str = adesk_utf_uri_encode(str, 200);
	}
	// paths should be lowercased
	str = str.toLowerCase();
	// remove all entities,
	str = str.replace(/&.+?;/g, '');
	// harmfull chars,
	str = str.replace(/[^%a-z0-9 _-]/g, '');
	// whitespaces
	str = str.replace(/\s+/g, '-');
	// and other...
	str = str.replace(/-+/g, '-');
	str = adesk_str_trim(str, '-');
	// return clean string
	return str;
}



function adesk_str_remove_accents(string) {
	// if none found, return the string right away
	if ( !string.match(/[\u80-\uff]/) ) {
		return string;
	}
	if ( adesk_utf_check(string) ) {
		// if string is in utf8
		var chars = new Array();
		chars[((195 << 6) | 128).toString()] = 'A';
		chars[((195 << 6) | 129).toString()] = 'A';
		chars[((195 << 6) | 130).toString()] = 'A';
		chars[((195 << 6) | 131).toString()] = 'A';
		chars[((195 << 6) | 132).toString()] = 'A';
		chars[((195 << 6) | 133).toString()] = 'A';
		chars[((195 << 6) | 135).toString()] = 'C';
		chars[((195 << 6) | 136).toString()] = 'E';
		chars[((195 << 6) | 137).toString()] = 'E';
		chars[((195 << 6) | 138).toString()] = 'E';
		chars[((195 << 6) | 139).toString()] = 'E';
		chars[((195 << 6) | 140).toString()] = 'I';
		chars[((195 << 6) | 141).toString()] = 'I';
		chars[((195 << 6) | 142).toString()] = 'I';
		chars[((195 << 6) | 143).toString()] = 'I';
		chars[((195 << 6) | 145).toString()] = 'N';
		chars[((195 << 6) | 146).toString()] = 'O';
		chars[((195 << 6) | 147).toString()] = 'O';
		chars[((195 << 6) | 148).toString()] = 'O';
		chars[((195 << 6) | 149).toString()] = 'O';
		chars[((195 << 6) | 150).toString()] = 'O';
		chars[((195 << 6) | 153).toString()] = 'U';
		chars[((195 << 6) | 154).toString()] = 'U';
		chars[((195 << 6) | 155).toString()] = 'U';
		chars[((195 << 6) | 156).toString()] = 'U';
		chars[((195 << 6) | 157).toString()] = 'Y';
		chars[((195 << 6) | 159).toString()] = 's';
		chars[((195 << 6) | 160).toString()] = 'a';
		chars[((195 << 6) | 161).toString()] = 'a';
		chars[((195 << 6) | 162).toString()] = 'a';
		chars[((195 << 6) | 163).toString()] = 'a';
		chars[((195 << 6) | 164).toString()] = 'a';
		chars[((195 << 6) | 165).toString()] = 'a';
		chars[((195 << 6) | 167).toString()] = 'c';
		chars[((195 << 6) | 168).toString()] = 'e';
		chars[((195 << 6) | 169).toString()] = 'e';
		chars[((195 << 6) | 170).toString()] = 'e';
		chars[((195 << 6) | 171).toString()] = 'e';
		chars[((195 << 6) | 172).toString()] = 'i';
		chars[((195 << 6) | 173).toString()] = 'i';
		chars[((195 << 6) | 174).toString()] = 'i';
		chars[((195 << 6) | 175).toString()] = 'i';
		chars[((195 << 6) | 177).toString()] = 'n';
		chars[((195 << 6) | 178).toString()] = 'o';
		chars[((195 << 6) | 179).toString()] = 'o';
		chars[((195 << 6) | 180).toString()] = 'o';
		chars[((195 << 6) | 181).toString()] = 'o';
		chars[((195 << 6) | 182).toString()] = 'o';
		chars[((195 << 6) | 182).toString()] = 'o';
		chars[((195 << 6) | 185).toString()] = 'u';
		chars[((195 << 6) | 186).toString()] = 'u';
		chars[((195 << 6) | 187).toString()] = 'u';
		chars[((195 << 6) | 188).toString()] = 'u';
		chars[((195 << 6) | 189).toString()] = 'y';
		chars[((195 << 6) | 191).toString()] = 'y';
		chars[((196 << 6) | 128).toString()] = 'A';
		chars[((196 << 6) | 129).toString()] = 'a';
		chars[((196 << 6) | 130).toString()] = 'A';
		chars[((196 << 6) | 131).toString()] = 'a';
		chars[((196 << 6) | 132).toString()] = 'A';
		chars[((196 << 6) | 133).toString()] = 'a';
		chars[((196 << 6) | 134).toString()] = 'C';
		chars[((196 << 6) | 135).toString()] = 'c';
		chars[((196 << 6) | 136).toString()] = 'C';
		chars[((196 << 6) | 137).toString()] = 'c';
		chars[((196 << 6) | 138).toString()] = 'C';
		chars[((196 << 6) | 139).toString()] = 'c';
		chars[((196 << 6) | 140).toString()] = 'C';
		chars[((196 << 6) | 141).toString()] = 'c';
		chars[((196 << 6) | 142).toString()] = 'D';
		chars[((196 << 6) | 143).toString()] = 'd';
		chars[((196 << 6) | 144).toString()] = 'D';
		chars[((196 << 6) | 145).toString()] = 'd';
		chars[((196 << 6) | 146).toString()] = 'E';
		chars[((196 << 6) | 147).toString()] = 'e';
		chars[((196 << 6) | 148).toString()] = 'E';
		chars[((196 << 6) | 149).toString()] = 'e';
		chars[((196 << 6) | 150).toString()] = 'E';
		chars[((196 << 6) | 151).toString()] = 'e';
		chars[((196 << 6) | 152).toString()] = 'E';
		chars[((196 << 6) | 153).toString()] = 'e';
		chars[((196 << 6) | 154).toString()] = 'E';
		chars[((196 << 6) | 155).toString()] = 'e';
		chars[((196 << 6) | 156).toString()] = 'G';
		chars[((196 << 6) | 157).toString()] = 'g';
		chars[((196 << 6) | 158).toString()] = 'G';
		chars[((196 << 6) | 159).toString()] = 'g';
		chars[((196 << 6) | 160).toString()] = 'G';
		chars[((196 << 6) | 161).toString()] = 'g';
		chars[((196 << 6) | 162).toString()] = 'G';
		chars[((196 << 6) | 163).toString()] = 'g';
		chars[((196 << 6) | 164).toString()] = 'H';
		chars[((196 << 6) | 165).toString()] = 'h';
		chars[((196 << 6) | 166).toString()] = 'H';
		chars[((196 << 6) | 167).toString()] = 'h';
		chars[((196 << 6) | 168).toString()] = 'I';
		chars[((196 << 6) | 169).toString()] = 'i';
		chars[((196 << 6) | 170).toString()] = 'I';
		chars[((196 << 6) | 171).toString()] = 'i';
		chars[((196 << 6) | 172).toString()] = 'I';
		chars[((196 << 6) | 173).toString()] = 'i';
		chars[((196 << 6) | 174).toString()] = 'I';
		chars[((196 << 6) | 175).toString()] = 'i';
		chars[((196 << 6) | 176).toString()] = 'I';
		chars[((196 << 6) | 177).toString()] = 'i';
		chars[((196 << 6) | 178).toString()] = 'IJ';
		chars[((196 << 6) | 179).toString()] = 'ij';
		chars[((196 << 6) | 180).toString()] = 'J';
		chars[((196 << 6) | 181).toString()] = 'j';
		chars[((196 << 6) | 182).toString()] = 'K';
		chars[((196 << 6) | 183).toString()] = 'k';
		chars[((196 << 6) | 184).toString()] = 'k';
		chars[((196 << 6) | 185).toString()] = 'L';
		chars[((196 << 6) | 186).toString()] = 'l';
		chars[((196 << 6) | 187).toString()] = 'L';
		chars[((196 << 6) | 188).toString()] = 'l';
		chars[((196 << 6) | 189).toString()] = 'L';
		chars[((196 << 6) | 190).toString()] = 'l';
		chars[((196 << 6) | 191).toString()] = 'L';
		chars[((197 << 6) | 128).toString()] = 'l';
		chars[((197 << 6) | 129).toString()] = 'L';
		chars[((197 << 6) | 130).toString()] = 'l';
		chars[((197 << 6) | 131).toString()] = 'N';
		chars[((197 << 6) | 132).toString()] = 'n';
		chars[((197 << 6) | 133).toString()] = 'N';
		chars[((197 << 6) | 134).toString()] = 'n';
		chars[((197 << 6) | 135).toString()] = 'N';
		chars[((197 << 6) | 136).toString()] = 'n';
		chars[((197 << 6) | 137).toString()] = 'N';
		chars[((197 << 6) | 138).toString()] = 'n';
		chars[((197 << 6) | 139).toString()] = 'N';
		chars[((197 << 6) | 140).toString()] = 'O';
		chars[((197 << 6) | 141).toString()] = 'o';
		chars[((197 << 6) | 142).toString()] = 'O';
		chars[((197 << 6) | 143).toString()] = 'o';
		chars[((197 << 6) | 144).toString()] = 'O';
		chars[((197 << 6) | 145).toString()] = 'o';
		chars[((197 << 6) | 146).toString()] = 'OE';
		chars[((197 << 6) | 147).toString()] = 'oe';
		chars[((197 << 6) | 148).toString()] = 'R';
		chars[((197 << 6) | 149).toString()] = 'r';
		chars[((197 << 6) | 150).toString()] = 'R';
		chars[((197 << 6) | 151).toString()] = 'r';
		chars[((197 << 6) | 152).toString()] = 'R';
		chars[((197 << 6) | 153).toString()] = 'r';
		chars[((197 << 6) | 154).toString()] = 'S';
		chars[((197 << 6) | 155).toString()] = 's';
		chars[((197 << 6) | 156).toString()] = 'S';
		chars[((197 << 6) | 157).toString()] = 's';
		chars[((197 << 6) | 158).toString()] = 'S';
		chars[((197 << 6) | 159).toString()] = 's';
		chars[((197 << 6) | 160).toString()] = 'S';
		chars[((197 << 6) | 161).toString()] = 's';
		chars[((197 << 6) | 162).toString()] = 'T';
		chars[((197 << 6) | 163).toString()] = 't';
		chars[((197 << 6) | 164).toString()] = 'T';
		chars[((197 << 6) | 165).toString()] = 't';
		chars[((197 << 6) | 166).toString()] = 'T';
		chars[((197 << 6) | 167).toString()] = 't';
		chars[((197 << 6) | 168).toString()] = 'U';
		chars[((197 << 6) | 169).toString()] = 'u';
		chars[((197 << 6) | 170).toString()] = 'U';
		chars[((197 << 6) | 171).toString()] = 'u';
		chars[((197 << 6) | 172).toString()] = 'U';
		chars[((197 << 6) | 173).toString()] = 'u';
		chars[((197 << 6) | 174).toString()] = 'U';
		chars[((197 << 6) | 175).toString()] = 'u';
		chars[((197 << 6) | 176).toString()] = 'U';
		chars[((197 << 6) | 177).toString()] = 'u';
		chars[((197 << 6) | 178).toString()] = 'U';
		chars[((197 << 6) | 179).toString()] = 'u';
		chars[((197 << 6) | 180).toString()] = 'W';
		chars[((197 << 6) | 181).toString()] = 'w';
		chars[((197 << 6) | 182).toString()] = 'Y';
		chars[((197 << 6) | 183).toString()] = 'y';
		chars[((197 << 6) | 184).toString()] = 'Y';
		chars[((197 << 6) | 185).toString()] = 'Z';
		chars[((197 << 6) | 186).toString()] = 'z';
		chars[((197 << 6) | 187).toString()] = 'Z';
		chars[((197 << 6) | 188).toString()] = 'z';
		chars[((197 << 6) | 189).toString()] = 'Z';
		chars[((197 << 6) | 190).toString()] = 'z';

		chars[((197 << 6) | 191).toString()] = 's';
		chars[((226 << 12) | (130 << 6) | 172).toString()] = 'E';
		chars[((194 << 6) | 163).toString()] = '';
		// do the replacements
		for (var i = 0; i < string.length; i++) {
			var code = string.charCodeAt(i).toString();
			var chr = string[i];
			if (chars[code]) {
				string[i] = chars[code];
			}
		}
	} else {
		// assume it is ISO-8859-1 if not UTF-8
		var chars = {
			'in' :
				String.fromCharCode(128) + String.fromCharCode(131) + String.fromCharCode(138) + String.fromCharCode(142) + String.fromCharCode(154) + String.fromCharCode(158) + String.fromCharCode(159) +
				String.fromCharCode(162) + String.fromCharCode(165) + String.fromCharCode(181) + String.fromCharCode(192) + String.fromCharCode(193) + String.fromCharCode(194) + String.fromCharCode(195) +
				String.fromCharCode(196) + String.fromCharCode(197) + String.fromCharCode(199) + String.fromCharCode(200) + String.fromCharCode(201) + String.fromCharCode(202) + String.fromCharCode(203) +
				String.fromCharCode(204) + String.fromCharCode(205) + String.fromCharCode(206) + String.fromCharCode(207) + String.fromCharCode(209) + String.fromCharCode(210) + String.fromCharCode(211) +
				String.fromCharCode(212) + String.fromCharCode(213) + String.fromCharCode(214) + String.fromCharCode(216) + String.fromCharCode(217) + String.fromCharCode(218) + String.fromCharCode(219) +
				String.fromCharCode(220) + String.fromCharCode(221) + String.fromCharCode(224) + String.fromCharCode(225) + String.fromCharCode(226) + String.fromCharCode(227) + String.fromCharCode(228) +
				String.fromCharCode(229) + String.fromCharCode(231) + String.fromCharCode(232) + String.fromCharCode(233) + String.fromCharCode(234) + String.fromCharCode(235) + String.fromCharCode(236) +
				String.fromCharCode(237) + String.fromCharCode(238) + String.fromCharCode(239) + String.fromCharCode(241) + String.fromCharCode(242) + String.fromCharCode(243) + String.fromCharCode(244) +
				String.fromCharCode(245) + String.fromCharCode(246) + String.fromCharCode(248) + String.fromCharCode(249) + String.fromCharCode(250) + String.fromCharCode(251) + String.fromCharCode(252) +
				String.fromCharCode(253) + String.fromCharCode(255),
			'out' :
				'EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy',
			'inin' :
				[ String.fromCharCode(140), String.fromCharCode(156), String.fromCharCode(198), String.fromCharCode(208), String.fromCharCode(222), String.fromCharCode(223), String.fromCharCode(230), String.fromCharCode(240), String.fromCharCode(254) ],
			'outout' :
				[ 'OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th' ]
		};
		// replace single characters
		string = adesk_str_strtr(string, chars['in'], chars['out']);
		// replace double characters
		string = adesk_str_replace(chars['inin'], chars['outout'], string);
	}
	// return a clean string
	return string;
}

function adesk_str_file_humansize(size) {
	var count = 0;
	var format = new Array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	while( ( size / 1024 ) > 1 && count < 8 ) {
		size = size / 1024;
		count++;
	}
	//var decimals = size < 10;

	return Math.round(size) + ' ' + format[count];
}

// Always pass adesk_strings.js vars decimalDelim and commaDelim if default
function adesk_number_format(nStr, decimalDelim, commaDelim) {
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? decimalDelim + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + commaDelim + '$2');
	}
	return x1 + x2;
}

function adesk_str_strtr(str, list) {
	if ( arguments[2] ) {
		var r = arguments[2];
		for ( var i = 0; i < list.length; i++ ) {
			str = str.replace( new RegExp(list.charAt(i), "g"), r.charAt(i) );
		}
	} else {
		for ( var c in list ) {
			str = str.replace( new RegExp(c, "g"), list[c] );
		}
	}
	return str;
}

function adesk_str_replace(search, replace, subject) {
	if ( typeof(search) == "string" ) return subject.replace(RegExp(search, "g"), replace);
	for ( var i in search ) {
		if ( replace[i] && typeof(search[i]) + typeof(replace[i]) == "stringstring" )
			subject = subject.replace(RegExp(search[i], "g"), replace[i]);
	}
	return subject;
}

function strip_tags(str, trim) {
	str += '';
	var r = str.replace(/<\/?[^>]+>/gi, '');
	r = r.replace(/&nbsp;/g, ' ');
	if ( trim ) r = adesk_str_trim(r);
	return r;
}

function adesk_str_escapeq(str) {
	str += '';
	str = str.replace(/\\/g, '\\\\');
	str = str.replace(/'/g, "\\'");
	str = str.replace(/"/g, '\\"');
	return str;
}

function adesk_str_htmlescape(str) {
	str += '';
	str = str.replace(/&/g, "&amp;");
	str = str.replace(/</g, "&lt;");
	str = str.replace(/>/g, "&gt;");
	str = str.replace(/'/g, "&#039;"); //'
	str = str.replace(/"/g, "&quot;"); //"

	return str;
}

function adesk_str_jsescape(str) {
	str += '';
	str = str.replace(/'/g, "\\'"); //"
	str = str.replace(/"/g, '\\"'); //'

	return str;
}

function adesk_str_email(email) {
	email += '';
    return email.match( /^[\+_a-z0-9-'&=]+(\.[\+_a-z0-9-']+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,17})$/i );
}

function adesk_str_is_url(url) {
	url += '';
    return url.match( /((http|https|ftp):\/\/|www)[a-z0-9\-\._]+\/?[a-z0-9_\.\-\?\+\/~=&#%;:\|,\[\]]*[a-z0-9\/=?&;%\[\]]{1}/i );
}


/**
*
*  Javascript sprintf
*  http://www.webtoolkit.info/
*
*
**/

sprintfWrapper = {

	init : function () {

		if (typeof arguments == "undefined") { return null; }
		if (arguments.length < 1) { return null; }
		if (typeof arguments[0] != "string") { return null; }
		if (typeof RegExp == "undefined") { return null; }

		var string = arguments[0];
		var exp = new RegExp(/(%([%]|(\-)?(\+|\x20)?(0)?(\d+)?(\.(\d)?)?([bcdfosxX])))/g);
		var matches = new Array();
		var strings = new Array();
		var convCount = 0;
		var stringPosStart = 0;
		var stringPosEnd = 0;
		var matchPosEnd = 0;
		var newString = '';
		var match = null;

		while (match = exp.exec(string)) {
			if (match[9]) { convCount += 1; }

			stringPosStart = matchPosEnd;
			stringPosEnd = exp.lastIndex - match[0].length;
			strings[strings.length] = string.substring(stringPosStart, stringPosEnd);

			matchPosEnd = exp.lastIndex;
			matches[matches.length] = {
				match: match[0],
				left: match[3] ? true : false,
				sign: match[4] || '',
				pad: match[5] || ' ',
				min: match[6] || 0,
				precision: match[8],
				code: match[9] || '%',
				negative: parseInt(arguments[convCount]) < 0 ? true : false,
				argument: String(arguments[convCount])
			};
		}
		strings[strings.length] = string.substring(matchPosEnd);

		if (matches.length == 0) { return string; }
		if ((arguments.length - 1) < convCount) { return null; }

		var code = null;
		var match = null;
		var i = null;

		for (i=0; i<matches.length; i++) {

			if (matches[i].code == '%') { substitution = '%' }
			else if (matches[i].code == 'b') {
				matches[i].argument = String(Math.abs(parseInt(matches[i].argument)).toString(2));
				substitution = sprintfWrapper.convert(matches[i], true);
			}
			else if (matches[i].code == 'c') {
				matches[i].argument = String(String.fromCharCode(parseInt(Math.abs(parseInt(matches[i].argument)))));
				substitution = sprintfWrapper.convert(matches[i], true);
			}
			else if (matches[i].code == 'd') {
				matches[i].argument = String(Math.abs(parseInt(matches[i].argument)));
				substitution = sprintfWrapper.convert(matches[i]);
			}
			else if (matches[i].code == 'f') {
				matches[i].argument = String(Math.abs(parseFloat(matches[i].argument)).toFixed(matches[i].precision ? matches[i].precision : 6));
				substitution = sprintfWrapper.convert(matches[i]);
			}
			else if (matches[i].code == 'o') {
				matches[i].argument = String(Math.abs(parseInt(matches[i].argument)).toString(8));
				substitution = sprintfWrapper.convert(matches[i]);
			}
			else if (matches[i].code == 's') {
				matches[i].argument = matches[i].argument.substring(0, matches[i].precision ? matches[i].precision : matches[i].argument.length)
				substitution = sprintfWrapper.convert(matches[i], true);
			}
			else if (matches[i].code == 'x') {
				matches[i].argument = String(Math.abs(parseInt(matches[i].argument)).toString(16));
				substitution = sprintfWrapper.convert(matches[i]);
			}
			else if (matches[i].code == 'X') {
				matches[i].argument = String(Math.abs(parseInt(matches[i].argument)).toString(16));
				substitution = sprintfWrapper.convert(matches[i]).toUpperCase();
			}
			else {
				substitution = matches[i].match;
			}

			newString += strings[i];
			newString += substitution;

		}
		newString += strings[i];

		return newString;

	},

	convert : function(match, nosign){
		if (nosign) {
			match.sign = '';
		} else {
			match.sign = match.negative ? '-' : match.sign;
		}
		var l = match.min - match.argument.length + 1 - match.sign.length;
		var pad = new Array(l < 0 ? 0 : l).join(match.pad);
		if (!match.left) {
			if (match.pad == "0" || nosign) {
				return match.sign + pad + match.argument;
			} else {
				return pad + match.sign + match.argument;
			}
		} else {
			if (match.pad == "0" || nosign) {
				return match.sign + match.argument + pad.replace(/0/g, ' ');
			} else {
				return match.sign + match.argument + pad;
			}
		}
	}
}

sprintf = sprintfWrapper.init;
// array.js

function adesk_array_keys( input, search_value, strict ) {
    // Return all the keys of an array
    //
    // +    discuss at: http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_array_keys/
    // +       version: 801.3120
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: array_keys( {firstname: 'Kevin', surname: 'van Zonneveld'} );
    // *     returns 1: {0: 'firstname', 1: 'surname'}

    var tmp_arr = new Array(), strict = !!strict, include = true, cnt = 0;

    for ( key in input ) {
        include = true;
        if ( search_value != undefined ) {
            if ( strict && input[key] !== search_value ) {
                include = false;
            } else if ( input[key] != search_value ) {
                include = false;
            }
        }

        if ( include ) {
            tmp_arr[cnt] = key;
            cnt++;
        }
    }

    return tmp_arr;
}

function adesk_array_indexof(ary, val) {
	var i;

	for (i = 0; i < ary.length; i++) {
		if (ary[i] == val)
			return i;
	}

	return -1;
}

function adesk_array_values( input ) {
    // Return all the values of an array
    //
    // +    discuss at: http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_array_values/
    // +       version: 801.3120
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: array_values( {firstname: 'Kevin', surname: 'van Zonneveld'} );
    // *     returns 1: {0: 'Kevin', 1: 'van Zonneveld'}

    var tmp_arr = new Array(), cnt = 0;

    for ( key in input ){
        tmp_arr[cnt] = input[key];
        cnt++;
    }

    return tmp_arr;
}


function adesk_array_remove(node, arr, renum) {
	var newArr = new Array();
	for ( var i = 0; i < arr.length; i++ ) {
		if ( arr[i] != node ) {
			var j = ( renum ? newArr.length : i );
			newArr[j] = arr[i];
		}
	}
	return newArr;
}

function adesk_array_remove_key(k, arr) {
	var newArr = { };
	for ( var i in arr ) {
		if ( i != k ) {
			newArr[i] = arr[i];
		}
	}
	return newArr;
}
var adesk_liveedit_active_id = null;
var adesk_liveedit_enabled = false;

var adesk_liveedit_revert_after = function() {
	// do nothing
};

function adesk_liveedit_func_api(fcall, fcb, extra, id, method, post_id) {
	return function(anon_relid, anon_text) {
		if (method == "get") {
			adesk_ajax_call_cb(apipath, fcall, fcb, anon_relid, anon_text, extra);
		}
		else {
			var post = adesk_form_post($(post_id));
			adesk_ajax_post_cb(apipath, fcall, fcb, post, anon_relid, anon_text, extra);
		}
		if (adesk_editor_is(id + "Editor"))
			$(id).innerHTML = anon_text;
		else
			$(id).innerHTML = adesk_str_htmlescape(anon_text);
	};
}

function adesk_liveedit_func_cb(id, hook) {
	return function(xml) {
		var ary = adesk_dom_read_node(xml, null);
		adesk_ui_api_callback();

		if (ary.succeeded != "0") {
			adesk_result_show(ary.message);
			adesk_dom_liveedit_showtext(id);

			if (typeof adesk_liveedit_onclose != 'undefined')
				adesk_liveedit_onclose(id);

			/*
			if (adesk_editor_is(id + "Editor")) {
				var ed = tinyMCE.get(id + "Editor");
				$(id).innerHTML = ed.getContent();
			} else {
				$(id).innerHTML = $(id + "Editor").value;
			}
			*/

			if (hook !== null && typeof hook == "function")
				hook(ary);
		} else {
			adesk_error_show(ary.message);
			$(id).innerHTML = eval(sprintf("%s_orig", id));
			$(id + "Editor").value = $(id).innerHTML;
		}
	};
}

function adesk_liveedit_func_edit(id) {
	return function() {
		adesk_dom_liveedit_showform(id);
		if (!adesk_editor_is(id + "Editor"))
			$(id + "Editor").select();
		adesk_liveedit_active_id = id;
	};
}

function adesk_liveedit_func_save(id, relid, rev, api) {
	return function() {
		if (adesk_liveedit_active_id === null) {
			return rev();
		}

		var orig = eval(id + "_orig");
		var newstr;

		if (adesk_editor_is(id + "Editor")) {
			var ed = tinyMCE.get(id + "Editor");
			newstr = ed.getContent();
		} else {
			newstr = $(id + "Editor").value;
		}

		if (orig == newstr || newstr == '')
			return rev();

		eval(id + "_orig = newstr;");
		api(relid, newstr);
		adesk_liveedit_active_id = null;
		return false;
	};
}

function adesk_liveedit_func_revert(id) {
	return function() {
		var orig = eval(id + "_orig");
		$(id + 'Editor').value = orig;
		adesk_form_value_set($(id + 'Editor'), orig);
		if (adesk_editor_is(id + "Editor"))
			$(id).innerHTML = orig;
		else
			$(id).innerHTML = adesk_str_htmlescape(orig);
		adesk_dom_liveedit_showtext(id);
		adesk_liveedit_active_id = null;

		if (typeof adesk_liveedit_onclose != 'undefined')
			adesk_liveedit_onclose(id);
		return false;
	};
}

function adesk_liveedit_setparams(tid, text, func, column, relid) {
	window[tid + "_orig"] = text;

	// We may be called multiple times on a single page (e.g. from a paginator), so we want to
	// avoid recreating these functions.

	if (typeof window[tid + "_cb"] == "undefined") {
		window[tid + "_cb"]     = adesk_liveedit_func_cb(tid);
		window[tid + "_api"]    = adesk_liveedit_func_api(func, window[tid + "_cb"], column, tid);
		window[tid + "_revert"] = adesk_liveedit_func_revert(tid);
		window[tid + "_edit"]   = adesk_liveedit_func_edit(tid);
		window[tid + "_save"]   = adesk_liveedit_func_save(tid, relid, window[tid + "_revert"], window[tid + "_api"]);
	}
}

function adesk_liveedit_text(tid, text) {
	var out = sprintf("<div ondblclick=\"if (adesk_liveedit_enabled) %s_edit()\" class='adesk_liveedit_text' style='display:block' id='%s'>%s</div>", tid, tid, text);
	out += sprintf("<div id='%s_contain' style='display: none'>", tid);
	out += sprintf("<form method='POST' onsubmit=\"return %s_save()\">", tid);
	out += sprintf("<input type='text' class='adesk_liveedit_form_text' id='%sEditor' value='%s' onblur=\"%s_save()\" onkeypress=\"adesk_dom_keypress_doif(event, 27, %s_revert)\" />", tid, text, tid, tid);
	out += "</form>";
	out += "</div>";
	return out;
}

function adesk_liveedit_area(tid, text) {
	var class_html   = adesk_js_admin["htmleditor"] ? "currenttab" : "othertab";
	var class_text   = !adesk_js_admin["htmleditor"] ? "currenttab" : "othertab";

	var out = sprintf("<div ondblclick=\"if (adesk_liveedit_enabled) %s_edit()\" class='adesk_liveedit_text' style='display:block' id='%s'>%s</div>", tid, tid, text);
	out += sprintf("<div id='%s_contain' style='display: none'>", tid);
	out += "<ul class='navlist'>";
	out += sprintf("<li id='%sEditorLinkDefault' class='disabledtab' style='float:right; text-align:right; width:100px'>", tid);
	out += sprintf("<a href='#' onclick='return setDefaultEditor(\"%s\");'>%s</a>", tid, jsSetAsDefault);
	out += "</li>";
	out += "<li class='notatab'>" + jsContent + "</li>";
	out += sprintf("<li id='%sEditorLinkOn' class='%s'>", tid, class_html);
	out += sprintf("<a href='#' onclick='return toggleEditor(\"%s\", true);'><span>%s</span></a>", tid, jsHtmlEditor);
	out += "</li>";
	out += "</ul>";
	// Can't use sprintf here since it chokes on the percent signs in the width/height
	out += "<textarea id='" + tid + "Editor' style='width:" + adesk_js_site.brand_editorw + "; height:" + adesk_js_site.brand_editorh + "'>" + text + "</textarea>";
	out += "<br/>";
	out += sprintf("<input type='button' onclick='%s_save()' value='%s' />", tid, jsOK);
	out += sprintf("<input type='button' onclick='%s_revert()' value='%s' />", tid, jsCancel);
	out += "</div>";

	// space for toggle editor

	return out;
}
// utf.js

function adesk_utf_unescape(str) {
	return str.replace(/&#([0-9]+);/g, function(str, mat) { return String.fromCharCode(parseInt(mat, 10)); });
}

function adesk_utf_check(str) {
	for ( var i = 0; i < str.length; i++ ) {
		if ( str.charCodeAt(i) < 0x80 ) {
			// do nothing if 0bbbbbbb
			continue;
		} else if ( ( str.charCodeAt(i) & 0xE0 ) == 0xC0 ) {
			// 110bbbbb
			n = 1;
		} else if ( ( str.charCodeAt(i) & 0xF0 ) == 0xE0 ) {
			// 1110bbbb
			n = 2;
		} else if ( ( str.charCodeAt(i) & 0xF8 ) == 0xF0 ) {
			// 11110bbb
			n = 3;
		} else if ( ( str.charCodeAt(i) & 0xFC ) == 0xF8 ) {
			// 111110bb
			n = 4;
		} else if ( ( str.charCodeAt(i) & 0xFE ) == 0xFC ) {
			// 1111110b
			n = 5;
		} else {
			// it does not match any model
			return false;
		}
		// loop through found bytes offset
		for ( var j = 0; j < n; j++ ) {
			if ( ( ++i == str.length ) || ( ( str.charCodeAt(i) & 0xC0 ) != 0x80 ) ) {
				return false;
			}
		}
	}
	// it is utf8 string, nothing bad found
	return true;
}

function adesk_utf_reinterpret(str) {
	// If we have a UTF-8 string which we don't recognize as UTF-8 (each byte is interpreted
	// separately), put it back together.
	
	var _out = "";
	var a, b, c, d;

	for (var i = 0; i < str.length; i++) {
		a = str.charCodeAt(i);

		switch (adesk_utf_codelen(a)) {
			case 1:
			default:
				_out += str.charAt(i);
				break;

			case 2:
				a = a & 31;
				b = str.charCodeAt(++i) & 63;
				_out += String.fromCharCode((a << 6) | b);
				break;

			case 3:
				a = a & 15;
				b = str.charCodeAt(++i) & 63;
				c = str.charCodeAt(++i) & 63;
				_out += String.fromCharCode((a << 12) | (b << 6) | c);
				break;

			case 4:
				a = a & 7;
				b = str.charCodeAt(++i) & 63;
				c = str.charCodeAt(++i) & 63;
				d = str.charCodeAt(++i) & 63;
				_out += String.fromCharCode((a << 18) | (b << 12) | (c << 6) | d);
				break;
		}
	}

	return _out;
}

function adesk_utf_codelen(b) {
	if ((b & 240) == 240)
		return 4;
	if ((b & 224) == 224)
		return 3;
	if ((b & 192) == 192)
		return 2;
	return 1;
}

function adesk_utf_uri_encode(str, length) {
	// define needed vars
	if ( !length ) length = 0;
	var unicode = '';
	var values = new Array();
	var octets = 1;
	// loop through string
	for ( i = 0; i < str.length; i++ ) {
		var value = str.charCodeAt(i);
		if ( value < 128 ) {
			// if regular char
			if ( length && ( unicode.length + 1 > length ) ) {
				break;
			}
			unicode = unicode + String.fromCharCode(value);
		} else {
			// where is it?
			if ( values.length == 0 ) octets = ( value < 224 ? 2 : 3 );
			values.push(value);
			if ( length && ( unicode.length + octets * 3 > length ) ) {
				break;
			}
			// when found all parts, combine them
			if ( values.length == octets ) {
				unicode = unicode + '%' . values[0].toString(16) + '%' + values[1].toString(16);
				if ( octets == 3 ) unicode = unicode + '%' + values[2].toString(16);
				var values = new Array();
				var octets = 1;
			}
		}
	}
	return unicode;
}
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
// paginator.js
if ( paginators === undefined || paginators === null ) {
	var paginators      = new Array();
	var paginator_b64   = true;
}


/* PAGINATOR OBJECT METHODS */
function ACPaginator_init() {
	/*
		provide links to other results
	*/

	if (this.offset >= this.total && this.total > 0)
		this.paginate(this.offset - this.limit);

	// previous page link
	this.hasPrevious = ( this.offset > 0 );
	if ( this.hasPrevious ) {
		var prevOffset = this.offset - this.limit;
		if ( prevOffset <= 0 ) prevOffset = 0;
		this.previousOffset = prevOffset;
	}
	// next page link
	this.hasNext = ( this.total > this.offset + this.fetched );
	if ( this.hasNext ) {
		nextOffset = this.offset + this.fetched;
		this.nextOffset = nextOffset;
	}
	/*
		links to all other pages
	*/
	// here we will hold all pages
	this.links = new Array();
	// how many pages are there
	this.linksCnt = ( this.total == 0 ? 1 : Math.ceil(this.total / this.limit) );
	// where are we now?
	this.thisPage = 1;
	// loop through all
	for ( var i = 1; i <= this.linksCnt; i++ ) {
		this.links[i] = new Array();
		this.lastOffset = ( i - 1 ) * this.limit;
//alert('lastOffset: ' + this.lastOffset + '; currentOffset: ' + this.offset);
		if ( this.offset == this.lastOffset ) this.thisPage = i;
		this.links[i]['thisone'] = ( this.offset == this.lastOffset );
		this.links[i]['offset'] = this.lastOffset;
	}
	// loop through all, here define what to show
	for ( var i = 1; i <= this.linksCnt; i++ ) {
		this.links[i]['showit'] = ( this.showSpan == 0 || ( i > this.thisPage - this.showSpan && i < this.thisPage + this.showSpan ) );
	}
	// fill all HTML elements
	this.populate();
//bible(this);
}

function ACPaginator_rebuild(offset) {
	// passing in new offset
	this.offset = offset;
	this.init();
}

// passing in the DOM object for paginator we will fill
function ACPaginator_populate() {
	if ( !this.box ) return;
	// now fetch all objects we need to fill
	var thisSpan = document.getElementById('paginatorThisPage' + this.id);
	var prevSpan = document.getElementById('paginatorPrevious' + this.id);
	var nextSpan = document.getElementById('paginatorNext' + this.id);
	var firstSpan = document.getElementById('paginatorFirst' + this.id);
	var lastSpan = document.getElementById('paginatorLast' + this.id);
	var pageSpan = document.getElementById('paginatorPages' + this.id);
	//var limitSpan = document.getElementById('paginatorLimitBox' + this.id);
	var limitSelect = document.getElementById('paginatorLimit' + this.id);
	if ( !( prevSpan && nextSpan && firstSpan && lastSpan && pageSpan ) ) return;
	// fill them one by one
	// this page button
	var data = '';
	data = sprintf(jsPaginatorThis, this.thisPage, this.linksCnt);
	this.pushData(thisSpan, data);
	// previous button
	var data = '';
	if ( this.hasPrevious ) {
		data = '<a href="javascript:paginators[' + this.id + '].paginate(' + this.previousOffset + ');">' + jsPaginatorPrevious + '</a>';
	}
	this.pushData(prevSpan, data);
	// next button
	data = '';
	if ( this.hasNext ) {
		data = '<a href="javascript:paginators[' + this.id + '].paginate(' + this.nextOffset + ');">' + jsPaginatorNext + '</a>';
	}
	this.pushData(nextSpan, data);
	// first button
	data = '';
	if ( this.showSpan > 0 && this.thisPage > this.showSpan + 1 ) {
		data = '<a href="javascript:paginators[' + this.id + '].paginate(' + this.links[1].offset + ');">&laquo;</a> ...';
	}
	this.pushData(firstSpan, data);
	// last button
	data = '';
	if ( this.showSpan > 0 && this.thisPage <= this.linksCnt - this.showSpan ) {
		data = '... <a href="javascript:paginators[' + this.id + '].paginate(' + this.lastOffset + ');">&raquo;</a>';
	}
	this.pushData(lastSpan, data);
	// all pages
	data = '';
	for ( var i = 1; i <= this.linksCnt; i++ ) {
		if ( this.links[i]['showit'] ) {
			if ( !this.links[i]['thisone'] ) {
				data = data + '<a class="paginatorPageLink" href="javascript:paginators[' + this.id + '].paginate(' + this.links[i]['offset'] + ');">' + i + '</a>';
			} else if ( this.linksCnt > 1 ) {
				data = data + '<strong>' + i + '</strong>';
			}
		}
	}
	this.pushData(pageSpan, data);
	// limit select
	if ( limitSelect ) limitSelect.value = this.limit;
}

function ACPaginator_pushData(element, data) {
	element.innerHTML = data;
	element.className = ( data != '' ? '' : 'adesk_hidden' );
}

function ACPaginator_tabelize(rows, offset) {
	alert('Returned ' + rows.length + ' rows starting from offset ' + offset);
}

function ACPaginator_paginate(offset) {
	// fetch new list
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, offset, limit);
}

function ACPaginator_limitize(limit) {
	// set new limit
	this.limit = limit;
	this.offset = 0;
	// fetch new list
	this.paginate(this.offset);
}



/* PAGINATOR OBJECT */
function ACPaginator(id, total, fetched, limit, offset) {
	// input properties
	this.id = id;
	this.box = document.getElementById('paginatorBox' + id);
	this.total = total;
	this.fetched = fetched;
	this.limit = limit;
	this.offset = offset;

	// internal properties
	this.tabelized = false;
	this.hasPrevious = false;
	this.hasNext = false;
	this.previousOffset = 0;
	this.lastOffset = 0;
	this.nextOffset = limit; // first offset is limit
	this.links = new Array();
	this.linksCnt = 1;
	this.thisPage = 1;
	this.showSpan = 3;
	this.ajaxURL = 'awebdeskapi.php';
	this.ajaxAction = 'paginate';
//	this.baseURL = '';
//	this.offsetName = 'offset';

	this.boxes = [];

	// methods
	this.init = ACPaginator_init;
	this.rebuild = ACPaginator_rebuild;
	this.populate = ACPaginator_populate;
	this.pushData = ACPaginator_pushData;

	this.tabelize = ACPaginator_tabelize;
	this.paginate = ACPaginator_paginate;

	this.limitize = ACPaginator_limitize;

	// init (constructor)
	//this.init();
}






/* PAGINATOR OBJECT HANDLER */
function paginate(paginator, offset) {
	// fetch new list
	paginator.paginate(offset);
}

function paginateCB(xml, text) {
	var ary = adesk_dom_read_node(xml, paginator_b64 ? adesk_b64_decode : null);
	if ( isNaN(parseInt(ary.paginator, 10)) ) return;
	var id = ary.paginator;
	if (paginators[id].offset != ary.offset)
		paginators[id].boxes = [];
	// refill paginator
	paginators[id].offset = ( isNaN(parseInt(ary.offset, 10)) ? 0 : parseInt(ary.offset, 10) );
	//paginators[id].limit = ( isNaN(parseInt(ary.limit, 10)) ? 0 : parseInt(ary.limit, 10) );
	paginators[id].total = ( isNaN(parseInt(ary.total, 10)) ? 0 : parseInt(ary.total, 10) );
	paginators[id].fetched = ( isNaN(parseInt(ary.cnt, 10)) ? 0 : parseInt(ary.cnt, 10) ); /*ary.rows.length*/
	// rebuild paginator
	paginators[id].tabelize(ary.rows, paginators[id].offset, ary);
	paginators[id].tabelized = true;
	// rebuild paginator
	paginators[id].rebuild(( isNaN(parseInt(ary.offset)) ? 0 : parseInt(ary.offset) ));
}

function adesk_paginator_tabelize(table, tbodyid, rows, offset, trfunc) {
	adesk_ui_api_callback();
	if ( typeof(table.dontreuse) != 'undefined' ) {
		// support for "dontreuse" switch
		adesk_dom_remove_children($(tbodyid));
	}

	var trs = $(tbodyid).getElementsByTagName("tr");
	var i;

	table.selection = adesk_form_check_selection_get($(tbodyid), "multi[]");

	for (i = 0; i < rows.length; i++) {
		if (i >= trs.length || typeof(table.dontreuse) != 'undefined' )
			var newRow = $(tbodyid).appendChild(table.newrow(rows[i]));
		else
			var newRow = table.reuserow(rows[i], trs[i]);

		if ( typeof trfunc == 'function' ) {
			trfunc(rows[i], newRow);
		}
	}

	if ( typeof(table.dontreuse) == 'undefined' ) {
		// Unfortunately, we HAVE to use getElements to remove the children.  It seems
		// to be a reference thing.
		while ( $(tbodyid).getElementsByTagName('tr').length > rows.length ) {
			$(tbodyid).removeChild ($(tbodyid).getElementsByTagName('tr')[ rows.length ]);
		}
	}
}
function adesk_star_clear(starObjId) {
	var elems = $(starObjId).getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++)
		elems[i].className = "adesk_star_none";

	return elems;
}

function adesk_star_hover(starObjId, limit) {
	var elems = adesk_star_clear(starObjId);

	for (var i = 0; i < elems.length; i++) {
		elems[i].className = "adesk_star_hover";

		if ((i+1) >= limit)
			break;
	}
}

function adesk_star_render(starObjId) {
	var rating = $(starObjId + "_rating").innerHTML;
	var elems = adesk_star_clear(starObjId);
	var cr = rating;		// Ratings counter
	var cls = "";

	for (var i = 0; i < elems.length; i++) {

		cls = "adesk_star_none";
		if (cr >= 1.0)
			cls = "adesk_star_full";
		else if (cr >= 0.5)
			cls = "adesk_star_half";

		elems[i].className = cls;
		cr -= 1.0;
	}
}

function adesk_star_callback(xml) {
	var ary = adesk_dom_read_node(xml, null);

	adesk_star_set(ary.prefix, 0, ary.rating);
}

function adesk_star_set(starObjId, relid, val) {
	var rateid = starObjId + "_rating";

	if ($(rateid) !== null) {
		$(rateid).innerHTML = val;
		adesk_star_render(starObjId);
	}
}

function adesk_star_get(starObjId) {
	var rateid = starObjId + "_rating";

	if ($(rateid) !== null) {
		return $(rateid).innerHTML;
	}
	return 0;
}

function adesk_stars(rating) {
	var count = 5;
	var links = "";
	var cr = parseFloat(rating);
	var ci = 0;
	var cls;

	while (count--) {
		ci++;

		cls = "adesk_star_none";
		if (cr >= 1.0)
			cls = "adesk_star_full";
		else if (cr >= 0.5)
			cls = "adesk_star_half";

		links += sprintf("<a class=\"%s\" href=\"javascript:void(0)\" style=\"cursor:default\">", cls);
		links += sprintf("<img style=\"padding: 0px\" border=\"0\" align=\"absmiddle\" src=\"%s/media/adesk_star_clear.gif\" />", acgpath);
		links += "</a>";

		cr -= 1.0;
	}

	return "<span>" + links + "</span>";
}

function adesk_star_disable(starObjId) {
	var rel = $(starObjId);
	if ( !rel ) return;
	var val = $(starObjId + '_rating').innerHTML;
	var stars = adesk_stars(val);
	rel.innerHTML = stars;
}
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
// tooltip.js

/***********************************************
* Cool DHTML tooltip script II- ? Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

var offsetfromcursorX=12 //Customize x offset of tooltip
var offsetfromcursorY=10 //Customize y offset of tooltip

var offsetdivfrompointerX=10 //Customize x offset of tooltip DIV relative to pointer image
var offsetdivfrompointerY=14 //Customize y offset of tooltip DIV relative to pointer image. Tip: Set it to (height_of_pointer_image-1).

var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
var tipobj=""
var pointerobj=""

function adesk_tooltip_init(pointerurl) {
	if ( !pointerurl ) {
		var pointerurl = ( document.location.href.match(/\/manage\//) ? '../awebdesk' : 'awebdesk' );
	}
	pointerurl += '/media/arrow2.gif';
	var ie=document.all
	var ns6=document.getElementById && !document.all
	document.write('<div id="dhtmltooltip" style="visibility:hidden;left:0;top:0;"></div>') //write out tooltip DIV
	document.write('<img id="dhtmlpointer" style="visibility:hidden;left:0;top:0;" src="' + pointerurl + '">') //write out pointer image
	if (ie||ns6)
		tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""
	pointerobj=document.all? document.all["dhtmlpointer"] : document.getElementById? document.getElementById("dhtmlpointer") : ""
	document.onmousemove=positiontip
}

function ietruebody(){
	return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function adesk_tooltip_show(thetext, thewidth, thecolor, decodeit){
	var ie=document.all;
	var ns6=document.getElementById && !document.all;
	if (ns6||ie){
		if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px";
		if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor;
		if (typeof decodeit!="undefined" && decodeit) thetext = adesk_b64_decode(thetext);
		thetext = adesk_str_htmlescape(thetext);
		// Don't escape the br tags (either <br> or <br/>
		thetext = thetext.replace(/&lt;br \/&gt;/g, "<br/>");
		thetext = thetext.replace(/&lt;br\/&gt;/g, "<br/>");
		thetext = thetext.replace(/&lt;br&gt;/g, "<br/>");
		tipobj.innerHTML=thetext;
		enabletip=true;
		return false;
	}
}

function positiontip(e){
	var ie=document.all
	var ns6=document.getElementById && !document.all
	if (enabletip){
		var nondefaultpos=false
		var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
		var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
		//Find out how close the mouse is to the corner of the window
		var winwidth=ie&&!window.opera? ietruebody().clientWidth : window.innerWidth-20
		var winheight=ie&&!window.opera? ietruebody().clientHeight : window.innerHeight-20

		var rightedge=ie&&!window.opera? winwidth-event.clientX-offsetfromcursorX : winwidth-e.clientX-offsetfromcursorX
		var bottomedge=ie&&!window.opera? winheight-event.clientY-offsetfromcursorY : winheight-e.clientY-offsetfromcursorY

		var leftedge=(offsetfromcursorX<0)? offsetfromcursorX*(-1) : -1000

		//if the horizontal distance isn't enough to accomodate the width of the assets menu
		if (rightedge<tipobj.offsetWidth){
			//move the horizontal position of the menu to the left by it's width
			tipobj.style.left=curX-tipobj.offsetWidth+"px"
			nondefaultpos=true
		}
		else if (curX<leftedge)
		tipobj.style.left="5px"
		else{
			//position the horizontal position of the menu where the mouse is positioned
			tipobj.style.left=curX+offsetfromcursorX-offsetdivfrompointerX+"px"
			pointerobj.style.left=curX+offsetfromcursorX+"px"
		}

		//same concept with the vertical position
		if (bottomedge<tipobj.offsetHeight){
			tipobj.style.top=curY-tipobj.offsetHeight-offsetfromcursorY+"px"
			nondefaultpos=true
		}
		else{
			tipobj.style.top=curY+offsetfromcursorY+offsetdivfrompointerY+"px"
			pointerobj.style.top=curY+offsetfromcursorY+"px"
		}
		tipobj.style.visibility="visible"
		if (!nondefaultpos)
		pointerobj.style.visibility="visible"
		else
		pointerobj.style.visibility="hidden"
	}
}

function adesk_tooltip_hide(){
	var ie=document.all
	var ns6=document.getElementById && !document.all
	if (ns6||ie){
		enabletip=false
		tipobj.style.visibility="hidden"
		pointerobj.style.visibility="hidden"
		tipobj.style.left="-1000px"
		tipobj.style.backgroundColor=''
		tipobj.style.width=''
	}
}
// date.js

var adesk_date = {

    ms_second: 1000,
    ms_minute: 1000 * 60,
    ms_hour:   1000 * 60 * 60,
    ms_day:    1000 * 60 * 60 * 24,
    ms_week:   1000 * 60 * 60 * 24 * 7

};

function adesk_date_today() {
    var d = new Date();
    d.setHours(0);
    d.setMinutes(0);
    d.setSeconds(0);
    d.setMilliseconds(0);
    return d;
}

function adesk_date_month_first(d) {
    var r = new Date(d);
    r.setDate(1);
    return r;
}

function adesk_date_month_end(d) {
    var r = adesk_date_month_first(d);
    r.setMonth(d.getMonth() + 1);
    return new Date(r - adesk_date.ms_day);
}

function adesk_date_month_days(d) {
    var r = adesk_date_month_end(d);
    return r.getDate();
}

function adesk_date_month_next(d) {
    var r = new Date(d);
    r.setMonth(d.getMonth() + 1);
    return r;
}

function sql2date(str) {
	var arr = str.match(/\d+/g);
	if ( arr.length != 6 && arr.length != 3 ) return new Date;
	if ( arr.length == 3 ) {
		if ( str.match(/-/) ) {
			// dates
			return new Date(arr[0], arr[1] - 1, arr[2]);
		} else {
			// times
			return new Date(0, 0, 0, arr[0], arr[1], arr[2]);
		}
	}
	return new Date(arr[0], arr[1] - 1, arr[2], arr[3], arr[4], arr[5]);
}



/**
 * Date.format()
 * string format ( string format )
 * Formatting rules according to http://php.net/strftime
 *
 * Copyright (C) 2006  Dao Gottwald
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * Contact information:
 *   Dao Gottwald  <dao at design-noir.de>
 *
 * @version  0.7
 * @todo     %g, %G, %U, %V, %W, %z, more/better localization
 * @url      http://design-noir.de/webdev/JS/Date.format/
 */

var _lang = (navigator.systemLanguage || navigator.userLanguage || navigator.language || navigator.browserLanguage || '').replace(/-.*/,'');
switch (_lang) {
	case 'de':
		Date._l10n = {
			days: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
			months: ['Januar','Februar','M\u00E4rz','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
			date: '%e.%m.%Y',
			time: '%H:%M:%S'};
		break;
	case 'es':
		Date._l10n = {
			days: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','S\u00E1bado'],
			months: ['enero','febrero','marcha','abril','puede','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'],
			date: '%e.%m.%Y',
			time: '%H:%M:%S'};
		break;
	case 'fr':
		Date._l10n = {
			days: ['dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi'],
			months: ['janvier','f\u00E9vrier','mars','avril','mai','juin','juillet','ao\u00FBt','septembre','octobre','novembre','decembre'],
			date: '%e/%m/%Y',
			time: '%H:%M:%S'};
		break;
	case 'it':
		Date._l10n = {
			days: ['domenica','luned\u00EC','marted\u00EC','mercoled\u00EC','gioved\u00EC','venerd\u00EC','sabato'],
			months: ['gennaio','febbraio','marzo','aprile','maggio','giugno','luglio','agosto','settembre','ottobre','novembre','dicembre'],
			date: '%e/%m/%y',
			time: '%H.%M.%S'};
		break;
	case 'pt':
		Date._l10n = {
			days: ['Domingo','Segunda-feira','Ter\u00E7a-feira','Quarta-feira','Quinta-feira','Sexta-feira','S\u00E1bado'],
			months: ['Janeiro','Fevereiro','Mar\u00E7o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
			date: '%e/%m/%y',
			time: '%H.%M.%S'};
		break;
	case 'en':
	default:
		Date._l10n = {
			days: ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
			months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
			date: '%Y-%m-%e',
			time: '%H:%M:%S'};
		break;
}
Date._pad = function(num, len) {
	for (var i = 1; i <= len; i++)
		if (num < Math.pow(10, i))
			return new Array(len-i+1).join(0) + num;
	return num;
};
Date.prototype.format = function(format) {
	if (format.indexOf('%%') > -1) { // a literal `%' character
		format = format.split('%%');
		for (var i = 0; i < format.length; i++)
			format[i] = this.format(format[i]);
		return format.join('%');
	}
	format = format.replace(/%D/g, '%m/%d/%y'); // same as %m/%d/%y
	format = format.replace(/%r/g, '%I:%M:%S %p'); // time in a.m. and p.m. notation
	format = format.replace(/%R/g, '%H:%M:%S'); // time in 24 hour notation
	format = format.replace(/%T/g, '%H:%M:%S'); // current time, equal to %H:%M:%S
	format = format.replace(/%x/g, Date._l10n.date); // preferred date representation for the current locale without the time
	format = format.replace(/%X/g, Date._l10n.time); // preferred time representation for the current locale without the date
	var dateObj = this;
	return format.replace(/%([aAbhBcCdegGHIjmMnpStuUVWwyYzZ])/g, function(match0, match1) {
		return dateObj.format_callback(match0, match1);
	});
}
Date.prototype.format_callback = function(match0, match1) {
	switch (match1) {
		case 'a': // abbreviated weekday name according to the current locale
			return Date._l10n.days[this.getDay()].substr(0,3);
		case 'A': // full weekday name according to the current locale
			return Date._l10n.days[this.getDay()];
		case 'b':
		case 'h': // abbreviated month name according to the current locale
			return Date._l10n.months[this.getMonth()].substr(0,3);
		case 'B': // full month name according to the current locale
			return Date._l10n.months[this.getMonth()];
		case 'c': // preferred date and time representation for the current locale
			return this.toLocaleString();
		case 'C': // century number (the year divided by 100 and truncated to an integer, range 00 to 99)
			return Math.floor(this.getFullYear() / 100);
		case 'd': // day of the month as a decimal number (range 01 to 31)
			return Date._pad(this.getDate(), 2);
		case 'e': // day of the month as a decimal number, a single digit is preceded by a space (range ' 1' to '31')
			return Date._pad(this.getDate(), 2);
		/*case 'g': // like %G, but without the century
			return ;
		case 'G': // The 4-digit year corresponding to the ISO week number (see %V). This has the same format and value as %Y, except that if the ISO week number belongs to the previous or next year, that year is used instead
			return ;*/
		case 'H': // hour as a decimal number using a 24-hour clock (range 00 to 23)
			return Date._pad(this.getHours(), 2);
		case 'I': // hour as a decimal number using a 12-hour clock (range 01 to 12)
			var x = this.getHours() % 12;
			if ( x == 0 ) x = 12;
			return Date._pad(x, 2);
		case 'j': // day of the year as a decimal number (range 001 to 366)
			return Date._pad(this.getMonth() * 30 + Math.ceil(this.getMonth() / 2) + this.getDay() - 2 * (this.getMonth() > 1) + (!(this.getFullYear() % 400) || (!(this.getFullYear() % 4) && this.getFullYear() % 100)), 3);
		case 'm': // month as a decimal number (range 01 to 12)
			return Date._pad(this.getMonth() + 1, 2);
		case 'M': // minute as a decimal number
			return Date._pad(this.getMinutes(), 2);
		case 'n': // newline character
			return '\n';
		case 'p': // either `am' or `pm' according to the given time value, or the corresponding strings for the current locale
			return this.getHours() < 12 ? 'am' : 'pm';
		case 'S': // second as a decimal number
			return Date._pad(this.getSeconds(), 2);
		case 't': // tab character
			return '\t';
		case 'u': // weekday as a decimal number [1,7], with 1 representing Monday
			return this.getDay() || 7;
		/*case 'U': // week number of the current year as a decimal number, starting with the first Sunday as the first day of the first week
			return ;
		case 'V': // The ISO 8601:1988 week number of the current year as a decimal number, range 01 to 53, where week 1 is the first week that has at least 4 days in the current year, and with Monday as the first day of the week. (Use %G or %g for the year component that corresponds to the week number for the specified timestamp.)
			return ;
		case 'W': // week number of the current year as a decimal number, starting with the first Monday as the first day of the first week
			return ;*/
		case 'w': // day of the week as a decimal, Sunday being 0
			return this.getDay();
		case 'y': // year as a decimal number without a century (range 00 to 99)
			return this.getFullYear().toString().substr(2);
		case 'Y': // year as a decimal number including the century
			return this.getFullYear();
		/*case 'z':
		case 'Z': // time zone or name or abbreviation
			return ;*/
		default:
			return match0;
	}
}


/**
 * strftime() - JavaScript porting from PHP's strftime: http://php.net/strftime
 * string strftime ( string format [, int timestamp] )
 *
 * Copyright (C) 2006  Dao Gottwald  <dao at design-noir.de>
 *
 * Licensed under the terms of the GNU Lesser General Public License:
 *   http://www.opensource.org/licenses/lgpl-license.php
 *
 * @version  1.0
 * @require  Date.format()
 * @url      http://design-noir.de/webdev/JS/Date.format/
 */

function strftime(format, timestamp) {
	var t = new Date;
	if (typeof timestamp != 'undefined')
		t.setTime(timestamp * 1000);
	return t.format(format);
}

function tstamp() { return Math.round(((new Date()).getTime()-Date.UTC(1970,0,1))/1000); }// custom_fields.js

var field_dom_id = 0;
function adesk_custom_fields_title(field, showhidden) {
	if (typeof showhidden == "undefined")
		showhidden = false;

    if (field.type != "6" || showhidden)
        return field.title;
}

function adesk_custom_fields_bubble(node, field) {
	if ( !field.bubble_content ) return node;
	if ( field.bubble_content == '' ) return node;
	return Builder.node(
		"span",
		[
			node,
			Builder.node("div", { id: 'field' + field.id + 'bubble', className: 'adesk_help', style: 'display: none;' }, [ Builder._text(field.bubble_content) ])
		]
	);
}

function adesk_custom_fields_cons(field, showhidden) {
	if (typeof showhidden == "undefined")
		showhidden = false;

    var f_name = "field[" + field.id + "," + field.dataid + "]";
    var f_type = parseInt(field.type, 10);
   	var props = {};
    if ( field.bubble_content && field.bubble_content != '' ) {
		props.onmouseover = "adesk_dom_toggle_display('field" + field.id + "bubble', 'block');";
		props.onmouseout  = "adesk_dom_toggle_display('field" + field.id + "bubble', 'block');";
    }
    switch (f_type) {
        case 1:     // Text field
            if (field.val === "")
                field.val = field.onfocus;
            // properties
            props.type = "text";
            props.name = f_name;
            props.value = field.val;
			props.onKeyUp = "if (typeof custom_field_text_onkeyup == 'function' && window.event && window.event.keyCode) custom_field_text_onkeyup(window.event.keyCode)";
            return adesk_custom_fields_bubble(Builder.node("input", props), field);

        case 2:     // Text box
            var f_cols;
            var f_rows;
            if (field.onfocus !== '') {
                var dim = field.onfocus.split("||");
                f_cols = dim[0];
                f_rows = dim[1];
            } else {
                f_cols = 30;
                f_rows = 5;
            }
            if (field.val === '')
                field.val = field.expl;
            // properties
            props.rows = f_rows;
            props.cols = f_cols;
            props.name = f_name;
            return adesk_custom_fields_bubble(Builder.node("textarea", props, [ Builder._text(field.val) ]), field);

        case 3:     // Checkbox
            if (field.val === '')
                field.val = field.onfocus;
            // properties
            props.type = "checkbox";
            props.name = f_name;
            props.value = "checked";
            if (field.val == "checked")
                props.checked = "true";
            return Builder.node(
            	"span",
            	[
                    Builder.node("input", { type: "hidden", name: f_name, value: "unchecked" }),
                    adesk_custom_fields_bubble(Builder.node("input", props), field)
                ]
            );

        case 4:     // Radio button(s)
            if (field.val === '')
                field.val = field.onfocus;
            var f_ary    = new Array();
            f_ary.push(Builder.node("input", { type: "hidden", name: f_name, value: "unchecked" }));

            field.expl = field.expl.replace(/\r?\n/g, "||");
            var list = field.expl.split("||");

			// This isn't a valid field; skip it.
			if ((list.length % 2) == 1)
				return Builder._text(" ");

            for (var i = 0; i < list.length; i += 2) {
				props = {};
                // properties
                props.type = "radio";
                props.name = f_name;
                props.value = list[i+1];
                if (field.val == list[i+1])
                    props.checked = "true";
                f_ary.push(Builder.node("input", props));
                f_ary.push(Builder._text(list[i+0]));
            }

            return Builder.node("div", f_ary);

        case 5:     // Dropdown
            if (field.val === '')
                field.val = field.onfocus;

            var f_ary = new Array();
            field.expl = field.expl.replace(/\r?\n/g, "||");
            var list = field.expl.split("||");

			// This isn't a valid field; skip it.
			if ((list.length % 2) == 1)
				return Builder._text(" ");

            var found = false;
            for (var i = 0; i < list.length; i += 2) {
                var f_opt = { value: list[i+1] };

                if (field.val == list[i+1]) {
                    f_opt.selected = "true";
                    found = true;
                }

                f_ary.push(Builder.node("option", f_opt, [ Builder._text(list[i+0]) ]));
            }

            // properties
            props.name = f_name;
            props.size = 1;
            var elem = Builder.node("select", props, f_ary);
            if ( found ) {
            	elem.value = field.val;
            }
            return adesk_custom_fields_bubble(elem, field);

        case 6:     // Hidden field
            if (field.val === '')
                field.val = field.onfocus;

			if (showhidden)
				return Builder.node("input", { type: "text", name: f_name, value: field.val });
			else
				return Builder.node("input", { type: "hidden", name: f_name, value: field.val });

        case 7:     // List box (select with multiple)
			var div    = Builder.node("div");
			var input  = Builder.node("input", { type: "hidden", name: f_name, value: "~|" });
            field.expl = field.expl.replace(/\r?\n/g, "||");
            var list   = field.expl.split("||");
			var f_ary  = new Array();

			// This isn't a valid field; skip it.
			if ((list.length % 2) == 1)
				return Builder._text(" ");

            for (var i = 0; i < list.length; i += 2) {
                var f_opt = { value: list[i+1] };

                f_ary.push(Builder.node("option", f_opt, [ Builder._text(list[i+0]) ]));
            }

			var select = Builder.node("select", { name: f_name, multiple: true }, f_ary);
			div.appendChild(input);
			div.appendChild(adesk_custom_fields_bubble(select, field));
			select.value = field.val;
			return div;
        case 8:     // Checkbox group
			var input  = Builder.node("input", { type: "hidden", name: f_name + "[]", value: "~|" });
			field.expl = field.expl.replace(/\r?\n/g, "||");
            var list   = field.expl.toString().split("||");
			var f_ary  = new Array();
			var values = field.val.toString().split("||");

			f_ary.push(input);

			// This isn't a valid field; skip it.
			if ((list.length % 2) == 1)
				return Builder._text(" ");

            for (var i = 0; i < list.length; i += 2) {
                var f_opt = { type: "checkbox", name: f_name + "[]", value: list[i+1] };

                if (values.indexOf(list[i+1]) > -1) {
                    f_opt.checked = "true";
                    found = true;
                }

				f_ary.push(adesk_custom_fields_bubble(Builder.node("label", { className: "cFieldCheckboxGroup" }, [
					Builder.node("input", f_opt),
					Builder._text(list[i])
				]), field));
				f_ary.push(Builder.node("br"));
            }
			return Builder.node("div", f_ary);

        case 9:     // Date field
            field_dom_id++;
            if (field.val === "")
                field.val = field.onfocus;
            // properties
            props.id = 'datecfield' + field_dom_id;
            props.type = "text";
            props.name = f_name;
            props.value = field.val;
            if ( field.val == 'now' ) {
				var dteNow = new Date();
				sMonth = dteNow.getMonth() + 1;
				sDay = dteNow.getDate();
				sYear = dteNow.getFullYear();
				sHours = dteNow.getHours();
				sActDate = sYear + "-" + ( sMonth < 10 ? '0' : '') + sMonth + "-" + ( sDay < 10 ? '0' : '') + sDay;
            	props.value = sActDate;
            }
            var nodes = [
            	Builder.node("input", props),
            	Builder._text(" "),
            	Builder.node(
            		"a",
            		{ href: '#', onclick: 'return false;', id: 'datecbutton' + field_dom_id},
            		[ Builder.node('img', { src: acgpath + '/media/calendar.png', border: 0 }) ]
            	)
            ];
            window.setTimeout(
	            function() {
					if ($('datecfield' + field_dom_id)) {
						Calendar.setup({
							inputField: 'datecfield' + field_dom_id,
							ifFormat: '%Y-%m-%d',
							button: 'datecbutton' + field_dom_id,
							showsTime: false,
							timeFormat: '24'
						});
					}
	            },
	            1000
            );
            return adesk_custom_fields_bubble(Builder.node("span", nodes), field);

        default:
            break;
    }

    return Builder._text("Sorry!  Unknown field");
}




var ACCustomFields = null;
var ACCustomFieldsObj = null;
var ACCustomFieldsResult = {};


/* CUSTOM FIELDS OBJECT */
if (typeof Class != "undefined") {
	ACCustomFields = Class.create();
	ACCustomFields.prototype = {
		// Make this true if you want hidden fields to be displayed (as text fields).
		showhidden: false,

		initialize:
			function(props) {
				if ( !props ) props = { };
				// if checkboxes are used, it will preserve the selection in this array
				this.selection = [];
				// this array holds the current relations list (RELIDs)
				this.rels = ( !props.rels ? [] : props.rels );
				// this array holds all handlers for ajax response
				// index is updating object id, and value is the type of list we'll build there
				// options for type are:
				// display (shows fields),
				// list (gives a list of fields with checkboxes),
				// pers (builds a personalization dropdown)
				this.handlers = {};
				// sourceType is determining what is holding the RELIDs.
				// can be SELECT or CHECKBOX
				// default: SELECT
				this.sourceType = ( !props.sourceType ? 'SELECT' : props.sourceType );
				// which SELECT object is holding the list of RELIDs
				// which DIV object is holding the list of RELID checkboxes
				this.sourceId = ( !props.sourceId ? 'parentsList' : props.sourceId );
				// what is the name of CHECKBOXES that hold RELIDs
				this.sourceName = ( !props.sourceName ? 'p[]' : props.sourceName );
				// which API function to call
				this.api = ( !props.api ? 'list.list_field_update' : props.api );
				// which index in API response holds fields array
				this.responseIndex = ( !props.responseIndex ? 'row' : props.responseIndex );
				// any additional handlers (for some other data)?
				this.additionalHandler = ( !props.additionalHandler ? null : props.additionalHandler );
				// if global custom fields should be fetched or not
				this.includeGlobals = ( !props.includeGlobals ? 0 : props.includeGlobals );
				// if some custom param should be sent
				this.apiParam = ( !props.apiParam? '' : props.apiParam );
			},

		addHandler:
			function(targetId, type) {
				this.handlers[targetId] = type;
			},

		addCustomHandler:
			function(targetId, func, responseIndex) {
				this.handlers[targetId] = { func: func, responseIndex: responseIndex};
			},

		removeHandler:
			function(targetId) {
				if (typeof this.handlers[targetId] != "undefined")
					delete this.handlers[targetId];
			},

		fetch:
			function(id) {
				// fetch relation ids
				if ( this.sourceType == 'SELECT' ) {
					if ($(this.sourceId))
						this.rels = adesk_form_select_extract($(this.sourceId));
					else
						this.rels = adesk_dom_boxchoice(this.sourceId);
				} else if ( this.sourceType == 'CHECKBOX' ) {
					if ($(this.sourceId))
						this.rels = adesk_form_check_selection_get($(this.sourceId), this.sourceName);
					else
						this.rels = adesk_dom_boxchoice(this.sourceId);
				} else if ( this.sourceType != 'STATIC' ) {
					this.rels = [];
				}
				ACCustomFieldsObj = this;
				adesk_ui_api_call(jsLoading);
				adesk_ajax_call_cb('awebdeskapi.php', this.api, this.handle, id, this.rels.join('-'), this.includeGlobals, this.apiParam);
				somethingChanged = true;
			},

		handle:
			function(xml) {
				// need to use ACCustomFieldsObj instead of this ( a copy used for callback )
				var ary = adesk_dom_read_node(xml);
				adesk_ui_api_callback();
				ACCustomFieldsResult = ary[ACCustomFieldsObj.responseIndex];
				for ( var i in ACCustomFieldsObj.handlers ) {
					var type = ACCustomFieldsObj.handlers[i];
					if ( typeof type != 'function' ) {
						var targetObj = $(i);
						if ( !targetObj ) targetObj = i;
						if ( typeof type.func == 'function' ) {
							if ( !type.responseIndex ) type.responseIndex = ACCustomFieldsObj.responseIndex;
							if ( !type.targetObj ) type.targetObj = targetObj;
							type.func(ary[type.responseIndex], type.targetObj);
						} else if ( type == 'list' ) {
							ACCustomFieldsObj.handleList(ACCustomFieldsResult, targetObj);
						} else if ( type == 'links' ) {
							ACCustomFieldsObj.handlePersonalizationLinks(ACCustomFieldsResult, targetObj);
						} else if ( type == 'pers' ) {
							ACCustomFieldsObj.handlePersonalization(ACCustomFieldsResult, targetObj, 'tag');
						} else if ( type == 'pers-with-id-values' ) {
							ACCustomFieldsObj.handlePersonalization(ACCustomFieldsResult, targetObj, 'id');
						} else if ( type == 'display' ) {
							ACCustomFieldsObj.handleDisplay(ACCustomFieldsResult, targetObj);
						} else if ( typeof(type) == 'function' ) {
							type(ACCustomFieldsResult, targetObj);
						}
					}
				}
				if ( typeof(ACCustomFieldsObj.additionalHandler) == 'function') {
					ACCustomFieldsObj.additionalHandler(ary);
				}
			},



		/* HANDLERS */


		handleList:
			function(ary, rel) {
				adesk_dom_remove_children(rel);
				var total = 0;
				if ( ary ) {
					for ( var i = 0; i < ary.length; i++ ) {
						var row = ary[i];
						var props = { name: 'fields[]', id: 'custom' + row.id + 'Field', type: 'checkbox', value: row.id };
						if ( !this.selection || adesk_array_has(this.selection, row.id) ) {
							props.checked = 'checked';
						}
						rel.appendChild(
							Builder.node(
								"tr",
								[
									Builder.node("td", [ Builder._text(" ") ]),
									Builder.node(
										"td",
										[
											Builder.node(
												'label',
												[
													Builder.node(
														'input',
														props
													),
													Builder._text(row.title)
												]
											)
										]
									)
								]
							)
						);
						total++;
					}
				}
			},

		handlePersonalization:
			function(ary, rel, elem) {
				if ( !elem ) elem = 'tag';
				// custom fields
				var nodesin  = [];
				// check if there is an existing group
				// if yes, we'll remove it first
				var optgroups = rel.getElementsByTagName('optgroup');
				for ( var i = 0; i < optgroups.length; i++ ) {
					if ( optgroups[i].label == strPersListFields ) {
						rel.removeChild(optgroups[i]);
						break;
					}
				}
				for ( var i in ary ) {
					var f = ary[i];
					if ( typeof f != 'function' ) {
						if ( !f.tag ) {
							if ( !f.perstag || f.perstag == '' ) {
								f.perstag = 'PERS_' + f.id;
							}
							f.tag = '%' + f.perstag + '%';
						}
						nodesin.push( Builder.node('option', { value: f[elem] }, [ Builder._text(f.title) ]));
					}
				}
				if ( nodesin.length > 0 ) {
					rel.appendChild(Builder.node('optgroup', { label: strPersListFields }, nodesin));
				}
				rel.selectedIndex = 0;
				//alert('handle personalization now!' + nodesin.length + rel.id);
			},

		handlePersonalizationLinks:
			function(ary, rel) {
				// custom fields
				var nodesin  = [];
				// check if there is an existing group
				// if yes, we'll remove it first
				var divgroups = $$('#' + rel.id + ' div.personalizelisttitle a');
				for ( var i = 0; i < divgroups.length; i++ ) {
					if ( divgroups[i].innerHTML == strPersListFields ) {
						rel.removeChild(divgroups[i].parentNode.parentNode);
						break;
					}
				}
				for ( var i in ary ) {
					var f = ary[i];
					if ( typeof f != 'function' ) {
						if ( !f.tag ) {
							if ( !f.perstag || f.perstag == '' ) {
								f.perstag = 'PERS_' + f.id;
							}
							f.tag = '%' + f.perstag + '%';
						}
						nodesin.push(
							Builder.node(
								'li',
								[
									Builder.node(
										'a', {
											href: '#',
											onclick: "form_editor_personalize_insert('" + f.tag + "');return false;",
											style: 'font-weight:bold;'
										},
										[ Builder._text(f.title) ]
									)
								]
							)
						);
					}
				}
				if ( nodesin.length > 0 ) {
					adesk_dom_remove_children($("personalize_subinfo_field"));
					form_editor_personalization_push(nodesin, "personalize_subinfo_field");
				}
				//alert('handle personalization now!' + nodesin.length + rel.id);
			},

		handleDisplay:
			function(ary, targetId) {
				var rel = $(targetId);
				adesk_dom_remove_children(rel);
				var total = 0;
				var visible = 0;
				if ( ary ) {
					for ( var i = 0; i < ary.length; i++ ) {
						var row = ary[i];
						var node = adesk_custom_fields_cons(row, this.showhidden);

						if (typeof node.innerHTML != "undefined") {
							if ( parseInt(row.type, 10) != 6 || this.showhidden ) {
								rel.appendChild(Builder.node(
									"tr",
									[
										Builder.node("td", { valign: 'top'/*, width: "75"*/ }, [ Builder._text(adesk_custom_fields_title(row, this.showhidden)) ]),
										Builder.node("td", [ node ])
									]
								));
							} else {
								rel.appendChild(node);
								/*rel.appendChild(Builder.node(
									"tr",
									[
										Builder.node("td", [ Builder._text(" ") ]),
										Builder.node("td", [ node ])
									]
								));*/
							}
						}
						total++;
						if ( parseInt(row.type, 10) != 6 ) visible++;
					}
				}
			}
	};
}
// editor.js


/*
	TINY MCE
*/


function adesk_editor_toggle(id, settings) {
	// if adding an editor, and settings object is provided
	if ( !adesk_editor_is(id) && typeof settings == 'object' ) {
		// assign it
		if ( typeof settings.language == 'undefined' && typeof _twoletterlangid != 'undefined' ) {
			settings.language = _twoletterlangid;
		}
		tinyMCE.init(settings);
	}

	tinyMCE.execCommand( /*'mceToggleEditor'*/ ( !adesk_editor_is(id) ? 'mceAddControl' : 'mceRemoveControl' ), false, id);
}

function adesk_editor_switchtabs(inst) {
	var id = inst.editorId;

	// If they're both zero, we'll just be swapping zeros -- harmless...
	if ($(id).tabIndex == 0) {
		$(id).tabIndex = $(id + "_ifr").tabIndex;
		$(id + "_ifr").tabIndex = 0;
	} else if ($(id).tabIndex > 0) {
		$(id + "_ifr").tabIndex = $(id).tabIndex;
		$(id).tabIndex = 0;
	}
}

function adesk_editor_is(id) {
	return tinyMCE.getInstanceById(id) != null;
}

var adesk_editor_init_normal_object = {
		mode                            : "none",
		theme                           : "advanced",
		convert_urls                    : false,
		plugins                            : "safari,spellchecker,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,media,searchreplace,print,assetsmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,pagebreak,imagemanager",
		tab_focus                       : ":prev,:next",
		tabfocus_elements               : ":prev,:next",
		theme_advanced_buttons1         : "bold,italic,underline,strikethrough,separator,undo,redo,separator,cleanup,separator,bullist,numlist,link,|,insertimage",
		theme_advanced_buttons2         : "",
		theme_advanced_buttons3         : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align    : "left",
		theme_advanced_resize_horizontal   : false,
		theme_advanced_resizing            : false,
		entity_encoding					: "raw",
		gecko_spellcheck                : true,
		remove_linebreaks               : false,
		remove_instance_callback		   : "adesk_editor_switchtabs",
		init_instance_callback			   : "adesk_editor_switchtabs"


};

var adesk_editor_init_word_object = {
		mode                               : "none",
		theme                              : "advanced",
		plugins                            : "safari,spellchecker,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,media,searchreplace,print,assetsmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,pagebreak,imagemanager",
		convert_urls                       : false,
		theme_advanced_buttons1_add_before : "fullscreen, code,",
		theme_advanced_buttons1_add        : "fontselect,fontsizeselect",
		theme_advanced_buttons2_add        : "separator,forecolor,backcolor",
		theme_advanced_buttons2_add_before : "paste,pastetext,pasteword,separator,search,separator",
		theme_advanced_buttons3_add_before : "tablecontrols,separator",
		theme_advanced_buttons3_add        : "advhr,fullpage",
		theme_advanced_buttons4            : "",
		theme_advanced_disable 	           : "styleselect,help,hr,cleanup,visualaid",
		theme_advanced_toolbar_location    : "top",
		theme_advanced_toolbar_align       : "left",
		theme_advanced_statusbar_location  : "bottom",
		convert_fonts_to_spans             : false,
		font_size_style_values             : "8pt,10pt,12pt,14pt,18pt,24pt,36pt",
	    plugin_insertdate_dateFormat       : "%Y-%m-%d",
	    plugin_insertdate_timeFormat       : "%H:%M:%S",
		theme_advanced_resize_horizontal   : false,
		theme_advanced_resizing            : false,
		remove_instance_callback		   : "adesk_editor_switchtabs",
		init_instance_callback			   : "adesk_editor_switchtabs",

		entity_encoding					: "raw",
		theme_advanced_blockformats        : "p,div,address,pre,h1,h2,h3,h4,h5,h6",
		spellchecker_languages             : "+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv",
	fullpage_fontsizes : '13px,14px,15px,18pt,xx-large',
	fullpage_default_xml_pi : false,
	fullpage_default_langcode : 'en',
	fullpage_default_title : "My document title",
		gecko_spellcheck                : true,
		
		/*
		
		NEVER
			EVER
				MODIFY THE FOLLOWING CODE. (jfv)
					
		START ================================================== */
			apply_source_formatting            : true, /*true*/
			remove_linebreaks               : false,  /*false*/
			element_format : "html",  /*html*/
			force_hex_style_colors : false,  /*false*/
			inline_styles : true,  /*true*/
			preformatted : true, /*true*/
			verify_css_classes : false,  /*false*/
			verify_html : false /*false*/
		/* ================================================== END */
};

var adesk_editor_init_mid_object = {
		mode                               : "none",
		theme                              : "advanced",
		plugins                            : "safari,spellchecker,advimage,advlink,emotions,iespell,inlinepopups,assetsmenu,imagemanager,tabfocus",
		tab_focus                          : ":prev,:next",
		tabfocus_elements                  : ":prev,:next",
		convert_urls                       : false,
		theme_advanced_buttons1            : "fontselect,fontsizeselect,forecolor,backcolor,bold,italic,underline,strikethrough,removeformat,separator,undo,redo,separator,bullist,numlist,separator,outdent,indent,separator,link,insertimage",
		theme_advanced_buttons2            : "",

		theme_advanced_buttons3            : "",
		theme_advanced_toolbar_location    : "top",
		theme_advanced_toolbar_align       : "left",
		convert_fonts_to_spans             : true,

		theme_advanced_font_sizes: "10px,12px,13px,14px,16px,18px,20px",
		font_size_style_values : "10px,12px,13px,14px,16px,18px,20px",
		remove_instance_callback		   : "adesk_editor_switchtabs",
		init_instance_callback			   : "adesk_editor_switchtabs",

	    plugin_insertdate_dateFormat       : "%Y-%m-%d",
	    plugin_insertdate_timeFormat       : "%H:%M:%S",

		content_css 					   : "/awebdesk/editor_tiny/themes/advanced/skins/default/defaultcontent.css",
		theme_advanced_resize_horizontal   : false,
		theme_advanced_resizing            : false,
		apply_source_formatting            : false,
		cleanup                            : false,
		theme_advanced_blockformats        : "p,div,address,pre,h1,h2,h3,h4,h5,h6",
		spellchecker_languages             : "+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv",
		entity_encoding					: "raw",
		gecko_spellcheck                : true,
		remove_linebreaks               : false
};

function adesk_editor_init_normal() {
	tinyMCE.init(adesk_editor_init_normal_object);
}

function adesk_editor_init_word() {
	tinyMCE.init(adesk_editor_init_word_object);
}

function adesk_editor_init_mid() {
	tinyMCE.init(adesk_editor_init_mid_object);
}

function adesk_editor_resize(editor) {
    // Have this function executed via TinyMCE's init_instance_callback option!
    // requires TinyMCE3.x
    var container = editor.contentAreaContainer, /* new in TinyMCE3.x -
        for TinyMCE2.x you need to retrieve the element differently! */
        formObj = document.forms[0], // this might need some adaptation to your site
        dimensions = {
            x: 0,
            y: 0,
            maxX: 0,
            maxY: 0
        }, doc, docFrame;

    dimensions.x = formObj.offsetLeft; // get left space in front of editor
    dimensions.y = formObj.offsetTop; // get top space in front of editor

    dimensions.x += formObj.offsetWidth; // add horizontal space used by editor
    dimensions.y += formObj.offsetHeight; // add vertical space used by editor

    // get available width and height
    if (window.innerHeight) {
        dimensions.maxX = window.innerWidth;
        dimensions.maxY = window.innerHeight;
    } else {
		// check if IE for CSS1 compatible mode
        doc = (document.compatMode && document.compatMode == "CSS1Compat")
            ? document.documentElement
            : document.body || null;
        dimensions.maxX = doc.offsetWidth - 4;
        dimensions.maxY = doc.offsetHeight - 4;
    }

    // extend container by the difference between available width/height and used width/height
    docFrame = container.children [0] // doesn't seem right : was .style.height;
    docFrame.style.width = container.style.width = (container.offsetWidth + dimensions.maxX - dimensions.x - 2) + "px";
    docFrame.style.height = container.style.height = (container.offsetHeight + dimensions.maxY - dimensions.y - 2) + "px";
}


function adesk_editor_adjust_height(editorID) {
	var frame, doc, docHeight, frameHeight;

	frame = document.getElementById(editorID+"_ifr");
	if ( frame != null ) {
		//get the document object
		if (frame.contentDocument) {
			doc = frame.contentDocument;
		} else if (frame.contentWindow) {
			doc = frame.contentWindow.document;
		} else if (frame.document) {
			doc = frame.document;
		}

		if ( doc == null )
			return;

		//prevent the scrollbar from showing
		doc.body.style.overflow = "hidden";

		docHeight;
		frameHeight = parseInt(frame.style.height);

		//Firefox
		if ( doc.height ) { docHeight = doc.height; }
		//MSIE
		else { docHeight = parseInt(doc.body.scrollHeight); }

		//MAKE BIGGER
		if ( docHeight > frameHeight-20 ) { frame.style.height = (docHeight+20) + "px"; }
		//MAKE SMALLER
		else if ( docHeight < frameHeight-20 ) { frame.style.height = Math.max((docHeight+20), 100) + "px"; }

		//only repeat while editor is visible
		setTimeout("adesk_editor_adjust_height('" + editorID + "')", 1);
	}
}

var adesk_editor_mime_state = {};

function new_adesk_editor_mime_prompt(prfx, val) {
	// if setting changed
	if (typeof adesk_editor_mime_state[prfx] == "undefined")
		adesk_editor_mime_state[prfx] = "text";

	if ( val != adesk_editor_mime_state[prfx] ) {
		// it was text, and had no text -> stop
		if ( adesk_editor_mime_state[prfx] == 'text' && $(prfx + 'textField').value == '' ) {
			new_adesk_editor_mime_switch(prfx, val);
			return false;
		}
		// it was html, and had no text -> stop
		if ( adesk_editor_mime_state[prfx] == 'html' && adesk_str_trim(strip_tags(adesk_form_value_get($(prfx + 'Editor')))) == '' ) {
			new_adesk_editor_mime_switch(prfx, val);
			return false;
		}
		// ask to confirm change
		if ( confirm(editorConfirmSwitch) ) {
			if ( adesk_editor_mime_state[prfx] == 'text' ) {
				// it was text, copy it into HTML, add BRs
				adesk_form_value_set($(prfx + 'Editor'), nl2br($(prfx + 'textField').value));
			} else if ( adesk_editor_mime_state[prfx] == 'html' ) {
				// it was HTML, copy it as text, strip tags
				var html = adesk_form_value_get($(prfx + 'Editor'));
				html = html.replace(/<title>[^<]+<\/title>/, "");
				$(prfx + 'textField').value = adesk_str_trim(strip_tags(html));
			} else if ( adesk_editor_mime_state[prfx] == 'mime' ) {
				// nothing here?
			}
		//} else {
			// why would we get out here? just "convert" was declined, not the switch!
			//return false;
		}
		// remove "convert html" from text box if text-only
		var rel = $(prfx + '_conv_html2text');
		if ( rel ) {
			adesk_dom_hideif($(rel), val == 'text');
		}
		// do the actual change of editors now
		new_adesk_editor_mime_switch(prfx, val);
	}
	return false;
}

function new_adesk_editor_mime_switch(prfx, val) {
	adesk_dom_hideif($(prfx + 'text'), !val || val == 'html');
	adesk_dom_hideif($(prfx + 'html'), !val || val == 'text');

	adesk_editor_mime_state[prfx] = val;
}


function new_adesk_editor_mime_toggle(prfx, show) {
	var type = $(prfx + 'formatField').value;
	adesk_dom_hideif($(prfx + 'table'), !show);
	if ( $(prfx + 'attachments') ) {
		adesk_dom_hideif($(prfx + 'attachments'), !show);
	}
	new_adesk_editor_mime_switch(prfx, ( show ? type : false ));
}

function adesk_editor_mime_prompt(prfx, val) {
	// if setting changed
	if (typeof adesk_editor_mime_state[prfx] == "undefined")
		adesk_editor_mime_state[prfx] = "text";

	if ( val != adesk_editor_mime_state[prfx] ) {
		// it was text, and had no text -> stop
		if ( adesk_editor_mime_state[prfx] == 'text' && $(prfx + 'textField').value == '' ) {
			adesk_editor_mime_switch(prfx, val);
			return false;
		}
		// it was html, and had no text -> stop
		if ( adesk_editor_mime_state[prfx] == 'html' && adesk_str_trim(strip_tags(adesk_form_value_get($(prfx + 'Editor')))) == '' ) {
			adesk_editor_mime_switch(prfx, val);
			return false;
		}
		// ask to confirm change
		if ( confirm(editorConfirmSwitch) ) {
			if ( adesk_editor_mime_state[prfx] == 'text' ) {
				// it was text, copy it into HTML, add BRs
				adesk_form_value_set($(prfx + 'Editor'), nl2br($(prfx + 'textField').value));
			} else if ( adesk_editor_mime_state[prfx] == 'html' ) {
				// it was HTML, copy it as text, strip tags
				var html = adesk_form_value_get($(prfx + 'Editor'));
				html = html.replace(/<title>[^<]+<\/title>/, "");
				$(prfx + 'textField').value = adesk_str_trim(strip_tags(html));
			} else if ( adesk_editor_mime_state[prfx] == 'mime' ) {
				// nothing here?
			}
		} else {
			return false;
		}
		// remove "convert html" from text box if text-only
		var rel = $(prfx + '_conv_html2text');
		if ( rel ) {
			rel.className = ( val == 'text' ? 'adesk_hidden' : 'adesk_inline' );
		}
		// do the actual change of editors now
		adesk_editor_mime_switch(prfx, val);
	}
	return false;
}

function adesk_editor_mime_switch(prfx, val) {
	$(prfx + 'text').className = ( !val || val == 'html' ? 'adesk_hidden' : 'adesk_block' );
	$(prfx + 'html').className = ( !val || val == 'text' ? 'adesk_hidden' : 'adesk_block' );

	adesk_editor_mime_state[prfx] = val;
}


function adesk_editor_mime_toggle(prfx, show) {
	var type = $(prfx + 'formatField').value;
	$(prfx + 'table').className = ( !show ? 'adesk_hidden' : 'adesk_table_rowgroup' );
	if ( $(prfx + 'attachments') ) {
		$(prfx + 'attachments').className = ( !show ? 'adesk_hidden' : 'adesk_block' );
	}
	adesk_editor_mime_switch(prfx, ( show ? type : false ));
}


// uses variable ACCustomFieldsResult
function adesk_editor_personalize_render(c, m) {
	//ACCustomFieldsResult
	var sub;
	m.add({
		title : 'Some item 1',
		onclick : function() {
			tinyMCE.activeEditor.execCommand('mceInsertContent', false, 'Some item 1');
		}
	});
	m.add({
		title : 'Some item 2',
		onclick : function() {
			tinyMCE.activeEditor.execCommand('mceInsertContent', false, 'Some item 2');
		}
	});
	//m.add({title : 'Some title', 'class' : 'mceMenuItemTitle'}).setDisabled(1);
	sub = m.addMenu({
		title : 'Some item 3'
	});
	sub.add({
		title : 'Some item 3.1',
		onclick : function() {
			tinyMCE.activeEditor.execCommand('mceInsertContent', false, 'Some item 3.1');
		}
	});
	sub.add({
		title : 'Some item 3.2',
		onclick : function() {
			tinyMCE.activeEditor.execCommand('mceInsertContent', false, 'Some item 3.2');
		}
	});
}

var editorTemplates = [];
function adesk_editor_template_render(c, m) {
	var sub;
	var tpl = { html: [], text: [] };
	var globals = { html: 0, text: 0 };
	for ( var i in editorTemplates ) {
		var t = editorTemplates[i];
		if ( typeof t != 'function' ) {
if ( typeof t.content == 'undefined' ) continue;
			if ( typeof t.global == 'undefined' ) {
				if ( typeof t.is_global != 'undefined' ) {
					t.global = t.is_global;
				} else {
					t.global = 0;
				}
			}
			if ( t.global == 1 ) {
				globals[t.format]++;
			}
			tpl[t.format].push(t);
		}
	}
	if ( tpl.html.length > 0 ) {
		//m.add({title : strPersSubscriberTags, 'class' : 'mceMenuItemTitle'}).setDisabled(1);
		if ( globals.html > 0 ) sub1 = m.addMenu({ title : strPersGlobalTemplates });
		if ( tpl.html.length != globals.html ) sub2 = m.addMenu({ title : strPersListTemplates });
		for ( var i = 0; i < tpl.html.length; i++ ) {
			if ( tpl.html[i].global == 1 ) {
				var html = tpl.html[i].content;
				html = html.replace(/<title>[^<]+<\/title>/, "");
				sub1.add({
					title : tpl.html[i].name,
					onclick : function(val) {
						return function() {
							tinyMCE.activeEditor.execCommand('mceInsertContent', false, val);
						}
					}(html)
				});
			} else {
				var html = tpl.html[i].content;
				html = html.replace(/<title>[^<]+<\/title>/, "");
				sub2.add({
					title : tpl.html[i].name,
					onclick : function(val) {
						return function() {
							tinyMCE.activeEditor.execCommand('mceInsertContent', false, val);
						}
					}(html)
				});
			}
		}
	} else {
		alert('There are no templates in the system.');
	}
}

function adesk_editor_deskrss_click() {
	alert('clicked!');
}

function adesk_editor_conditional_click() {
	alert('clicked!');
}

function adesk_editor_insert(editorID, value) {
	if ( adesk_editor_is(editorID) ) {
		var editor = tinyMCE.get(editorID);
		/*
		try {
			editor.execCommand('mceInsertContent', false, value);
		} catch (e) {
			adesk_editor_cursor_move2end(editorID);
			editor.execCommand('mceInsertContent', false, value);
		}
		*/
		editor.execCommand('mceInsertContent', false, value);
	} else {
		adesk_form_insert_cursor($(editorID), value);
	}
}

// This is the function that moves the cursor to the end of content
function adesk_editor_cursor_move2end(editorID) {
    inst = tinyMCE.getInstanceById(editorID);
    tinyMCE.execInstanceCommand(editorID, "selectall", false, null);
    if (tinyMCE.isMSIE) {
        rng = inst.getRng();
        rng.collapse(false);
        rng.select();
    } else {
        sel = inst.getSel();
        sel.collapseToEnd();
    }
}

function adesk_editor_syntaxhighlighter(obj, menuvar) {
	if ( !obj.plugins ) return obj;
	if ( obj.plugins.match('codehighlighting') ) return obj;
	if ( !menuvar ) menuvar = 'theme_advanced_buttons3_add_before';
	obj.plugins += ",codehighlighting";
	obj[menuvar] += ",separator,codehighlighting";
	obj.extended_valid_elements = "textarea[name|class|cols|rows]";
    obj.remove_linebreaks = false;
	return obj;
}
// form.js

function adesk_form_post_alt(id) {
	// Use prototype's serialize method.
	return $($(id).getElementsByTagName("form")[0]).serialize(true);
}

// u push an id of a holder object (doesn't have to be a form), and it returns an object with all form elements
function adesk_form_post(id) {
	var ary = { };
    var form = $(id);
    /*
    	INPUTS
    */
    var elements = form.getElementsByTagName('input');
    for ( var i = 0; i < elements.length; i++ ) {
    	var el = elements[i];
    	if ( !adesk_form_input_ok(el) ) continue;
		var name = el.name;
    	var isArray = ( name.indexOf('[') > 0 && name.indexOf(']') > 0 && name.indexOf('[') < name.indexOf(']') );
    	if ( isArray ) {
    		var autoInc = name.indexOf('[]') > 0;
			if (autoInc)
				name = name.substr(0, name.indexOf('[]'));
			else
				name = name.substr(0, name.indexOf('['));
    		if ( typeof(ary[name]) == 'undefined' ) {
    			ary[name] = ( autoInc ? [ ] : { } );
    		}
			var aryIndex = ( autoInc ? ary[name].length : el.name.match(/\[(.*)\]/)[1] );
			ary[name][aryIndex] = adesk_form_value_get(el);
    	} else {
			ary[name] = adesk_form_value_get(el);
    	}
    }
    /*
    	TEXT AREAS
    */
    var elements = form.getElementsByTagName('textarea');
    for ( var i = 0; i < elements.length; i++ ) {
    	var el = elements[i];
		if ( !el.name ) continue;
		var name = el.name;
    	var isArray = ( name.indexOf('[') > 0 && name.indexOf(']') > 0 && name.indexOf('[') < name.indexOf(']') );
    	if ( isArray ) {
    		var autoInc = name.indexOf('[]') > 0;
    		name = name.substr(0, name.indexOf('['));
    		if ( typeof(ary[name]) == 'undefined' ) {
    			ary[name] = ( autoInc ? [ ] : { } );
    		}
			var aryIndex = ( autoInc ? ary[name].length : el.name.match(/\[(.*)\]/)[1] );
			ary[name][aryIndex] = adesk_form_value_get(el);
    	} else {
			ary[name] = adesk_form_value_get(el);
    	}
    }
    /*
    	SELECTS
    */
    var elements = form.getElementsByTagName('select');
    for ( var i = 0; i < elements.length; i++ ) {
    	var el = elements[i];
		if ( !el.name ) continue;
		var name = el.name;
   		var isArray = ( name.indexOf('[') > 0 && name.indexOf(']') > 0 && name.indexOf('[') /*+ 1*/ < name.indexOf(']') );
    	if ( isArray ) {
	    	//if ( elements[i].selectedIndex == -1 ) continue;
    		var autoInc = name.indexOf('[]') > 0;
    		name = name.substr(0, name.indexOf('['));
    		if ( typeof(ary[name]) == 'undefined' ) {
    			ary[name] = ( autoInc ? [ ] : { } );
    		}
			var aryIndex = ( autoInc ? ary[name].length : el.name.match(/\[(.*)\]/)[1] );
			ary[name][aryIndex] = adesk_form_select_extract(el);
    	} else {
			ary[name] = adesk_form_select_extract(el);
    	}
    }
    // that's all form elements
    return ary;
}

// if 'input' tag would be posted
function adesk_form_input_ok(el) {
	if ( !el.name ) return false;
	if ( el.type == 'button' ) return false;
	if ( el.type == 'reset' ) return false;
	if ( el.type == 'image' ) return false;
	if ( el.type == 'file' ) return false;
	if ( el.type == 'radio' && !el.checked ) return false;
	if ( el.type == 'checkbox' && !el.checked ) return false;
	return true;
}

// extract a value of 'select' tag
// multiple returns array, regular just a string value
function adesk_form_select_extract(el) {
	var multi = ( typeof(el.multiple) != 'undefined' && el.multiple );
	if ( !multi ) return el.value;
	var ary = [ ];
    var options = el.getElementsByTagName('option');
    for ( var i = 0; i < options.length; i++ ) {
    	if ( options[i].selected ) ary.push(options[i].value);
    }
	return ary;
}

// we need this to support tiny mce editor
// always use this function to get value of textarea/input/div/etc
// if there's a chance that tiny will be used
function adesk_form_value_get(el) {
	if ( !el.id ) return el.value;
	if ( typeof tinyMCE == 'undefined') return el.value;
	if ( !adesk_editor_is(el.id) ) return el.value;
	var editor = tinyMCE.get(el.id);
	return editor.getContent();
}

// we need this to support tiny mce editor
// always use this function to set value of textarea/input/div/etc
// if there's a chance that tiny will be used
function adesk_form_value_set(el, val) {
	if ( !val ) val = '';
	el.value = val;

	if ( !el.id ) {
		return;
	}
	if ( !tinyMCE ) {
		return;
	}
	if ( !adesk_editor_is(el.id) ) {
		return;
	}
	var editor = tinyMCE.get(el.id);
	editor.setContent(val);
}




/*
	CHECK ALL FUNCTIONALITIES
	used on checkboxes for "select all" in list
	used on select-multiple for "select all" in list
*/
var selectAllSwitch = false; // supports one list per page for now, apparently


// put this on every checkbox in LIST form
// when de-checked: removes multicheck check and flag [and hides Xpage holder if provided]
// first is THIS, second is select all checkbox object, and third is cross-page holder obj if paginated list
function adesk_form_check_selection_none(thisCheckbox, allChecker, xPageHolder) {
	if ( !thisCheckbox.checked && allChecker.checked ) {
		allChecker.checked = false;
		selectAllSwitch = false;
		if ( xPageHolder ) xPageHolder.className = 'adesk_hidden';
	}
}


// simple "check all X checkboxes" function
// the value of this checkbox holds a name of checkbox group
// it's container form is searched
//  == used only for non paginated lists ==
// ( paginated === cross-page support )
function adesk_form_check_all(allChecker) {
	var arr = allChecker.form.elements[allChecker.value];
	if ( arr !== undefined ) {
		var val = allChecker.checked;
		var lng = arr.length;
		if ( lng !== undefined ) {
			for ( var i = 0; i < lng; i++ ) {
				if ( !arr[i].disabled )
					arr[i].checked = val;
				else
					arr[i].checked = false;
			}
		} else {
			if ( !arr.disabled )
				arr.checked = val;
		}
	}
}


// put this on your 'check all' checkbox of LIST form
// first value is THIS, and second is a box of cross-page bar if exists (use $('id') here)
//  == used only for PAGINATED lists ==
//  == works only with 1 paginator on page (4now)! ==
function adesk_form_check_selection_all(allChecker, xPageHolder) {
	// first (de)select all checkboxes
	adesk_form_check_all(allChecker); // run simple check-all first
	// get sub-element references
	var spans = xPageHolder.getElementsByTagName('span');
	var hrefs = xPageHolder.getElementsByTagName('a');
	if ( spans.length != 3 || hrefs.length != 1 ) return;
	// see if "select cross page all" is needed
	var xpage = ( allChecker.checked && paginators[1].linksCnt > 1 );
	// now start switching
	xPageHolder.className = ( xpage ? 'adesk_inline' : 'adesk_hidden' );
	if ( !allChecker.checked || paginators[1].linksCnt == 1 ) selectAllSwitch = false;
	/*selectXPageAllAll*/
	spans[0].className = ( selectAllSwitch ? 'adesk_inline' : 'adesk_hidden' );
	/*selectXPageAllPage*/
	spans[1].className = ( !selectAllSwitch ? 'adesk_inline' : 'adesk_hidden' );
	/*selectXPageAllLink*/
	hrefs[0].className = ( !selectAllSwitch ? 'adesk_inline' : 'adesk_hidden' );
	/*selectXPageAllCount*/
	spans[2].innerHTML = paginators[1].total;
}

// this function sets "select all cross-page" flag
function adesk_form_check_selection_xpage(xPageHolder) {
	selectAllSwitch = true;
	// get sub-element references
	var spans = xPageHolder.getElementsByTagName('span');
	var hrefs = xPageHolder.getElementsByTagName('a');
	if ( spans.length != 3 || hrefs.length != 1 ) return;
	spans[0].className = 'adesk_inline';
	spans[1].className = 'adesk_hidden';
	hrefs[0].className = 'adesk_hidden';
	return false;
}




// check if anything is selected
// used on LIST form submission checks
// screams if button is pressed and no items are listed or if nothing is selected
// has option to let thru (to act as SELECT ALL!!!) if last param is string message and not null
function adesk_form_check_selection_check(daddy, fieldName, jsNothingSelected, jsNothingFound, jsNothingSelectedButContinue) {
	// check if anything is selected
	var inputs = daddy.getElementsByTagName('input');
	var checked = 0;
	var total = 0;
	for ( var i = 0; i < inputs.length; i++ ) {
		if ( inputs[i].type == 'checkbox' && inputs[i].name == fieldName ) {
			total++;
			if ( inputs[i].checked ) {
				checked++;
			}
		}
	}
	// check if anything is found
	if ( total == 0 ) {
		if ( jsNothingSelectedButContinue ) {
			return confirm(jsNothingSelectedButContinue);
		} else {
			alert(jsNothingFound);
			return false;
		}
	}
	// check if anything is selected
	if ( checked == 0 ) {
		if ( total != 0 && jsNothingSelectedButContinue ) {
			return confirm(jsNothingSelectedButContinue);
		} else {
			alert(jsNothingSelected);
			return false;
		}
		return false;
	}
	return true;
}

// gets an array of checked ids in LIST form
function adesk_form_check_selection_get(daddy, fieldName) {
	// check if anything is selected
	var selection = [ ];
	var inputs = daddy.getElementsByTagName('input');
	for ( var i = 0; i < inputs.length; i++ ) {
		if ( inputs[i].type == 'checkbox' && inputs[i].name == fieldName ) {
			if ( inputs[i].value && inputs[i].checked ) {
				if ( inputs[i].value > 0 )
					selection.push(inputs[i].value);
			}
		}
	}
	return selection;
}

function adesk_form_check_selection_set(daddy, fieldName, arr) {
	var inputs = daddy.getElementsByTagName('input');
	for ( var i = 0; i < inputs.length; i++ ) {
		if ( inputs[i].type == 'checkbox' && inputs[i].name == fieldName ) {
			if ( inputs[i].value ) {
				inputs[i].checked = adesk_array_has(arr, inputs[i].value);
			}
		}
	}
	return false;
}

// checks ALL checkboxes within an element
// (convenient for "select all permissions for this group" types)
function adesk_form_check_selection_element_all(objId, boolChecked) {
	var rel = $(objId);
	var inputs = rel.getElementsByTagName('input');
	for ( var i = 0; i < inputs.length; i++ ) {
		if ( inputs[i].type == 'checkbox' ) {
			inputs[i].checked = boolChecked;
		}
	}
	return false;
}

// checks ALL checkboxes within an element WITH A GIVEN NAME
// (convenient for "select all permissions for this group" types)
function adesk_form_check_selection_element_byname(objId, objName, boolChecked) {
	var rel = $(objId);
	var inputs = rel.getElementsByTagName('input');
	for ( var i = 0; i < inputs.length; i++ ) {
		if ( inputs[i].type == 'checkbox' && inputs[i].name && inputs[i].name == objName ) {
			inputs[i].checked = boolChecked;
		}
	}
	return false;
}



function adesk_form_select_multiple_all(obj, firstIsSelectAll) {
	// first parameter defines whether we should select just a first one ("select all" case)
	var el = obj.getElementsByTagName('option');
	// first is always selected
	if ( el.length > 0 ) {
		if ( !el[0].disabled ) el[0].selected = true;
		for ( var i = 1; i < el.length; i++ ) {
			if ( !el[i].disabled ) el[i].selected = !firstIsSelectAll;
		}
	}
	return false;
}

function adesk_form_select_multiple_none(obj) {
	// short way
	obj.selectedIndex = -1;
	return false;
	// long way
	var el = obj.getElementsByTagName('option');
	for ( var i = 0; i < el.length; i++ ) {
		el[i].selected = false;
	}
	return false;
}

function adesk_form_select_multiple(obj, arr) {
	var options = obj.getElementsByTagName('option');
	for ( var i = 0; i < options.length; i++ ) {
		var o = options[i];
		o.selected = adesk_array_has(arr, o.value);
	}
	return false;
}



// simple "field filled check" function. u pass what messages are displayed
function adesk_form_text_value_check(field, defaultValue, message1, message2) {
	// check for input
	if ( $(field).value == '' ) {
		var newTitle = ( message2 == null ? null : prompt(message2, defaultValue) );
		if ( newTitle == '' || !newTitle ) {
			alert(message1);
			$(field).focus();
			return false;
		}
		$(field).value = newTitle;
	}
	return true;
}

function adesk_form_upload_start(uploader) {
	// what else needs to be done here?
	//...
	// submit the form (upload the file)
	uploader.form.submit();
	return true;
}

function adesk_form_upload_stop(upload_id, upload_name, upload_file, upload_limit) {
	if ( upload_file.succeeded ) {
		// update parent page
		adesk_form_upload_set(upload_id, upload_name, upload_file, upload_limit);
		window.parent.somethingChanged = true;
		window.parent.adesk_result_show(upload_file.message);
	} else {
		// what needs to be done here?
		window.parent.adesk_error_show(upload_file.message);
	}
}

function adesk_form_upload_set(upload_id, upload_name, upload_file, upload_limit) {
	// update parent
	var daddy = window.parent.document;
	// update visible file list
	var uploadList = daddy.getElementById(upload_id + '_list');
	uploadList.appendChild(
		window.parent.Builder.node(
			"div",
			{ id: "upload_check_holder_" + upload_file.id, className: "adesk_upload_list_item" },
			[
				window.parent.Builder.node(
					"input",
					{
						id: "upload_check_" + upload_file.id,
						name: upload_name + '[]',
						type: "checkbox",
						value: upload_file.id,
						checked: "checked",
						onchange: "adesk_form_upload_remove(this, '" + upload_file.action + "_remove');"
					}
				),
				window.parent.Builder.node('span', { className: 'filename' }, [ window.parent.Builder._text(upload_file.filename) ]),
				window.parent.Builder._text(' - '),
				window.parent.Builder.node('span', { className: 'filesize' }, [ window.parent.Builder._text(upload_file.humansize) ])
			]
		)
	);
	if ( upload_limit > 0 && uploadList.getElementsByTagName('input').length == upload_limit ) {
		var uploadForm = daddy.getElementById(upload_id + '_iframe');
		uploadForm.className = 'adesk_hidden';
	}
}

var adesk_form_upload_remove_timers = {};
function adesk_form_upload_remove(rel, action) {
	var id = rel.id;
	var val = rel.value;
	// just set a timer for 5 seconds. then we will remove it if still unchecked
	if ( rel.checked ) {
		if ( adesk_form_upload_remove_timers[id] ) window.clearTimeout(adesk_form_upload_remove_timers[id]);
	} else {
		adesk_form_upload_remove_timers[id] = window.setTimeout("adesk_form_upload_remove_call('" + id + "', '" + action + "')", 5 * 1000);
	}
	return true;
}

function adesk_form_upload_remove_call(id, action) {
	var rel = $(id);
	if ( !rel ) return;
	if ( rel.checked ) return; // stop if he checked it again
	var val = rel.value;
	// clear timer if still on
	if ( adesk_form_upload_remove_timers[id] ) window.clearTimeout(adesk_form_upload_remove_timers[id]);
	// do a call
	adesk_ui_api_call(jsDeleting);
	adesk_ajax_call_cb('awebdeskapi.php', action, adesk_form_upload_remove_real_callback, val);
}

function adesk_form_upload_remove_real_callback(xml, txt) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();
	if ( ary.succeeded && ary.succeeded == 1 ) {
		// if checkbox found, remove it
		var rel = $('upload_check_holder_' + ary.id);
		if ( rel ) {
			var reldaddy = rel.parentNode;
			reldaddy.removeChild(rel);
			// re-show the form
			var uploadForm = reldaddy.parentNode.getElementsByTagName('iframe')[0];
			if ( uploadForm.className == 'adesk_hidden' ) uploadForm.className = 'adesk_upload_frame';
		}
		window.parent.somethingChanged = true;
		adesk_result_show(ary.message);
	} else {
		adesk_error_show(ary.message);
	}
}


function adesk_form_multicheck_get(id, isChecked) {
	var props = {
		name: "multi[]",
		type: "checkbox",
		value: id,
		onchange: "adesk_form_check_selection_none(this, $('acSelectAllCheckbox'));"
	};
	if ( isChecked ) props.checked = true;
	return Builder.node('input', props);
}

function adesk_form_disable(id, flag) {
	var rel = $(id);
	if ( !rel ) return;
	// inputs
	var fields = rel.getElementsByTagName('input');
	for ( var i = 0; i < fields.length; i++ ) fields[i].disabled = flag;
	// selects
	var fields = rel.getElementsByTagName('select');
	for ( var i = 0; i < fields.length; i++ ) fields[i].disabled = flag;
	// textareas
	var fields = rel.getElementsByTagName('textarea');
	for ( var i = 0; i < fields.length; i++ ) fields[i].disabled = flag;
}

function adesk_form_highlight(field) {
       field.focus();
       field.select();
}


///////////////////////////////////////////////////////////
//insert myValue into myField that is selected           //
//uses a selection set with beginning and end            //
///////////////////////////////////////////////////////////
function adesk_form_insert_cursor(myField, myValue) {
	if ( document.selection ) {
		// IE
		myField.focus();
		sel = document.selection.createRange();
		sel.text = myValue;
	} else if ( myField.selectionStart || myField.selectionStart == '0' ) {
		// Mozilla/Netscape
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		myField.value =
			myField.value.substring(0, startPos) +
			myValue +
			myField.value.substring(endPos, myField.value.length)
		;
	} else {
		// unsuppored, append
		myField.value += myValue;
	}
}

function adesk_form_textarea_adjust(obj, incrementValue, minHeight, maxHeight) {
	//minHeight = parseInt(minHeight, 10);
	maxHeight = parseInt(maxHeight, 10);
	//if ( isNaN(minHeight) ) minHeight = incrementValue;
	if ( isNaN(maxHeight) ) maxHeight = incrementValue * 2;
	// how many columns; after how many will we break a single line
	var cols = ( obj.cols && obj.cols > 0 ? parseInt(obj.cols, 10) : 70 );
	// how many lines in this text
	var lines = obj.value.split("\n");
	// that is our basic count
	var count = lines.length;
	// if wrapping is off
	if ( !obj.wrap || obj.wrap != 'off' ) {
		// break lines by columns and add the number of wrapped ones
		for ( var i = 0; i < lines.length; i++ ) {
			var line = lines[i];
			count += Math.ceil(line.length / cols) - 1; // we counted one already
		}
	}
	// get the number of rows allowed in one incrementValue
	var rows = Math.ceil(incrementValue / 20);
	if ( count > rows ) {
		// if more lines in text than lines per increment
		var height = Math.ceil(count / rows) * incrementValue;
		if ( maxHeight != 0 && height > maxHeight ) height = maxHeight;
		obj.style.height = height + 'px';
	} else {
		// if less lines in text than lines per increment
		// set minimal (1 incrementValue)
		obj.style.height = incrementValue + 'px';
	}
}
var ACTable = Class.create();

ACTable.prototype = {
initialize:
	function() {
		this.cols = [];
		this.selection = [];
	},

setcol:
	function(i, cb) {
		if ( !cb && typeof(i) == 'function' ) cb = i;
		if ( isNaN(parseInt(i)) ) i = this.cols.length;
		this.cols[i] = cb;
	},

addcol:
	function(cb) {
		this.cols.push(cb);
	},

	// Unset column index.  Use this function if there is a case that you would
	// want to omit a certain column from being displayed.

unsetcol:
	function(index) {
		if (typeof this.cols[index] != "undefined")
			this.cols.splice(index, 1);
	},

newrow:
	function(row) {
		var tds = [];
		var td  = null;
		var sub = null;

		for (var i = 0; i < this.cols.length; i++) {
			td = Builder.node("td");

			sub = this.cols[i](row, td);
			if ( typeof sub == "string" || typeof sub == "number" )
				sub = Builder._text(sub);

			td.appendChild(sub);
			tds.push(td);
		}

		return Builder.node("tr", { className: "adesk_table_row" }, tds);
	},

reuserow:
	function(row, tr) {
		var sub = null;
		var tds = tr.getElementsByTagName("td");
		for (var i = 0; i < this.cols.length; i++) {
			// Can't set any columns if they don't exist -- although maybe
			// in this case we should add them.
			if (i >= tds.length)
				break;

			adesk_dom_remove_children(tds[i]);

			sub = this.cols[i](row, tds[i]);
			if ( typeof sub == "string" || typeof sub == "number" )
				sub = Builder._text(sub);

			tds[i].appendChild(sub);
		}

		// Just to be safe, make sure the row is visible
		row.className = "adesk_table_row";
		return row;
	}
};
// ihook.js

var adesk_ihook_table = { };

function adesk_ihook_define(key, func) {
	if ( !adesk_ihook_exists(key) ) {
		adesk_ihook_table[key] = [ ];
	}
	adesk_ihook_table[key].push(func);
}

function adesk_ihook_undefine(key, func) {
	if ( adesk_ihook_exists(key) ) {
		for ( var i = 0; adesk_ihook_table[key].length; i++ ) {
			if ( adesk_ihook_table[key][i] == func ) {
				adesk_ihook_table[key].splice(i, 1);
				return;
			}
		}
	}
}

function adesk_ihook_exists(key) {
	return typeof adesk_ihook_table[key] != 'undefined';
}

function adesk_ihook(key, param) {
	var r = null;
	if ( adesk_ihook_exists(key) ) {
		for ( var i in adesk_ihook_table[key] ) {
			if ( !isNaN(parseInt(i)) ) {
	    		var func = adesk_ihook_table[key][i];
	    		if ( typeof func == 'function' ) {
		            r = func(param);
	    		}
			}
		}
	}
	return r;
}
