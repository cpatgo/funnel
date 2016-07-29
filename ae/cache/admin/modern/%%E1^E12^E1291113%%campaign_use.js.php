<?php /* Smarty version 2.6.12, created on 2016-07-08 15:20:02
         compiled from campaign_use.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'campaign_use.js', 1, false),array('modifier', 'alang', 'campaign_use.js', 6, false),array('modifier', 'js', 'campaign_use.js', 6, false),)), $this); ?>
//var campaign_use_listfilter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['listfilter']), $this);?>
;
//var campaign_id = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['campaignid']), $this);?>
;

var campaign_obj = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['campaign']), $this);?>
;

var campaign_sendat_str = '<?php echo ((is_array($_tmp=((is_array($_tmp='at')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_sendspecial_str = '<?php echo ((is_array($_tmp=((is_array($_tmp="This campaign will be sent to every subscriber individually based on their subscription date/time.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_rightnow_str = '<?php echo ((is_array($_tmp=((is_array($_tmp='Immediately')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var campaign_noname_str = '<?php echo ((is_array($_tmp=((is_array($_tmp='Please select a name for this campaign before you continue')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';


<?php echo '

function campaignname() {
	if ( $(\'summary_campaign_label_box\').style.display == \'none\' ) {
		var val = adesk_str_trim($(\'summary_campaign_input\').value);
		if ( val == \'\' ) {
			$(\'summary_campaign_label\').innerHTML = \'<em>\' + jsNone + \'</em>\';
		} else {
			$(\'summary_campaign_label\').innerHTML = val;
		}

	}
	$(\'summary_campaign_label_box\').toggle();
	$(\'summary_campaign_input_box\').toggle();
}

function form_check() {
	var val = adesk_str_trim($(\'summary_campaign_input\').value);
	if ( val == \'\' ) {
		// open editor
		if ( $(\'summary_campaign_input_box\').style.display == \'none\' ) campaignname();
		alert(campaign_noname_str);
		$(\'summary_campaign_input\').focus();
		return false;
	}
	return true;
}


function campaign_use_onload() {
	pageLoaded = true;
}

adesk_dom_onload_hook(campaign_use_onload);

'; ?>
