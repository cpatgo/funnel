var list_form_str_cant_insert = '{"You do not have permission to add lists"|alang|js}';
var list_form_str_cant_update = '{"You do not have permission to edit lists"|alang|js}';
var list_form_str_cant_find   = '{"List not found."|alang|js}';
var list_form_str_twitter1   = '{"Could not obtain permission from Twitter. Please try again shortly."|alang|js}';
var list_form_str_twitter2   = '{"Successfully confirmed permission from Twitter."|alang|js}';
var list_form_str_twitter3   = '{"Twitter authentication error"|alang|js}';
var list_form_str_twitter4   = '{"Connecting"|alang|js}';
var list_form_str_twitter5   = '{"Retrieving your Twitter account details"|alang|js}';
var list_form_str_twitter6   = '{"Copying Twitter tokens to all lists"|alang|js}';
var list_form_str_twitter7   = '{"Successfully mirrored Twitter tokens to all lists"|alang|js}';
var list_form_str_twitter8   = '{"Failed to mirror Twitter tokens to all lists"|alang|js}';
var list_form_str_twitter9   = '{"Are you sure you want to mirror this Twitter account to all lists? This will overwrite any existing Twitter accounts."|alang|js}';

var list_form_str_senderinfo       = '{"You can not continue until you enter Sender Information."|alang|js}';
var list_form_str_senderinfo_email = '{"Sender Information needs to contain postal, not e-mail address."|alang|js}';
var list_form_str_senderinfo_url   = '{"Sender Information needs to contain postal, not web address."|alang|js}';

var list_form_str_senderinfo_company = '{"Your Company"|alang|js}';
var list_form_str_senderinfo_address = '{"Address"|alang|js}';
var list_form_str_senderinfo_apt = '{"Apt/Suite"|alang|js}';
var list_form_str_senderinfo_city = '{"City"|alang|js}';
var list_form_str_senderinfo_state = '{"State"|alang|js}';
var list_form_str_senderinfo_zip = '{"Zip"|alang|js}';
var list_form_str_senderinfo_country = '{"Country"|alang|js}';

//adesk_editor_init_word_object.plugins += ",ota_personalize,ota_conditional,ota_template";
//adesk_editor_init_word_object.theme_advanced_buttons1_add += ",ota_personalize,ota_conditional,ota_template";

var sender_fields_actual = ['sendernameField', 'senderaddr1Field', 'senderaddr2Field', 'sendercityField', 'senderstateField', 'senderzipField', 'sendercountryField'];
var sender_fields_preview = ['company', 'address1', 'address2', 'city', 'state', 'zip', 'country'];
var sender_fields_default = [list_form_str_senderinfo_company, list_form_str_senderinfo_address, list_form_str_senderinfo_apt, list_form_str_senderinfo_city, list_form_str_senderinfo_state, list_form_str_senderinfo_zip, list_form_str_senderinfo_country];

{jsvar name=fields var=$fields}

{if $__ishosted}
var ourcustomhostedflag = true;
{/if}

{literal}
var list_form_id = -1;
var requestedtab = 'general';

