<?php /* Smarty version 2.6.12, created on 2016-07-08 17:09:18
         compiled from form.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'form.form.htm', 7, false),array('modifier', 'help', 'form.form.htm', 15, false),array('modifier', 'truncate', 'form.form.htm', 44, false),)), $this); ?>
<div id="form" class="adesk_hidden">
  <form method="POST" onsubmit="form_form_save(form_form_id); return false">
    <input type="hidden" name="id" id="form_id" />
    <input type="hidden" name="lists_optinoptout" id="lists_optinoptout" value="" />

    <div id="formlistpanel_div" class="h2_wrap">
      <h2 onclick="adesk_dom_toggle_class('formlistpanel', 'h2_content_invis', 'h2_content');"><?php echo ((is_array($_tmp='Subscription Form Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
      <div id="formlistpanel" class="h2_content">

        <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td><label for="nameField"><?php echo ((is_array($_tmp='Form Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
            <td>
            	<input type="text" name="name" id="nameField" value="" size="45" />
              <?php echo ((is_array($_tmp="Brief Description for you to recognize. Does NOT affect your actual form.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

             </td>
          </tr>
          <tr valign="top">
            <th colspan="2"><hr width="100%" size="1" noshade="noshade" /></th>
          </tr>
          <tr>
            <td>
              <label for="type"><?php echo ((is_array($_tmp='Form Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
            </td>
            <td>
							<select name="type" id="type" onchange="form_options_type_change(this.value);">
							  <option value="both"><?php echo ((is_array($_tmp="Subscribe &amp; Unsubscribe")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
							  <option value="subscribe"><?php echo ((is_array($_tmp='Subscribe Only')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
							  <option value="unsubscribe"><?php echo ((is_array($_tmp='Unsubscribe Only')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
							</select>
						</td>
          </tr>
          <tr valign="top">
            <th colspan="2"><hr width="100%" size="1" noshade /></th>
          </tr>
          <tr valign="top">
            <td><?php echo ((is_array($_tmp='Lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
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
" onclick="customFieldsObj.fetch(0);" <?php if (count ( $this->_tpl_vars['listsList'] ) == 1): ?>checked="checked"<?php endif; ?> />
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
          <tr id="list_options_tr" valign="top">
            <td><?php echo ((is_array($_tmp='List Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
            <td>
              <label>
              	<input name="allowselection" id="allowselFieldYes" value="1" type="radio" />
                <?php echo ((is_array($_tmp="Allow user to select lists they wish to subscribe to or unsubscribe from.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

              </label>
              <br />
							<label>
	              <input name="allowselection" id="allowselFieldNo" value="0" type="radio" />
	              <?php echo ((is_array($_tmp="Force user to subscribe to or unsubscribe from all lists selected above.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	              <?php echo ((is_array($_tmp="User will not have options for lists and will not see lists they are subscribing to.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

              </label>
            </td>
          </tr>
          <tr id="opt_confirmation_tr" valign="top">
            <td><?php echo ((is_array($_tmp="Opt-In/Out Confirmation")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
            <td>
              <label>
                <input name="emailconfirmations" id="emailconfirmationsEach" value="1" type="radio" checked="checked" onclick="form_opt_confirmation_change();" />
                <?php echo ((is_array($_tmp="Send individual email confirmations for each list.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

                <?php echo ((is_array($_tmp="User will receive an email confirmation for each list that has the opt-in/out email confirmation turned on.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

              </label>
              <br />
              <label>
                <input name="emailconfirmations" id="emailconfirmationsAll" value="0" type="radio" onclick="form_opt_confirmation_change();" />
                <?php echo ((is_array($_tmp="Send a single email confirmation for all lists.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

                <?php echo ((is_array($_tmp="User will receive a single email confirmation that will be for all the lists selected.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

              </label>

							<div id="optinoutchoose" class="form_confirmation">
          			<div><?php echo ((is_array($_tmp="Send this list's confirmation email one time for all lists instead of indiviual confirmations for each list.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
          			<div>
            			<select id="optinoutidField" name="optid" size="1">
										<?php $_from = $this->_tpl_vars['optsetsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['o']):
?>
              				<option value="<?php echo $this->_tpl_vars['o']['id']; ?>
"><?php echo $this->_tpl_vars['o']['name']; ?>
</option>
										<?php endforeach; endif; unset($_from); ?>
            			</select>
            			<a href="desk.php?action=optinoptout" onclick="optinout_get($('optinoutidField').value);return false;"><?php echo ((is_array($_tmp='Manage')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
            			<a href="desk.php?action=optinoptout#form-0" onclick="optinout_get(0);return false;"><?php echo ((is_array($_tmp='Add New')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
          			</div>
        			</div>

						  							<?php if ($this->_tpl_vars['admin']['pg_list_opt']): ?>
				        <div id="optinoutnew" class="adesk_hidden">
				          				          <br />
				          <div>
				            <input type="button" id="optinout_form_save" class="adesk_button_save" value="<?php echo ((is_array($_tmp='Save')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="form_form_optinout_set();" />
				            <input type="button" id="optinout_form_cancel" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="$('optinoutnew').className = 'adesk_hidden';" />
				          </div>
				        </div>
							<?php endif; ?>

						</td>
          </tr>
          <tr valign="top">
            <th colspan="2"><hr width="100%" size="1" noshade /></th>
          </tr>
          <tr>
            <td><?php echo ((is_array($_tmp="Fields to request:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
            <td>
            	<label>
	              <input name="ack4email" type="checkbox" id="ask4emailField" value="1" checked="checked" disabled="disabled" />
	              <?php echo ((is_array($_tmp='Email Address ')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

              </label>
             </td>
          </tr>
          <tr id="ask4fname_tr">
            <td>&nbsp;</td>
            <td>
              <label>
                <input name="ask4fname" id="ask4fnameField" type="checkbox" value="1" />
                <?php echo ((is_array($_tmp='First Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

              </label>
            </td>
          </tr>
          <tr id="ask4lname_tr">
            <td>&nbsp;</td>
            <td>
              <label>
                <input name="ask4lname" id="ask4lnameField" type="checkbox" value="1" />
                <?php echo ((is_array($_tmp='Last Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

              </label>
            </td>
          </tr>

          <tr id="custom_fields_trs_hr">
            <td>&nbsp;</td>
            <td><hr width="100%" size="1" noshade /></td>
          </tr>

          <tbody id="custom_fields_trs">
						<?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
		          <tr>
		            <td>&nbsp;</td>
		            <td>
		              <label>
		                <input name="fields[]" id="custom<?php echo $this->_tpl_vars['field']['id']; ?>
Field" type="checkbox" value="<?php echo $this->_tpl_vars['field']['id']; ?>
" />
		                <?php echo $this->_tpl_vars['field']['title']; ?>

		              </label>
		            </td>
		          </tr>
						<?php endforeach; endif; unset($_from); ?>
                        
                          <tr>
		            <td>&nbsp;</td>
		            <td>
		             <a href="desk.php?action=list_field" target="_blank"><?php echo ((is_array($_tmp='Add a custom field')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		            </td>
		          </tr>
					</tbody>

          <tbody id="custom_fields_table"></tbody>

          <tr>
            <td>&nbsp;</td>
            <td><hr width="100%" size="1" noshade /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>
<?php if ($this->_tpl_vars['site']['gd']): ?>
              <label>
                <input name="captcha" id="captchaField" type="checkbox" value="1" />
                <?php echo ((is_array($_tmp='Use Captcha Image')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

              </label>
<?php else: ?>
              <input type="hidden" id="captchaField" value="0" />
              <?php echo ((is_array($_tmp="Captcha requires GD library to be installed with PHP, and GD is not installed or enabled on your system. Please contact your web host to install or enable GD.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

<?php endif; ?>
            </td>
          </tr>
        </table></div>

      </div>
    </div>

    <div id="form_completion_options_div" class="h2_wrap">
      <h2 onclick="adesk_dom_toggle_class('formredirpanel', 'h2_content_invis', 'h2_content');"><?php echo ((is_array($_tmp='Form Completion Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
      <div id="formredirpanel" class="h2_content_invis">

				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "redirection.form.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

      </div>
    </div>

    <br />
    <div>
      <input type="button" id="form_submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="form_form_save(form_form_id)" />
      <input type="button" id="view_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Manage Forms')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.location='desk.php?action=form'" />
      <!--<input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1)" />-->
    </div>
    <input type="submit" style="display:none"/>
  </form>
</div>