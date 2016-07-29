<?php /* Smarty version 2.6.12, created on 2016-07-08 14:48:21
         compiled from spamcheck.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'spamcheck.inc.htm', 3, false),)), $this); ?>
<div id="send_test_spam" class="adesk_modal" align="center" style="display:none;">
  <div class="adesk_modal_inner" align="left">
	<h3 class="m-b"><?php echo ((is_array($_tmp='Check against spam filters')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

    <div id="spamloader" class="adesk_hidden" align="center" style="margin:10px;">
		<img src="images/loader3.gif" />
		<div style="font-size:10px; color:#999999;"><?php echo ((is_array($_tmp='Loading')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		<div align="left" style="margin-top:20px;">
		  <input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_ui_api_callback();adesk_dom_toggle_display('send_test_spam', 'block');" class="adesk_button_ok" />
		</div>
    </div>

    <div id="spamresult" class="adesk_hidden">
		<hr />
		<div style="font-size:10px; color:#999999;" align="center"><?php echo ((is_array($_tmp="Your spam probability is:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		<div id="emailcheck_score" style="font-size:38px;" align="center"></div>
		<div id="emailcheck_table" class="adesk_hidden">
			<hr />
			<?php echo ((is_array($_tmp="Things that could cause your message to be flagged as spam:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br /><br />
			<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tbody id="emailcheck_rules"></tbody>
	        </table></div>
			<hr />
		</div>
		<?php if (! isset ( $this->_tpl_vars['mode'] ) || $this->_tpl_vars['mode'] != 'report'): ?>
		<div align="right"><a href="#" onclick="$('spamresult').className='adesk_hidden';$('spamform').className='adesk_block';return false;"><?php echo ((is_array($_tmp='Check another')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></div>
		<?php endif; ?>
		<br />

	    <div>
	      <input type="button" value='<?php echo ((is_array($_tmp='OK')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_dom_toggle_display('send_test_spam', 'block');" class="adesk_button_ok" />
	    </div>
	</div>

    <div id="spamform" class="adesk_block">
      <div class="adesk_help_inline"><?php echo ((is_array($_tmp="Type in a name or email of a subscriber that you wish to check the test email for. Partial search phrases are allowed.  You can also type an email that does not exist in your list.  If the email is found in any list, it will be personalized ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
      <br />
      <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td><?php echo ((is_array($_tmp='To email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
          <td width="15">&nbsp;</td>
          <td id="spamcheckemailtypelabel"><?php echo ((is_array($_tmp='Format')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        </tr>
        <tr>
          <td>
		  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'autocomplete.inc.htm', 'smarty_include_vars' => array('fieldPrefix' => 'subscriber','fieldID' => 'subscriberEmailCheckField','fieldName' => 'spamcheckemail','fieldSize' => '25','fieldValue' => $this->_tpl_vars['admin']['email'],'fieldStyle' => 'width:90%;')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		  <script>$('subscriberEmailCheckField').onkeypress = adesk_ui_stopkey_enter;</script>
          </td>
          <td>&nbsp;</td>
          <td width="100">
            <select name="spamcheckemailtype" id="spamcheckemailtype" size="1">
              <option value="mime"><?php echo ((is_array($_tmp='HTML and TEXT')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              <option value="html"><?php echo ((is_array($_tmp='HTML')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              <option value="text"><?php echo ((is_array($_tmp='Text')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
            </select>
          </td>
        </tr>
        <tbody id="spamcheckemailsplitbox" class="adesk_hidden">
          <tr>
            <td colspan="3">
              <?php echo ((is_array($_tmp="Select a message you wish to use for this test:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </td>
          </tr>
          <tr>
            <td colspan="3">
              <select name="spamcheckemailsplit" id="spamcheckemailsplit" size="5" style="width:99%" onchange="spamcheck_set(this.value);"></select>
            </td>
          </tr>
        </tbody>
      </table></div>
	<br />

    <div>
      <input type="button" value='<?php echo ((is_array($_tmp='Check')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="spamcheck_emailcheck();" class="adesk_button_ok" />
      <input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_dom_toggle_display('send_test_spam', 'block');" class="adesk_button_cancel" />
    </div>
    </div>

  </div>
</div>