<?php /* Smarty version 2.6.12, created on 2016-07-08 16:17:42
         compiled from unsubscribe.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'plang', 'unsubscribe.js', 1, false),array('modifier', 'js', 'unsubscribe.js', 1, false),)), $this); ?>
var unsubscribe_email_missing = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please include an email address.")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var unsubscribe_email_invalid = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please include a valid email address.")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

<?php echo '

var customFieldsObj = new ACCustomFields({
	sourceType: \'CHECKBOX\',
	sourceId: \'parentsListBox\',
	sourceName: \'nlbox[]\',
	api: \'form.form_list_change\',
	responseIndex: \'fields\',
	includeGlobals: 1,
	additionalHandler: function(ary) {

		var show_captcha = false;

		for ( var i = 0; i < ary.lists.length; i++ ) {
			if ( ary.lists[i].p_use_captcha == 1 ) {
				show_captcha = true;
			}
		}
		if ( adesk_js_site.gd != 1 ) show_captcha = false;

		// Show the captcha div if the list has 1 for p_use_captcha.
		$("unsubscribe_use_captcha").className = ( show_captcha ? \'\' : \'adesk_hidden\' );
	}
});
customFieldsObj.addHandler(\'custom_fields_table\', \'display\');

function unsubscribe_list_loadfields() {
	customFieldsObj.fetch(0);
	//update_custom_fields_checkbox(0);
}

function unsubscribe_validate() {
	if ( $(\'email\').value == \'\' ) {
		alert(unsubscribe_email_missing);
		$(\'email\').focus();
		return false;
	}

	if ( !adesk_str_email($(\'email\').value) ) {
		alert(unsubscribe_email_invalid);
		$(\'email\').focus();
		return false;
	}

	if ( !adesk_form_check_selection_check($(\'parentsListBox\'), \'nlbox[]\', jsNothingSelected, jsNothingFound) ) {
		return false;
	}

	return true;
}

'; ?>