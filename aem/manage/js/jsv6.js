

function filter_get_string() {
	var r = '';
	for ( var i in filterArray ) {
		var t = typeof(filterArray[i]);
		if ( t != 'object' && t != 'function' ) {
			if ( typeof(filterArray[i]) == 'boolean' ) {
				r += '&&' + i + '||' + ( filterArray[i] ? 1 : 0 );
			} else {
				r += '&&' + i + '||' + filterArray[i];
			}
		}
	}
	return r.substr(2);
}

function filter_set_value(key, val) {
	filterArray[key] = val;
}

function filter_remove_value(key) {
	filterArray = adesk_array_remove(key, filterArray);
}


 
function setIcon(ico) {
	$('iconField').value = ico;
	var box = $('iconSelect');
	var icons = box.getElementsByTagName('a');
	for ( var i = 0; i < icons.length; i++ ) {
		icons[i].className = ( ico == icons[i].title ? 'adesk_icon_selected' : '' );
	}
	return false;
}




function setSafeTitle(title) {
	// if safe title is already set, don't change it
	if ( $('stringidField').value != '' ) return;
	// no safe title set, try to convert title
	$('stringidField').value = adesk_str_urlsafe(title);
}



function jump(url) {
	adesk_loader_show();
	window.location = url;
}








/*
	DOM
*/

// in selects with optgroups it adds/edits/removes elements (updates after those actions)
function manage_groupped_list(list, group, action, id, title, descript) {
	var rel = list.getElementsByTagName('optgroup');
	for ( var i = 0; i < rel.length; i++ ) {
		if ( rel[i].label == group ) {
			return manage_list(rel[i], action, id, title, descript);
		}
	}
	// if action is add, and group doesn't exist, add group
	if ( action == 'add' ) {
		if ( rel.length == 0 ) {
			list.innerHTML += '<optgroup label="' + group + '"><option value="' + id + '" title="' + descript + '">' + title + '</option></optgroup>';
		} else {
			var el = clone_1st_element(list, 'optgroup', false);
			el.label = group;
			el.innerHTML = '<option value="' + id + '" title="' + descript + '">' + title + '</option>';
		}
		return;
	}
	return manage_list(list, action, id, title, descript);
}

// in selects without optgroups it adds/edits/removes elements (updates after those actions)
function manage_list(list, action, id, title, descript) {
	if ( action == 'add' ) {
		// ADD TO LIST
		// if we passed a group node ("groupped" function ensures that), then it will be added to that group
		var el = clone_1st_element(list, 'option', false);
		el.value = id;
		el.title = descript;
		el.innerHTML = title;
		//el.selected = false;
	} else {
		// edit and delete gotta find it first
		var options = list.getElementsByTagName('option');
		for ( var i = 0; i < options.length; i++ ) {
			if ( options[i].value == id ) {
				if ( action == 'edit' ) {
					// MODIFY IN LIST
					options[i].title = descript;
					options[i].innerHTML = title;
				} else if ( action == 'delete' ) {
					// REMOVE FROM LIST
					// get his parent
					var daddy = options[i].parentNode;
					// kill this node
					daddy.removeChild(options[i]);
					// if it was member of a group
					if ( daddy.nodeName.toLowerCase() == 'optgroup' ) {
						// if the only one
						if ( daddy.getElementsByTagName('option').length == 0 ) {
							// kill entire group
							daddy.parentNode.removeChild(daddy);
						}
					}
				}
				break;
			}
		}
	}
}




/*
    CLONER FUNCTIONS
*/

function clone_1st_element (node, elem, clearInputs) {
	return adesk_dom_clone_node (node, elem, 0, clearInputs);
}

function clone_1st_div (node) {
    return clone_1st_element (node, 'div', true);
}

function clone_1st_tr (node) {
    return clone_1st_element (node, 'tr', false);
}

function clear_inputs (node)
{
    var newinput = node.getElementsByTagName ('input');
    for (var i=0; i<newinput.length; i++) {
        if (newinput[i].type == 'text' || newinput[i].type == 'file') newinput[i].value = '';
    }
}

function clear_areas (node)
{
    var newinput = node.getElementsByTagName ('textarea');
    for (var i=0; i<newinput.length; i++) {
        newinput[i].innerHTML = '';
    }
}
function remove_element (node)
{
    var papa = node;
    var divs = node.parentNode.getElementsByTagName ('div');
    var subDivs = 0;
    for ( var i = 0; i < divs.length; i++ ) {
   		// is directly underneath, count it
    	if ( divs[i].parentNode == node.parentNode ) {
    		subDivs++;
    	}
    }
    if (subDivs > 1)
    {
        node.parentNode.removeChild (node);
    }
    else
    {
	    var newinput = node.getElementsByTagName ('input');
	    for (var i=0; i<newinput.length; i++) {
	        if (newinput[i].type == 'text' || newinput[i].type == 'file') newinput[i].value = '';
	    }
	    var newselect = node.getElementsByTagName ('select');
	    for (var i=0; i<newselect.length; i++) {
	        newselect[i].selectedIndex = -1;
	        //newselect[i].selectedIndex = (newselect[i].multiple?-1:0);
	    }
    }
}
/*
    CLONER END
*/










function update_custom_fields(form_id, list) {
	var callback = ( list ? update_custom_fields_list_callback : update_custom_fields_callback );
    somethingChanged = true;
	if ($("parentsList"))
		var lists = adesk_form_select_extract($('parentsList'));
	else
		var lists = adesk_dom_boxchoice("parentsList");
    adesk_ajax_call_cb('awebdeskapi.php', 'list.list_field_update', callback, form_id, lists.join('-'), 1);
}

function update_custom_fields_checkbox(id) {
	if ( !id ) id = 0;
    somethingChanged = true;
    var lists = adesk_form_check_selection_get($('parentsListBox'), 'p[]');
    adesk_ajax_call_cb('awebdeskapi.php', 'list.list_field_update', update_custom_fields_callback, id, lists.join('-'), 1);
}

function update_custom_fields_callback(xml, txt) {
	var rel = $('custom_fields_table');
	var ary = adesk_dom_read_node(xml);
	adesk_dom_remove_children(rel);
	var total = 0;
	var visible = 0;
	if ( ary.fields ) {
		if ( typeof ary.fields.length == 'undefined' ) ary.fields = [ ary.fields ];
		for ( var i = 0; i < ary.fields.length; i++ ) {
			var row = ary.fields[i];
			var node = adesk_custom_fields_cons(row);
			if ( parseInt(row.type, 10) != 6 ) {
				rel.appendChild(Builder.node(
					"tr",
					[
						Builder.node("td", { valign: 'top'/*, width: "75"*/ }, [ Builder._text(adesk_custom_fields_title(row)) ]),
						Builder.node("td", [ node ])
					]
				));
			} else {
				rel.appendChild(Builder.node(
					"tr",
					[
						Builder.node("td", [ Builder._text(" ") ]),
						Builder.node("td", [ node ])
					]
				));
			}
			total++;
			if ( parseInt(row.type, 10) != 6 ) visible++;
		}
	}
}

