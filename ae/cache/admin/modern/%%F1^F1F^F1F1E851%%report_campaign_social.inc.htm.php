<?php /* Smarty version 2.6.12, created on 2016-07-08 14:48:21
         compiled from report_campaign_social.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'report_campaign_social.inc.htm', 3, false),)), $this); ?>
<div id="social" class="adesk_modal_delete" align="center" style="display: none">
  <div class="adesk_modal_inner">
    <h3 class="m-b"><?php echo ((is_array($_tmp='Social Share')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
	<div>
	  <?php echo ((is_array($_tmp="Use these links to share this campaign on social networks.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	</div>

	<div style="margin: 15px 0;">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "social.share.inc.htm", 'smarty_include_vars' => array('shareURL' => $this->_tpl_vars['webcopy'],'shareTitle' => $this->_tpl_vars['campaign']['messages'][0]['subject'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>

    <div>
      <input type="button" class="adesk_button_close" value="<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="campaign_social_toggle(); adesk_ui_anchor_set(report_campaign_list_anchor())" />
    </div>
  </div>
</div>