function list_form_defaults() {
	$("form_id").value = 0;

	if (adesk_js_admin.pg_list_opt == 1) {
		//form_editor_personalization('optin', [ 'subscriber', 'sender', 'system' ], 'mime');
		//form_editor_personalization('optout', [ 'subscriber', 'sender', 'system' ], 'mime');
	}

	var values = {
		name: '', stringid: '', //fromemail: '', fromname: '', replyto: '',
		//descript: '',
		analyticsua: '',
		analyticssource: '',
		carboncopy: '',
		subscriptnotify: '',
		unsubscriptnotify: '',
		bounceid: 1,
		toname: strDefaultTO,
		optinoutid: 1,
		sendername: '',
		senderaddr1: '',
		senderaddr2: '',
		sendercity: '',
		senderstate: '',
		senderzip: '',
		sendercountry: '',
		senderphone: '',
		userid: adesk_js_admin.id
	};
	for ( var i in values ) {
		if ( !$(i + 'Field') ) {
			//alert(i);
		} else {
			$(i + 'Field').value = values[i];
		}
	}
	var checks = {
		//usetracking: false,
		analyticsread: false,
		analyticslink: false,
		twitter: false,
		facebook: false,
		//embedimg: false,
		privatelist: false,
		duplicatesend: false,
		duplicatesubscribe: false,
		requirename: false,
		unsubreason: false,
		lastbroadcast: false,
		usecaptcha: false
	};
	for ( var i in checks ) {
		if ( !$(i + 'PField') ) {
			//alert(i);
		} else {
			$(i + 'PField').checked = checks[i];
		}
	}
	// clean up analytics domains
	while ( $('analyticsClonerDiv').getElementsByTagName('div').length > 1 ) {
		remove_element($('analyticsClonerDiv').getElementsByTagName('div')[0]);
	}
	remove_element($('analyticsClonerDiv').getElementsByTagName('div')[0]);
	// optinout
	if (adesk_js_admin.pg_list_opt == 1) {
		optinoptout_defaults();
	}
	/*
	now show/hide parts of the page
	*/
	// general
	$('otherpanel').className = 'adesk_hidden';
	// permissions
	//$('limitspanel').className = 'h2_content_invis';
	//$('privilegespanel').className = 'h2_content_invis';
	// bounces
	if ( $('bounce') ) $('bounce').className = 'adesk_hidden';
	// analytics & twitter
	//$('main_tab_external').style.display = 'inline';
	$('analyticsread').className = 'adesk_hidden';
	$('analyticslink').className = 'adesk_hidden';
	$('twitter').className = 'adesk_hidden';
	$('twitter_confirmed').className = 'adesk_hidden';
	$('twitter_unconfirmed').className = '';
	$('twitter_token_diff').className = 'adesk_hidden';
	if ($('twitter_disabled')) $('twitter_disabled').hide();
	$('facebook').hide();
	$('facebook_confirmed').hide();
	$('facebook_unconfirmed').show();
	$('facebook_invalid').hide();

	$('list_title_add').show();
	$('list_title_edit').hide();

	// put default text for the sender preview address
	for (var i = 0; i < sender_fields_preview.length; i++) {
		var new_value = sender_fields_default[i];
		list_form_sender_livepreview(new_value, sender_fields_preview[i], 1);
	}
}


function list_form_load(id) {
	// Add/Edit Permission checks
	if (id > 0) {
		if (adesk_js_admin.pg_list_edit != 1) {
			adesk_ui_anchor_set(list_list_anchor());
			alert(list_form_str_cant_update);
			return;
		}
	}
	else {
		if (adesk_js_admin.pg_list_add != 1) {
			adesk_ui_anchor_set(list_list_anchor());
			alert(list_form_str_cant_insert);
			return;
		}
	}

	// List Limit check
	if (!canAddList && id == 0) {
		adesk_ui_anchor_set(list_list_anchor());
		alert(list_str_list_limit);
		return;
	}

	list_form_defaults();
	list_form_id = id;

	if (id > 0) {
		adesk_ui_api_call(jsLoading);
		$("form_submit").className = "adesk_button_update";
		$("form_submit").value = jsUpdate;
		$("external").className = "adesk_block";
		//$("admin_side").show();
		adesk_ajax_call_cb("awebdeskapi.php", "list.list_select_row", list_form_load_cb, id);
	}
	else {
		$("form_submit").className = "adesk_button_add glc_button glc_btn_large";
		$("form_submit").value = jsAdd;
		$("form").className = "adesk_block";
		$("external").className = "adesk_hidden";
		//list bug fix by Sandeep Kumar
		//$("admin_side").hide();;
		$('facebook_unconfirmed').hide();
		$('facebook_invalid').show();
	}
}