var update_custom_fields_list_preselect = {};

function update_custom_fields_list_callback(xml, txt) {
	var rel = $('custom_fields_table');
	var ary = adesk_dom_read_node(xml);
	adesk_dom_remove_children(rel);
	var total = 0;
	if ( ary.fields ) {
		if ( typeof ary.fields.length == 'undefined' ) ary.fields = [ ary.fields ];
		for ( var i = 0; i < ary.fields.length; i++ ) {
			var row = ary.fields[i];
			var props = { name: 'fields[]', id: 'custom' + row.id + 'Field', type: 'checkbox', value: row.id };
			if ( !update_custom_fields_list_preselect || adesk_array_has(update_custom_fields_list_preselect, row.id) ) {
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
}


function toggleEditor(id, action, settings) {
	if ( action == adesk_editor_is(id + 'Editor') ) return false;
	adesk_editor_toggle(id + 'Editor', settings);
	$(id + 'EditorLinkOn').className  = ( action ? 'currenttab' : 'othertab' );
	$(id + 'EditorLinkOff').className = ( !action ? 'currenttab' : 'othertab' );
	$(id + 'EditorLinkDefault').className = ( ( action != ( adesk_js_admin.htmleditor == 1 ) ) ? 'adesk_block' : 'adesk_hidden' );
	/*
	if ( !$(id + 'Editor') ) tmpEditorContent = adesk_form_value_get($(id + '_form')); else // heavy hack!!!
	tmpEditorContent = adesk_form_value_get($(id + 'Editor'));
	*/
	return false;
}

function setDefaultEditor(id) {
	var isEditor = adesk_editor_is(id + 'Editor');
	if ( isEditor == ( adesk_js_admin.htmleditor == 1 ) ) return false;
	// send save command
	// save new admin limit remotelly
	adesk_ajax_call_cb('awebdeskapi.php', 'user.user_update_value', null, 'htmleditor', ( isEditor ? 1 : 0 ));
	$(id + 'EditorLinkDefault').className = 'adesk_hidden';
	adesk_js_admin.htmleditor = ( isEditor ? 1 : 0 );
	return false;
}

// not used...
function editorContentChanged(inst) {
	tmpEditorContent = inst.getContent();
	somethingChanged = true;
}



function form_editor_personalization(prfx, sets, type, suffix, fields) {
	if ( typeof suffix != 'string' ) suffix = 'PersTags';
	if ( type != 'mime' && type != 'text' && type != 'html' ) type = 'mime';
	if ( type != 'html' ) {
		// personalization tags select
		var rel = $(prfx + suffix);
		// clean up personalization tags
		adesk_dom_remove_children(rel);
		// add "peronalize" text
		rel.appendChild(Builder.node('option', { value: '', selected: 'selected' }, [ Builder._text(strPersPersonalize) ]));
	}
	// check passed sets
	if ( !sets.length || sets.length == 0 ) sets = [ 'confirm', 'subscriber', 'sender', 'system' ];
	// prepare optgroups
	var subnodes = []; // subscriber personalization nodes
	var msgnodes = [];
	var socnodes = [];
	var othnodes = [];
	var sysnodes = []; // system personalization nodes
	var sndnodes = []; // sender personalization nodes
	var gcfnodes = []; // global custom fields nodes
	// add confirm link to system nodes
	if ( adesk_array_has(sets, 'confirm') ) {
		// set %CONFIRMLINK%
		msgnodes.push(Builder.node('option', { value: '%CONFIRMLINK%' }, [ Builder._text(strPersConfirmLink) ]));
	}
	// build regular system nodes
	if ( adesk_array_has(sets, 'system') ) {
		// set %PERS_UNSUB%
		msgnodes.push(Builder.node('option', { value: '%UNSUBSCRIBELINK%' }, [ Builder._text(strPersUnsubLink) ]));
		// set %PERS_UPDATE%
		msgnodes.push(Builder.node('option', { value: '%UPDATELINK%' }, [ Builder._text(strPersUpdateLink) ]));
		// set %PERS_WCOPY%
		msgnodes.push(Builder.node('option', { value: '%WEBCOPY%' }, [ Builder._text(strPersWCopyLink) ]));
		// set %PERS_FRIEND%
		msgnodes.push(Builder.node('option', { value: '%FORWARD2FRIEND%' }, [ Builder._text(strPersFriendLink) ]));
		// set %SOCIALSHARE%
		socnodes.push(Builder.node('option', { value: '%SOCIALSHARE%' }, [ Builder._text(strPersSocialLink) ]));
		// set %SOCIAL-FACEBOOK-LIKE%
		socnodes.push(Builder.node('option', { value: '%SOCIAL-FACEBOOK-LIKE%' }, [ Builder._text(strPersSocialFacebookLikeLink) ]));
		// set %SOCIALSHARE-*%
		for ( var k in personalization_social_networks ) {
			var v = personalization_social_networks[k];
			socnodes.push(Builder.node('option', { value: '%SOCIALSHARE-' + k.toUpperCase() + '%' }, [ Builder._text(v) ]));
		}
		// set %PERS_TODAY%
		othnodes.push(Builder.node('option', { value: '%TODAY%' }, [ Builder._text(strPersTodayLink) ]));
		// set %PERS_TODAY%
		othnodes.push(Builder.node('option', { value: '%TODAY*%' }, [ Builder._text(strPersTodayRangeLink) ]));
		// set %SENDER-INFO%
		othnodes.push(Builder.node('option', { value: '%SENDER-INFO%' }, [ Builder._text(strPersSenderInfoLink) ]));
	}
	// build subscriber nodes
	if ( adesk_array_has(sets, 'subscriber') ) {
		// set %PERS_EMAIL%
		subnodes.push(Builder.node('option', { value: '%EMAIL%' }, [ Builder._text(strPersEmailLink) ]));
		// set %PERS_FIRSTNAME%
		subnodes.push(Builder.node('option', { value: '%FIRSTNAME%' }, [ Builder._text(strPersFNameLink) ]));
		// set %PERS_LASTNAME%
		subnodes.push(Builder.node('option', { value: '%LASTNAME%' }, [ Builder._text(strPersLNameLink) ]));
		// set %PERS_LASTNAME%
		subnodes.push(Builder.node('option', { value: '%FULLNAME%' }, [ Builder._text(strPersNameLink) ]));
		// set %PERS_LISTNAME%
		subnodes.push(Builder.node('option', { value: '%LISTNAME%' }, [ Builder._text(strPersListnameLink) ]));
		// set %PERS_IP%
		subnodes.push(Builder.node('option', { value: '%SUBSCRIBERIP%' }, [ Builder._text(strPersIPLink) ]));
		// set %PERS_DATETIME%
		subnodes.push(Builder.node('option', { value: '%SUBDATETIME%' }, [ Builder._text(strPersSDateTimeLink) ]));
		// set %PERS_DATE%
		subnodes.push(Builder.node('option', { value: '%SUBDATE%' }, [ Builder._text(strPersSDateLink) ]));
		// set %PERS_TIME%
		subnodes.push(Builder.node('option', { value: '%SUBTIME%' }, [ Builder._text(strPersSTimeLink) ]));
		// set %PERS_ID%
		subnodes.push(Builder.node('option', { value: '%SUBSCRIBERID%' }, [ Builder._text(strPersSIDLink) ]));
	}
	// build global custom fields nodes
	if ( adesk_array_has(sets, 'subscriber') && fields ) {
		// general custom fields
		for ( var i in fields ) {
			var f = fields[i];
			if (typeof f != "function") {
				if ( !f.perstag || f.perstag == '' ) {
					f.perstag = 'PERS_' + f.id;
				}
				var tag = '%' + f.perstag + '%';
				gcfnodes.push(Builder.node('option', { value: tag }, [ Builder._text(f.title) ]));
			}
		}
	}
	if ( type != 'html' ) {
		if ( subnodes.length > 0 ) {
			rel.appendChild(Builder.node('optgroup', { label: strPersSubscriberTags }, subnodes));
		}
		if ( msgnodes.length > 0 ) {
			rel.appendChild(Builder.node('optgroup', { label: strPersMessageTags }, msgnodes));
		}
		if ( socnodes.length > 0 ) {
			rel.appendChild(Builder.node('optgroup', { label: strPersSocialTags }, socnodes));
		}
		if ( othnodes.length > 0 ) {
			rel.appendChild(Builder.node('optgroup', { label: strPersOtherTags }, othnodes));
		}
		if (gcfnodes.length > 0) {
			rel.appendChild(Builder.node("optgroup", { label: strPersListFields }, gcfnodes));
		}
		rel.selectedIndex = 0;
	}
}


function form_editor_personalization_link_push(value, title, description) {
	if(!description){ description = ''; }
	return Builder.node(
		'li',
		[
			Builder.node(
				'a',
				{
					href: '#',
					onclick: "form_editor_personalize_insert('" + value + "');return false;",
					style: 'font-weight:bold;'
				},
				[ Builder._text(title) ]
			),

			Builder.node(
				'div',
				{
				},
				[ Builder._text(description) ]
			)

		]
	);
}

function form_editor_personalization_show(id) {
	$$(".personalizelistsection").each(function(e) { e.hide(); });
	$(id).show();


 	$("subinfo_tab").className = "othertab";
 	$("message_tab").className = "othertab";
 	$("socmedia_tab").className = "othertab";
 	$("other_tab").className = "othertab";

 	switch (id) {
 		case "personalize_subinfo":
 			$("subinfo_tab").className = "currenttab";
 			break;

 		case "personalize_message":
 			$("message_tab").className = "currenttab";
 			break;

 		case "personalize_socmedia":
 			$("socmedia_tab").className = "currenttab";
 			break;

 		case "personalize_other":
 			$("other_tab").className = "currenttab";
 			break;

 		default:
 			break;
 	}
}

function form_editor_personalization_push(nodes, id) {
	for (var i = 0; i < nodes.length; i++)
		$(id).appendChild(nodes[i]);
}

function form_editor_personalization_section_push(nodes, title, idname) {
	return Builder.node(
		'div',
		{
			className: 'personalizelistsection',
			id: idname,
			style: "display:none"
		},
		[
			Builder.node('ul', nodes)
		]
	);
}

function form_editor_personalization_links(sets, type) {
	if ( type != 'mime' && type != 'text' && type != 'html' ) type = 'mime';
	// check passed sets
	if ( !sets.length || sets.length == 0 ) sets = [ 'confirm', 'subscriber', 'sender', 'system' ];
	// prepare optgroups
	var subtnodes = []; // top subscriber personalization nodes
	var subfnodes = [];	// field subscriber nodes
	var subbnodes = [];	// bottom subscriber nodes
	var msgnodes = []; // system personalization nodes
	var socnodes = []; // sender personalization nodes
	var othnodes = []; // global custom fields nodes
	// add confirm link to system nodes
	if ( adesk_array_has(sets, 'confirm') ) {
		// set %CONFIRMLINK%
		msgnodes.push(form_editor_personalization_link_push('%CONFIRMLINK%', strPersConfirmLink));
	}
	// set %PERS_UNSUB%
	msgnodes.push(form_editor_personalization_link_push('%UNSUBSCRIBELINK%', strPersUnsubLink, strPersUnsubLink_DESC));
	// set %PERS_WCOPY%
	msgnodes.push(form_editor_personalization_link_push('%WEBCOPY%', strPersWCopyLink, strPersWCopyLink_DESC));
	// set %PERS_UPDATE%
	msgnodes.push(form_editor_personalization_link_push('%UPDATELINK%', strPersUpdateLink, strPersUpdateLink_DESC));
	// set %PERS_FRIEND%
	msgnodes.push(form_editor_personalization_link_push('%FORWARD2FRIEND%', strPersFriendLink, strPersFriendLink_DESC));
	//Customization
	msgnodes.push(form_editor_personalization_link_push('%UNSUBSCRIBELINK%&ALL', strPersUnsubLinkAll, strPersUnsubLinkAll_DESC));
	// set %SOCIALSHARE%
	socnodes.push(form_editor_personalization_link_push('%SOCIALSHARE%', strPersSocialLink));
	// set %SOCIAL-FACEBOOK-LIKE%
	socnodes.push(form_editor_personalization_link_push('%SOCIAL-FACEBOOK-LIKE%', strPersSocialFacebookLikeLink));
	// set %SOCIALSHARE-*%
	for ( var k in personalization_social_networks ) {
		var v = personalization_social_networks[k];
		socnodes.push(form_editor_personalization_link_push('%SOCIALSHARE-' + k.toUpperCase() + '%', v));
	}

	// set %PERS_TODAY%
	othnodes.push(form_editor_personalization_link_push('%TODAY%', strPersTodayLink));
	// set %PERS_TODAY%
	othnodes.push(form_editor_personalization_link_push('%TODAY*%', strPersTodayRangeLink));
	// set %SENDER-INFO%
	othnodes.push(form_editor_personalization_link_push('%SENDER-INFO%', strPersSenderInfoLink));

	// set %PERS_EMAIL%
	subtnodes.push(form_editor_personalization_link_push('%EMAIL%', strPersEmailLink));
	// set %PERS_FIRSTNAME%
	subtnodes.push(form_editor_personalization_link_push('%FIRSTNAME%', strPersFNameLink));

	// set %PERS_LASTNAME%
	subtnodes.push(form_editor_personalization_link_push('%LASTNAME%', strPersLNameLink));
	// set %PERS_LASTNAME%
	subtnodes.push(form_editor_personalization_link_push('%FULLNAME%', strPersNameLink));



	// build global custom fields nodes
	if ( fields ) {
		// general custom fields
		for ( var i in fields ) {
			var f = fields[i];
			if ( !f.perstag || f.perstag == '' ) {
				f.perstag = 'PERS_' + f.id;
			}
			var tag = '%' + f.perstag + '%';
			subfnodes.push(form_editor_personalization_link_push(tag, f.title));
		}
	}
	// set %PERS_DATETIME%
	subbnodes.push(form_editor_personalization_link_push('%SUBDATETIME%', strPersSDateTimeLink, strPersSDateTimeLink_DESC));
	// set %PERS_DATE%
	subbnodes.push(form_editor_personalization_link_push('%SUBDATE%', strPersSDateLink, strPersSDateLink_DESC));
	// set %PERS_TIME%
	subbnodes.push(form_editor_personalization_link_push('%SUBTIME%', strPersSTimeLink, strPersSTimeLink_DESC));
	// set %PERS_IP%
	subbnodes.push(form_editor_personalization_link_push('%SUBSCRIBERIP%', strPersIPLink, strPersIPLink_DESC));

	// set %PERS_LISTNAME%
	subbnodes.push(form_editor_personalization_link_push('%LISTNAME%', strPersListnameLink, strPersListnameLink_DESC));
	// set %PERS_ID%
	subbnodes.push(form_editor_personalization_link_push('%SUBSCRIBERID%', strPersSIDLink, strPersSIDLink_DESC));
	if ( subtnodes.length > 0 ) {
		form_editor_personalization_push(subtnodes, "personalize_subinfo_top");
	}
	if ( subfnodes.length > 0 ) {
		adesk_dom_remove_children($("personalize_subinfo_field_global"));
		form_editor_personalization_push(subfnodes, "personalize_subinfo_field_global");
	}
	if ( subbnodes.length > 0 ) {
		form_editor_personalization_push(subbnodes, "personalize_subinfo_bottom");
	}
	if ( msgnodes.length > 0 ) {
		form_editor_personalization_push(msgnodes, "personalize_message");
	}
	if ( socnodes.length > 0 ) {
		form_editor_personalization_push(socnodes, "personalize_socmedia");
	}
	if ( othnodes.length > 0 ) {
		form_editor_personalization_push(othnodes, "personalize_other");
	}
}

var persResultSet = null;
function form_editor_sender_personalization(ary, rel) {
	persResultSet = ary;
	// custom fields
	var nodesin  = [];
	// check if there is an existing group
	// if yes, we'll remove it first
	var divgroups = $$('#' + rel.id + ' div.personalizelisttitle a');
	for ( var i = 0; i < divgroups.length; i++ ) {
		if ( divgroups[i].innerHTML == strPersSenderTags ) {
			rel.removeChild(divgroups[i].parentNode.parentNode);
			break;
		}
	}

	if(typeof ary[0] == 'undefined')
		return;

	if ( ary[0].text ) {
		for ( var i in ary[0].text ) {
			var f = ary[0].text[i];
			if ( typeof f != 'function' ) {
				if ( !f.tag || f.tag == '' ) {
					f.tag = 'PERS_' + f.id;
				}
				var ftag_value = '%' + f.tag + '%';
				//f.tag = '%' + f.tag + '%';
				//here
				//nodesin.push( Builder.node('option', { value: ftag_value }, [ Builder._text(f.name) ]));
				nodesin.push(form_editor_personalization_link_push(ftag_value, f.name));
			}
		}
	}
	if ( ary[0].html ) {
		for ( var i in ary[0].html ) {
			var f = ary[0].html[i];
			if ( typeof f != 'function' ) {
				if ( !f.tag || f.tag == '' ) {
					f.tag = 'PERS_' + f.id;
				}
				var ftag_value = '%' + f.tag + '%';
				//f.tag = '%' + f.tag + '%';
				nodesin.push(form_editor_personalization_link_push(ftag_value, f.name));
			}
		}
	}
	if ( nodesin.length > 0 ) {
		adesk_dom_remove_children($("personalize_senderinfo"));
		form_editor_personalization_push(nodesin, "personalize_senderinfo");
	}
	//alert('handle personalization now!' + nodesin.length + rel.id);
}


function form_editor_insert(field, value) {
	// only today tag should be reset
	if ( value.match( /^%TODAY[+-]\d+%$/ ) ) {
		value = '%TODAY*%';
	}
	if ( value == '%TODAY*%' ) {
		var entered = prompt(strEnterRange, '+1');
		if ( !entered || !entered.match( /^[-+]?\d+$/ ) ) {
			alert(strEnterRangeInvalid);
			return '';
		}
		if ( !entered.match(/^[-+].*$/) ) {
			entered = '+' + entered;
		}
		value = '%TODAY' + entered + '%';
	}
	adesk_form_insert_cursor(field, value);
}

function form_editor_defaults(prfx, format, sets) {
	$(prfx + 'textField').value = '';
	$(prfx + 'formatField').value = format;
	adesk_form_value_set($(prfx + 'Editor'), '');
	// prepare personalization tags
	form_editor_personalization_links(sets, format);
	//form_editor_personalization(prfx, sets, format);
	// show appropriate editor
	adesk_editor_mime_switch(prfx, $(prfx + 'formatField').value);
}

function form_editor_update(prfx, ary, suffix) {
	if ( typeof suffix != 'string' ) suffix = 'PersTags';
	$(prfx + 'formatField').value = ary.format;
	$(prfx + 'textField').value = ary.text;
	adesk_form_value_set($(prfx + 'Editor'), ary.html);
	// custom fields
	form_editor_update_fields_links(ary);
	// show appropriate editor
	adesk_editor_mime_switch(prfx, $(prfx + 'formatField').value);
}


function form_editor_update_fields(prfx, ary, suffix) {
	if ( typeof suffix != 'string' ) suffix = 'PersTags';
	// custom fields
	var rel = $(prfx + suffix);
	if ( !$('messageField') && typeof customFieldsObj != 'undefined' ) {
		ACCustomFieldsResult = ary.fields;
		customFieldsObj.handlePersonalization(ACCustomFieldsResult, rel);
		customFieldsObj.additionalHandler(ary);
	} else {
		var nodesin  = [];
		for ( var i in ary.fields ) {
			var f = ary.fields[i];
			if ( typeof f != 'function' ) {
				if ( !f.perstag || f.perstag == '' ) {
					f.perstag = 'PERS_' + f.id;
				}
				f.perstag = '%' + f.perstag + '%';
				nodesin.push( Builder.node('option', { value: f.perstag }, [ Builder._text(f.title) ]));
			}
		}
		if ( nodesin.length > 0 ) {
			rel.appendChild(Builder.node('optgroup', { label: strPersListFields }, nodesin));
		}
		rel.selectedIndex = 0;
	}
}

function form_editor_update_fields_links(ary) {
	// custom fields
	var rel = $('personalizelist');
	if ( !$('messageField') && typeof customFieldsObj != 'undefined' ) {
		ACCustomFieldsResult = ary.fields;
		customFieldsObj.handlePersonalizationLinks(ACCustomFieldsResult, rel);
		if( typeof ary.personalizations == 'undefined')
			ary.personalizations = [];
		customFieldsObj.additionalHandler(ary);
	} else {
		var nodesin  = [];
		for ( var i in ary.fields ) {
			var f = ary.fields[i];
			if ( typeof f != 'function' ) {
				if ( !f.perstag || f.perstag == '' ) {
					f.perstag = 'PERS_' + f.id;
				}
				f.perstag = '%' + f.perstag + '%';
				nodesin.push( Builder.node('option', { value: f.perstag }, [ Builder._text(f.title) ]));
				nodesin.push(form_editor_personalization_link_push(f.perstag, f.title));
			}
		}
		if ( nodesin.length > 0 ) {
			adesk_dom_remove_children($("personalize_subinfo_field"));
			form_editor_personalization_push(nodesin, "personalize_subinfo_field");
		}
	}
}


function optinoptout_defaults() {
	if($('form_lists')) $('form_lists').selectedIndex = -1;
	var values = {
		optname: '',
		optinsubject: strOptInSubjectDefault,
		optinfromemail: '',
		optinfromname: '',
		optoutsubject: strOptOutSubjectDefault,
		optoutfromemail: '',
		optoutfromname: ''
	};
	for ( var i in values ) {
		$(i + 'Field').value = values[i];
	}
	$('optinconfirmFieldYes').checked = true;
	$('optoutconfirmFieldNo').checked = true;
	// set editor scene
	form_editor_defaults('optin', 'html', [ 'confirm', 'subscriber', 'sender', 'system' ]);
	form_editor_defaults('optout', 'html', [ 'confirm', 'subscriber', 'sender', 'system' ]);
	form_editor_personalization('conditionalfield', [ 'subscriber' ], 'text', '');
	// set optinout scene
	adesk_editor_mime_toggle('optin', $('optinconfirmFieldYes').checked);
	adesk_editor_mime_toggle('optout', $('optoutconfirmFieldYes').checked);
	//adesk_editor_mime_switch('optin', $('optinformatField').value); // toggle calls these
	$('optinformatField').value = "html";
	//adesk_editor_mime_switch('optout', $('optoutformatField').value);
	// files
	if ( $('optin_attach_list') ) {
		adesk_dom_remove_children($('optin_attach_list'));
		adesk_dom_remove_children($('optout_attach_list'));
	}
	adesk_form_value_set($("optinEditor"), strDefaultOptInText + "<br /><a href=\"%CONFIRMLINK%\">%CONFIRMLINK%</a>");
	$("optintextField").value = strDefaultOptInText + "%CONFIRMLINK%";
	adesk_form_value_set($("optoutEditor"), strDefaultOptOutText + "<br /><a href=\"%CONFIRMLINK%\">%CONFIRMLINK%</a>");
	$("optouttextField").value = strDefaultOptOutText + "%CONFIRMLINK%";
}

function optinoptout_update(ary) {
	// optin
	$('optnameField').value = ary.optname;
	$('optinconfirmField' + ( ary.optin_confirm == 1 ? 'Yes' : 'No' )).checked = true;
	$('optinsubjectField').value = ary.optin_subject;
	$('optinfromnameField').value = ary.optin_from_name;
	$('optinfromemailField').value = ary.optin_from_email;
	// optout
	$('optoutconfirmField' + ( ary.optout_confirm == 1 ? 'Yes' : 'No' )).checked = true;
	$('optoutsubjectField').value = (ary.optout_subject != "") ? ary.optout_subject : strOptOutSubjectDefault;
	$('optoutfromnameField').value = (ary.optout_from_name == "" && ary.optin_from_name != "") ? ary.optin_from_name : ary.optout_from_name;
	$('optoutfromemailField').value = (ary.optout_from_email == "" && ary.optin_from_email != "") ? ary.optin_from_email : ary.optout_from_email;
	// optinout panels switch
	ary.format = ary.optin_format;
	ary.text = ary.optin_text;
	ary.html = ary.optin_html;
	form_editor_update('optin', ary);
	ary.format = ary.optout_format;
	ary.text = ary.optout_text;
	ary.html = ary.optout_html;
	form_editor_update('optout', ary);
	form_editor_update_fields('conditionalfield', ary, '');
	adesk_editor_mime_toggle('optin', $('optinconfirmFieldYes').checked);
	adesk_editor_mime_toggle('optout', $('optoutconfirmFieldYes').checked);
	//adesk_editor_mime_switch('optin', $('optinformatField').value); // toggle calls these
	//adesk_editor_mime_switch('optout', $('optoutformatField').value);
	// files
	if ( $('optin_attach_list') ) {
		if ( ary.optin_files ) {
			for ( var i = 0; i < ary.optin_files.length; i++ ) {
				var this1 = ary.optin_files[i];
				this1.action = 'optinoptout_attach';
				this1.filename = this1.name;
				this1.filesize = this1.size;
				this1.humansize = adesk_str_file_humansize(this1.filesize);
				adesk_form_upload_set('optin_attach', 'optoutattach', this1, adesk_js_admin.limit_attachment);
			}
		}
		// files
		if ( ary.optout_files ) {
			for ( var i = 0; i < ary.optout_files.length; i++ ) {
				var this1 = ary.optout_files[i];
				this1.action = 'optinoptout_attach';
				this1.filename = this1.name;
				this1.filesize = this1.size;
				this1.humansize = adesk_str_file_humansize(this1.filesize);
				adesk_form_upload_set('optout_attach', 'optoutattach', this1, adesk_js_admin.limit_attachment);
			}
		}
	}
}


function bounce_defaults() {
	var values = {
		bounceemail: '',
		bouncebatch: 120,
		bouncehost: '',
		bounceport: 110,
		bounceuser: '',
		bouncepass: '',
		bouncehard: 3,
		bouncesoft: 6
	};
	for ( var i in values ) {
		$(i + 'Field').value = values[i];
	}
	$('bouncetypeFieldPOP3').checked = true;
	// bounce
	$('bouncepop3').className = (  $('bouncetypeFieldPOP3').checked ? 'adesk_table_rowgroup' : 'adesk_hidden' );
	$('bounceform').className = ( !$('bouncetypeFieldNone').checked ? 'adesk_block' : 'adesk_hidden' );
	//$('popfaqpanel').className = 'h2_content_invis';
	$('bouncelists').className = 'adesk_table_rowgroup';
}

function bounce_update(ary) {
	$('bounceemailField').value = ary.email;
	$('bouncebatchField').value = ary.emails_per_batch;
	$('bouncehostField').value = ary.host;
	$('bounceportField').value = ary.port;
	$('bounceuserField').value = ary.user;
	$('bouncepassField').value = ary.pass;
	$('bouncehardField').value = ary.limit_hard;
	$('bouncesoftField').value = ary.limit_soft;
	$('bouncetypeField' + ( ary.type == 'pop3' ? 'POP3' : ( ary.type == 'pipe' ? 'Pipe' : 'None' ) )).checked = true;
	$('bounceform').className = ( !$('bouncetypeFieldNone').checked ? 'adesk_block' : 'adesk_hidden' );
	$('bouncepop3').className = (  $('bouncetypeFieldPOP3').checked ? 'adesk_table_rowgroup' : 'adesk_hidden' );
	$('bouncelists').className = ( ary.id != 1 ? 'adesk_table_rowgroup' : 'adesk_hidden' );
}


function export_link_build(assets, ary) {
	var link = 'export.php?action=' + assets;
	for ( var i in ary ) {
		link += '&' + i + '=' + ary[i];
	}
	if ( typeof(ourflag) == 'undefined' || prompt('Go to this export URL?', link) )
	window.location.href = link;
}

var listfilters = {};
function list_filter(rnd) {
	var post = adesk_form_post($("listfilter" + rnd));
	listfilters[rnd] = post.listid;
	adesk_ajax_post_cb(
		"awebdeskapi.php",
		"subscriber.subscriber_filter_post",
		function(xml) {
			var ary = adesk_dom_read_node(xml);
			list_filters_update(ary.filterid, post.listid, true);
			/*
			alert(
				'filter: ' + ary.filterid +
				'\nlist: ' + ( post.listid == 0 ? '- all -' : post.listid )
			);
			*/
		},
		post
	);
}

function list_filters_update(filterid, listid, doassetsFilters) {
	for ( var rnd in listfilters ) {
		if ( $('listFilterManager' + rnd).value != listid ) {
			$('listFilterManager' + rnd).value = listid;
			listfilters[rnd] = listid;
		}
	}
	if ( doassetsFilters ) {
		// try to change the list filter on this assets page
		if ( $('JSListManager') ) $('JSListManager').value = listid;
		// try to run assign filterid based on assets
/*		try {
			var v = eval(adesk_action + "_list_filter = " + filterid + ";");
		} catch (e) {
			// do nothing, none found
		}
*/		// try to run assign listid based on assets
		try {
			var v = eval(adesk_action + "_listfilter = " + listid + ";");
		} catch (e) {
			// do nothing, none found
		}
		// try to run a search function based on assets
		var func = null;
		try {
			var func = eval(adesk_action + "_list_search");
		} catch (e) {
			// do nothing, none found
		}
		if ( typeof func == "function" ) {
			func();
		}
	}
}


function optinout_get(id) {
	optinoptout_defaults();
	$('hiddenOptinId').value = id;
	if ( id > 0 ) {
		// ajax call
		adesk_ui_api_call(jsLoading);
		adesk_ajax_call_cb("awebdeskapi.php", "optinoptout.optinoptout_select_row", optinout_get_cb, id);
	//} else {alert('here');
		if ( !customFieldsObj ) { // list page
			var customFieldsObj = new ACCustomFields({
				sourceType: 'SELECT',
				sourceId: 'parentsList',
				api: 'list.list_field_update',
				responseIndex: 'fields',
				includeGlobals: 0,
				additionalHandler: function(ary) {
					// deal with personalization tags
					form_editor_sender_personalization(ary.personalizations, $('personalizelist'));
					/*
					adesk_editor_toggle('optinEditor', adesk_editor_init_word_object);
					adesk_editor_toggle('optinEditor', adesk_editor_init_word_object);
					adesk_editor_toggle('optoutEditor', adesk_editor_init_word_object);
					adesk_editor_toggle('optoutEditor', adesk_editor_init_word_object);
					*/
				}
			});
		}
		customFieldsObj.handlePersonalizationLinks(ACCustomFieldsResult, $('personalizelist'));
		customFieldsObj.handlePersonalization(ACCustomFieldsResult, $('conditionalfield'));
		customFieldsObj.additionalHandler(ACCustomFieldsResult);
		//customFieldsObj.fetch(0);
	}
	$('optinoutnew').className = 'adesk_block';
}

function optinout_get_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	optinoptout_update(ary);
}

function optinout_set() {
	var post = adesk_form_post($("optinoutnew"));
	post.id = $('hiddenOptinId').value;
	optinout_save(post, optinout_set_cb);
}

function optinout_save(post, callback) {
	if ( post.optname == '' ) {
		alert(strOptNameEmpty);
		$('optnameField').focus();
		return;
	}
	if ( adesk_js_admin.optinconfirm && post.optin_confirm != 1 ) {
		alert(strOptInNeeded);
		return;
	}
	if ( post.optin_confirm == 1 ) {
		if ( !adesk_str_email(post.optin_from_email) ) {
			alert(strOptInEmailNotEmail);
			$('optinfromemailField').focus();
			return;
		}
		if ( post.optin_subject == '' ) {
			alert(strOptInSubjectEmpty);
			$('optinsubjectField').focus();
			return;
		}
		if ( post.optin_format != 'html' && !post.optin_text.match(/%CONFIRMLINK%/) ) {
			alert(strOptInTextConfirmMissing);
			$('optintextField').focus();
			return;
		}
		if ( post.optin_format != 'text' && !post.optin_html.match(/%CONFIRMLINK%/) ) {
			alert(strOptInHTMLConfirmMissing);
			return;
		}
	}
	if ( post.optout_confirm == 1 ) {
		if ( !adesk_str_email(post.optout_from_email) ) {
			alert(strOptOutEmailNotEmail);
			$('optoutfromemailField').focus();
			return;
		}
		if ( post.optout_subject == '' ) {
			alert(strOptOutSubjectEmpty);
			$('optoutsubjectField').focus();
			return;
		}
		if ( post.optout_format != 'html' && !post.optout_text.match(/%CONFIRMLINK%/) ) {
			alert(strOptOutTextConfirmMissing);
			$('optouttextField').focus();
			return;
		}
		if ( post.optout_format != 'text' && !post.optout_html.match(/%CONFIRMLINK%/) ) {
			alert(strOptOutHTMLConfirmMissing);
			return;
		}
	}

	adesk_ui_api_call(jsSaving);
	if (post.id > 0)
		adesk_ajax_post_cb("awebdeskapi.php", "optinoptout.optinoptout_update_post", callback, post);
	else
		adesk_ajax_post_cb("awebdeskapi.php", "optinoptout.optinoptout_insert_post", callback, post);
}

function optinout_set_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		// add to dropdown
		$('optinoutidField').appendChild(Builder.node('option', { value: ary.id, selected: true }, [ Builder._text(ary.name) ]));
		// hide add form
		$('optinoutnew').className = 'adesk_hidden';
	} else {
		adesk_error_show(ary.message);
	}
}




