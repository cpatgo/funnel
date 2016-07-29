<?php /* Smarty version 2.6.12, created on 2016-07-08 14:18:25
         compiled from sync.delete.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'sync.delete.htm', 10, false),)), $this); ?>
<div id="syncDeletePanel" class="adesk_modal" align="center" style="display:none">
  <div class="adesk_modal_inner">

<form action="desk.php" method="get" name="deleteSyncForm" id="deleteSyncForm">
<input type="hidden" name="action" value="sync" />
<input type="hidden" id="syncDeleteIDfield" name="id" value="<?php echo $this->_tpl_vars['data']['id']; ?>
" />


<div class="h2_wrap_static">
  <h4><?php echo ((is_array($_tmp='General')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h4>
  <div id="syncDeleteBox" class="h2_content">
    <p><?php echo ((is_array($_tmp="Selected Sync Jobs:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</p>
	<ul></ul>
  </div>
</div>


<p>&nbsp;</p>

<div class="h2_wrap">
  <h4 onclick="adesk_dom_toggle_class('syncConfirmBox', 'h2_content', 'h2_content_invis');"><?php echo ((is_array($_tmp="Are you sure?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h4>
  <div id="categoryConfirmBox" class="h2_content">
    <p align="center">
      <?php echo ((is_array($_tmp="Are you sure you are sure?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
<?php if (! $this->_tpl_vars['demoMode']): ?>
      <input type="button" value="<?php echo ((is_array($_tmp="Yes - Delete")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="sync_delete();" />
<?php else: ?>
      <span class="demoDisabled2"><?php echo ((is_array($_tmp='Disabled in demo')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
<?php endif; ?>
      <input type="button" value="<?php echo ((is_array($_tmp="NO - Back")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_dom_toggle_display('syncDeletePanel', 'block');return false;window.history.go(-1);" />
    </p>
  </div>
</div>

</form>


  </div>
</div>