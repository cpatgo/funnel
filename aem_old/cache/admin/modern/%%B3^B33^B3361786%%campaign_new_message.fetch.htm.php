<?php /* Smarty version 2.6.12, created on 2016-07-08 14:19:52
         compiled from campaign_new_message.fetch.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'campaign_new_message.fetch.htm', 3, false),)), $this); ?>
<div id="message_fetch" class="adesk_modal" align="center" style="display:none;">
	<div class="adesk_modal_inner" align="left">
		<h3 class="m-b"><?php echo ((is_array($_tmp='Fetch From URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

		<div class="adesk_help_inline"><?php echo ((is_array($_tmp="Use HTML from any URL you specify as the contents of your email.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

		<br />
		<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td><?php echo ((is_array($_tmp='URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td width="15">&nbsp;</td>
				<td></td>
			</tr>
			<tr>
				<td>
					<input type="text" name="fetchurl_temp" id="fetchurl" value="<?php echo $this->_tpl_vars['fetchurl']; ?>
" style="width:99%;" />
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td>
					<?php echo ((is_array($_tmp="When should we fetch this?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				</td>
			</tr>
			<tr>
				<td>
					<label>
						<input id="fetchnow" name="fetch_temp" type="radio" value="now" onclick="campaign_fetch_radiochoose(this.value)" <?php if ($this->_tpl_vars['fetch'] == 'now'): ?>checked<?php endif; ?> />
						<?php echo ((is_array($_tmp='Fetch now and put it into the editor so I can edit before sending')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					</label>
				</td>
			</tr>
			<tr>
				<td>
					<label>
						<input id="fetchsend" name="fetch_temp" type="radio" value="send" onclick="campaign_fetch_radiochoose(this.value)" <?php if ($this->_tpl_vars['fetch'] == 'send' || $this->_tpl_vars['fetch'] == 'cust'): ?>checked<?php endif; ?> />
						<?php echo ((is_array($_tmp='Fetch at time of sending')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					</label>
				</td>
			</tr>
		</table></div>
		<br />

		<div>
			<input id="message_fetch_ok" type="button" <?php if ($this->_tpl_vars['fetch'] == 'now'): ?>value='<?php echo ((is_array($_tmp='Insert')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
'<?php else: ?>value='<?php echo ((is_array($_tmp='Save')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
'<?php endif; ?> onclick="campaign_fetch_insert();" class="adesk_button_ok" />
			<input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="$('message_fetch').hide()">
			<input type="hidden" id="fetchwhat" name="fetchurl" value="<?php echo $this->_tpl_vars['fetchurl']; ?>
" />
			<input type="hidden" id="fetchwhen" name="fetch" value="<?php if ($this->_tpl_vars['fetch'] == 'now'): ?>now<?php else: ?>send<?php endif; ?>" />
		</div>
	</div>
</div>