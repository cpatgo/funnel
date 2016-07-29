<?php /* Smarty version 2.6.12, created on 2016-07-08 16:17:37
         compiled from account.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'plang', 'account.js', 1, false),array('modifier', 'js', 'account.js', 1, false),)), $this); ?>
var account_email_missing = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please include an email address.")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var account_email_invalid = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please include a valid email address.")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var account_captcha_missing = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please include a text from the image.")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

<?php echo '

function account_validate() {
	if ( $(\'emailField\').value == \'\' ) {
		alert(account_email_missing);
		$(\'emailField\').focus();
		return false;
	}

	if ( !adesk_str_email($(\'emailField\').value) ) {
		alert(account_email_invalid);
		$(\'emailField\').focus();
		return false;
	}

	if ( $(\'imgverify\').value == \'\' ) {
		alert(account_captcha_missing);
		$(\'imgverify\').focus();
		return false;
	}

	return true;
}

'; ?>