<?php /* Smarty version 2.6.12, created on 2016-07-18 15:47:30
         compiled from complaint.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'complaint.js', 3, false),array('modifier', 'js', 'complaint.js', 3, false),array('function', 'jsvar', 'complaint.js', 11, false),)), $this); ?>
// complaint.js

var abuse_str_view_campaign = '<?php echo ((is_array($_tmp=((is_array($_tmp='View Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var abuse_str_conf_reset = '<?php echo ((is_array($_tmp=((is_array($_tmp="This action will delete all abuse complaints!")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
\n\n<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you wish to reset all abuse complaints for this group?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var abuse_str_change_number = '<?php echo ((is_array($_tmp=((is_array($_tmp="Abuse Ratio has to be between zero (0) and a hundred (100).")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var abuse_str_notify_to_none = '<?php echo ((is_array($_tmp=((is_array($_tmp="No recipients have been selected.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var abuse_str_notify_from_none = '<?php echo ((is_array($_tmp=((is_array($_tmp="From e-mail address is not valid. Please provide a valid one.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var abuse_str_notify_subject_none = '<?php echo ((is_array($_tmp=((is_array($_tmp="Subject should not be empty. Please provide a subject.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var abuse_str_notify_message_none = '<?php echo ((is_array($_tmp=((is_array($_tmp="Notification message should not be empty. Please provide a message.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

<?php echo smarty_function_jsvar(array('name' => 'abuse','var' => $this->_tpl_vars['abuse']), $this);?>


<?php echo '

function abuse_toggle(panel) {
	adesk_dom_toggle_class(\'infobox\', \'adesk_block\', \'adesk_hidden\');
	adesk_dom_toggle_class(panel + \'box\', \'adesk_block\', \'adesk_hidden\');
	return false;
}

function abuse_change() {
	return abuse_toggle(\'change\');
}

function abuse_notify() {
	return abuse_toggle(\'notify\');
}

function abuse_view() {
	// if open, close it and exit
	if ( $(\'viewbox\').className != \'adesk_hidden\' ) {
		abuse_toggle(\'view\');
		return false;
	}

	// ajax call
	adesk_ui_api_call(jsLoading);
	adesk_ajax_call_cb(apipath, \'abuse.abuse_list\', abuse_view_cb, abuse.id, abuse.hash);
	return false;
}

function abuse_view_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	var rel = $(\'abusesbox\');
	adesk_dom_remove_children(rel);
	for ( var i = 0; i < ary.row.length; i++ ) {
		var row = ary.row[i];
		var nodes = [ ];
		nodes.push(Builder.node(\'div\', [ Builder._text(row.rdate) ]));
		nodes.push(
			Builder.node(
				\'div\',
				[
					Builder.node(
						\'a\',
						{ href: plink + \'/manage/desk.php?action=subscriber_view&id=\' + row.subscriberid },
						[ Builder._text(row.email) ]
					)
				]
			)
		);
		nodes.push(
			Builder.node(
				\'div\',
				[
					Builder.node(
						\'a\',
						{ href: plink + \'/manage/desk.php?action=report_campaign&id=\' + row.campaignid },
						[ Builder._text(abuse_str_view_campaign) ]
					)
				]
			)
		);
		rel.appendChild(Builder.node(\'div\', { className: \'abuse_row\' }, nodes));
	}

	// open the list
	abuse_toggle(\'view\');
}


function abuse_reset() {
	if ( !confirm(abuse_str_conf_reset) ) {
		return false;
	}
	// ajax call
	adesk_ui_api_call(jsResetting);
	adesk_ajax_call_cb(apipath, \'abuse.abuse_reset\', abuse_reset_cb, abuse.id, abuse.hash);
	return false;
}

function abuse_reset_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded == 1) {
		adesk_result_show(ary.message);
		$("current_label").innerHTML = \'0\';
		$("abuses_label").innerHTML = \'0\';
		// remove the reset and view buttons
		$("abuse_button_view").style.display = "none";
		$("abuse_button_reset").style.display = "none";
		abuse_toggle(\'reset\');
	} else {
		adesk_error_show(ary.message);
	}
}

function abuse_notify_send() {
	var post = adesk_form_post($(\'notifybox\'));
	post.id = abuse.id;
	post.hash = abuse.hash;

	// form check
	if ( typeof post.to == \'undefined\' ) {
		alert(abuse_str_notify_to_none);
		return false;
	}
	if ( !adesk_str_email(post.from_mail) ) {
		alert(abuse_str_notify_from_none);
		return false;
	}
	if ( post.subject == \'\' ) {
		alert(abuse_str_notify_subject_none);
		return false;
	}
	if ( post.message == \'\' ) {
		alert(abuse_str_notify_message_none);
		return false;
	}

	// ajax call
	adesk_ui_api_call(jsLoading);
	adesk_ajax_post_cb(apipath, \'abuse.abuse_notify\', abuse_notify_send_cb, post);
	return false;
}

function abuse_notify_send_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded == 1) {
		adesk_result_show(ary.message);
		abuse_toggle(\'notify\');
	} else {
		adesk_error_show(ary.message);
	}
}

function abuse_update() {
	var newval = parseInt($(\'group_abuseratio\').value, 10);
	if ( isNaN(newval) || newval < 0 || newval > 100 ) {
		alert(abuse_str_change_number);
		$(\'group_abuseratio\').focus();
		return false;
	}

	// ajax call
	adesk_ui_api_call(jsUpdating);
	adesk_ajax_call_cb(apipath, \'abuse.abuse_update\', abuse_update_cb, abuse.id, abuse.hash, newval);
	return false;
}

function abuse_update_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded == 1) {
		adesk_result_show(ary.message);
		var newval = $(\'group_abuseratio\').value;
		$(\'current_label\').innerHTML = newval;
		// hide overlimit actions
		//if ( parseInt($(\'current_label\').value, 10) > parseInt(newval, 10) ) {
			//
		//}
		abuse_toggle(\'change\');
		abuse_toggle(\'update\');
	} else {
		adesk_error_show(ary.message);
	}
}

'; ?>
