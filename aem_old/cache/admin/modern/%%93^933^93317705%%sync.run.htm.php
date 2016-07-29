<?php /* Smarty version 2.6.12, created on 2016-07-08 14:18:25
         compiled from sync.run.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'sync.run.htm', 7, false),)), $this); ?>
<div id="syncRunPanel" class="adesk_modal" align="center" style="display:none; overflow: auto">
  <div class="adesk_modal_inner" style="width: 600px;">
  <h3 id="syncRunTitle"></h3>
  <input id="syncRunID" type="hidden" value="0" />
	<table width="100%" border="0" cellspacing="0" cellpadding="5">
	  <tr>
		<td width="150"><?php echo ((is_array($_tmp="User@Host")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><span id="syncRunUser"></span></td>
	  </tr>
	  <tr>
		<td><?php echo ((is_array($_tmp='Database')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><span id="syncRunDB"></span></td>
	  </tr>
	  <tr>
		<td valign="top"><?php echo ((is_array($_tmp='Table')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td>
		  <span id="syncRunTable"></span>
		  <a href="#" id="syncRunTablesLink" class="adesk_hidden" onclick="adesk_dom_toggle_display('syncRunTablesBox', 'block');this.innerHTML = ( this.innnerHTML == syncShowTables ? syncHideTables : syncShowTables );return false;"><?php echo ((is_array($_tmp='Show Tables')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		  <div id="syncRunTablesBox" style="display: none; margin: 10px 0; padding: 5px; border: 1px solid #eee;">
		    <ul id="syncRunTablesList"></ul>
		  </div>
		</td>
	  </tr>
	  <tr>
		<td valign="top"><?php echo ((is_array($_tmp='Query')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><div id="syncRunQuery" style="padding: 5px; border: 1px solid #eee;"></div></td>
	  </tr>
	  	</table>
	<div style="display:none"> 	  <input id="syncRunDetails" type="button" value="<?php echo ((is_array($_tmp='Details...')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" class="adesk_hidden" onclick="adesk_sync_details();" />
	</div>

		<div id="syncRunNotice" align="center">
			<div id="progressBar" class="adesk_progressbar" align="left"></div>
			<div><?php echo ((is_array($_tmp="Your synchronization has been started.  You may click 'Done' to leave this window at any time.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div><br />
			<div style="color:#999999;"><?php echo ((is_array($_tmp="You can monitor the progress here or leave this page and let it run in the background.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

		</div>
		<iframe id="syncRunFrame" name="syncRunFrame" class="adesk_hidden" width="100%" height="300" scrolling="auto" src="about:blank" border="0" style="border:0px;"></iframe>
		<script>/* register iframe for autoexpand here */</script>
		<div id="syncRunResult" class="adesk_hidden" align="center">
			<div><?php echo ((is_array($_tmp="Your synchronization has been completed.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div><br />
			<?php if (isset ( $this->_tpl_vars['site']['isAEM'] )): ?>
			<div>
				<a href="desk.php?action=subscriber"><?php echo ((is_array($_tmp='Manage Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				&nbsp; | &nbsp;
				<a href="desk.php?action=sync#add-0-1"><?php echo ((is_array($_tmp='Add More Synchronizations')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				&nbsp; | &nbsp;
				<a href="#" onclick="return adesk_sync_report();"><?php echo ((is_array($_tmp='View Report')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			<?php endif; ?>
		</div>

	<br />
	<br />
	<div id="sync_before_run" style="text-align: right">
	  <input id="syncRunStart" type="button" value="<?php echo ((is_array($_tmp='Start Sync')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" class="adesk_button_ok" onclick="adesk_sync_start();" />
	  <input id="test_to_run_button" class="adesk_hidden" type="button" onclick="adesk_dom_toggle_display('syncRunPanel', 'block');sync_run($('syncRunID').value, false);" value='<?php echo ((is_array($_tmp='Run')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' />
	  <input type="button" onclick="adesk_dom_toggle_display('syncRunPanel', 'block');" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' />
	</div>
	<div id="sync_after_run" style="display:none; text-align: right">
	  <input type="button" onclick="$('syncRunPanel').hide()" value='<?php echo ((is_array($_tmp='Done')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' class="adesk_button_ok">
	</div>
  </div>
</div>