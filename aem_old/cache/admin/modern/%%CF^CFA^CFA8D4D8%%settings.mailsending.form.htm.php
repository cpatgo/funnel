<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:51
         compiled from settings.mailsending.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'settings.mailsending.form.htm', 5, false),array('modifier', 'help', 'settings.mailsending.form.htm', 111, false),)), $this); ?>
<input type="hidden" id="mailer_form_id" name="id" value="" />
<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="0" id="mailconnItemTable">
  <tr>
    <td colspan="2">
      <h5><?php echo ((is_array($_tmp='Mail Connection')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
    </td>
  </tr>
  <tr>
    <td width="25" valign="top">&nbsp;</td>
    <td>
      <strong><?php echo ((is_array($_tmp='Connection Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong>
      <input id="smname" name="smname" value="" type="text" size="20" />
      <br />
      <?php echo ((is_array($_tmp="Name this mail connection so you can recognize it better in your lists. This does not have any influence on server names the connection might use.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    </td>
  </tr>
  <tr>
    <td colspan="2"><hr /></td>
  </tr>

  <tr>
    <td colspan="2">
      <h5><?php echo ((is_array($_tmp='Sending Method')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
    </td>
  </tr>
  <tr>
    <td width="25" valign="top"><input id="smmail" name="send" type="radio" value="0" onclick="if(this.checked)$('smtpInfo').className='adesk_hidden';" /></td>
    <td>
      <label for="smmail">
        <strong><?php echo ((is_array($_tmp='Default Sending Method')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong><br />
        (<?php echo ((is_array($_tmp="Will use the default MTA on your system. Such as sendmail, qmail, etc...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
)
      </label>
    </td>
  </tr>

  
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="25"><input id="smsmtp" name="send" type="radio" value="1" onclick="if(this.checked)$('smtpInfo').className='adesk_table_rowgroup';" /></td>
    <td><label for="smsmtp"><strong><?php echo ((is_array($_tmp='SMTP')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong></label></td>
  </tr>
  <tbody id="smtpInfo" class="adesk_hidden">
<?php if (0 && $this->_tpl_vars['site']['brand_links']): ?>
    <tr>
      <td colspan="2">
		<div class="adesk_help_inline" style="font-size:11px;">
			<p><strong><font color="#333333"><?php echo ((is_array($_tmp="If using your own SMTP server information:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</font></strong> <br />
			<?php echo ((is_array($_tmp="You can send using any SMTP server that you have information for.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</p>
			<ol>
			  <li><?php echo ((is_array($_tmp="Enter your SMTP server, port # (typically 25), username, and password")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
			  <li><?php echo ((is_array($_tmp="Some servers require a specific encryption to be set and/or require POP3 authentication")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
			  <li><?php echo ((is_array($_tmp='Send a test email to verify your settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
			</ol>
			<p><?php echo ((is_array($_tmp="If you do not have your SMTP information or have questions about your SMTP info you can contact your webmaster or server administrator.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
			  <br />
			    <strong><font color="#666666"><?php echo ((is_array($_tmp="If using the %s SMTP service:")) ? $this->_run_mod_handler('alang', true, $_tmp, '<a href="http://awebdesk.smtp.com/" target="_blank">awebdesk.SMTP.com</a>') : smarty_modifier_alang($_tmp, '<a href="http://awebdesk.smtp.com/" target="_blank">awebdesk.SMTP.com</a>')); ?>
</font></strong><br />
			    <?php echo ((is_array($_tmp="Use of %s is 100%% optional for using this product.  It is a service that assists with email delivery and sending of your emails.")) ? $this->_run_mod_handler('alang', true, $_tmp, 'awebdesk.smtp.com') : smarty_modifier_alang($_tmp, 'awebdesk.smtp.com')); ?>
</p>
			<ol>
			  <li><?php echo ((is_array($_tmp="Signup at %s")) ? $this->_run_mod_handler('alang', true, $_tmp, '<a href="http://awebdesk.smtp.com/" target="_blank">awebdesk.smtp.com</a>') : smarty_modifier_alang($_tmp, '<a href="http://awebdesk.smtp.com/" target="_blank">awebdesk.smtp.com</a>')); ?>
</li>
			  <li><?php echo ((is_array($_tmp="Enter %s as the SMTP server (or the server that they specify is your SMTP server)")) ? $this->_run_mod_handler('alang', true, $_tmp, 'awebdesk.smtp.com') : smarty_modifier_alang($_tmp, 'awebdesk.smtp.com')); ?>
</li>
			  <li><?php echo ((is_array($_tmp='Enter port 25 for the SMTP port')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
			  <li><?php echo ((is_array($_tmp="Enter your username & password that %s provides you")) ? $this->_run_mod_handler('alang', true, $_tmp, 'awebdesk.smtp.com') : smarty_modifier_alang($_tmp, 'awebdesk.smtp.com')); ?>
</li>
			  <li><?php echo ((is_array($_tmp='Send a test email to verify your settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
			</ol>
		</div>
      </td>
    </tr>
<?php endif; ?>
  <tr>
    <td width="25" valign="top">&nbsp;</td>
    <td>
      <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0">
        <tr>
          <td valign="top">
            <?php echo ((is_array($_tmp='SMTP Server')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
            <input name="smhost" type="text" id="smhost" value="" size="20" tabindex="1" />
          </td>
          <td valign="top">
            <?php echo ((is_array($_tmp='Port')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
            <input name="smport" type="text" id="smport" value="" size="8" tabindex="2" />
          </td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">
            <?php echo ((is_array($_tmp='Username')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            <?php echo ((is_array($_tmp="If authentication is not required, do not enter a username.")) ? $this->_run_mod_handler('help', true, $_tmp, 'block') : smarty_modifier_help($_tmp, 'block')); ?>

            <br />
            <div style="padding-right: 10px;"><input name="smuser" type="text" id="smuser" value="" size="20" tabindex="3" /></div>
          </td>
          <td valign="top">
            <?php echo ((is_array($_tmp='Password')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            <?php echo ((is_array($_tmp="If authentication is not required, do not enter a password.")) ? $this->_run_mod_handler('help', true, $_tmp, 'block') : smarty_modifier_help($_tmp, 'block')); ?>

            <br />
            <input name="smpass" type="password" id="smpass" value="" size="20" tabindex="4" />
          </td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">
            <?php echo ((is_array($_tmp='Encryption')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <br />
            <select id="smenc" name="smenc" onchange="$('smport').value=(this.value==8?25:465);" tabindex="5">
              <option value="8"><?php echo ((is_array($_tmp='Off')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              <option value="4"><?php echo ((is_array($_tmp='SSL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              <option value="2"><?php echo ((is_array($_tmp='TLS')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
            </select>
          </td>
          <td valign="top">
            <label>
              <?php echo ((is_array($_tmp='Use POP3 Authentication')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
              <input name="smpop3b4" type="checkbox" id="smpop3b4"  value="1" tabindex="6" />
            </label>
          </td>
         </tr>
	  </table></div>

    </td>
  </tr>
</tbody>
<tbody id="rotatorEdit">
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><hr /></td>
  </tr>
  <tr>
   	<td>&nbsp;</td>
	<td><?php echo ((is_array($_tmp="Emails Per Cycle:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

      <input name="smthres" type="text" id="smthres" value="50" size="5" />
      <?php echo ((is_array($_tmp="(Optional) Specify how many emails you wish to send with this mail connection before looking for the next connection. If you only have one mail connection this setting does not matter.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

    </td>
  </tr>
</tbody>

  <tr>
    <td colspan="2"><hr /></td>
  </tr>
  <tr>
    <td colspan="2">
      <h5><?php echo ((is_array($_tmp='Sending Speed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
    </td>
  </tr>
  <tr>
   	<td valign="top">
     <input name="ltype" id="dontstop" type="radio" value="dontstop" onclick="$('sdbox').className='adesk_hidden';$('limbox').className='adesk_hidden';" />
   	</td>
	<td>
	  <label for="dontstop">
	    <?php echo ((is_array($_tmp='Send without limitations')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </label>
    </td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
   	<td valign="top">
      <input name="ltype" id="sd" type="radio" value="sd" onclick="$('sdbox').className='adesk_block';$('limbox').className='adesk_hidden';" />
   	</td>
	<td>
	  <label for="sd">
	    <?php echo ((is_array($_tmp='Enable sending throttling and pausing')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </label>
      <?php echo ((is_array($_tmp="(Optional) This option will automatically pause your mailing after a certain number of messages.  This helps reduce your mail servers load.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

      <div id="sdbox" class="adesk_hidden" style="margin-left:40px;">
        <?php echo ((is_array($_tmp='Your sending will pause for')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        <input type="text" name="sdnum" id="sdnum" value="" size="3" onchange="if(!checkSendPause())return false;calculateSendingSpeed();" />
        <?php echo ((is_array($_tmp='seconds after')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        <input type="text" name="sdfreq" id="sdfreq" value="" size="3" onchange="calculateSendingSpeed();" />
        <?php echo ((is_array($_tmp="emails are sent.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

      </div>
    </td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
   	<td valign="top">
	    <input name="ltype" id="lim" type="radio" value="lim" onclick="$('sdbox').className='adesk_hidden';$('limbox').className='adesk_block';" />
   	</td>
	<td>
	  <label for="lim">
	    <?php echo ((is_array($_tmp='Limit number of emails to send for a specific time period')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </label>
      <?php echo ((is_array($_tmp="(Optional) You can specify a limit of the number of emails to send in a certain time period. (Example = Only allow 1,000 emails per hour)")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

      <div id="limbox" class="adesk_hidden" style="margin-left:40px;">
        <input name="sdlim" type="text" id="sdlim" value="" size="5" onchange="calculateSendingSpeed();" />
        <select name="sdspan" id="sdspan" size="1" onchange="calculateSendingSpeed();">
          <option value="hour"><?php echo ((is_array($_tmp='Per Hour')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="day"><?php echo ((is_array($_tmp='Per Day')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
        </select>
      </div>
    </td>
  </tr>
    <td colspan="2"><hr /></td>
  </tr>
  <tr valign="top">
    <td>&nbsp;</td>
    <td>
      <?php echo ((is_array($_tmp="Used by User Groups:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
						<div class="adesk_checkboxlist">
							<?php $_from = $this->_tpl_vars['groupsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
								<div>
									<label>
										<input type="checkbox" id="p_<?php echo $this->_tpl_vars['p']['id']; ?>
" name="p[]" value="<?php echo $this->_tpl_vars['p']['id']; ?>
" class="groupfield" <?php if (count ( $this->_tpl_vars['listsList'] ) == 1): ?>checked="checked"<?php endif; ?> />
										<?php echo $this->_tpl_vars['p']['title']; ?>

									</label>
								</div>
							<?php endforeach; endif; unset($_from); ?>
						</div>
						<div align="right" style="width: 300px;">
			<?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			<a href="#" onclick="$$('.groupfield').each(function(e) <?php echo '{ e.checked = true; }'; ?>
); return false"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			&middot;
			<a href="#" onclick="adesk_dom_boxclear('groupfield'); return false"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
						</div>
    </td>
  </tr>

</table></div>
<br />