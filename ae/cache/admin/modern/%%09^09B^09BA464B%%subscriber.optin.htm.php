<?php /* Smarty version 2.6.12, created on 2016-07-08 14:15:47
         compiled from subscriber.optin.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'subscriber.optin.htm', 4, false),)), $this); ?>
<div id="optin" class="adesk_modal_delete" align="center" style="display: none">
  <div class="adesk_modal_inner">
    <form method="POST">
	  <h3 class="m-b"><?php echo ((is_array($_tmp="Send Email Reminder to Unconfirmed Subscriber(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
	  <span id="optin_message"></span>
	  <ul id="optin_list"></ul>
	  <br />
	  <br />
	  <div>
		<?php echo ((is_array($_tmp="You can choose which Email Confirmation Set to send to the selected subscriber(s):")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </div>
	  <div>
	    <select name="optid" size="1" id="optin_optid">
<?php $_from = $this->_tpl_vars['optins']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
	      <option value="<?php echo $this->_tpl_vars['v']['id']; ?>
"><?php echo $this->_tpl_vars['v']['name']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
	    </select>
	  </div>
	  <br />
	  <div>
		<input type="button" class="adesk_button_ok" value="<?php echo ((is_array($_tmp='OK')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="subscriber_optin(subscriber_optin_id)" />
		<input type="button" class="adesk_button_cancel" value="<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_dom_toggle_display('optin', 'block'); adesk_ui_anchor_set(subscriber_list_anchor())" />
	  </div>
    </form>
  </div>
</div>