function list_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		var list_form_load_error_msg = (ary.message) ? ary.message : list_form_str_cant_find;
		adesk_error_show(list_form_load_error_msg);
		adesk_ui_anchor_set(list_list_anchor());
		return;
	}

	list_form_id = ary.id;

	$("form_id").value = ary.id;

	/*
	set ary values here
	*/
	// general
	$('stringidField').value = ary.stringid;
	$('nameField').value = ary.name;
	//$('fromemailField').value = ary.from_email;
	//$('fromnameField').value = ary.from_name;
	//$('replytoField').value = ary.reply2;
	//$('descriptField').value = ary.descript;
	// user group access
	// permissions
	//$('usetrackingPField').checked = ary.p_use_tracking == 1;
	$('analyticsreadPField').checked = ary.p_use_analytics_read == 1;
	$('analyticsuaField').value = ary.analytics_ua;
	$('analyticslinkPField').checked = ary.p_use_analytics_link == 1;
	$('analyticssourceField').value = ary.analytics_source;
	// filling analytics domains here
	if ( ary.analytics_domains.length > 0 ) {
		var domains = ary.analytics_domains.split('\n');
		for ( var i = 0; i < domains.length; i++ ) {
			if ( i > 0 ) clone_1st_div($('analyticsClonerDiv'));// first one is already there
			var alldivs = $('analyticsClonerDiv').getElementsByTagName('div');
			if ( alldivs[i] ) {
				alldivs[i].getElementsByTagName('input')[0].value = domains[i];
			}
		}
	}

	$('twitterPField').checked = ary.p_use_twitter == 1;
	$('twitter').show();
	$('twitter_checkbox').show();
	if (ary.twitter_token && ary.twitter_token_secret) {
		adesk_ui_api_call(list_form_str_twitter5 + "...");
		adesk_ajax_call_cb("awebdeskapi.php", "list.list_twitter_oauth_verifycredentials", list_form_external_twitter_verifycredentials_cb, ary.twitter_token, ary.twitter_token_secret);
	}
	else {
		if (!adesk_js_site.twitter_consumer_key || !adesk_js_site.twitter_consumer_secret) {
			$('twitter').hide();
			$('twitter_checkbox').hide();
		}
		else {
			adesk_ajax_call_cb("awebdeskapi.php", "list.list_twitter_oauth_init2", list_twitter_oauth_init2_cb, ary.id);
		}
	}
	
	$('facebookPField').checked = (ary.p_use_facebook == 1 || ary.facebook_oauth_me);
	$('facebook').hide();
	if (ary.p_use_facebook == 1) $('facebook').show();
	$('facebook_confirmed').hide();
	$('facebook_unconfirmed').show();
	$('facebook_invalid').hide();
	if (ary.facebook_oauth_me) {
		// session valid for facebook
		$('facebook').show();
		$('facebook_confirmed').show();
		$('facebook_unconfirmed').hide();
		$('facebook_account_profile').href = ary.facebook_oauth_me[0].link;
		$('facebook_account_profile').innerHTML = ary.facebook_oauth_me[0].name + ' (' + ary.facebook_oauth_me[0].email + ')';
		$('facebook_account_logout_url').href = ary.facebook_oauth_logout_url;
	}
	else if (!ary.facebook_oauth_login_url && ary.facebook_oauth_logout_url) {
		// sometimes the login URL is not set (for example, if a user changes their Facebook password after connecting their account in EM)
		$('facebook_confirmed').show();
		$('facebook_unconfirmed').hide();
		$('facebook_account_logout_url').href = ary.facebook_oauth_logout_url;
	}
	else {
		// session NOT valid for facebook - they need to log in
		$('facebook_account_login_url').href = ary.facebook_oauth_login_url;
	}

	//$('embedimgPField').checked = ary.p_embed_image == 1;
	$('carboncopyField').value = ary.carboncopy;
	$('privatelistPField').checked = ary.private == 1;
	$('duplicatesendPField').checked = ary.p_duplicate_send == 1;
	$('duplicatesubscribePField').checked = ary.p_duplicate_subscribe == 1;
	$('requirenamePField').checked = ary.require_name == 1;
	$('unsubreasonPField').checked = ary.get_unsubscribe_reason == 1;
	$('lastbroadcastPField').checked = ary.send_last_broadcast == 1;
	$('usecaptchaPField').checked = ary.p_use_captcha == 1;
	$('subscriptnotifyField').value = ary.subscription_notify;
	$('unsubscriptnotifyField').value = ary.unsubscription_notify;
	$('useridField').value = ary.luserid;
	$('tonameField').value = ary.to_name;
	// optinoptout
	$('optinoutidField').value = ary.optinoptout;
	//optinoptout_update(ary);
	// sender info
	$('sendernameField').value = ary.sender_name;
	$('senderaddr1Field').value = ary.sender_addr1;
	$('senderaddr2Field').value = ary.sender_addr2;
	$('sendercityField').value = ary.sender_city;
	$('senderstateField').value = ary.sender_state;
	$('senderzipField').value = ary.sender_zip;
	$('sendercountryField').value = ary.sender_country;
	$('senderphoneField').value = ary.sender_phone;
	/*
	var rel = $('optinoutidField');
	if ( ary.optsets ) {
		var options = rel.getElementsByTagName('option');
		for ( var i = 0; i < options.length; i++ ) {
			var o = options[i];
			var found = false;
			for ( var j in ary.optsets ) {
				var b = ary.optsets[j];
				if ( b.id == o.value ) {
					found = true;
					break;
				}
			}
			o.selected = found;
		}
	} else {
		rel.value = 1;
	}
	*/
	// bounce
	if (adesk_js_admin.pg_list_bounce) {
		var rel = $('bounceidField');
		if ( ary.bounces ) {
			var options = rel.getElementsByTagName('option');
			for ( var i = 0; i < options.length; i++ ) {
				var o = options[i];
				var found = false;
				for ( var j in ary.bounces ) {
					var b = ary.bounces[j];
					if ( b.id == o.value ) {
						found = true;
						break;
					}
				}
				o.selected = found;
			}
		} else {
			rel.value = 1;
		}
	}

	// set the stage
	//$('main_tab_external').style.display = ( ary.p_use_tracking == 1 ? 'inline' : 'none' );
	$('analyticsread').className = ( ary.p_use_analytics_read == 1 ? 'adesk_block' : 'adesk_hidden' );
	$('analyticslink').className = ( ary.p_use_analytics_link == 1 ? 'adesk_block' : 'adesk_hidden' );
	$('twitter').className = ( ary.p_use_twitter == 1 ? 'adesk_block' : 'adesk_hidden' );

	$('list_title_add').hide();
	$('list_title_edit').show();

	// sender preview: use the text from the textboxes if anything is there. if not, use the default value
	for (var i = 0; i < sender_fields_actual.length; i++) {
		if ( $(sender_fields_actual[i]).value != "" ) {
			var new_value = $(sender_fields_actual[i]).value;
		}
		else {
			var new_value = sender_fields_default[i];
		}
		list_form_sender_livepreview(new_value, sender_fields_preview[i], 1);
	}

	$("form").className = "adesk_block";
}