function parents_list_select(all, firstIsAll) {
	if ( all ) {
		adesk_form_select_multiple_all($('parentsList'), firstIsAll);
	} else {
		adesk_form_select_multiple_none($('parentsList'));
	}

	// campaign_new - only stuff
	if ( $('step2next') ) {
		// stop the scene until the call returns
		$('step2next').disabled = true;
		$('campaignfilterbox').hide();
	}
	if (typeof customFieldsObj == "object")
		customFieldsObj.fetch(0);
	return false;
}

function parents_box_select(all, firstIsAll) {
	if (all) {
		$$(".parentsList").each(function(e) { e.checked = true; });
	} else {
		$$(".parentsList").each(function(e) { e.checked = false; });
	}

	// campaign_new - only stuff
	if ( $('step2next') ) {
		campaign_step2_checknext();
		// stop the scene until the call returns
		$('step2next').disabled = true;
		$('campaignfilterbox').hide();
	}
	if (typeof customFieldsObj == "object")
		customFieldsObj.fetch(0);
	return false;
}


/*

ACTIVE RSS

*/


function form_editor_deskrss_open(type, insertObj) {
	if ( !insertObj ) insertObj = '';
	// set type
	$('deskrss4').value = type;
	$('deskrss2').value = insertObj;
	// set data
	$('deskrssurl').value = 'http://';
	$('deskrssloop').value = '10';
	$('deskrssall').checked = true;
	$('deskrsspreviewbox').className = 'adesk_hidden';
	// open modal
	$('message_deskrss').toggle();
}

