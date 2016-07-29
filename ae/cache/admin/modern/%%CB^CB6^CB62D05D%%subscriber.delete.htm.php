<?php /* Smarty version 2.6.12, created on 2016-07-08 14:15:47
         compiled from subscriber.delete.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'subscriber.delete.htm', 4, false),)), $this); ?>
<form method="POST">
  <div id="delete" class="adesk_modal_delete" align="center" style="display: none">
	<div class="adesk_modal_inner">
	  <h3 class="m-b"><?php echo ((is_array($_tmp="Delete Subscriber(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
	  <span id="delete_message"></span>
	  <br />
	  <br />
	  <div>
		<?php echo ((is_array($_tmp="You can choose to remove the selected subscriber(s) from all of your lists, or only the ones you select.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<a href="#" onclick="adesk_dom_toggle_display('delete_lists'); return false"><?php echo ((is_array($_tmp="Click here.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
	  </div>
	  <div id="delete_lists" style="display:none"></div>
	  <ul id="delete_list"></ul>
	  <br />
	  <div>
		<input type="button" class="adesk_button_ok" value="<?php echo ((is_array($_tmp='OK')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="subscriber_delete(subscriber_delete_id)" />
		<input type="button" class="adesk_button_cancel" value="<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_dom_toggle_display('delete', 'block'); adesk_ui_anchor_set(subscriber_list_anchor())" />
	  </div>
	</div>
  </div>
</form>
<div id="delete_multilists" style="display:none">
  <?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['l']):
?>
  <div>
	<label><input type="checkbox" name="listids[]" value="<?php echo $this->_tpl_vars['l']['id']; ?>
" checked="checked" /> <?php echo $this->_tpl_vars['l']['name']; ?>
</label>
  </div>
  <?php endforeach; endif; unset($_from); ?>
</div>