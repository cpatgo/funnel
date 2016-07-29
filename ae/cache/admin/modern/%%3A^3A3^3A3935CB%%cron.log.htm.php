<?php /* Smarty version 2.6.12, created on 2016-07-08 14:41:25
         compiled from cron.log.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'cron.log.htm', 4, false),)), $this); ?>
<div id="log" class="adesk_modal" align="center" style="display: none">
  <div class="adesk_modal_inner">
    <div>
      <input type="button" class="adesk_button_close" value="<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_dom_toggle_display('log', 'block'); adesk_ui_anchor_set(cron_list_anchor())" />
    </div>
	<p>
		<?php echo ((is_array($_tmp="This page will show you the activity for all of the possible files that can be executed through a cron.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php echo ((is_array($_tmp="By looking at this information you can see if your cronjobs are setup properly.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php echo ((is_array($_tmp="Every time the cron file ran in the past 14 days will be listed.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	</p>
    <div><?php echo ((is_array($_tmp="Log Entries:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="log_count"></span></div>
    <br />
    <ol id="log_list" class="adesk_hidden"></ol>
    <div id="log_empty" class="adesk_block"><?php echo ((is_array($_tmp="File did not ever run.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
    <br />
	<p>
		<em><?php echo ((is_array($_tmp="Please note that it is normal for cronjobs to have no ending time.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</em>
	</p>
    <div>
      <input type="button" class="adesk_button_close" value="<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_dom_toggle_display('log', 'block'); adesk_ui_anchor_set(cron_list_anchor())" />
    </div>
  </div>
</div>