function form_editor_deskrss_insert() {
	// close the modal
	$('message_deskrss').toggle();
	// build the code
	var code = form_editor_deskrss_build();
	if ( code == '' ) return;
	// push it into needed editor
	adesk_editor_insert($('deskrss2').value, ( $('deskrss4').value == 'html' ? nl2br(code) : code ));
}

function form_editor_deskrss_preview() {
	// build the code
	var code = form_editor_deskrss_build();
	if ( code == '' ) return;
	if ( $('deskrss4').value == 'html' ) code = nl2br(code);
	// push it into preview box
	$('deskrsspreview').value = code;
	$('deskrsspreviewbox').className = 'adesk_block';
}

function form_editor_deskrss_build() {
	// what type of code to build
	var type = $('deskrss4').value;
	// what url to fetch
	var url = $('deskrssurl').value;
	if ( !adesk_str_is_url(url) ) {
		alert(strURLNotURL);
		$('deskrssurl').focus();
		return '';
	}
	// how many to show
	if ( !adesk_ui_numbersonly($('deskrssloop')) ) {
		$('deskrssloop').value = 0;
	}
	var loop = $('deskrssloop').value;
	// what to show
	var show = ( $('deskrssnew').checked ? 'NEW' : 'ALL' ); // ALL/NEW
	// what are line breaks
	var nl = ( type == 'html' ? '<br />\n' : '\n' );
	var code =
		'%RSS-FEED|URL:' + url + '|SHOW:' + show + '%\n\n' + // start feed section
		'%RSS:CHANNEL:TITLE%\n\n' + // print out title
		'%RSS-LOOP|LIMIT:' + loop + '%\n\n' + // start item section
		'%RSS:ITEM:DATE%\n' + // within a section
		'%RSS:ITEM:TITLE%\n' +
		'%RSS:ITEM:SUMMARY%\n' +
		'%RSS:ITEM:LINK%\n\n' +
		'%RSS-LOOP%\n\n' +
		'%RSS-FEED%\n' // end section
	;
	return code;
}

