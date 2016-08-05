var form_view_str_cant_insert = '{"You do not have permission to add Subscription Form"|alang|js}';
var form_view_str_cant_update = '{"You do not have permission to edit Subscription Form"|alang|js}';
var form_view_str_cant_find   = '{"Subscription Form not found."|alang|js}';
var form_view_str_email       = '{"E-mail:"|alang|js}';
var form_view_str_fname       = '{"First Name:"|alang|js}';
var form_view_str_lname       = '{"Last Name:"|alang|js}';
var form_view_str_captcha     = '{"Enter the text as it appears on the image:"|alang|js}';
var form_view_str_lists       = '{"Select Lists:"|alang|js}';
var form_view_str_sub         = '{"Subscribe"|alang|js}';
var form_view_str_unsub       = '{"Unsubscribe"|alang|js}';
var form_view_str_link        = '{"Click Here To Subscribe"|alang|js}';

var form_view_charset         = _charset;

{literal}
var form_view_type = 'html';
var form_view_id = 0;
var form_view_obj = null;

function form_view_edit(id) {
	window.location = "desk.php?action=form#form-" + id;
}

function form_view_load(id, type) {
	form_view_type = type;
	form_view_id = id;

	if (id == 0) {
		adesk_ui_anchor_set(form_list_anchor());
		alert(form_view_str_cant_find);
		return;
	}
	adesk_ui_api_call(jsLoading);
	adesk_ajax_call_cb("awebdeskapi.php", "form.form_select_row", form_view_load_cb, id, form_view_type);
}

function form_view_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(form_view_str_cant_find);
		adesk_ui_anchor_set(form_list_anchor());
		return;
	}
	form_view_id = ary.id;

	if (form_form_id == 1000) {
		$("formview_default").className = "";
		$("formview_integration").className = "adesk_hidden";
		$("formview_code").className = "adesk_hidden";
	}
	else {
		$("formview_default").className = "adesk_hidden";
		$("formview_integration").className = "";
		$("formview_code").className = "";

		form_view_obj = ary;
		$('charsetField').value = ary.charset;

		if ($('charsetField').value == "")
			$('charsetField').value = _charset;

		form_view_generate(ary, form_view_type);
	}

	$("view").className = "adesk_block";
}

function form_view_switch(type) {
	$('form_select_html').className = ( type == 'html' ? 'selected' : '' );
	$('form_select_link').className = ( type == 'link' ? 'selected' : '' );
	$('form_select_popup').className = ( type == 'popup' ? 'selected' : '' );
	$('form_select_xml').className = ( type == 'xml' ? 'selected' : '' );
	$('integration_details_html').className = ( type == 'html' ? 'integration_details' : 'adesk_hidden' );
	$('integration_details_link').className = ( type == 'link' ? 'integration_details' : 'adesk_hidden' );
	$('integration_details_popup').className = ( type == 'popup' ? 'integration_details' : 'adesk_hidden' );
	$('integration_details_xml').className = ( type == 'xml' ? 'integration_details' : 'adesk_hidden' );
	form_view_generate(form_view_obj, type);
	return false;
}

function form_view_regenerate() {
	adesk_ajax_call_cb("awebdeskapi.php", "form.form_update_charset", adesk_ajax_cb(form_view_update_charset_cb), form_view_id, $('charsetField').value);
	form_view_generate(form_view_obj, form_view_type);
	return false;
}

function form_view_update_charset_cb(ary) {
}

function form_view_generate(arr, type) {
	if ( $('charsetField').value != form_view_charset ) {
		arr.html = arr.html.replace(
			/<input type="hidden" name="_charset" value="[^"]*" \/>/,
			'<input type="hidden" name="_charset" value="' + $('charsetField').value + '" />'
		);
		arr.htmllink += '&_charset=' + $('charsetField').value;
		form_view_charset = $('charsetField').value;
	}
	var code = $('codeBox');
	//var preview = $('previewBox');
	var preview = $('previewLink');
	if ( type == 'link' ) {
		// show it in textarea
		code.value = '<a href="' + arr.htmllink + '">' + form_view_str_link + '</a>';
		// show it in preview
		preview.href = arr.htmllink;
		preview.target = '_blank';
		preview.onclick = function() { return true; };
	} else if ( type == 'popup' ) {
		// show it in textarea
		code.value =
			'<scr' + 'ipt>\n' +
			'<!--\n\n' +
			'var subscriptionform' + arr.id + ' = window.open(\n\t' +
			'"' + arr.htmllink + '",\n\t' +
			'"subscription_form_popup",\n\t' +
			'"toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=779,height=700,left=0,top=0"\n' +
			');\n\n' +
			//'var subscriptionform' + arr.id + ' = window.open("' + arr.htmllink + '", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=779,height=700,left=0,top=0");\n' +
			'-->\n' +
			'</scr' + 'ipt>\n';
			//code.value = '<scr ipt><!-- var subscriptionform' + arr.id + ' = window.open("' + arr.htmllink + '", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=779,height=700,left=0,top=0"); --></scr ipt>';
		// show it in preview
		preview.href = arr.htmllink;
		preview.target = '';
		preview.onclick = function() {
			var subscriptionformPopup = window.open(
				arr.htmllink,
				'subscription_form_popup',
				'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=779,height=700,left=0,top=0'
			);
			return false;
		};
	} else if ( type == 'xml' ) {
		// show it in textarea
		code.value = arr.xml;
		// show it in preview
		preview.href = arr.xmllink;
		preview.target = '_blank';
		preview.onclick = function() { return true; };
		alert('waiting for flash forms?');
	} else if ( type == 'html' ) {
		// show it in textarea
		code.value = arr.html;
		// show it in preview
		preview.href = arr.htmllink;
		preview.target = '_blank';
		preview.onclick = function() { return true; };
	}
	// show it in textarea
	//code.value = ( type == 'xml' ? arr.xml : arr.html );
	// show it in preview
	//adesk_dom_remove_children(preview);
	//preview.appendChild(Builder.node('div', { onclick: 'adesk_form_highlight(this);' }, [ Builder._text(arr.html) ]));
}


