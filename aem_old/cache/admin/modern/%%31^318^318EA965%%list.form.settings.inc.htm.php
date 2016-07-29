<?php /* Smarty version 2.6.12, created on 2016-07-08 16:53:15
         compiled from list.form.settings.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'list.form.settings.inc.htm', 4, false),array('modifier', 'help', 'list.form.settings.inc.htm', 10, false),)), $this); ?>
    <div id="general" class="adesk_block">

      <div class="h2_wrap_static">
        <h5><?php echo ((is_array($_tmp='List Info')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
        <div class="adesk_blockquote">
          <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td colspan="2">
                <?php echo ((is_array($_tmp='Name of List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
                <input name="name" type="text" id="nameField" value="" size="50" style="width:300px;" onblur="setSafeTitle(this.value);" tabindex="1" /><?php echo ((is_array($_tmp="This is used in the public archive and also in the administrative section.  Lists are used to group sets of subscribers")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

              </td>
            </tr>

            <tr>
              <td colspan="2">
                <div><a href="#" onclick="adesk_dom_toggle_class('otherpanel', 'adesk_hidden', 'adesk_block');return false;" tabindex="2"><?php echo ((is_array($_tmp="Additional & Advanced Settings")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></div>
        <div id="otherpanel" class="adesk_hidden" style="margin-top: 10px;">
          <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="2">
                <?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

                <a href="#" onclick="adesk_form_check_selection_element_all('otherpanel', true);return false;"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
                &middot;
                <a href="#" onclick="adesk_form_check_selection_element_all('otherpanel', false);return false;"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
              </td>
            </tr>
            <tr>
              <td><?php echo ((is_array($_tmp='Allow duplicate emails to subscribe')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td><input type="checkbox" name="p_duplicate_subscribe" id="duplicatesubscribePField" value="1" /> <?php echo ((is_array($_tmp="Check this option if you would like to allow subscribers to subscribe multiple times to this list with the same email address.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
</td>
            </tr>
            <tr>
              <td><?php echo ((is_array($_tmp='Allow duplicate emails to be sent')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td><input type="checkbox" name="p_duplicate_send" id="duplicatesendPField" value="1" /> <?php echo ((is_array($_tmp="Check this option if you would like to allow a single campaign to be sent to a single email address multiple times.  Multiple emails would only be sent if an email is subscribed multiple times to the same list.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
</td>
            </tr>
            <tr>
              <td><?php echo ((is_array($_tmp='Ask for reason for unsubscription')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td><input type="checkbox" name="get_unsubscribe_reason" id="unsubreasonPField" value="1" /> <?php echo ((is_array($_tmp="If you check this option your subscribers will be asked why they unsubscribed after they unsubscribe from this list.  Subscribers do not necesarily have to give a reason when they unsubscribe - but this option asks them to submit their reason if they would like to share that information.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
</td>
            </tr>
            <tr>
              <td><?php echo ((is_array($_tmp='Send last campaign to new subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td><input type="checkbox" name="send_last_broadcast" id="lastbroadcastPField" value="1" /> <?php echo ((is_array($_tmp="If you check this option all new subscribers will be sent a copy of the last campaign you sent immediately upon subscribing.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
</td>
            </tr>
            <tr>
              <td><?php echo ((is_array($_tmp='Use Captcha Image')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td>
<?php if ($this->_tpl_vars['site']['gd']): ?>
            	<input type="checkbox" name="p_use_captcha" id="usecaptchaPField" value="1" /> <?php echo ((is_array($_tmp="If you check this option your subscribers will have to look at an image and enter the characters that they see in order to subscribe.  This is used to prevent automated bots or automated software from subscribing to your list.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

<?php else: ?>
            	<input type="hidden" id="usecaptchaPField" value="0" />
            	<?php echo ((is_array($_tmp="Captcha requires GD library to be installed with PHP, and GD is not installed or enabled on your system. Please contact your web host to install or enable GD.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

<?php endif; ?>
              </td>
            </tr>
            <tr>
              <td><?php echo ((is_array($_tmp='Require Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td><input type="checkbox" name="require_name" id="requirenamePField" value="1" /> <?php echo ((is_array($_tmp="When a user subscribes they are asked to enter a name.  If you check this box it will not let them subscribe unless they enter a name")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
</td>
            </tr>
            <tr>
              <td><?php echo ((is_array($_tmp='Do not show in public section')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td><input type="checkbox" name="private" id="privatelistPField" value="1" /> <?php echo ((is_array($_tmp="There is a public section where your subscribers can manage their subscription and view past campaigns.  If you check this box this list will not be shown in the public section.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
</td>
            </tr>
            <tr>
              <td><?php echo ((is_array($_tmp='Default Subscriber Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td><input type="text" name="to_name" id="tonameField" value="" size="30" /> <?php echo ((is_array($_tmp="If you personalize a campaign with the subscribers name - and a subscriber did not enter a name - it will use this default name as their name when sending that campaign.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
 </td>
            </tr>
            <tr>
              <td><?php echo ((is_array($_tmp='Send copies of campaigns to')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td>
                <input name="carboncopy" type="text" id="carboncopyField" value="" size="30" />
                <?php echo ((is_array($_tmp="All campaigns sent to this list will be sent to the provided email address(es). Separate multiple emails by comma")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

              </td>
            </tr>
            <tr>
              <td><?php echo ((is_array($_tmp='Public URL Short Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td>
                <input name="stringid" type="text" id="stringidField" value="" size="30" onblur="setSafeTitle(this.value);" />
                <?php echo ((is_array($_tmp="If search-friendly URLs are used, this list can have a human readable ID that will be a part of the links.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

              </td>
            </tr>
            <tr>
              <td><?php echo ((is_array($_tmp='Subscription notification email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td>
                <input name="subscription_notify" type="text" id="subscriptnotifyField" value="" size="30" />
                <?php echo ((is_array($_tmp="Whenever a subscriber subscribes to this list, the system will send an email to these recipients. Separate multiple emails by comma")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

            </tr>
            <tr>
              <td>
                <?php echo ((is_array($_tmp='Unsubscription notification email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br /></td>
              <td>
                <input name="unsubscription_notify" type="text" id="unsubscriptnotifyField" value="" size="30" />
                <?php echo ((is_array($_tmp="Whenever a subscriber unsubscribes from this list, the system will send an email to these recipients. Separate multiple emails by comma")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

              </td>
            </tr>

<?php if (adesk_admin_ismaingroup ( )): ?>
            <tr>
              <td>
                <?php echo ((is_array($_tmp='List Owner')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br /></td>
              <td>
                <select name="userid" id="useridField" size="1">
<?php $_from = $this->_tpl_vars['groupsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['g']):
?>
                  <optgroup label="<?php echo $this->_tpl_vars['g']['title']; ?>
">
<?php $_from = $this->_tpl_vars['g']['admins']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['u']):
?>
                    <option value="<?php echo $this->_tpl_vars['u']['id']; ?>
"><?php echo $this->_tpl_vars['u']['username']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
                  </optgroup>
<?php endforeach; endif; unset($_from); ?>
                </select>
                <?php echo ((is_array($_tmp="The user who owns the list will determine which branding settings the list uses.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

              </td>
            </tr>
                        <tr>
              <td>
                <?php echo ((is_array($_tmp="Additional List Owners<br />(comma separated user ids)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br /></td>
              <td>
                <input name="additional_owners" type="text" id="additionalownersField" value="" size="30" />
                <?php echo ((is_array($_tmp="If you want to allow access to this list to additonal users, type the comma separated userids. For e.g: 5(for single user,no comma) or 5,6,7. If you dont know user's userid contact administrators or get it from admin->users(if you have access to admin panel)")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

              </td>
            </tr>
<?php else: ?>
<input type="hidden" name="userid" id="useridField" />
<?php endif; ?>

          </table></div>
        </div>
              </td>
            </tr>
          </table></div>
        </div>
      </div>

	  <br />

      <div class="h2_wrap_static">
        <h5><?php echo ((is_array($_tmp="Sender's Contact Information")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
        <div class="adesk_blockquote">
<?php if (! $this->_tpl_vars['__ishosted'] && ! $this->_tpl_vars['admin']['forcesenderinfo']): ?>
          <div class="adesk_help_inline" style="font-size:12px;"><?php echo ((is_array($_tmp="You can easily include your senders contact information (specific to the list you are sending to) by placing the personalization tag %SENDER-INFO% within your outgoing emails. Typically we suggest to place this near the bottom of your email.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
<?php endif; ?>
          <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td colspan="2">
                <?php echo ((is_array($_tmp="Company (or Organization)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
                <input name="sender_name" type="text" id="sendernameField" value="" size="50" tabindex="3" style="width:300px;" onkeyup="list_form_sender_livepreview(this.value, 'company', 1);" />
              </td>

	              <td rowspan="4">&nbsp;</td>
	              <td rowspan="4" valign="top" width="350">
					  <div class="adesk_help_inline" style="font-size:12px; width:300px; <?php if (! $this->_tpl_vars['__ishosted']): ?>display:none<?php endif; ?>">
	              		<?php echo ((is_array($_tmp="Your senders contact information is required for compliance with email sending laws.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

						<div style="font-size:11px; margin-top:10px;"><?php echo ((is_array($_tmp="The company name and address will be included in the footer of all of your emails sent. A preview is below:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
						<div style="font-size:10px;padding:5px; background:#FCFCF2; border:1px dotted #ccc; margin-top:10px;">
							<span style="text-decoration:underline;"><?php echo ((is_array($_tmp='Unsubscribe')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span><br>
							<br />
							<span id="sender_company_display"><?php echo ((is_array($_tmp='Your Company')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span><br />
							<span id="sender_address1_display"><?php echo ((is_array($_tmp='Address')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>, <span id="sender_address2_display"><?php echo ((is_array($_tmp="Suite/Apt")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>,<br />
							<span id="sender_city_display"><?php echo ((is_array($_tmp='City')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>, <span id="sender_state_display"><?php echo ((is_array($_tmp='State')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span> <span id="sender_zip_display"><?php echo ((is_array($_tmp='ZIP')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span><br />
							<span id="sender_country_display"><?php echo ((is_array($_tmp='Country')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
						</div>
					</div>
	              </td>
            </tr>

            <tr>
              <td colspan="2">
                <?php echo ((is_array($_tmp='Address')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
                <input name="sender_addr1" type="text" id="senderaddr1Field" value="" size="50" tabindex="4" style="width:300px;" onkeyup="list_form_sender_livepreview(this.value, 'address1', 1);" /><br />
                <input name="sender_addr2" type="text" id="senderaddr2Field" value="" size="50" tabindex="5" style="width:300px;" onkeyup="list_form_sender_livepreview(this.value, 'address2', 1);" />
              </td>
            </tr>

            <tr>
              <td>
                <?php echo ((is_array($_tmp='City')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
                <input name="sender_city" type="text" id="sendercityField" value="" size="20" tabindex="6" style="width:140px;" onkeyup="list_form_sender_livepreview(this.value, 'city', 1);" />
              </td>
              <td>
                <?php echo ((is_array($_tmp="State, Province, or Region")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
                <input name="sender_state" type="text" id="senderstateField" value="" size="20" tabindex="7" style="width:140px;" onkeyup="list_form_sender_livepreview(this.value, 'state', 1);" />
              </td>
            </tr>
            <tr>
              <td>
                <?php echo ((is_array($_tmp='Zip or Postal Code')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
                <input name="sender_zip" type="text" id="senderzipField" value="" size="20" tabindex="8" style="width:140px;" onkeyup="list_form_sender_livepreview(this.value, 'zip', 1);" />
              </td>
              <td>
                <?php echo ((is_array($_tmp='Country')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
                <input name="sender_country" type="text" id="sendercountryField" value="" size="20" tabindex="9" style="width:140px;" onkeyup="list_form_sender_livepreview(this.value, 'country', 1);" />
              </td>
            </tr>
            <tr style="display:none;">
              <td width="50%">&nbsp;

              </td>
              <td>
                <?php echo ((is_array($_tmp="Phone #")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
                <input name="sender_phone" type="text" id="senderphoneField" value="" size="20" style="width:140px;" />
              </td>
            </tr>

          </table></div>
        </div>
      </div>

    </div>