function adesk_editor_deskrss_click() {
	form_editor_deskrss_open('html');
}



function form_editor_deskrss_loop_changed() {
	window.setTimeout(
		function() {
			adesk_ui_numbersonly($('deskrssloop'), true);
		},
		100
	);
}


/*

PERSONALIZATION TAGS

*/


function form_editor_personalize_open(type, insertObj) {
	if ( !insertObj ) insertObj = '';
	// set type
	$('personalize4').value = type;
	$('personalize2').value = insertObj;
	// collapse all open divs
	var divs = $$('#personalizelist ul');
	for ( var i = 0; i < divs.length; i++ ) {
		divs[i].className = ( i == 0 ? 'personalizelistgroup' : 'adesk_hidden' );
	}
	// open modal
	$('message_personalize').toggle();
}

function form_editor_personalize_insert(value) {
	if ( value == '' ) {
		alert(strPersMissing);
		return;
	}
	// close the modal
	$('message_personalize').toggle();
	// build the code
	var code = form_editor_personalize_build(value);
	if ( code == '' ) return;
	// push it into needed editor
	adesk_editor_insert($('personalize2').value, ( $('personalize4').value == 'html' ? nl2br(code) : code ));
}

function form_editor_personalize_build(val) {
	// what type of code to build
	var type = $('personalize4').value;
	// now handle custom (html?) cases
	var text = '';
	// only today tag should be reset
	if ( val.match( /^%TODAY[+-]\d+%$/ ) ) {
		val = '%TODAY*%';
	}
	if ( val == '%CONFIRMLINK%' ) {
		text = strConfirmLinkText;
	} else if ( val == '%UNSUBSCRIBELINK%' ) {
		text = strUnsubscribeText;
	} else if ( val == '%UPDATELINK%' ) {
		text = strSubscriberUpdateText;
	} else if ( val == '%WEBCOPY%' ) {
		text = strWebCopyText;
	} else if ( val == '%FORWARD2FRIEND%' ) {
		text = strForward2FriendText;
	} else if ( val == '%SOCIALSHARE%' ) {
		//text = strForward2FriendText; // don't prompt for anything, just use val
	} else if ( val == '%TODAY*%' ) {
		var entered = prompt(strEnterRange, '+1');
		if ( !entered ) return;
		if ( !entered.match( /^[-+]?\d+$/ ) ) {
			alert(strEnterRangeInvalid);
			return;
		}
		if ( !entered.match(/^[-+].*$/) ) {
			entered = '+' + entered;
		}
		val = '%TODAY' + entered + '%';
	}
	if ( type == 'html' && text != '' ) {
		entered = prompt(strEnterText, text);
		if ( !entered ) entered = text;
		val = '<a href="' + val + '">' + entered + '</a>';
	}
	return val;
}

