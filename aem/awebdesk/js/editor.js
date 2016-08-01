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
