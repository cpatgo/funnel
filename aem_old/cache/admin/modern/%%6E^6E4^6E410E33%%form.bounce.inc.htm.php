<?php /* Smarty version 2.6.12, created on 2016-07-18 12:02:28
         compiled from form.bounce.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'form.bounce.inc.htm', 2, false),array('modifier', 'truncate', 'form.bounce.inc.htm', 43, false),)), $this); ?>
    <div class="h2_wrap_static">
      <h5><?php echo ((is_array($_tmp="Method To Deal With Bounced E-Mails")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
      <div class="h2_content">
          <div>
            <label>
              <input name="type" id="bouncetypeFieldNone" type="radio" value="none" onchange="$('bounceform').className = ( !this.checked ? 'adesk_block' : 'adesk_hidden' );" />
              <strong><?php echo ((is_array($_tmp="No Bounced E-mail Management")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong>
              <?php echo ((is_array($_tmp="(Default)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </label>
          </div>
          <div class="adesk_greyout"><?php echo ((is_array($_tmp="Bounced E-mail Messages will be sent to the From sender of the mailing. No configuration is needed. Recommended for novice users.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

          <div>
            <label>
              <input name="type" id="bouncetypeFieldPipe" type="radio" value="pipe" onchange="$('bounceform').className = ( this.checked ? 'adesk_block' : 'adesk_hidden' ); $('bouncepop3').className = 'adesk_hidden';" />
              <strong><?php echo ((is_array($_tmp='Pipe from Email Address')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong>
            </label>
          </div>

          <div>
            <label>
              <input name="type" id="bouncetypeFieldPOP3" type="radio" value="pop3" onchange="$('bounceform').className = ( this.checked ? 'adesk_block' : 'adesk_hidden' ); $('bouncepop3').className = 'adesk_table_rowgroup';" />
              <strong><?php echo ((is_array($_tmp='Use POP3 Account')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong>
            </label>
          </div>
      <div id="bounceform" class="adesk_hidden">
        <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td><?php echo ((is_array($_tmp="E-mail Address")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
            <td><input name="email" type="text" id="bounceemailField" value="" size="50" /></td>
          </tr>
<?php if (! $this->_tpl_vars['included']): ?>
          <tbody id="bouncelists" class="adesk_table_rowgroup">
            <tr valign="top">
              <td><?php echo ((is_array($_tmp="Used in Lists:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td>

								<div id="parentsList_div" class="adesk_checkboxlist">
									<?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
										<div>
											<label>
												<input type="checkbox" id="p_<?php echo $this->_tpl_vars['p']['id']; ?>
" class="parentsList" name="p[]" value="<?php echo $this->_tpl_vars['p']['id']; ?>
" <?php if (count ( $this->_tpl_vars['listsList'] ) == 1): ?>checked="checked"<?php endif; ?> />
												<?php echo ((is_array($_tmp=$this->_tpl_vars['p']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>

											</label>
										</div>
									<?php endforeach; endif; unset($_from); ?>
								</div>
							  <div>
									<?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

									<a href="#" onclick="parents_box_select(1, 0); return false;"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
									&middot;
									<a href="#" onclick="parents_box_select(0, 0); return false;"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
							  </div>

              </td>
          </tr>
          </tbody>
<?php endif; ?>
          <tbody id="bouncepop3">
            <tr>
              <td><?php echo ((is_array($_tmp='POP3 Host')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br /><?php echo ((is_array($_tmp="(IE: pop.example.com)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td valign="top"><input name="host" type="text" id="bouncehostField" value="" size="50" /></td>
            </tr>
            <tr>
              <td><?php echo ((is_array($_tmp='POP3 Port')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br /><?php echo ((is_array($_tmp="(Default is 110)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td><input name="port" type="text" value="" id="bounceportField" size="8" /></td>
            </tr>
            <tr>
              <td><?php echo ((is_array($_tmp='POP3 Username')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td><input name="user" type="text" id="bounceuserField" value="" size="50" /></td>
            </tr>
            <tr>
              <td><?php echo ((is_array($_tmp='POP3 Password')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td><input name="pass" type="password" id="bouncepassField" value="" size="50" /></td>
            </tr>
            <tr>
              <td><?php echo ((is_array($_tmp='Number of emails to process at one time')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td><input name="emails_per_batch" type="text" id="bouncebatchField" value="" size="8" /></td>
            </tr>
          </tbody>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
                    <tr>
            <td valign="top"><strong><?php echo ((is_array($_tmp='Bounce Management Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong></td>
            <td>
              <?php echo ((is_array($_tmp='Number of times an address may soft bounce before being removed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
              <input name="limit_soft" type="text" id="bouncesoftField" value="" size="8" />
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>
              <?php echo ((is_array($_tmp='Number of times an address may hard bounce before being removed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
              <input name="limit_hard" type="text" id="bouncehardField" value="" size="8" />
            </td>
          </tr>
        </table></div>
        </div>

<?php if (! $this->_tpl_vars['included']): ?>
        <br />
        <div>
          <input type="button" id="form_submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="bounce_management_form_save(bounce_management_form_id)" />
          <input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1)" />
        </div>
        <input type="submit" style="display:none"/>
<?php else: ?>
        <br />
        <div>
          <input type="button" id="bounce_form_save" class="adesk_button_save" value="<?php echo ((is_array($_tmp='Add')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="list_form_bounce_set();" />
          <input type="button" id="bounce_form_cancel" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="$('bouncenew').className = 'adesk_hidden';" />
          <input type="hidden" name="included" value="1" />
        </div>
<?php endif; ?>
      </div>
    </div>