function adesk_editor_personalize_click() {
	var ed = tinyMCE.activeEditor;
	//tinyMCE.activeEditor.execCommand('mceInsertContent', false, form_editor_personalize_build());
	form_editor_personalize_open('html');
}




/*

CONDITIONAL CONTENT

*/


function form_editor_conditional_open(type, insertObj) {
	if ( !insertObj ) insertObj = '';
	// set type
	$('conditional4').value = type;
	$('conditional2').value = insertObj;
	// set data
	$('conditionalfield').value = '';
	$('conditionalcond' ).selectedIndex = 0;
	$('conditionalvalue').value = '';
	// open modal
	$('message_conditional').toggle();
}

function form_editor_conditional_insert() {
	if ( $('conditionalfield').value == '' ) {
		alert(strPersMissing);
		$('conditionalfield').focus();
		return;
	}
	// close the modal
	$('message_conditional').toggle();
	// build the code
	var code = form_editor_conditional_build();
	if ( code == '' ) return;
	// push it into needed editor
	if ( $('conditional4').value == 'html' && adesk_editor_is($('conditional2').value) ) {
		var ed = tinyMCE.activeEditor;
		ed.execCommand('mceInsertContent', false, nl2br(code));
	} else {
		adesk_form_insert_cursor($($('conditional2').value), code);
	}
}

