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
*/