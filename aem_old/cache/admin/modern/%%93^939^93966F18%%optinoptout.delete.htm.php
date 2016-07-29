<?php /* Smarty version 2.6.12, created on 2016-07-18 12:02:57
         compiled from optinoptout.delete.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'optinoptout.delete.htm', 3, false),)), $this); ?>
<div id="delete" class="adesk_modal_delete" align="center" style="display: none">
  <div class="adesk_modal_inner">
    <h3 class="m-b"><?php echo ((is_array($_tmp="Delete Email Confirmation Set(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
    <span id="delete_message"></span>
    <br />
    <ul id="delete_list"></ul>
    <br />
    <div>
      <input type="button" class="adesk_button_ok" value="<?php echo ((is_array($_tmp='OK')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="optinoptout_delete(optinoptout_delete_id)" />
      <input type="button" class="adesk_button_cancel" value="<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_dom_toggle_display('delete', 'block'); adesk_ui_anchor_set(optinoptout_list_anchor())" />
    </div>
  </div>
</div>