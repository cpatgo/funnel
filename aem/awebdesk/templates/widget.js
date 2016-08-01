// widget.js

var widget_title_str = '{"Title:"|alang|js}';
var widget_showin_str = '{"Show In:"|alang|js}';
var widget_alert_nobars_str = '{"Please select at least one bar to show this widget in."|alang|js}\n\n{"If not, you can remove this widget."|alang|js}';
var widget_confirm_nobars2delete_str = '{"You have not selected any bars to show this widget in."|alang|js}\n\n{"Do you want to remove this widget instead?"|alang|js}';

{jsvar var=$allbars name=allWidgetBars};
{jsvar var=$allwidgets name=allWidgets};
{jsvar var=$publicwidgets name=publicWidgets};
{jsvar var=$adminwidgets name=adminWidgets};
{jsvar var=$publicinstalled name=publicInstalled};
{jsvar var=$admininstalled name=adminInstalled};

{literal}

var widget_save_ihooks = {};

function widget_install_public(element, dropon, event) {
	return widget_install(element, dropon, event, 'public');
}
function widget_install_admin(element, dropon, event) {
	return widget_install(element, dropon, event, 'admin');
}
function widget_install(element, dropon, event, section) {
	var widgetid = element.id.replace(section + '-widget-', '');
	if ( widgetid == element.id ) return;

	// show loader(s)
	widget_load_show(section);
	adesk_ui_api_call(jsInstalling);

	// do ajax call
	adesk_ajax_call_cb("awebdeskapi.php", "widget!widget_install", widget_install_cb, widgetid, section);
}

function widget_install_cb(xml, txt) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	var widgets = ( ary.section == 'admin' ? adminWidgets : publicWidgets );
	widget_build(ary.id, ary.section, widgets[ary.widget]);
	widget_open(ary.id);

	widget_load_hide(ary.section);
	if (ary.succeeded && ary.succeeded == "1") {
		adesk_result_show(ary.message);
	} else {
		adesk_error_show(ary.message);
	}
}



function widget_uninstallall(section) {
	if ( !confirm(jsAreYouSure) ) return;

	// show loader(s)
	widget_load_show(section);
	adesk_ui_api_call(jsRemoving);

	// do ajax call
	adesk_ajax_call_cb("awebdeskapi.php", "widget!widget_uninstallall", widget_uninstallall_cb, section);
}

function widget_uninstallall_cb(xml, txt) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();


	var widgets = ( ary.section == 'admin' ? adminInstalled : publicInstalled );
	for ( var i in widgets ) {
		var widget = widgets[i];
		widget_destroy(widget.id, ary.section);
	}

	widget_load_hide(ary.section);
	if (ary.succeeded && ary.succeeded == "1") {
		adesk_result_show(ary.message);
	} else {
		adesk_error_show(ary.message);
	}
}


function widget_uninstall(id) {
	var widget = widget_find(id);
	if ( !widget ) return;
	if ( !confirm(jsAreYouSure) ) return;

	// show loader(s)
	widget_load_show(widget.section);
	adesk_ui_api_call(jsRemoving);

	// do ajax call
	adesk_ajax_call_cb("awebdeskapi.php", "widget!widget_uninstall", widget_uninstall_cb, id, widget.section);
}

function widget_uninstall_cb(xml, txt) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	widget_destroy(ary.id, ary.section);

	widget_load_hide(ary.section);
	if (ary.succeeded && ary.succeeded == "1") {
		adesk_result_show(ary.message);
	} else {
		adesk_error_show(ary.message);
	}
}



function widget_load_show(section) {
	if ( section != 'admin' ) section = 'public';

	$(section + '_widget_clear').style.display = "none";
	$(section + '_widget_loading').style.display = "block";
}

function widget_load_hide(section) {
	if ( section != 'admin' ) section = 'public';

	$(section + '_widget_loading').style.display = "none";
	$(section + '_widget_clear').style.display = "block";
}

