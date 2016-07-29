<?php /* Smarty version 2.6.12, created on 2016-07-08 14:15:47
         compiled from subscriber.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'subscriber.form.htm', 6, false),array('modifier', 'escape', 'subscriber.form.htm', 12, false),array('modifier', 'help', 'subscriber.form.htm', 52, false),)), $this); ?>
<div id="form" class="adesk_hidden">
  <form method="POST" onsubmit="subscriber_form_save(subscriber_form_id); return false">
	<input type="hidden" name="id" id="form_id" />
	<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5">
	  <tr>
		<td valign="top"><?php echo ((is_array($_tmp='Subscribed to Lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td>
		  <div id="listDiv" class="adesk_checkboxlist">
			<?php $_from = $this->_tpl_vars['subscriberLists']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
			<label>
			  <input type="checkbox" value="<?php echo $this->_tpl_vars['p']['id']; ?>
" name="p[]" class="listField" onchange="customFieldsObj.fetch(0)" <?php if (count ( $this->_tpl_vars['subscriberLists'] ) == 1): ?>checked="checked"<?php endif; ?> />
			  <?php echo ((is_array($_tmp=$this->_tpl_vars['p']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<br>
			</label>
			<?php endforeach; endif; unset($_from); ?>
		  </div>
		  <?php if (count ( $this->_tpl_vars['subscriberLists'] ) > 1): ?>
			  <div>
				<?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<a href="#" onclick="subscriber_form_list_all(true); return false"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				&middot;
				<a href="#" onclick="subscriber_form_list_all(); return false"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			  </div>
		  <?php endif; ?>
		</td>
	  </tr>
	  <tr>
		<td valign="top"></td>
		<td>
			<a href="#" onclick="$('statusadvanced').toggle(); return false"><?php echo ((is_array($_tmp='Advanced Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		  <div id="statusadvanced" style="display:none; margin: 15px 0 15px 15px;">
		  	<?php echo ((is_array($_tmp='Status')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:
		  	<br />
				<select name="status" id="statusField" size="1" onchange="subscriber_form_setstatus(this.value)" style="margin-bottom: 10px;">
				  <?php if (! $this->_tpl_vars['__ishosted']): ?>
				  <option value="0"><?php echo ((is_array($_tmp='Unconfirmed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
				  <?php endif; ?>
				  <option value="1"><?php echo ((is_array($_tmp='Active')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
				  <option value="2"><?php echo ((is_array($_tmp='Unsubscribed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
				  <option value="3"><?php echo ((is_array($_tmp='Bounced')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
				</select>
				<div>
				  <label>
					<input type="checkbox" name="noresponders" id="norespondersField" value="1" />
					<?php echo ((is_array($_tmp='Do not send any future autoresponders')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				  </label>
				</div>
				<div id="liststatus0Stuff" style="display:none">
				  <label>
					<input type="checkbox" name="sendoptin" id="sendoptinField" value="1" />
					<?php echo ((is_array($_tmp="Send opt-in email (to confirm subscription)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				  </label>
				  <?php echo ((is_array($_tmp="Subscriber will receive an opt-in email with a link to confirm list subscription(s).")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

				</div>
				<div id="liststatus1Stuff" style="display:none">
				  <div>
					<label>
					  <input type="checkbox" name="instantresponders" id="instantrespondersField" value="1" />
					  <?php echo ((is_array($_tmp='Send instant autoresponders')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					</label>
				  </div>
				  <div>
					<label>
					  <input type="checkbox" name="lastmessage" id="lastmessageField" value="1" />
					  <?php echo ((is_array($_tmp='Send the last broadcast campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					</label>
				  </div>
				</div>
		  </div>
		</td>
	  </tr>
	  <tr>
		<td valign="top"><?php echo ((is_array($_tmp='Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input type="text" value="" name="email" id="emailField" /></td>
	  </tr>
	  <tr>
		<td valign="top"><?php echo ((is_array($_tmp='First Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input type="text" value="" name="first_name" id="firstnameField" /></td>
	  </tr>
	  <tr>
		<td valign="top"><?php echo ((is_array($_tmp='Last Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input type="text" value="" name="last_name" id="lastnameField" /></td>
	  </tr>

	  <tbody id="custom_fields_table"></tbody>

	</table></div>

	<br />
	<div>
	  <input type="button" id="form_view" class="adesk_button_view" value="<?php echo ((is_array($_tmp='View this Subscriber')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.location = 'desk.php?action=subscriber_view&id=' + subscriber_form_id;" style="float: right;" />
	  <input type="button" id="form_submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="subscriber_form_save(subscriber_form_id)" />
	  <input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1)" />
	</div>
	<input type="submit" style="display:none"/>
  </form>
</div>