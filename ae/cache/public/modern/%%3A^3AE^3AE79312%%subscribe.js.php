<?php /* Smarty version 2.6.12, created on 2016-07-08 15:27:03
         compiled from subscribe.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'plang', 'subscribe.js', 1, false),array('modifier', 'js', 'subscribe.js', 1, false),)), $this); ?>
var subscribe_names_missing = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please include your name.")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscribe_email_missing = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please include an email address.")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscribe_email_invalid = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please include a valid email address.")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscribe_captcha_missing = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please include a text from the image.")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var check4name = false;
var show_captcha = false;

<?php echo '

var customFieldsObj = new ACCustomFields({
	sourceType: \'CHECKBOX\',
	sourceId: \'parentsListBox\',
	sourceName: \'nlbox[]\',
	api: \'form.form_list_change\',
	responseIndex: \'fields\',
	includeGlobals: 1,
	additionalHandler: function(ary) {

		show_captcha = false;
		check4name = false;

		for ( var i = 0; i < ary.lists.length; i++ ) {
			if ( ary.lists[i].p_use_captcha == 1 ) {
				show_captcha = true;
			}
			if ( ary.lists[i].require_name == 1 ) {
				check4name = true;
			}
		}
		if ( adesk_js_site.gd != 1 ) show_captcha = false;

		// Show the captcha div if the list has 1 for p_use_captcha.
		$("subscribe_use_captcha").className = ( show_captcha ? \'\' : \'adesk_hidden\' );
	}
});
customFieldsObj.addHandler(\'custom_fields_table\', \'display\');

function subscribe_list_loadfields() {
	customFieldsObj.fetch(0);
	//update_custom_fields_checkbox(0);
}

function subscribe_validate() {
	if ( $(\'subscribe_email\').value == \'\' ) {
		alert(subscribe_email_missing);
		$(\'subscribe_email\').focus();
		return false;
	}

	if ( !adesk_str_email($(\'subscribe_email\').value) ) {
		alert(subscribe_email_invalid);
		$(\'subscribe_email\').focus();
		return false;
	}

	if ( !adesk_form_check_selection_check($(\'parentsListBox\'), \'nlbox[]\', jsNothingSelected, jsNothingFound) ) {
		return false;
	}

	// if require name is on for any selected list
	if ( check4name ) {
		if ( $(\'firstnameField\').value == \'\' && $(\'lastnameField\').value == \'\' ) {
			alert(subscribe_names_missing);
			if ( $(\'firstnameField\').value == \'\' ) {
				$(\'firstnameField\').focus();
			} else {
				$(\'lastnameField\').focus();
			}
			return false;
		}
	}

	// if captcha is on for any selected list
	if ( show_captcha ) {
		if ( $(\'imgverify\').value == \'\' ) {
			alert(subscribe_captcha_missing);
			$(\'imgverify\').focus();
			return false;
		}
	}

	return true;
}

'; ?>