function list_form_save(id) {
	var post = adesk_form_post($("form"));
	if ( post.name == '' ) {
		//adesk_ui_anchor_set(list_form_anchor(id, 'general'));
		alert(strListNameEmpty);
		$('nameField').focus();
		return;
	}
	/* deprecated
	if ( !adesk_str_email(post.from_email) ) {
		//adesk_ui_anchor_set(list_form_anchor(id, 'general'));
		alert(strListEmailNotEmail);
		$('fromemailField').focus();
		return;
	}
	*/

	if ( typeof ourcustomhostedflag != 'undefined' || adesk_js_admin.forcesenderinfo ) {
		if ( post.sender_name == '' || post.sender_addr1 == '' || post.sender_city == '' || post.sender_zip == '' || post.sender_country == '' ) {
			//adesk_ui_anchor_set(list_form_anchor(id, 'general'));
			alert(list_form_str_senderinfo);
			$('sendernameField').focus();
			return;
		}
		if (
			adesk_str_email(post.sender_name) ||
			adesk_str_email(post.sender_addr1) ||
			adesk_str_email(post.sender_addr2) ||
			adesk_str_email(post.sender_zip) ||
			adesk_str_email(post.sender_city) ||
			adesk_str_email(post.sender_country)
		) {
			alert(list_form_str_senderinfo_email);
			$('sendernameField').focus();
			return;
		}
		/*
		if (
			adesk_str_url(post.sender_name) ||
			adesk_str_url(post.sender_addr1) ||
			adesk_str_url(post.sender_addr2) ||
			adesk_str_url(post.sender_zip) ||
			adesk_str_url(post.sender_city) ||
			adesk_str_url(post.sender_country)
		) {
			alert(list_form_str_senderinfo_url);
			$('sendernameField').focus();
			return;
		}
		*/
	}

	if ( typeof(post.p_use_analytics_read) != 'undefined' && !post.analytics_ua.match(/^UA-\d+-\d+$/i) ) {
		//adesk_ui_anchor_set(list_form_anchor(id, 'external'));
		alert(strListAnalyticsUAEmpty);
		$('analyticsuaField').focus();
		return false;
	}

	if ( typeof(post.p_use_twitter) != 'undefined' ) {
		if ( post.twitter_user == '' ) {
			//adesk_ui_anchor_set(list_form_anchor(id, 'external'));
			alert(strListTwitterUserEmpty);
			$('twitteruserField').focus();
			return false;
		}
		if ( post.twitter_pass == '' ) {
			//adesk_ui_anchor_set(list_form_anchor(id, 'external'));
			alert(strListTwitterPassEmpty);
			$('twitterpassField').focus();
			return false;
		}
	}

	adesk_ui_api_call(jsSaving);

	if (id > 0)
		adesk_ajax_post_cb("awebdeskapi.php", "list.list_update_post", list_form_save_cb, post);
	else
		adesk_ajax_post_cb("awebdeskapi.php", "list.list_insert_post", list_form_save_cb, post);
}

