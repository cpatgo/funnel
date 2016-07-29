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
