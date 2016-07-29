<?php /* Smarty version 2.6.12, created on 2016-07-08 14:14:43
         compiled from campaign_new.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'campaign_new.js', 1, false),array('modifier', 'alang', 'campaign_new.js', 10, false),array('modifier', 'js', 'campaign_new.js', 10, false),)), $this); ?>
var campaign_form_id = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['campaignid']), $this);?>
;

var isEdit = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['isEdit']), $this);?>
;
var showAllMessages = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['showAllMessages']), $this);?>
;
var isDemo = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['demoMode']), $this);?>
;

var campaign_actionid_readopen = 0;
var campaign_saving = false;

var campaign_str_andwillsend = '<?php echo ((is_array($_tmp=((is_array($_tmp='and will send')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_str_remindernote = '<?php echo ((is_array($_tmp=((is_array($_tmp="(Each subscriber will receive an email when their date field matches the conditions set for this campaign)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_str_selectone = '<?php echo ((is_array($_tmp=((is_array($_tmp='Select the message that you would like to use for this campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_str_selectmul = '<?php echo ((is_array($_tmp=((is_array($_tmp='Select the messages that you would like to use for this campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var campaign_str_approximately = '<?php echo ((is_array($_tmp=((is_array($_tmp='approximately')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_finish_str = '<?php echo ((is_array($_tmp=((is_array($_tmp='Finish')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_sendnow_str = '<?php echo ((is_array($_tmp=((is_array($_tmp='Send Now')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_rightnow_str = '<?php echo ((is_array($_tmp=((is_array($_tmp='Immediately')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_sendat_str = '<?php echo ((is_array($_tmp=((is_array($_tmp='at')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_sendspecial_str = '<?php echo ((is_array($_tmp=((is_array($_tmp="This campaign will be sent to every subscriber individually based on their subscription date/time.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_cancel_str = '<?php echo ((is_array($_tmp=((is_array($_tmp="This will delete this draft you are working on.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
\n\n' + jsAreYouSure;
var campaign_noname_str = '<?php echo ((is_array($_tmp=((is_array($_tmp='Please enter a name for this campaign before you continue')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_nolist_str = '<?php echo ((is_array($_tmp=((is_array($_tmp='Please select at least one list before you continue')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_nofilter_str =
	'<?php echo ((is_array($_tmp=((is_array($_tmp="You have indicated that you would use a list segment, but have not selected any.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
\n\n' +
	'<?php echo ((is_array($_tmp=((is_array($_tmp="- Press OK to continue WITHOUT applying a segment")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
\n' +
	'<?php echo ((is_array($_tmp=((is_array($_tmp="- Press CANCEL to stay and select a segment")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
'
;
var campaign_nomessage_str = '<?php echo ((is_array($_tmp=((is_array($_tmp='Please select a message to send before you continue')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_nomessages_str = '<?php echo ((is_array($_tmp=((is_array($_tmp='Please select at least two messages to send before you continue')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_splitsum_str = '<?php echo ((is_array($_tmp=((is_array($_tmp="Message ratios invalid. The combined value (sum) of all split messages in WINNER scenario has to be less than 100%.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_messageedit_str = '<?php echo ((is_array($_tmp=((is_array($_tmp="This will edit the message in a new window.\n\nDo you wish to continue?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_actionscnt_str = '<?php echo ((is_array($_tmp=((is_array($_tmp="%s Action(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_bouncesall_str = '<?php echo ((is_array($_tmp=((is_array($_tmp="- Use All Bounce Addresses -")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var campaign_nounsub_str = '<?php echo ((is_array($_tmp=((is_array($_tmp="Your unsubscription message should contain a personalization tag %UNSUBSCRIBELINK%.\n\nPlease add that before continuing.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_nounsub_warn_str = '<?php echo ((is_array($_tmp=((is_array($_tmp="Your unsubscription message should contain a personalization tag %UNSUBSCRIBELINK%.\n\nAre you sure you wish to continue without it?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var campaign_approve_str  = '<?php echo ((is_array($_tmp=((is_array($_tmp="(Submit for approval)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_demomode_str = '<?php echo ((is_array($_tmp=((is_array($_tmp="(Disabled in demo)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var campaign_message_search_found = '<?php echo ((is_array($_tmp=((is_array($_tmp="Search returned %s results.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var autosaveTimer = null;

var pageLoaded = false;

var stepb4 = null;

<?php if ($this->_tpl_vars['__ishosted']): ?>
var ourcustomhostedflag = true;
<?php endif; ?>

<?php echo '

function campaign_hosted_checkapproval() {
	adesk_ajax_call_cb("awebdeskapi.php", "approval.approval_hostedstatus", adesk_ajax_cb(campaign_hosted_checkapproval_cb), campaign_obj.id);
}

function campaign_hosted_checkapproval_cb(ary) {
	if (ary.waiting) {
		window.setTimeout(\'campaign_hosted_checkapproval()\', 3000);
		return;
	}

	if (ary.approved) {
		$("approvalqueue_waiting").hide();
		$("approvalqueue_sending").show();
	} else {
		$("approvalqueue_waiting").hide();
		switch (ary.message) {
			case "approved":
				$("approvalqueue_sending").show();
				break;

			case "declined":
				$("approvalqueue_declined").show();
				break;

			case "moreinfo":
				$("approvalqueue_moreinfo").show();
				break;

			case "pending":
				$("approvalqueue_pending").show();
				break;

			default:		// Really shouldn\'t get here.
				$("approvalqueue_waiting").show();
				break;
		}
	}
}

function step_update(movement) {
	var rel = $(\'campaign_new_progress\');
	var divs = rel.getElementsByTagName(\'div\');
	for ( var i = 0; i < divs.length; i++ ) {
		if ( i + 1 < campaign_obj.step ) {
			divs[i].className = \'done\';
		} else if ( i + 1 == campaign_obj.step ) {
			divs[i].className = \'selected\';
		} else {
			divs[i].className = \'\';
		}
		if ( $(\'step\' + (i + 1)) ) {
			if ((i+1) == 3 && movement == "dec") {
				adesk_dom_showif($(\'step\' + (i + 1) + \'_select\'), i + 1 == campaign_obj.step);
			} else if ((i+1) == 3 && movement == "inc") {
				$("messagenew").hide();
				$("usemessage").show();
				var select_cond = (
					i + 1 == campaign_obj.step && // is this step
					(
						campaign_obj.type == \'split\' || // is split
						campaign_messages_count() > 0 // campaign object already has some messages assigned
					)
				);
				var orig_cond = i + 1 == campaign_obj.step;
				adesk_dom_showif($(\'step\' + (i + 1) + \'_select\'), select_cond);
				adesk_dom_showif($(\'step\' + (i + 1)), orig_cond && !select_cond);
			} else {
				adesk_dom_showif($(\'step\' + (i + 1)), i + 1 == campaign_obj.step);
			}
		} else {
			//alert(\'Unknown step? This should not happen... \' + (i + 1));
		}
	}
}

function step_iscurrent() {
	if ( stepb4 == campaign_obj.step ) return false;
	stepb4 = campaign_obj.step;
	return true;
}

function campaign_has_readactions() {
	return typeof campaign_obj.readactions != "undefined" && typeof campaign_obj.readactions[0] != "undefined";
}

function step_forward() {
	var rel = $(\'step\' + campaign_obj.step + \'next\');
	if ( rel && rel.disabled ) {
		return;
	}
	if ( !step_iscurrent() ) return;
	//if ( $(\'step\' + campaign_obj.step).className != \'adesk_block\' ) return;
	if ( campaign_obj.step == 6 ) {
		if ( campaign_obj.type == \'single\' || campaign_obj.type == \'split\' ) {
			adesk_dom_showif($(\'finalsent\'), $(\'schedulenow\').checked && adesk_js_admin.send_approved);
			adesk_dom_showif($(\'finalscheduled\'), !$(\'schedulenow\').checked && adesk_js_admin.send_approved);
			if ( !$(\'schedulenow\').checked ) {
				$(\'finalscheduledtime\').innerHTML = campaign_obj.sdate;
			}
			$(\'finalfinished\').hide();
		} else if ( campaign_obj.type == \'recurring\' || campaign_obj.type == \'deskrss\' ) {
			$(\'finalsent\').hide();
			$(\'finalscheduled\').show();
			$(\'finalscheduledtime\').innerHTML = campaign_obj.sdate;
			$(\'finalfinished\').hide();
		} else { // responders/reminders/special
			$(\'finalsent\').hide();
			$(\'finalscheduled\').hide();
			$(\'finalfinished\').show();
		}
		$(\'reportbutton\').hide();
		campaign_form_save(true); // final save
	} else if ( campaign_obj.step == 5 ) {
		// summary

		// show or hide "Inbox Preview" link
		for ( var i in campaign_obj.messages ) {
			var m = campaign_obj.messages[i];
			if ( typeof m != \'function\' ) {
				if (m.format == "text") {
					$("inboxpreview_link_tr").style.display = "none";
				}
				else {
					$("inboxpreview_link_tr").style.display = "";
				}
			}
		}

		$(\'summary_campaign\').innerHTML = $(\'campaign_name\').value;
		// lists
		var lists = [];
		for ( var i in campaign_obj.lists ) {
			var l = campaign_obj.lists[i];
			if ( typeof l != \'function\' ) {
				lists.push(l.name);
			}
		}
		$(\'summary_lists\').innerHTML = lists.join(\'<br />\');

		// filter
		if ( !( $(\'usefilter\').checked && adesk_dom_radiochoice("filterField") ) ) {
			$(\'summary_filter\').innerHTML = jsNone;
		} else {
			$(\'summary_filter\').innerHTML = adesk_dom_radiotitle("filterField"); //$(\'filterField\').getElementsByTagName(\'option\')[$(\'filterField\').selectedIndex].innerHTML;
		}
		// messages (set them inside modals too)
		var testemailRel  = $(\'testemailsplit\');
		var emailcheckRel = $(\'spamcheckemailsplit\');
		adesk_dom_remove_children(testemailRel);
		if ( emailcheckRel ) adesk_dom_remove_children(emailcheckRel);
		var messages = [];
		for ( var i in campaign_obj.messages ) {
			var m = campaign_obj.messages[i];
			if ( typeof m != \'function\' ) {
				var msg = m.fromemail;
				if ( m.fromname != \'\' ) msg = \'"\' + m.fromname + \'" <\' + msg + \'>\';
				messages.push(
					\'<span onmouseover="adesk_tooltip_show(\\\'\' + adesk_b64_encode(msg) + \'\\\', 250, \\\'\\\', true);" onmouseout="adesk_tooltip_hide();">\' +
					strip_tags(m.subject, true) +
					\'</span>\' +
					\' (<a href="desk.php?action=message&onsave=close#form-\' + m.id + \'" onclick="if(confirm(campaign_messageedit_str))adesk_ui_openwindow(this.href);return false;">\' +
					jsOptionEdit + \'</a>)\'
				);
				// in modals
				testemailRel.appendChild(
					Builder.node(\'option\', { value: m.id }, [ Builder._text(strip_tags(m.subject, true)) ])
				);
				if ( emailcheckRel ) emailcheckRel.appendChild(
					Builder.node(\'option\', { value: m.id }, [ Builder._text(strip_tags(m.subject, true)) ])
				);
			}
		}
		testemailRel.selectedIndex  = ( campaign_obj.type == \'split\' ? -1 : 0 );
		if ( emailcheckRel ) emailcheckRel.selectedIndex = ( campaign_obj.type == \'split\' ? -1 : 0 );
		$(\'summary_messages\').innerHTML = messages.join(\'<br />\');
		// schedule
		if ( campaign_obj.type == \'single\' || campaign_obj.type == \'split\' ) {
			//adesk_dom_showif($(\'summary_schedule\'), !$(\'schedulenow\').checked);
			if ( $(\'schedulenow\').checked ) {
				$(\'summary_schedule\').innerHTML = campaign_rightnow_str;
			} else {
				campaign_obj.sdate = $(\'scheduledateField\').value.replace(/\\//g, \'-\') + \' \' + $(\'schedulehour\').value + \':\' + $(\'scheduleminute\').value;
				$(\'summary_schedule\').innerHTML = $(\'scheduledateField\').value + \' \' + campaign_sendat_str + \' \' + $(\'schedulehour\').value + \':\' + $(\'scheduleminute\').value;
			}
		} else if ( campaign_obj.type == \'recurring\' ) {
			campaign_obj.sdate = $(\'recurrdateField\').value.replace(/\\//g, \'-\') + \' \' + $(\'recurrhour\').value + \':\' + $(\'recurrminute\').value;
			$(\'summary_schedule\').innerHTML = $(\'recurrdateField\').value + \' \' + campaign_sendat_str + \' \' + $(\'recurrhour\').value + \':\' + $(\'recurrminute\').value + \' \' + campaign_str_andwillsend + \' \' + recurragain_options[$(\'recurragain\').value];
		} else if ( campaign_obj.type == \'deskrss\' ) {
			campaign_obj.sdate = $(\'deskrssdateField\').value.replace(/\\//g, \'-\') + \' \' + $(\'deskrsshour\').value + \':\' + $(\'deskrssminute\').value;
			$(\'summary_schedule\').innerHTML = $(\'deskrssdateField\').value + \' \' + campaign_sendat_str + \' \' + $(\'deskrsshour\').value + \':\' + $(\'deskrssminute\').value;
		} else { // responders/reminders/special
			$(\'summary_schedule\').innerHTML = campaign_sendspecial_str;
		}
		// recipients
		if (campaign_obj.wildcards) {
			$(\'summary_recipients\').innerHTML = campaign_obj.total_amt + " " + campaign_str_approximately;
		} else if (campaign_obj.type == \'reminder\') {
			$(\'summary_recipients\').innerHTML = campaign_obj.total_amt + " " + campaign_str_remindernote;
		} else {
			$(\'summary_recipients\').innerHTML = campaign_obj.total_amt;
		}
		// b4usend
		// final button
		var approvaladdon = ( adesk_js_admin.send_approved != 1 ? \' \' + campaign_approve_str : \'\' );
		var demoaddon = ( isDemo ? \' \' + campaign_demomode_str : \'\' );
		if ($(\'step6next\')) $(\'step6next\').value = ( ( campaign_obj.type == \'single\' || campaign_obj.type == \'split\' ) && $(\'schedulenow\').checked ? campaign_sendnow_str : campaign_finish_str ) + approvaladdon + demoaddon;
	} else if ( campaign_obj.step == 4 ) {
		// check if unsub is not found in message and not here in unsub fields
		if ( typeof ourcustomhostedflag == \'undefined\' ) {
			if ( !campaign_message_unsub_check(\'html\') && ( !$(\'includeunsubyes\').checked || !campaign_message_unsub_has(adesk_form_value_get($(\'unsubEditor\'))) ) ) {
				if ( adesk_js_admin.unsubscribelink == 1 ) {
					stepb4 = null;
					alert(campaign_nounsub_str);
					return;
				} else {
					if ( !confirm(campaign_nounsub_warn_str) ) {
						stepb4 = null;
						return;
					}
					//campaign_obj.htmlunsub = ( !campaign_message_unsub_check(\'html\') ? 1 : 0 );
					campaign_obj.htmlunsub = 0;
				}
			}
			if ( !campaign_message_unsub_check(\'text\') && ( !$(\'includeunsubyes\').checked || !campaign_message_unsub_has(adesk_form_value_get($(\'includeunsubtext\'))) ) ) {
				if ( adesk_js_admin.unsubscribelink == 1 ) {
					alert(campaign_nounsub_str);
					stepb4 = null;
					return;
				} else {
					if ( !confirm(campaign_nounsub_warn_str) ) {
						stepb4 = null;
						return;
					}
					//campaign_obj.textunsub = ( !campaign_message_unsub_check(\'text\') ? 1 : 0 );
					campaign_obj.textunsub = 0;
				}
			}
		}
		// panels
		adesk_dom_showif($(\'schedule_singlesplit\'), campaign_obj.type == \'single\' || campaign_obj.type == \'split\');
		adesk_dom_showif($(\'schedule_recurring\'), campaign_obj.type == \'recurring\');
		adesk_dom_showif($(\'schedule_responder1\'), campaign_obj.type == \'responder\');
		adesk_dom_showif($(\'schedule_responder2\'), campaign_obj.type == \'responder\' && !isEdit);
		adesk_dom_showif($(\'schedule_reminder\'), campaign_obj.type == \'reminder\');
		adesk_dom_showif($(\'schedule_deskrss\'), campaign_obj.type == \'deskrss\');
		// in panels
		if ( campaign_obj.type == \'single\' || campaign_obj.type == \'split\' ) {
			campaign_schedule_schedule_set();
		}
		if ( campaign_obj.type == \'recurring\' ) {
			campaign_schedule_recurring_set();
		}
		if ( campaign_obj.type == \'responder\' ) {
			campaign_schedule_responder_set();
		}
		if ( campaign_obj.type == \'reminder\' ) {
			campaign_schedule_reminder_set();
		}
		if ( campaign_obj.type == \'deskrss\' ) {
			campaign_schedule_deskrss_set(false);
		}
	} else if ( campaign_obj.step == 3 ) {

		if ( campaign_obj.type == \'split\' ) {
			// extract all messages, fetch them
			var messages = adesk_dom_boxchoice("messageField");
			// check if any messages are selected
			if ( messages.length < 2 ) {
				alert(campaign_nomessages_str);
				stepb4 = null;
				return;
			}
			if ( campaign_obj.split_type != \'even\' ) {
				if ( !message_split_sumup() ) {
					alert(campaign_splitsum_str);
					stepb4 = null;
					return;
				}
			}
		} else {
			// check if any messages are selected
			if (adesk_dom_boxempty("messageField")) {
				alert(campaign_nomessage_str);
				stepb4 = null;
				return;
			}
			var messages = adesk_dom_boxchoice("messageField");
		}


		var lists = adesk_dom_boxchoice("parentsList");
		// fetch the message (prepared)
		adesk_ui_api_call(jsLoading, 60);
		adesk_ajax_call_cb("awebdeskapi.php", "message.message_select_array_available", campaign_step3_cb, messages.join(\',\'), lists.join(\',\'));
		return;
	} else if ( campaign_obj.step == 2 ) {
		// check if any lists are selected
		var lists = adesk_dom_boxchoice("parentsList");

		if ( lists.length == 0 ) {
			alert(campaign_nolist_str);
			stepb4 = null;
			return;
		}
		// check if any filters are selected
		if ( $(\'usefilter\').checked && !adesk_dom_radiochoice("filterField") ) {
			if ( !confirm(campaign_nofilter_str) ) {
				stepb4 = null;
				return;
			}
		}
		var lists = adesk_dom_boxchoice("parentsList");
		var filterid = ( $(\'usefilter\').checked ? parseInt(adesk_dom_radiochoice(\'filterField\'), 10) : 0 );
		if ( isNaN(filterid) ) filterid = 0;
		// fetch the message (prepared)
		adesk_ui_api_call(jsCounting + \' \' + jsWait4AWhile, 0);
		adesk_ajax_call_cb("awebdeskapi.php", "campaign.campaign_subscribers", campaign_step2_cb, lists.join(\'-\'), filterid);
		return;
	} else {
		// check if name is set
		if ( $(\'campaign_name\').value == \'\' ) {
			alert(campaign_noname_str);
			stepb4 = null;
			return;
		}
		// set autosave
		campaign_autosave();
	}
	campaign_obj.step++;
	step_update("inc");
}

function step_back() {
	//if ( $(\'step\' + campaign_obj.step).className != \'adesk_block\' ) return;
	if ( !step_iscurrent() ) return;
	if ( campaign_obj.step == 7 ) {
		// there shouldn\'t be going back from this step
		return;
	} else if ( campaign_obj.step == 6 ) {
		//
	} else if ( campaign_obj.step == 5 ) {
		//
	} else if ( campaign_obj.step == 4 ) {
		//
	} else if ( campaign_obj.step == 3 ) {
		//
	} else if ( campaign_obj.step == 2 ) {
		//
	} else { // step=1
		// stop the autosaver?
		campaign_autosave_stop();
		campaign_obj.id = 0;
		$(\'form_id\').value = campaign_obj.id;
	}
	campaign_obj.step--;
	step_update("dec");
}

function step_reset() {
	//window.location.reload();
	window.location.href = \'desk.php?action=campaign\';
}

function step_cancel() {
	if ( !confirm(campaign_cancel_str) ) return;
	// stop the autosaver?
	campaign_autosave_stop();
	if ( campaign_obj.id > 0 ) {
		// remove this campaign
		adesk_ui_api_call(jsRemoving);
		adesk_ajax_call_cb("awebdeskapi.php", "campaign.campign_delete", step_reset, campaign_obj.id);
	} else {
		step_reset();
	}
}

// on the "Would you like to create a new message?" screen
function step_forward_message() {
	// if "Yes - I want to create a new message / email" radio is chosen
	if ($(\'step3_newmessage\').checked) {
		$(\'step3_select\').hide(); // hide message select list box
		$(\'h2_step3_1\').hide(); // hide the <h2> "Select the message that you would like to use for this campaign"
		campaign_message_create();
	}
	else {
		// if "No - I want to use an existing message (that I have already saved) for this campaign" radio is chosen
		$(\'step3_select\').show(); // show message select list box
		$(\'messagenew\').hide();
		$(\'usemessage\').show();
		$(\'step3buttons\').show();
		$(\'h2_step3_1\').show(); // show the <h2> "Select the message that you would like to use for this campaign"
	}
	$(\'step3\').hide();
}

function campaign_autosave() {
	if ( adesk_js_admin.autosave == 0 ) return;
	if ( !autosaveTimer ) autosaveTimer = window.setInterval(function() { campaign_form_save(false) }, adesk_js_admin.autosave * 1000);
}

function campaign_autosave_stop() {
	if ( adesk_js_admin.autosave == 0 ) return;
	if ( autosaveTimer ) clearInterval(autosaveTimer);
	autosaveTimer = false;
}

function campaign_form_save(finalSave) {
	campaign_saving = true;
	$(\'message_form_submit\').disabled = true;

	var checkagainst =
		"<!DOCTYPE html PUBLIC \\"-//W3C//DTD XHTML 1.0 Transitional//EN\\" \\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\\">\\n<html>\\n<head>\\n<title>My document title</title>\\n</head>\\n<body>\\n\\n</body>\\n</html>";

	if (campaign_obj.step < 6 && $("messagesubjectField").value != "" && adesk_form_value_get($("messageEditor")) != checkagainst) {
		message_form_save(campaign_step3_messageid, -1);
	} else {
		campaign_form_save_after(finalSave);
	}
}

function campaign_form_save_after(finalSave) {
	var post = campaign_post_prepare();

	campaign_saving = true;
	$(\'message_form_submit\').disabled = true;

	if ( typeof post.p == "undefined" || post.p.length == 0 ) {
		alert(campaign_nolist_str);
		return;
	}

	if ( finalSave ) {
		//finalSave = false;
		campaign_autosave_stop();
		adesk_ajax_handle_text = campaign_form_save_cb_txt;
	}
	var apilink = \'awebdeskapi.php?final=\' + ( finalSave ? 1 : 0 );

	adesk_ui_api_call(jsSaving, 0);

	if (campaign_obj.id > 0)
		adesk_ajax_post_cb(apilink, "campaign.campaign_update_post", campaign_form_save_cb, post);
	else
		adesk_ajax_post_cb(apilink, "campaign.campaign_insert_post", campaign_form_save_cb, post);
}

function campaign_form_save_cb_txt(txt) {
	adesk_ui_error_mailer(txt, \'\');
}

function campaign_form_save_cb(xml) {
	// now reset the text handler
	adesk_ajax_handle_text = null;
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded == 1) {
		adesk_result_show(ary.message);
		campaign_obj.id = ary.id;
		$(\'form_id\').value = campaign_obj.id;
		if ( !adesk_array_has([\'responder\', \'reminder\', \'special\'], campaign_obj.type) ) {
			$(\'reportbutton\').show();
		}
	} else {
		adesk_error_show(ary.message);
	}
	/*
	if ( finalSaveState ) {
		finalSaveState = false;
		campaign_autosave_stop();
	}
	*/

	campaign_saving = false;
	$(\'message_form_submit\').disabled = false;
}

