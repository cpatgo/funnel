<?php /* Smarty version 2.6.12, created on 2016-07-08 14:06:08
         compiled from strings.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'strings.js', 5, false),array('modifier', 'js', 'strings.js', 5, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "adesk_strings.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


var campaign_status_strings = [
	'<?php echo ((is_array($_tmp=((is_array($_tmp='Draft')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	'<?php echo ((is_array($_tmp=((is_array($_tmp='Scheduled')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	'<?php echo ((is_array($_tmp=((is_array($_tmp='Sending')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	'<?php echo ((is_array($_tmp=((is_array($_tmp='Paused')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	'<?php echo ((is_array($_tmp=((is_array($_tmp='Stopped')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	'<?php echo ((is_array($_tmp=((is_array($_tmp='Completed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	'<?php echo ((is_array($_tmp=((is_array($_tmp='Disabled')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	'<?php echo ((is_array($_tmp=((is_array($_tmp='Pending Approval')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
'
];

<?php echo '
var campaign_split_offsets = {
'; ?>

	hour: '<?php echo ((is_array($_tmp=((is_array($_tmp="hour(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	day: '<?php echo ((is_array($_tmp=((is_array($_tmp="days(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	week: '<?php echo ((is_array($_tmp=((is_array($_tmp="week(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	month: '<?php echo ((is_array($_tmp=((is_array($_tmp="months(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
'
<?php echo '
};

var campaign_split_types = {
'; ?>

	click: '<?php echo ((is_array($_tmp=((is_array($_tmp='CLICK')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	read: '<?php echo ((is_array($_tmp=((is_array($_tmp='READ')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
'
<?php echo '
};

var campaign_type_strings = {
'; ?>

	single: '<?php echo ((is_array($_tmp=((is_array($_tmp="One-Time Mailing")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	recurring: '<?php echo ((is_array($_tmp=((is_array($_tmp='Recurring Mailing')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	responder: '<?php echo ((is_array($_tmp=((is_array($_tmp='AutoResponder')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	reminder: '<?php echo ((is_array($_tmp=((is_array($_tmp='AutoReminder')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	split: '<?php echo ((is_array($_tmp=((is_array($_tmp='Split Test')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	deskrss: '<?php echo ((is_array($_tmp=((is_array($_tmp='RSS Mailing')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	text: '<?php echo ((is_array($_tmp=((is_array($_tmp="Text-Only Mailing")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
'
<?php echo '
};

var campaign_types_strings = {
'; ?>

	single: '<?php echo ((is_array($_tmp=((is_array($_tmp="One-Time Mailings")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	recurring: '<?php echo ((is_array($_tmp=((is_array($_tmp='Recurring Mailings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	responder: '<?php echo ((is_array($_tmp=((is_array($_tmp='AutoResponders')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	reminder: '<?php echo ((is_array($_tmp=((is_array($_tmp='AutoReminders')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	split: '<?php echo ((is_array($_tmp=((is_array($_tmp='Split Tests')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	deskrss: '<?php echo ((is_array($_tmp=((is_array($_tmp='RSS Mailings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
'
<?php echo '
};
'; ?>



var jsOptionResendUse = '<?php echo ((is_array($_tmp=((is_array($_tmp='Resend')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var jsAddAndSend                            = '<?php echo ((is_array($_tmp=((is_array($_tmp="Add & Send Campaign")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsUpdateAndSend                         = '<?php echo ((is_array($_tmp=((is_array($_tmp="Update & Send Campaign")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';


var strEmailNotEmail = '<?php echo ((is_array($_tmp=((is_array($_tmp="Email Address is not valid.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strURLNotURL = '<?php echo ((is_array($_tmp=((is_array($_tmp="URL is not valid.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

// add admin side strings here
var strListNameEmpty = '<?php echo ((is_array($_tmp=((is_array($_tmp="List Name can not be empty.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strListNotEmail = '<?php echo ((is_array($_tmp=((is_array($_tmp="List Email Address has to contain an email address.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strListAnalyticsUAEmpty = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please enter Google Analytics UA Number in the following format:\n\nUA-xxxxxxx-y")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var strListTwitterUserEmpty = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please enter your Twitter Username to use Twitter notifications.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strListTwitterPassEmpty = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please enter your Twitter Password to use Twitter notifications.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var strHeaderTitleEmpty = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please name this Email Header.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strHeaderNameEmpty = '<?php echo ((is_array($_tmp=((is_array($_tmp="Email Header Name can not be left blank.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strHeaderNameInvalid = '<?php echo ((is_array($_tmp=((is_array($_tmp="You can not use a restricted Email Header Name.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strHeaderValueEmpty = '<?php echo ((is_array($_tmp=((is_array($_tmp="Email Header Value can not be left blank.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
//var strListEmpty = '<?php echo ((is_array($_tmp=((is_array($_tmp="")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
//var strListEmpty = '<?php echo ((is_array($_tmp=((is_array($_tmp="")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var strSubscriberNotEmail = '<?php echo ((is_array($_tmp=((is_array($_tmp="Subscriber Email Address is not valid.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';


var strSubscriberRuleLong = '<?php echo ((is_array($_tmp=((is_array($_tmp="Automatically %s list '%s' when subscriber %s list '%s'")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strSubscriberRuleSourceSub = '<?php echo ((is_array($_tmp=((is_array($_tmp='subscribes to')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strSubscriberRuleSourceUnsub = '<?php echo ((is_array($_tmp=((is_array($_tmp='unsubscribes from')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strSubscriberRuleTargetSub = '<?php echo ((is_array($_tmp=((is_array($_tmp='subscribe to')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strSubscriberRuleTargetUnsub = '<?php echo ((is_array($_tmp=((is_array($_tmp='unsubscribe from')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strSubscriberRuleSub = '<?php echo ((is_array($_tmp=((is_array($_tmp='subscribe')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strSubscriberRuleUnsub = '<?php echo ((is_array($_tmp=((is_array($_tmp='unsubscribe')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strSubscriberRuleWrong = '<?php echo ((is_array($_tmp=((is_array($_tmp='You cannot select the same list as both source and destination')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';


var strOptNameEmpty = '<?php echo ((is_array($_tmp=((is_array($_tmp="Email Confirmation Set can not be left empty. Please name this set.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var strOptInNeeded = '<?php echo ((is_array($_tmp=((is_array($_tmp="Email Confirmation Set needs to have Opt-In set up.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strOptInEmailNotEmail = '<?php echo ((is_array($_tmp=((is_array($_tmp="Opt-in Email Address is not valid.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strOptInSubjectEmpty = '<?php echo ((is_array($_tmp=((is_array($_tmp="Opt-in Email Subject can not be left empty.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strOptInTextConfirmMissing = '<?php echo ((is_array($_tmp=((is_array($_tmp="Opt-in Text version does not contain a confirmation link.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strOptInHTMLConfirmMissing = '<?php echo ((is_array($_tmp=((is_array($_tmp="Opt-in HTML version does not contain a confirmation link.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strOptOutEmailNotEmail = '<?php echo ((is_array($_tmp=((is_array($_tmp="Opt-out Email Address is not valid.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strOptOutSubjectEmpty = '<?php echo ((is_array($_tmp=((is_array($_tmp="Opt-out Email Subject can not be left empty.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strOptOutTextConfirmMissing = '<?php echo ((is_array($_tmp=((is_array($_tmp="Opt-out Text version does not contain a confirmation link.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strOptOutHTMLConfirmMissing = '<?php echo ((is_array($_tmp=((is_array($_tmp="Opt-out HTML version does not contain a confirmation link.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strOptInSubjectDefault = '<?php echo ((is_array($_tmp=((is_array($_tmp='Please confirm your subscription')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strOptOutSubjectDefault = '<?php echo ((is_array($_tmp=((is_array($_tmp='Please confirm your unsubscription')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var strMessageEmailNotEmail = '<?php echo ((is_array($_tmp=((is_array($_tmp="From Email Address is not valid.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strMessageSubjectEmpty = '<?php echo ((is_array($_tmp=((is_array($_tmp="Email Subject can not be left empty.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strMessageTextConfirmMissing = '<?php echo ((is_array($_tmp=((is_array($_tmp="Text version does not contain an unsubscription link.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strMessageHTMLConfirmMissing = '<?php echo ((is_array($_tmp=((is_array($_tmp="HTML version does not contain an unsubscription link.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var strMessageReadsTimes = '<?php echo ((is_array($_tmp=((is_array($_tmp="Read/Opened")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strMessageReadsTitle = '<?php echo ((is_array($_tmp=((is_array($_tmp="Read(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strMessageClicksTimes = '<?php echo ((is_array($_tmp=((is_array($_tmp="Link(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strMessageClicksTitle = '<?php echo ((is_array($_tmp=((is_array($_tmp="Click(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strMessageForwardsDate = '<?php echo ((is_array($_tmp=((is_array($_tmp='Date')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strMessageForwardsTo = '<?php echo ((is_array($_tmp=((is_array($_tmp="Forward(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';


var strMailingFullStats = '<?php echo ((is_array($_tmp=((is_array($_tmp='Full report for this mailing')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strResponderFullStats = '<?php echo ((is_array($_tmp=((is_array($_tmp="Full report for this auto-responder")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';



var strTrackUpdateSubscriberName = '<?php echo ((is_array($_tmp=((is_array($_tmp='Update Subscriber Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strTrackWebCopyName = '<?php echo ((is_array($_tmp=((is_array($_tmp='Web Copy Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strTrackForward2FriendName = '<?php echo ((is_array($_tmp=((is_array($_tmp='Forward to a Friend Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';



var strPersGlobalTemplates         = '<?php echo ((is_array($_tmp=((is_array($_tmp='System Templates')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersListTemplates           = '<?php echo ((is_array($_tmp=((is_array($_tmp="List-Related Templates")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersSubscriberTags          = '<?php echo ((is_array($_tmp=((is_array($_tmp='Subscriber Personalization Tags')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersMessageTags             = '<?php echo ((is_array($_tmp=((is_array($_tmp='Message Option and Link Tags')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersSocialTags              = '<?php echo ((is_array($_tmp=((is_array($_tmp='Social Media Tags')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersOtherTags               = '<?php echo ((is_array($_tmp=((is_array($_tmp='Other Tags')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersSubscriberTags          = '<?php echo ((is_array($_tmp=((is_array($_tmp='Subscriber Personalization Tags')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersSubscriberTags          = '<?php echo ((is_array($_tmp=((is_array($_tmp='Subscriber Personalization Tags')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersSenderTags              = '<?php echo ((is_array($_tmp=((is_array($_tmp='Sender Personalization Tags')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var strPersListFields = '<?php echo ((is_array($_tmp=((is_array($_tmp="List-Related Fields")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersConfirmLink = '<?php echo ((is_array($_tmp=((is_array($_tmp='Confirmation Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersUnsubLink = '<?php echo ((is_array($_tmp=((is_array($_tmp='Unsubscribe Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersUnsubLink_DESC = '<?php echo ((is_array($_tmp=((is_array($_tmp="Unsubscribes the subscriber from the list used to send the email.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersUnsubLinkAll = '<?php echo ((is_array($_tmp=((is_array($_tmp="Unsubscribe Link (All Lists)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersUnsubLinkAll_DESC = '<?php echo ((is_array($_tmp=((is_array($_tmp="Unsubscribe from all lists. Even lists not included in the email sent.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersUpdateLink = '<?php echo ((is_array($_tmp=((is_array($_tmp='Update Subscription Account Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersUpdateLink_DESC = '<?php echo ((is_array($_tmp=((is_array($_tmp="Allow subscribers to update their subscription details.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersWCopyLink = '<?php echo ((is_array($_tmp=((is_array($_tmp='Web Version Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersWCopyLink_DESC = '<?php echo ((is_array($_tmp=((is_array($_tmp="Allow subscribers to view the email in their browser.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersFriendLink = '<?php echo ((is_array($_tmp=((is_array($_tmp='Send to Friend Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersFriendLink_DESC = '<?php echo ((is_array($_tmp=((is_array($_tmp="Your subscribers can forward the email to multiple people.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersSocialLink = '<?php echo ((is_array($_tmp=((is_array($_tmp='Social Submit Links')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersSocialFacebookLikeLink = '<?php echo ((is_array($_tmp=((is_array($_tmp='Facebook Like Button')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersEmailLink = '<?php echo ((is_array($_tmp=((is_array($_tmp='Email Address')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';//"
var strPersEmailLinkDesc = '<?php echo ((is_array($_tmp=((is_array($_tmp="The subscribers email address.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';//"
var strPersFNameLink = '<?php echo ((is_array($_tmp=((is_array($_tmp='First Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';//"
var strPersLNameLink = '<?php echo ((is_array($_tmp=((is_array($_tmp='Last Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';//"
var strPersNameLink = '<?php echo ((is_array($_tmp=((is_array($_tmp='Full Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';//"
var strPersListnameLink = '<?php echo ((is_array($_tmp=((is_array($_tmp="Subscriber's List")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';//"
var strPersListnameLink_DESC = '<?php echo ((is_array($_tmp=((is_array($_tmp="Show the name of the list that this email was sent to.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';//"

var strPersIPLink = '<?php echo ((is_array($_tmp=((is_array($_tmp="Subscriber's IP Address")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';//"
var strPersIPLink_DESC = '<?php echo ((is_array($_tmp=((is_array($_tmp="The IP Address of the subscriber when they subscribed to this list.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersSDateLink = '<?php echo ((is_array($_tmp=((is_array($_tmp='Date Subscribed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersSDateLink_DESC = '<?php echo ((is_array($_tmp=((is_array($_tmp="The date that this subscriber subscribed.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var strPersSDateTimeLink = '<?php echo ((is_array($_tmp=((is_array($_tmp='Date and Time Subscribed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersSDateTimeLink_DESC = '<?php echo ((is_array($_tmp=((is_array($_tmp="The date and time the subscriber subscribed.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersSTimeLink = '<?php echo ((is_array($_tmp=((is_array($_tmp='Time Subscribed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersSTimeLink_DESC = '<?php echo ((is_array($_tmp=((is_array($_tmp="The time of the day the subscriber subscribed.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersTodayLink = '<?php echo ((is_array($_tmp=((is_array($_tmp="Today's Date")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';//"
var strPersTodayRangeLink = '<?php echo ((is_array($_tmp=((is_array($_tmp="Today's Date +/- X day(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';//"
var strPersSenderInfoLink = '<?php echo ((is_array($_tmp=((is_array($_tmp='List Sender Info')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';//"
var strPersSIDLink = '<?php echo ((is_array($_tmp=((is_array($_tmp='Subscriber ID')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersSIDLink_DESC = '<?php echo ((is_array($_tmp=((is_array($_tmp="Display the ID # of the subscriber.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersMissing = '<?php echo ((is_array($_tmp=((is_array($_tmp='Please select a Personalization Tag')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var strConfirmLinkText = '<?php echo ((is_array($_tmp=((is_array($_tmp='Click here to confirm your subscription')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strUnsubscribeText = '<?php echo ((is_array($_tmp=((is_array($_tmp='Click here to unsubscribe')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strSubscriberUpdateText = '<?php echo ((is_array($_tmp=((is_array($_tmp='Click here to update your info')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strWebCopyText = '<?php echo ((is_array($_tmp=((is_array($_tmp='Click here to see a web copy of this email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strForward2FriendText = '<?php echo ((is_array($_tmp=((is_array($_tmp='Click here to forward this email to a friend')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strEnterText = '<?php echo ((is_array($_tmp=((is_array($_tmp="Enter the text for this link:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strEnterRange = '<?php echo ((is_array($_tmp=((is_array($_tmp="Enter the date range compared to sending date.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
' +
	'\n\n' + '<?php echo ((is_array($_tmp=((is_array($_tmp="Sample values: -10, -1, +3, 6 (plus is assumed)...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strEnterRangeInvalid = '<?php echo ((is_array($_tmp=((is_array($_tmp="Invalid range.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
' +
	'\n\n' + '<?php echo ((is_array($_tmp=((is_array($_tmp="Sample values: -10, -1, +3, 6 (plus is assumed)...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var strPersPersonalize = '<?php echo ((is_array($_tmp=((is_array($_tmp='Insert Personalization Tag')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var strDefaultTO = '<?php echo ((is_array($_tmp=((is_array($_tmp='Subscriber')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';


var strDefaultOptInText  = '<?php echo ((is_array($_tmp=((is_array($_tmp="Thank you for subscribing. Click here to confirm your subscription:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strDefaultOptOutText = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please click this link to confirm your unsubscription:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var strImportTypeXML  = '<?php echo ((is_array($_tmp=((is_array($_tmp="XML (Includes Dependent Files)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strImportTypeHTML = '<?php echo ((is_array($_tmp=((is_array($_tmp='HTML Only')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var strRssNew = '<?php echo ((is_array($_tmp=((is_array($_tmp='Emails will only be sent if new RSS items are found')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

<?php echo '
var personalization_social_networks = {
'; ?>

	facebook: '<?php echo ((is_array($_tmp=((is_array($_tmp='Share on Facebook Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	twitter: '<?php echo ((is_array($_tmp=((is_array($_tmp='Share on Twitter Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	digg: '<?php echo ((is_array($_tmp=((is_array($_tmp='Share on Digg Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	reddit: '<?php echo ((is_array($_tmp=((is_array($_tmp='Share on Reddit Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	delicious: '<?php echo ((is_array($_tmp=((is_array($_tmp="Share on del.icio.us Link")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	greader: '<?php echo ((is_array($_tmp=((is_array($_tmp='Share on GoogleReader Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
',
	stumbleupon: '<?php echo ((is_array($_tmp=((is_array($_tmp='Share on StumbleUpon Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
'
<?php echo '
};
'; ?>
