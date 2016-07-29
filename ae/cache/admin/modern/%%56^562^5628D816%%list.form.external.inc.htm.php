<?php /* Smarty version 2.6.12, created on 2016-07-08 16:53:15
         compiled from list.form.external.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'list.form.external.inc.htm', 5, false),array('modifier', 'help', 'list.form.external.inc.htm', 17, false),)), $this); ?>
<div id="external" class="adesk_block">

  <div class="h2_wrap_static">
		<br />
    <h5><?php echo ((is_array($_tmp='External Services')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
    <div class="adesk_blockquote">
	    <div>
	      <label>
	        <input type="checkbox" value="1" name="p_use_analytics_read" id="analyticsreadPField" onclick="adesk_dom_toggle_class('analyticsread', 'adesk_hidden', 'adesk_block');" />
	        <?php echo ((is_array($_tmp='Google Analytics Read Tracking')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	      </label>
	    </div>
	    <div id="analyticsread" class="adesk_hidden" style="margin-left:23px;">
	      <?php echo ((is_array($_tmp="Your Analytics Account Number (UA):")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
	      <input type="text" name="analytics_ua" id="analyticsuaField" size="12" />
	      <a href="http://www.google.com/support/analytics/bin/answer.py?hl=en&answer=81977" target="_blank"><?php echo ((is_array($_tmp="How to find my Analytics number?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
	      <?php echo ((is_array($_tmp="The Analytics account number should appear in the format UA-xxxxxxx-y. The '-y' towards the end refers to the profile number.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

	    </div>
	    <div>
	      <label>
	        <input type="checkbox" value="1" name="p_use_analytics_link" id="analyticslinkPField" onclick="adesk_dom_toggle_class('analyticslink', 'adesk_hidden', 'adesk_block');" />
	        <?php echo ((is_array($_tmp='Google Analytics Link Tracking')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	      </label>
	    </div>
	    <div id="analyticslink" class="adesk_hidden" style="margin-left:23px;">
	      <div>
	        <?php echo ((is_array($_tmp="Source Name:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
	        <input type="text" name="analytics_source" id="analyticssourceField" size="12" />
	        <a href="#" onclick="$('analyticssourceField').value = $('nameField').value;return false;"><?php echo ((is_array($_tmp='Use Name of List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
	        <?php echo ((is_array($_tmp="This will appear in your Analytics account as a Source of site visits. This is usually a Name of List")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

	      </div>
	      <div>
	        <div class="cloner" id="analyticsClonerDiv">
	          <h3><?php echo ((is_array($_tmp="Add domains that use Analytics for tracking site traffic:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
	          <p><?php echo ((is_array($_tmp="For wildcard characters, use '%'.<br />For example, to match all subdomains for the domain \"example.com\", type \"%.example.com\"<br />or, to match all IP addresses starting with 192.168, enter \"192.168.%\"")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</p>
	          <div class="listRow">
	            <input type="text" name="analytics_domains[]" value="" />
	            <input type="button" class="adesk_button_remove" onclick="remove_element(this.parentNode); return false;" value="<?php echo ((is_array($_tmp='Remove')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" />
	          </div>
	        </div>
	        <p>
	          <input type="button" class="adesk_button_add" onclick="clone_1st_div($('analyticsClonerDiv')); return false;" value="<?php echo ((is_array($_tmp='Add Domain or IP')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" />
	        </p>
	      </div>
	    </div>

			<?php if ($this->_tpl_vars['pass']): ?>

		    <div id="twitter_checkbox">
		      <label>
		        <input type="checkbox" value="1" name="p_use_twitter" id="twitterPField" onclick="adesk_dom_toggle_class('twitter', 'adesk_hidden', 'adesk_block');" />
		        <?php echo ((is_array($_tmp='Twitter')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		      </label>
		      <?php echo ((is_array($_tmp="Campaigns sent to this list will be auto-posted to Twitter with a link to your campaign web copy.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

		    </div>

				<div id="twitter_disabled" style="display: none; border:1px solid #F1DF0A; margin-bottom:10px; margin-top: 20px; font-size:13px; padding:10px; background-color:#FFFDE6;">
					<div style="background:url(../awebdesk/media/sign_warning.png); background-position:left; background-repeat:no-repeat; padding: 5px 0 5px 42px;">
			  		<?php echo ((is_array($_tmp='Twitter authentication is disabled until you verify your')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <a href="desk.php?action=service"><?php echo ((is_array($_tmp='Twitter application keys')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>.
			  	</div>
		  	</div>

				<div id="twitter" class="adesk_block" style="margin-left:23px;">

					<div id="twitter_confirmed">
						<p>
							<?php echo ((is_array($_tmp="Logged in to Twitter as:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

							<a href="" id="twitter_confirmed_screenname" target="_blank"></a>
							<?php echo ((is_array($_tmp="To update a different Twitter account, uncheck Twitter above, save, then come back and re-enter your information.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

							<?php if (adesk_admin_ismaingroup ( )): ?>
								<span id="twitter_token_diff">
									<a href="javascript: list_form_external_twitter_mirror();"><?php echo ((is_array($_tmp='Use this Twitter account for all Lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
									<?php echo ((is_array($_tmp="This will mirror Twitter authentication from this list to all lists, meaning all mailings will update to this Twitter account")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

								</span>
							<?php else: ?>
								<input type="hidden" id="twitter_token_diff" />
							<?php endif; ?>
						</p>
					</div>

					<div id="twitter_unconfirmed">
						<p>
							<a href="" id="twitter_register_url"><?php echo ((is_array($_tmp='Login to Twitter')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
							<?php echo ((is_array($_tmp="After logging into Twitter, you will be asked to confirm that AwebDesk is allowed to update your Twitter account, then copy the PIN number.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

						</p>
					</div>

				</div>

		    <div>
		      <label>
		        <input type="checkbox" value="1" name="p_use_facebook" id="facebookPField" onclick="list_form_external_facebook_toggle();" />
		        <?php echo ((is_array($_tmp='Facebook')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		      </label>
		      <?php echo ((is_array($_tmp="Campaigns sent to this list will be auto-posted to Facebook with a link to your campaign web copy.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

		    </div>

				<div id="facebook" style="margin-left:23px;">

					<div id="facebook_confirmed">
						<p>
							<?php echo ((is_array($_tmp="Logged in to Facebook as:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

							<a href="" id="facebook_account_profile" target="_blank"></a>
						</p>
						<p><a href="" id="facebook_account_logout_url"><?php echo ((is_array($_tmp='Logout of this Facebook account')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></p>
					</div>

					<div id="facebook_unconfirmed">
						<?php if (( $this->_tpl_vars['site']['facebook_app_id'] && $this->_tpl_vars['site']['facebook_app_secret'] ) || $this->_tpl_vars['__ishosted']): ?>
							<p>
								<a href="" id="facebook_account_login_url"><?php echo ((is_array($_tmp='Login to Facebook')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
								<?php echo ((is_array($_tmp="After logging into Facebook, you will be asked to confirm that AwebDesk is allowed to update your Facebook account.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

							</p>
						<?php else: ?>
							<?php if (! adesk_site_hosted_rsid ( )): ?>
							<p>
								<a href="desk.php?action=service"><?php echo ((is_array($_tmp='Configure your Facebook application on the External Services page')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>.
							</p>
							<?php endif; ?>
							<input type="hidden" name="facebook_account_login_url" id="facebook_account_login_url" />
						<?php endif; ?>
					</div>

					<div id="facebook_invalid" class="external_form_help">
						<p>
							<?php echo ((is_array($_tmp="Please save your list before setting up Facebook auto-sharing.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

						</p>
					</div>

				</div>

			<?php else: ?>

				<div style="border:1px solid #F1DF0A; margin-bottom:10px; margin-top: 20px; font-size:13px; padding:10px; background-color:#FFFDE6;">
					<div style="background:url(../awebdesk/media/sign_warning.png); background-position:left; background-repeat:no-repeat; padding: 5px 0 5px 42px;">
			  		<?php echo ((is_array($_tmp="Twitter and Facebook integration server requirements: cURL, HMAC, JSON, PHP 5+.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			  	</div>
		  	</div>

		    <div id="twitter">

					<input type="checkbox" name="p_use_twitter" id="twitterPField" class="adesk_hidden" />
					<input type="hidden" name="twitter_checkbox" id="twitter_checkbox" />
					<input type="hidden" name="twitter_confirmed" id="twitter_confirmed" />
					<input type="hidden" name="twitter_unconfirmed" id="twitter_unconfirmed" />
					<input type="hidden" name="twitter_status_confirmed" id="twitter_status_confirmed" />
					<input type="hidden" name="twitter_status_unconfirmed" id="twitter_status_unconfirmed" />
					<input type="hidden" id="twitter_token_diff" />

				</div>

		    <div id="facebook">

					<input type="checkbox" name="p_use_facebook" id="facebookPField" class="adesk_hidden" />
					<input type="hidden" name="facebook_confirmed" id="facebook_confirmed" />
					<input type="hidden" name="facebook_unconfirmed" id="facebook_unconfirmed" />
					<input type="hidden" name="facebook_account_logout_url" id="facebook_account_logout_url" />
					<input type="hidden" name="facebook_account_login_url" id="facebook_account_login_url" />
					<input type="hidden" name="facebook_invalid" id="facebook_invalid" />

				</div>

			<?php endif; ?>

    </div>

	</div>

</div>