function campaign_post_prepare() {
	var post = adesk_form_post($("campaignform"));
	// add link/read actions
	if (campaign_actionid_readopen > 0)
		post.actionid = campaign_actionid_readopen;
	if ( typeof post.linkmessage == \'undefined\' ) post.linkmessage = {};
	if ( typeof post.linkname    == \'undefined\' ) post.linkname    = {};
	if ( typeof post.linkurl     == \'undefined\' ) post.linkurl     = {};
	// add read (dummy) link
	if ( typeof post.p != "undefined" && post.p.length > 0 ) { // if lists are selected
		if ( ( post.campaign_type == \'split\' && post.messageid.length > 0 ) || post.messageid > 0 ) { // if message(s) is/are selected
			if ( $(\'trackreads\').checked && $(\'message_treads\').style.display != \'none\' ) {
				// add open link
				var linkid = campaign_actions_find(0, \'open\');
				post.linkmessage[linkid] = 0;
				post.linkname[linkid] = \'\';
				post.linkurl[linkid] = \'open\';
			}
		}
	}
	// add campaign id
	post.id = campaign_obj.id;
	// add sdate
	post.sdate = campaign_obj.sdate;
	// responder
	post.responder_offset = campaign_obj.responder_offset;
	post.responder_do_oldies = ( isEdit ? 0 : 1 );
	// link cleanup for mod_security probs
	post.htmlfetch = post.htmlfetch == \'http://\' ? \'\' : adesk_b64_encode(post.htmlfetch);
	post.textfetch = post.textfetch == \'http://\' ? \'\' : adesk_b64_encode(post.textfetch);
	for ( var i in post.linkurl ) {
		post.linkurl[i] = adesk_b64_encode(post.linkurl[i]);
	}

	return post;
}



function campaign_new_onload() {
	pageLoaded = true;
	var fetchfields = true;
	// lists preselect
	if ( campaign_new_listfilter && typeof(campaign_new_listfilter) == \'object\' ) {
		adesk_dom_boxset("parentsList", campaign_new_listfilter);
		campaign_step2_checknext();
	} else if ( campaign_new_listfilter > 0 ) {
		adesk_dom_boxset("parentsList", [ campaign_new_listfilter ]);
		campaign_step2_checknext();
	} else {
		adesk_dom_boxclear("parentsList");
		fetchfields = false;
	}
	/*
	// filter preselect
	if ( campaign_obj.filterid > 0 ) {
		$(\'filterField\').value = campaign_obj.filterid;
	} else {
		adesk_dom_radioclear("filterField");
	}
	// message preselect
	if ( campaign_new_messagefilter && typeof(campaign_new_messagefilter) == \'object\' ) {
		adesk_dom_boxset("messageField", campaign_new_messagefilter);
	} else if ( campaign_new_listfilter > 0 ) {
		adesk_dom_boxset("messageField", [ campaign_new_messagefilter ]);
	} else {
		adesk_dom_boxclear("messageField");
	}
	*/
	if ( campaign_obj.step > 1 ) {
		// do step1 stuff
		if ( campaign_obj.type != \'single\' ) campaign_type_set(campaign_obj.type);
		if ( campaign_obj.name && campaign_obj.name != \'\' ) {
			//$(\'campaign_name\').value = campaign_obj.name;
			$(\'step1next\').disabled = false;
		}
		campaign_obj.step = 1;
	}
	// set unsub personalizations
	//form_editor_personalization(\'conditionalfield\', [ \'subscriber\', \'sender\', \'system\' ], \'mime\', \'\');
	// get custom fields for the preselect value for add
	if ( fetchfields ) {
		customFieldsObj.fetch(0);
	} else {
		$(\'step2next\').disabled = true;
		$(\'campaignfilterbox\').hide();
	}
	if ( $(\'campaign_name\').value != \'\' ) {
		$(\'step1next\').disabled = false;
	}
	$("step1_loading").hide();
	$("step1").show();
	$(\'campaign_name\').focus();
}

function campaign_new_unload() {
	if ( campaign_obj.step > 2 && campaign_obj.step < 7 ) {
		return messageLP;
	}
}

function campaign_type_set(type) {
	$(\'campaign_type\').value = type;
	campaign_obj.type = type;
	$(\'campaign_type_single_radio\').checked = ( type == \'single\' ? true : false );
	$(\'campaign_type_responder_radio\').checked = ( type == \'responder\' ? true : false );
	if ( $(\'campaign_type_reminder_radio\') ) {
		$(\'campaign_type_reminder_radio\').checked = ( type == \'reminder\' ? true : false );
	}
	if ( $(\'campaign_type_deskrss_radio\') ) {
		$(\'campaign_type_deskrss_radio\').checked = ( type == \'deskrss\' ? true : false );
	}
	$(\'campaign_type_split_radio\').checked = ( type == \'split\' ? true : false );
	$(\'campaign_type_text_radio\').checked = (type == \'text\' ? true : false);

	return false;
}

function campaign_type_info(panel) {
	// hide already open ones
	var shown = $$(\'#typeinfo div\');
	var ids = [ \'typeinfosingle\', \'typeinforesponder\', \'typeinfosplit\', \'typeinforeminder\', \'typeinfodeskrss\' ];
	for ( var i = 0; i < shown.length; i++ ) {
		// if the ID of this <div> is in the allowed ids (array above), and it\'s display is NOT \'none\', hide it
		if ( adesk_array_has(ids, shown[i].id) && shown[i].style.display != \'none\' ) {
			shown[i].hide();
		}
	}
	// show the requested one
	$(\'typeinfo\' + panel).show();
	// show the modal
	$(\'typeinfo\').show();
}

function campaign_validate() {
	if ($("campaign_name").value == "") {
		alert(campaign_noname_str);
		return false;
	}

	return true;
}

// set onload
//adesk_dom_onload_hook(campaign_new_onload);
// set unload
//adesk_dom_unload_hook(campaign_new_unload);

'; ?>
