<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:45
         compiled from group.delete.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'group.delete.htm', 8, false),)), $this); ?>
<div id="delete" class="adesk_modal_delete" align="center" style="display: none">
  <div class="adesk_modal_inner">
    <span id="delete_message"></span>
    <br />
    <ul id="delete_list"></ul>
    <br />
	<div>
	  <?php echo ((is_array($_tmp="You can automatically add users to another group to take the place of this group.  If you want to do this, make your choice below.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  <br/>
	  <br/>
	  <select id="delete_alt">
	  </select>
	  <br/>
	  <br/>
	  <?php echo ((is_array($_tmp="If you choose no group, any users who only belong to the deleted group will be assigned either to the default admin or user groups.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  <br/>
	  <br/>
	</div>
    <div>
      <input type="button" class="adesk_button_ok" value="<?php echo ((is_array($_tmp='OK')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="group_delete(group_delete_id)" />
      <input type="button" class="adesk_button_cancel" value="<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_dom_toggle_display('delete', 'block'); adesk_ui_anchor_set(group_list_anchor())" />
    </div>
  </div>
</div>