function form_editor_conditional_build() {
	// what type of code to build
	var type = $('conditional4').value;
	// what value to use
	var field = $('conditionalfield').value;
	var cond  = $('conditionalcond' ).value;
	var value = $('conditionalvalue').value;
	field = '$' + field.replace(/%/g, '').replace(/-/g, '_');
	value = value.replace(/%/g, '~PERCENT~');
	value = "'" + value.replace(/'/g, '\\\'') + "'";
	if ( cond.indexOf('CONTAINS') != -1 ) {
		var expr = 'in_string(' + value + ', ' + field + ')';
		if ( cond == 'DCONTAINS' ) expr = '!' + expr;
	} else {
		var expr = field + ' ' + cond + ' ' + value;
	}
	var code =
		'%IF ' + expr + '%\n' + editorConditionalText + '\n%ELSE%\n' + editorConditionalElseText + '\n%/IF%\n'
	;
	return code;
}

function adesk_editor_conditional_click() {
	var ed = tinyMCE.activeEditor;
	//tinyMCE.activeEditor.execCommand('mceInsertContent', false, form_editor_conditional_build());
	form_editor_conditional_open('html');
}




/*

TEMPLATES

*/


function form_editor_template_open(type, insertObj) {
	if ( !insertObj ) insertObj = '';
	// set type
	$('editortemplate2').value = insertObj;
	$('editortemplate4').value = type;
	// set data
	$('templateinsert').value = 0;
	$('editortemplatebutton').disabled = true;
	$('templateinsert').value = 0;
	// switch the template type
	$('templateinserthtml').className = ( type != 'text' ? 'adesk_block' : 'adesk_hidden' );
	$('templateinserttext').className = ( type != 'html' ? 'adesk_block' : 'adesk_hidden' );
	// open modal
	$('message_template').toggle();
}


