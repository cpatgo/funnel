<?php /* Smarty version 2.6.12, created on 2016-07-08 14:15:47
         compiled from subscriber.exportlist.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'subscriber.exportlist.htm', 5, false),)), $this); ?>
<div id="exportlist" class="adesk_hidden">
  <div class="adesk_modal" align="center">
	<div class="adesk_modal_inner">
	  <form method="GET" onsubmit="return false">
		<h3 class="m-b"><?php echo ((is_array($_tmp='Export Subscribers Into a New List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
		<div>
		  <?php echo ((is_array($_tmp="Name:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
		  <input type="text" name="name" id="exportlist_name" />
		</div>
		<div>
		  <br />
		  <?php echo ((is_array($_tmp="How Many:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
		  <select name="howmany" id="exportlist_howmany" size="1">
			<option value="page" selected><?php echo ((is_array($_tmp='This Page Only')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			<option value="list"><?php echo ((is_array($_tmp='All Pages')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		  </select>
		</div>
		<br />

		<div>
		  <input type="button" value="<?php echo ((is_array($_tmp='Export')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="subscriber_exportlist_export()" class="adesk_button_ok" />
		  <input type="button" value="<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="subscriber_exportlist_close(); adesk_ui_anchor_set(subscriber_list_anchor())" />
		</div>
	  </form>
	</div>
  </div>
</div>