function list_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		requestedtab = null;
		list_form_id = -1;
		if(xml.localName=="list_insert_post") {
			adesk_dom_display_block('added');
		}
		adesk_ui_anchor_set(list_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}









function list_form_optinout_edit(show) {
	adesk_form_disable('optinform', !show);
	adesk_form_disable('optinselectors', false);
	adesk_form_disable('optoutform', !show);
	adesk_form_disable('optoutselectors', false);
}



function list_form_anchor(id, tab) {
	if ( !id ) id = list_form_id;
	if ( !tab ) tab = requestedtab;
	return sprintf("form-%s-%s", id, tab);
}



function list_groups_update(data) {
	var holder = $('listGroupsBox');
	var divs = holder.getElementsByTagName('div');
	for ( var i = 0; i < divs.length; i++ ) {
		var gh = divs[i]; // group holder
		if ( gh.id.substr(0, 11) == 'groupRelDiv' ) {
			var gid = gh.id.substr(11);
			// this group exists
			var datagroup = false;
			if ( data.groups ) {
				for ( var k in data.groups ) {
					if ( data.groups[k].id == gid ) {
						datagroup = data.groups[k];
						break;
					}
				}
			}
			var gp = gh.getElementsByTagName('input');
			for ( var j = 0; j < gp.length; j++ ) {
				var gpi = gp[j]; // group holder
				if ( gpi.name.substr(0, 2) == 'g[' ) {
					// group check
					gpi.checked = ( datagroup ? true : false );
				} else if ( gpi.name.substr(0, 3) == 'gp[' ) {
					// sections
					var section = gpi.name.substr(5 + gid.length); // 'gp[]['.length = 5
					section = section.substr(0, section.length - 1);
					gpi.checked = ( datagroup && datagroup[section] );
				}
			}
		}
	}
}