/* JS subscription form generator - unused at the moment */

function _form_view_generate(arr, type) {
	if ( type == 'html' ) {
		form_view_generate_html(arr);
	} else /*if ( type == 'xml' )*/ {
		form_view_generate_xml(arr);
	}
	//$('xmlButton').className = ( type != 'xml' ? 'adesk_inline' : 'adesk_hidden' );
	//$('htmlButton').className = ( type != 'html' ? 'adesk_inline' : 'adesk_hidden' );
}

function form_view_generate_html(arr) {
	var preview = $('previewBox');
	var code = $('codeBox');
	// do preview first (use Builder)
	adesk_dom_remove_children(preview);
	var nodes = [ ];
	// email div
	nodes.push(
		Builder.node(
			'div',
			[
				Builder._text(form_view_str_email),
				Builder.node('br'),
				Builder.node('input', { type: 'text', name: 'email', value: '' })
			]
		)
	);
	// fname div
	if ( form_view_obj.ask4fname == 1 ) {
		nodes.push(
			Builder.node(
				'div',
				[
					Builder._text(form_view_str_fname),
					Builder.node('br'),
					Builder.node('input', { type: 'text', name: 'firstname', value: '' })
				]
			)
		);
	}
	// lname div
	if ( form_view_obj.ask4lname == 1 ) {
		nodes.push(
			Builder.node(
				'div',
				[
					Builder._text(form_view_str_lname),
					Builder.node('br'),
					Builder.node('input', { type: 'text', name: 'lastname', value: '' })
				]
			)
		);
	}
	// custom fields
	for ( var i = 0; i < form_view_obj.fields.length; i++ ) {
		var f = form_view_obj.fields[i];
		var fieldnode = adesk_custom_fields_cons(f);
		if ( parseInt(f.type, 10) != 6 ) {
			nodes.push(
				Builder.node(
					'div',
					[
						Builder._text(adesk_custom_fields_title(f)),
						Builder.node('br'),
						fieldnode
					]
				)
			);
		} else {
			nodes.push(fieldnode);
		}
	}
	// captcha div
	if ( form_view_obj.captcha == 1 ) {
		nodes.push(
			Builder.node(
				'div',
				[
					Builder._text(form_view_str_captcha),
					Builder.node('br'),
					Builder.node(
						'img',
						{
							border: 1,
							vAlign: 'middle',
							src: adesk_js_site.p_link + '/awebdesk/scripts/imgrand.php'
						}
					),
					Builder.node('input', { type: 'text', name: 'captcha', value: '' })
				]
			)
		);
	}
	// list selection
	var listnodes = [
		Builder._text(form_view_str_lists),
		Builder.node('br')
	];
	for ( var i = 0; i < form_view_obj.lists.length; i++ ) {
		var l = form_view_obj.lists[i];
		if ( form_view_obj.allowselection == 1 ) {
			var props = {};
			if ( l.desc != '' ) {
				props.title = l.descript;
			}
			listnodes.push(
				Builder.node(
					'label',
					props,
					[
						Builder.node('input', { type: 'checkbox', name: 'nlbox[]', value: l.id, checked: 'checked' }),
						Builder._text(l.name),
						Builder.node('br')
					]
				)
			);
		} else {
			nodes.push(Builder.node('input', { type: 'hidden', name: 'nlbox[]', value: l.id }));
		}
	}
	if ( form_view_obj.allowselection == 1 ) {
		nodes.push(Builder.node('div', listnodes));
	}
	// action selection
	nodes.push(
		Builder.node(
			'div',
			[
				Builder.node('label', [ Builder.node('input', { type: 'radio', name: 'funcml', value: 'add', checked: 'checked' }), Builder._text(form_view_str_sub) ]),
				Builder.node('br'),
				Builder.node('label', [ Builder.node('input', { type: 'radio', name: 'funcml', value: 'unsub2', checked: 'checked' }), Builder._text(form_view_str_unsub) ])
			]
		)
	);
	// form id
	nodes.push(Builder.node('input', { type: 'hidden', name: 'p', value: form_view_obj.id }));
	// charset setting
	nodes.push(Builder.node('input', { type: 'hidden', name: '_charset', value: $('charsetField').value }));
	// submit div
	nodes.push(
		Builder.node(
			'div',
			[
				Builder.node('input', { type: 'submit', value: jsSubmit })/*,
				Builder._text(' '),
				Builder.node('input', { type: 'reset', value: jsReset })*/
			]
		)
	);
	preview.appendChild(
		Builder.node(
			'form',
			{
				method: 'post',
				action: adesk_js_site.p_link + '/surround.php'/*,
				'accept-charset': $('charsetField').value*/
			},
			nodes
		)
	);
	// copy it to code
	code.value = preview.innerHTML;
}

function form_view_generate_xml(arr) {
	// generate html
	form_view_generate_html(arr);
	// show it in preview
	var preview = $('previewBox');
	var code = $('codeBox');
	adesk_dom_remove_children(preview);
	preview.appendChild(Builder.node('div', { onclick: 'adesk_form_highlight(this);' }, [ Builder._text(code.value) ]));
}

{/literal}