function widget_build(id, section, ary) {
	var widget_props = {
		id: 'widget_' + id,
		//style: 'position: relative;',
		className: 'widget_draggable'
	};
	var widget_opener = Builder.node(
		'div',
		{ className: 'widget_opener' },
		[ Builder.node('img', { src: 'images/menu-down.gif', border: 0, onclick: sprintf('widget_open(%s);', id) }) ]
	);
	var widget_title = Builder.node(
		'div',
		{ className: 'widget_title' },
		[ Builder._text(ary.name) ]
	);
	var widget_body = Builder.node(
		'div',
		{ id: 'config_' + id, className: 'adesk_hidden' },
		[
			Builder.node(
				'div',
				{ className: 'widget_instance_title' },
				[
					Builder._text(widget_title_str),
					Builder.node('input', { id: 'title_' + id, type: 'text', 'name': 'widget_title', value: ary.name })
				]
			),
			Builder.node('br'),
			Builder.node(
				'div',
				{ id: 'options_' + id },
				[
					Builder.node('img', { id: 'loading_' + id, src: 'images/loading.gif', border: 0, alt: jsLoading })
				]
			),
			Builder.node('br'),
			Builder.node(
				'div',
				{ className: 'widget_instance_bars' },
				widget_bars(id, section, ary.widget)
			),
			Builder.node('br'),
			Builder.node(
				'div',
				{ style: 'float:right;padding-top:4px;' },
				[
					Builder.node('a', { href: '#', onclick: sprintf('widget_uninstall(%s);return false;', id) }, [ Builder._text(jsRemove) ]),
					Builder._text(' | '),
					Builder.node('a', { href: '#', onclick: sprintf('widget_open(%s);return false;', id) }, [ Builder._text(jsCancel) ])
				]
			),
			Builder.node(
				'div',
				[
					Builder.node('input', { id: 'save_' + id, type: 'button', value: jsSave, onclick: sprintf('widget_save(%s);', id) })
				]
			)
		]
	);

	var widget_subnodes = [ widget_opener, widget_title, widget_body ];
	var widget_node = Builder.node('div', widget_props, widget_subnodes);
	$(section + '_dropzone').appendChild(widget_node);

	//widget_sort_init(section);

	var tmpvar = {
		id: id,
		section: section,
		widget: ary.widget,
		title: '',
		bars: '',
		sort_order: 999
	};
	if ( section == 'admin' ) {
		adminInstalled[id] = tmpvar;
	} else {
		publicInstalled[id] = tmpvar;
	}
}

function widget_destroy(id, section) {
	var rel = $('widget_' + id);
	if ( rel ) rel.parentNode.removeChild(rel);
	if ( section == 'admin' ) {
		if ( typeof adminInstalled[id] != 'undefined' ) delete adminInstalled[id];
	} else {
		if ( typeof publicInstalled[id] != 'undefined' ) delete publicInstalled[id];
	}
}



function widget_sort_init(section) {
	Sortable.create(
		section + '_dropzone',
		{
			ghosting: true,
			tag: 'div',
			only: 'widget_draggable',
			handle: 'widget_title',
			containment: [ section + '_dropzone' ],
			dropOnEmpty: true,
			constraint: 'vertical',
			onUpdate: ( section == 'admin' ? widget_sort_admin : widget_sort_public )
		}
	);
	Droppables.add(
		section + '_dropzone',
		{
			onDrop: ( section == 'admin' ? widget_install_admin : widget_install_public ),
			containment: [ section + '_dropzone', section + '_widgets' ]
		}
	);
}

var widget_sort_previous = '';

function widget_sort_public() {
	return widget_sort('public');
}
function widget_sort_admin() {
	return widget_sort('admin');
}
function widget_sort(section) {
	sorter_ids     = "";
	sorter_orders  = "";
	var installed = $$('#' + section + '_dropzone .widget_draggable');
	for ( var i = 0; i < installed.length; i++ ) {
		var id = installed[i].id.replace('widget_', '');
		sorter_ids     += id;
		sorter_orders  += i.toString();
		if ( i < installed.length - 1 ) {
			sorter_ids     += ",";
			sorter_orders  += ",";
		}
	}
	if ( widget_sort_previous == sorter_ids ) return;
	widget_sort_previous = sorter_ids;
	adesk_ajax_call_cb("awebdeskapi.php", "widget!widget_sort", function(){}, section, sorter_ids, sorter_orders);
}