/*
function list_form_analytics_toggle(show) {
	$('main_tab_external').style.display = ( show ? 'inline' : 'none' );
}
*/

function list_form_external_twitter_verifycredentials_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.error) {
		adesk_error_show(list_form_str_twitter3 + ": " + ary.error);
		$('twitter').hide();
		$('twitter_checkbox').hide();
		$('twitter_disabled').show();
		return;
	}

	$("twitter_confirmed_screenname").href = "http://twitter.com/" + ary.screen_name;
	$("twitter_confirmed_screenname").innerHTML = ary.screen_name;
	$("twitter_confirmed").className = "";
	$("twitter_unconfirmed").className = "adesk_hidden";
	$("twitter_token_diff").className = (ary.diff) ? "" : "adesk_hidden";
}

function list_form_external_twitter_confirm() {
	var post = adesk_form_post($("form"));
	if (post.twitter_oauth_pin == "") {
		alert("Please provide a PIN number");
		return false;
	}
	post.savetodb = 1;
	adesk_ui_api_call(list_form_str_twitter4 + "...");
	adesk_ajax_post_cb("awebdeskapi.php", "list.list_twitter_oauth_getaccesstoken", list_form_external_twitter_confirm_cb, post);
}

function list_form_external_twitter_confirm_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if ( ary.oauth_token == "" || ary.oauth_token_secret == "" ) {
		adesk_error_show(list_form_str_twitter1);
	}
	else {
		adesk_result_show(list_form_str_twitter2);
		adesk_ui_api_call(list_form_str_twitter5 + "...");
		adesk_ajax_call_cb("awebdeskapi.php", "list.list_twitter_oauth_verifycredentials", list_form_external_twitter_verifycredentials_cb, ary.oauth_token, ary.oauth_token_secret);
		// show or hide the link "Update this Twitter account for all lists" - depending on if any other list has different tokens already set
		$("twitter_token_diff").className = (ary.diff) ? "" : "adesk_hidden";
	}
}

// mirrors tokens from one list to all other lists
function list_form_external_twitter_mirror() {
	if (!confirm(list_form_str_twitter9)) {
		return;
	}
	var post = adesk_form_post($("form"));
	adesk_ui_api_call(list_form_str_twitter6 + "...");
	adesk_ajax_post_cb("awebdeskapi.php", "list.list_twitter_token_mirror", list_form_external_twitter_mirror_cb, post);
}

function list_form_external_twitter_mirror_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if (ary.succeeded) {
		adesk_result_show(list_form_str_twitter7);
		$("twitter_token_diff").className = "adesk_hidden";
	}
	else {
		adesk_error_show(list_form_str_twitter8);
	}
}

function list_twitter_oauth_init2_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	
	if (ary.error) {
		adesk_error_show(ary.error);
		$('twitter').hide();
		$('twitter_checkbox').hide();
		$('twitter_disabled').show();
	}
	else {
		$('twitter_register_url').href = ary.register_url;
	}
}

function list_form_external_facebook_toggle() {
	if ( $('facebook').style.display == '' ) {
		$('facebook').hide();
	}
	else {
		$('facebook').show();
	}
}

// sender preview: shows a live preview when typing
function list_form_sender_livepreview(new_value, section, modify) {
	// modify can be 1 (overwrite) or 2 (append)
	if (modify == 1) {
		$('sender_' + section + '_display').innerHTML = new_value;
	}
	else if (modify == 2) {
		$('sender_' + section + '_display').innerHTML += new_value;
	}
}

{/literal}
