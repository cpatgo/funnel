if ( !window.opener ) top.location = 'index.php';
var o = window.opener;
var d = o.document;
var w = self;
if ( !o.campaign_obj ) top.location = 'index.php';

var preview_campaignid = {jsvar var=$campaignid};
var preview_messageid  = {jsvar var=$messageid};

var show_images  = true;
var orig_message = '';

var jsImagesEnabled = '{"Images Enabled"|alang|js}';
var jsImagesDisabled = '{"Images Disabled"|alang|js}';
var jsClosePopupError = '{"There was an error while building the Campaign Preview."|alang|js}\n\n{"Would you like to close this popup?"|alang|js}';

{literal}
function preview_step_check() {
	if ( !o.campaign_obj ) top.location = 'index.php';
}

function preview_menu_show() {
	preview_step_check();
	var obj = o.campaign_obj;
	/* SET MENU */
	// set message
	var msg = null;
	adesk_dom_remove_children($('preview_messageid'));
	// add messages
	for ( var i in o.campaign_obj.messages ) {
		var m = o.campaign_obj.messages[i];
		if ( typeof m.id != 'undefined' ) {
			if ( !msg ) msg = m;
			$('preview_messageid').appendChild(
				Builder.node('option', { value: m.id }, [ Builder._text(strip_tags(m.subject, true)) ])
			);
		}
	}
	// select the first one
	//$('preview_messageid').selectedIndex = 0;
	$('preview_messageid').value = preview_messageid;
	// show message select?
	$('preview_messageid_box').className = ( obj.type == 'split' ? 'adesk_inline' : 'adesk_hidden' );
	/* SET MESSAGE */
	preview_menu_set(msg);
}

function preview_menu_set(msg) {
	if ( !isNaN(parseInt(msg, 10)) ) {
		msg = parseInt(msg, 10);
		// find message
		for ( var i in o.campaign_obj.messages ) {
			var m = o.campaign_obj.messages[i];
			if ( typeof m != 'function' ) {
				if ( msg == m.id ) {
					msg = m;
					break;
				}
			}
		}
	}
	if ( typeof msg.format == 'undefined' ) return;
	// set format
	if ($('preview_format'))
		$('preview_format').value = ( msg.format == 'mime' ? 'html' : msg.format );
	// show format select?
	$('preview_format_box').className = ( msg.format == 'mime' ? 'adesk_inline' : 'adesk_hidden' );
	preview_menu_changed();
}

function preview_menu_changed() {
	// reload
	preview_menu_prepare();
}

function preview_menu_prepare() {
	preview_step_check();
	// set loader and issue an ajax call
	$('preview_message_loading').className = 'adesk_block';
	$('preview_message_info').className = 'adesk_hidden';
	$('preview_message_text').className = 'adesk_hidden';
	$('preview_message_html').className = 'adesk_hidden';
	$('preview_message_source_box').className = 'adesk_hidden';
	$('preview_message_source').value = '';
	// get opener's post
	var post = {};
	// add menu vars
	post.campaignid = preview_campaignid;
	post.messageid = preview_messageid;
	adesk_ajax_handle_text = preview_menu_prepare_cb_txt;
	adesk_ajax_post_cb("awebdeskapi.php", "campaign.campaign_preview", preview_menu_prepare_cb, post);
}

function preview_menu_prepare_cb_txt(txt) {
	o.adesk_ui_error_mailer(txt);
	if ( confirm(jsClosePopupError) ) window.close();
}

function preview_menu_prepare_cb(xml) {
	// now reset the text handler
	adesk_ajax_handle_text = null;
	var ary = adesk_dom_read_node(xml);

	if ( !ary.parts ) return;
	// hide loader and set the scene
	$('preview_message_loading').className = 'adesk_hidden';
	$('preview_message_info').className = 'adesk_block';
	if ($('preview_format')) {
		$('preview_message_text').className = ( $('preview_format').value == 'text' && ary.parts[0].text != '' ? 'adesk_block' : 'adesk_hidden' );
		$('preview_message_html').className = ( $('preview_format').value == 'html' && ary.parts[0].html != '' ? 'adesk_block' : 'adesk_hidden' );
		$('preview_images_box').className   = ( $('preview_format').value == 'html' && ary.parts[0].html != '' ? 'adesk_block' : 'adesk_hidden' );
	} else {
		$('preview_message_text').className = 'adesk_block';
		$('preview_message_html').className = 'adesk_hidden';
	}
	// populate info fields
	$('preview_message_from').innerHTML      = ary.from_name + ' &lt;' + ary.from_email + '&gt;';
	$('preview_message_to').innerHTML        = ary.to_name   + ' &lt;' + ary.to_email   + '&gt;';
	$('preview_message_subject').innerHTML   = ary.subject;
	adesk_dom_remove_children($('preview_message_attachments'));
	var attach = 0;
	for ( var i in ary.attachments ) {
		if ( typeof ary.attachments[i] != 'function' ) {
			var a = ary.attachments[i];
			var ac = 'adesk_attachment';
			if ( a.mimetype.indexOf('image') != -1 ) {
				ac = 'adesk_attachment_image';
			}
			$('preview_message_attachments').appendChild(
				Builder.node(
					'span',//'a',
					{ /*href: a.link,*/ className: ac },
					[ Builder._text(sprintf('%s (%s)', a.name, adesk_str_file_humansize(parseInt(a.size, 10)))) ]
				)
			);
			attach++;
		}
	}
	$('preview_message_attachments_box').className = ( attach > 0 ? 'adesk_block' : 'adesk_hidden' );
	// populate content fields
	$('preview_message_text').innerHTML = nl2br(ary.parts[0].text);
	$('preview_message_html').innerHTML = ary.parts[0].html;
	$('preview_message_source').value = ary.source;
	orig_message = ary.parts[0].html;
	if ( !show_images ) {
		show_images = true;
		images_toggle();
	}
}

function images_toggle() {
	show_images = !show_images;
	if ( !show_images ) {
		// hide images
		var clean_message = orig_message;
		var images = orig_message.match(/ src=".[^\"]*"/gi );
		if ( images ) {
			for ( var i = 0; i < images.length; i++ ) {
				clean_message = clean_message.replace(images[i], ' src="about:blank"');
			}
		}
		var images = orig_message.match(/ src='.[^\']*'/gi );
		if ( images ) {
			for ( var i = 0; i < images.length; i++ ) {
				clean_message = clean_message.replace(images[i], " src='about:blank'");
			}
		}
		$('preview_message_html').innerHTML = clean_message;
		$('preview_images_link').innerHTML  = jsImagesDisabled;
	} else {
		// show images
		$('preview_message_html').innerHTML = orig_message;
		$('preview_images_link').innerHTML  = jsImagesEnabled;
	}
}

{/literal}


adesk_dom_onload_hook(preview_menu_show);