function form_editor_template_insert_value(type, content) {
	// close the modal
	$('message_template').toggle();
	// build the code
	var code = content;
	if ( code == '' ) return;
	// push it into needed editor
	if ( type == 'html' ) {
		tinyMCE.activeEditor.execCommand('mceInsertContent', false, code);
	} else {
		adesk_form_insert_cursor($($('editortemplate2').value), code);
	}
}

function form_editor_template_insert(type) {
	var id = $('templateinsert').value;
	var lists = '';
	if ( $('parentsList') ) {
		lists = adesk_dom_boxchoice("parentsList").join('-');
	}
	adesk_ajax_call_cb(
		"awebdeskapi.php",
		"template.template_select_row",
		adesk_ajax_cb(
			function(ary) {
				if ( typeof ary.content != 'undefined' ) {
					form_editor_template_insert_value(type, ary.content);
				} else {
					adesk_error_show('Template not found.');
				}
			}
		),
		id,
		lists
	);
}

function form_editor_template_preview(id) {
	if ( id == 0 ) {
		$('templatequickpreview').hide();
		return;
	}
	$('templatequickpreviewimg').src = plink + '/manage/preview_message.php?which=tpl&id=' + id;
	$('templatequickpreview').show();
}

function startup_toggle_tab(container, section_show) {
	var container_divs = $(container).getElementsByTagName("div");
	var container_spans = $(container).getElementsByTagName("span");

	// Tab div on top always shows
	container_divs[0].className = "startup_box_title";

	// First make all other divs hidden
	// Start at 1, since we manually target the first div already
	for (var i = 1; i < container_divs.length; i++) {
		container_divs[i].className = "adesk_hidden";
	}

	// First make all spans default style
	for (var i = 0; i < container_spans.length; i++) {
		container_spans[i].className = "";
	}

	// Then show the div
	$("startup_box_div_" + section_show).className = "startup_box_container_inner";

	// Grab all inner divs within the section we are showing
	var selected_container_divs = $("startup_box_div_" + section_show).getElementsByTagName("div");

	// Make sure all inner divs are displayed, since they may have been hidden when we looped through all divs above
	for (var i = 0; i < selected_container_divs.length; i++) {
		selected_container_divs[i].className = "";
	}

	// Style the span selected
	$("startup_box_span_" + section_show).className = "startup_selected";
}

function quick_search() {
	var post = adesk_form_post($("quick_search"));
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "subscriber.subscriber_filter_post", quick_search_cb, post);
}

function quick_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	var action = 'subscriber';

	var sortorder = '01';

	window.location.href = 'desk.php?action=' + action + '#list-' + sortorder + '-0-' + ary.filterid;
}

function blog_posts() {
	adesk_ajax_call_cb(
		"awebdeskapi.php",
		"em.awebdesk_blog_posts",
		function(xml) {
			var ary = adesk_dom_read_node(xml);

			var rel = $('blogposts');

			var lim = 4;

			if ( typeof ary.row == 'undefined' ) return;

			var cnt = ( lim > ary.row.length ? ary.row.length : lim );

			for ( var i = 0; i < cnt; i++ ) {
				var row = ary.row[i];
				var nodes = [
					Builder.node(
						'div',
						{ className: 'blogpost_title' },
						[
							Builder.node(
								'a',
								{ href: row.link, title: row.pubdate + ' :: ' + adesk_str_shorten(row.summary, 100), target: '_blank' },
								[ Builder._text(adesk_str_shorten(row.title, 40)) ]
							)
						]
					)
					/*
					,
					Builder.node(
						'div',
						{ className: 'blogpost_body' },
						[
							Builder._text(adesk_str_shorten(row.summary, 50))
						]
					)
					*/
				];
				rel.appendChild(Builder.node('div', { className: 'blogpost' }, nodes));
			}
			rel.show();
		}
	);
}

function main_search(text) {
	if (text != '')
		window.location.href = sprintf('desk.php?action=subscriber&q=%s', text);
}

function help_search(text) {
	if ( adesk_str_trim(text) == '' ) return;
	window.location.href = sprintf(help_search_url, text);
}