function widget_open(id) {
	var widget = widget_find(id);
	if ( !widget ) return;
	var rel = $('config_' + id);
	if ( !rel ) return;
	adesk_dom_toggle_class(rel.id, 'widget_config', 'adesk_hidden');
	if ( rel.className == 'adesk_hidden' ) return; // done, closed it

	if ( !$('loading_' + id) ) return; // custom options section loaded

	widget_options(id);
}

function widget_find(id) {
	if ( typeof publicInstalled[id] != 'undefined' ) {
		return publicInstalled[id];
	} else if ( typeof adminInstalled[id] != 'undefined' ) {
		return adminInstalled[id];
	} else {
		return null;
	}
}

function widget_options(id) {
	// do ajax call
	adesk_ajax_call_cb("awebdeskapi.php", "widget!widget_options", widget_options_cb, id);
}

function widget_options_cb(xml, txt) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded && ary.succeeded == "1") {
		var widget = widget_find(ary.id);

		var rel = $('options_' + ary.id);
		adesk_dom_remove_children(rel);

		rel.innerHTML = ary.html.replace((/<script[^>]*>[\s\S]*?<\/script>/ig), '');
		var scripts = ary.html.match(/<script[^>]*>[\s\S]*?<\/script>/ig);
		//alert(ary.html);alert(rel.innerHTML);alert(scripts);if(scripts)alert(scripts.length);
		if ( scripts ) {
			for ( var i = 0; i < scripts.length; i++ ) {
				var js = adesk_str_trim(scripts[i].replace(/<[\/]?script[^>]*>/ig, ''));
				js = js.replace(/<!--/g, '').replace(/-->/g, '');
				try {
					eval(js);
				} catch (e) {
					alert("Widget has some JavaScript that could not be loaded.");
					if ( confirm("Show Code?") ) alert(js);
					//alert(js);alert(e);
				}
				var func = null;
				try {
					var func = eval("widget_save_" + widget.widget);
				} catch (e) {
				}
				if ( typeof func == 'function' ) {
					widget_save_ihooks[widget.widget] = func;
				}
			}
		}

		//adesk_result_show(ary.message);
	} else {
		adesk_error_show(ary.message);
	}
}

function widget_save(id) {
	var post = adesk_form_post($('config_' + id));
	post.id = id;

	var widget = widget_find(id);

	if ( typeof post.widget_bars == 'undefined' ) {
		/*
		if ( confirm(widget_confirm_nobars2delete_str) ) {
			widget_uninstall(id);
		}
		*/
		alert(widget_alert_nobars_str);
		return;
	}

	// widget's save ihooks
	if ( typeof widget_save_ihooks[widget.widget] == "function" ) {
		subresult = widget_save_ihooks[widget.widget](post, widget);
		if ( !subresult ) return;
		if ( typeof subresult.id != 'undefined' ) post = subresult;
	}

	// do ajax call
	adesk_ui_api_call(jsSaving);
	adesk_ajax_post_cb("awebdeskapi.php", "widget!widget_save", widget_save_cb, post);
}

function widget_save_cb(xml, txt) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded && ary.succeeded == "1") {
		widget_open(ary.id);
		adesk_result_show(ary.message);
	} else {
		adesk_error_show(ary.message);
	}
}

function widget_bars(id, section, widget) {
	if ( section != 'public' ) section = 'admin';
	if ( typeof allWidgets[widget] == 'undefined' ) return [];
	var r = [
		Builder._text(widget_showin_str),
		Builder.node('br')
	];
	var bars = [];
	for ( var i in allWidgets[widget].bars[section] ) {
		bars.push(
			Builder.node(
				'div',
				[
					Builder.node(
						'label',
						[
							Builder.node(
								'input',
								{
									id: 'bar_' + id + '_' + i,
									type: 'checkbox',
									name: 'widget_bars[' + i + ']',
									value: i
								}
							),
							Builder._text(allWidgets[widget].bars[section][i])
						]
					)
				]
			)
		);
	}
	r.push(Builder.node('div', { className: 'widget_instance_bars_list' }, bars));
	return r;
}

{/literal}
