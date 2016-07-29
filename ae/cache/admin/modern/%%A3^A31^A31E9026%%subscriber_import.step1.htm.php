<?php /* Smarty version 2.6.12, created on 2016-07-08 14:17:52
         compiled from subscriber_import.step1.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'subscriber_import.step1.htm', 5, false),array('modifier', 'help', 'subscriber_import.step1.htm', 19, false),array('modifier', 'escape', 'subscriber_import.step1.htm', 64, false),)), $this); ?>
<?php if ($this->_tpl_vars['__ishosted']): ?>


		<h5>
			<?php echo ((is_array($_tmp="What can I import?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
		</h5><div class="line"></div>
		<div style=" line-height:150%; margin-left:15px;  font-size:13px; ">
		All subscribers must have given you permission to send email to them.  <strong>Paid lists, rented lists, borrowed lists, scraped emails (that you copied from web sites), etc.. are not allowed.</strong> Your subscribers should be aware that you may email them and must have knowingly (and directly) confirmed that it is OK for you to email them.		</div>
<br />

<?php endif; ?>
<form id="importCfgForm" action="desk.php?action=subscriber_import" enctype="multipart/form-data" method="post" onsubmit="return import_submit_step1();">

	<h5><?php echo ((is_array($_tmp='Import From')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
	<div style="font-size:14px; margin-left:15px;">
		<div>
			<label>
				<input type="radio" id="from_file" name="from" value="file" onclick="import_set_from(this); adesk_ui_anchor_set(this.value);" checked="checked" />
				<?php echo ((is_array($_tmp='A File')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php echo ((is_array($_tmp='Recommended for large scale imports')) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

			</label>
		</div>
		<div>
			<label>
				<input type="radio" id="from_text" name="from" value="text" onclick="import_set_from(this); adesk_ui_anchor_set(this.value);" />
				<?php echo ((is_array($_tmp="Copy/Paste")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php echo ((is_array($_tmp='Recommended for small scale imports')) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

			</label>
		</div>
<?php if ($this->_tpl_vars['external_sources_supported']): ?>
		<div>
			<label>
				<input type="radio" id="from_external" name="from" value="external" onclick="import_set_from(this); adesk_ui_anchor_set(this.value);" />
				<?php echo ((is_array($_tmp='An External Service')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php echo ((is_array($_tmp="Recommended for synchronization with external services/applications")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

			</label>
		</div>
<?php endif; ?>


        <div>
			<label>
				<input type="radio" id="from_external_db" name="from" value="external_db"  onClick='goToLocation(this.value)'/>
				<?php echo ((is_array($_tmp='An External Database')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php echo ((is_array($_tmp='Recommended for synchronization with external database')) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

			</label>
		</div>



	</div>

	<br />

	<div id="import_file">
		<h5><?php echo ((is_array($_tmp='Your File')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>



		<div style="margin-left:15px;">
		  <div id="error_importfrom_file" style="display:none">
			<div style="border:1px solid #F1DF0A; margin-bottom:10px; font-size:13px; padding:10px; background-color:#FFFDE6;">
			  <div style="background:url(../awebdesk/media/sign_warning.png); background-position:left; background-repeat:no-repeat; padding-left:42px; padding-top: 5px; padding-bottom: 5px">
				<?php if (isset ( $this->_tpl_vars['submitResult'] ) && $this->_tpl_vars['submitResult']['section'] == 'importfrom_file'): ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['submitResult']['message'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

				<?php endif; ?>
			  </div>
			</div>
		  </div>
			<input type="file" name="file" value="" />
			<?php if ($this->_tpl_vars['maxfilesize']): ?>
			<span style="color:#999;"><?php echo ((is_array($_tmp="(Maximum file size: %s)")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['maxfilesize']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['maxfilesize'])); ?>
</span>
			<?php endif; ?>
		</div>
	</div>

	<div id="import_text" style="display:none">
		<h5><?php echo ((is_array($_tmp='Paste Your List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
		<div style="margin-left:15px;">
		<script>var removedInitialText = false;</script>
			<textarea name="text" rows="10" cols="80" style="width: 96%;height: 300px; color:#666;" onfocus="if(removedInitialText)return;this.value='';this.style.color='#000';removedInitialText=true;">
<?php echo ((is_array($_tmp='Type or paste your existing subscribers into this box using the following format')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:

bob@bobsmith.com
rob@robsmith.com

     - <?php echo ((is_array($_tmp='or')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 -

"Bob Smith", "bob@bobsmith.com", "some custom field value"
"Rob Smith", "rob@robsmith.com", "some custom field value"
			</textarea>
		</div>
	</div>

	<div id="import_external" style="display:none">
	  <h5><?php echo ((is_array($_tmp='Select External Source')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
	  <input type="hidden" name="external" id="external" />

	  <?php if (! $this->_tpl_vars['__ishosted'] && adesk_admin_ismaingroup ( ) && ! $this->_tpl_vars['all_external_sources_supported']): ?>
		  <div id="error_importfrom_file">
				<div style="border:1px solid #F1DF0A; margin-bottom:10px; font-size:13px; padding:10px; background-color:#FFFDE6;">
				  <div style="background:url(../awebdesk/media/sign_warning.png); background-position:left; background-repeat:no-repeat; padding-left:42px; padding-top: 5px; padding-bottom: 5px">
				  	<?php echo ((is_array($_tmp="Not all external sources are available, based on your server configuration. To use more external sources, try enabling:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				  	<ul>
					  	<?php $_from = $this->_tpl_vars['external_sources_check']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['requirement'] => $this->_tpl_vars['info']):
?>
					  		<?php if (! $this->_tpl_vars['info']['supported']): ?>
					  			<li><?php echo $this->_tpl_vars['info']['name']; ?>
</li>
					  		<?php endif; ?>
					  	<?php endforeach; endif; unset($_from); ?>
				  	</ul>
				  </div>
				</div>
		  </div>
	  <?php endif; ?>

	  <div class="import_external_source">

		  <?php $_from = $this->_tpl_vars['external_sources']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['source'] => $this->_tpl_vars['info']):
?>
		  	<?php if ($this->_tpl_vars['info']['supported']): ?>
					<div onclick="set_external('<?php echo $this->_tpl_vars['source']; ?>
'); adesk_ui_anchor_set('<?php echo $this->_tpl_vars['source']; ?>
');" id="external_div_<?php echo $this->_tpl_vars['source']; ?>
" class="import_external_source_notselected">
					  <img src="images/import-<?php echo $this->_tpl_vars['info']['image']; ?>
" />
					</div>
				<?php endif; ?>
		  <?php endforeach; endif; unset($_from); ?>

	  </div>
	  <br clear="all" />
	</div>

	<br />

	<div id="external_box_configs" style="display:none;font-size:14px;">
	  <h5><?php echo ((is_array($_tmp='External Source Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>

	  <div class="adesk_blockquote">
<?php if ($this->_tpl_vars['admin']['brand_links']): ?>
		<div id="external_box_hd" style="display:none"">
		  <div id="external_form_hd">
			<div class="external_form_help">
			  <?php echo ((is_array($_tmp="AwebDesk Help Desk takes your customer service to a whole new level with full automation controls, collaborative tools, endless customization options, and much more.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <a href="http://www.awebdesk.com/helpdesk/" target="blank"><?php echo ((is_array($_tmp='Learn More')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			<div>
			  <?php echo ((is_array($_tmp="Help Desk URL:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
			  <input type="text" name="hd_url" id="hd_url" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['hd']['form_values']['hd_url'] )):  echo $this->_tpl_vars['external_sources']['hd']['form_values']['hd_url'];  endif; ?>" />
			</div>
			<div>
			  <?php echo ((is_array($_tmp="Admin Username:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
			  <input type="text" name="hd_user" id="hd_user" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['hd']['form_values']['hd_user'] )):  echo $this->_tpl_vars['external_sources']['hd']['form_values']['hd_user'];  endif; ?>" />
			</div>
			<div>
			  <?php echo ((is_array($_tmp="Admin Password:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
			  <input type="password" name="hd_pass" id="hd_pass" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['hd']['form_values']['hd_pass'] )):  echo $this->_tpl_vars['external_sources']['hd']['form_values']['hd_pass'];  endif; ?>" />
			</div>
					  </div>
		  <div id="external_config_hd" style="display:none"></div>
		</div>
<?php endif; ?>

		<div id="external_box_hr" style="display:none">
		  <div id="external_form_hr">

			<div class="external_form_help">
			  <?php echo ((is_array($_tmp="Highrise is a contact management and CRM tool for small businesses and entrepenuers to manage their customer relationships.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <a href="http://highrisehq.com/" target="blank"><?php echo ((is_array($_tmp='Learn More')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			<div>
			  <?php echo ((is_array($_tmp="Highrise URL:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
			  <input type="text" name="hr_url" id="hr_url" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['hr']['form_values']['hr_url'] )):  echo $this->_tpl_vars['external_sources']['hr']['form_values']['hr_url'];  endif; ?>" />
			</div>
			<div>
			  <?php echo ((is_array($_tmp="API Token:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
			  <input type="text" name="hr_api" id="hr_api" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['hr']['form_values']['hr_api'] )):  echo $this->_tpl_vars['external_sources']['hr']['form_values']['hr_api'];  endif; ?>" />
			</div>
					  </div>
		  <div id="external_config_hr" style="display:none"></div>
		</div>

		<div id="external_box_google_contacts" style="display:none">
		  <div id="external_form_google_contacts">
				<div class="external_form_help">
				  <?php echo ((is_array($_tmp="Google Contacts are stored in each users Google Account; most Google services have access to the contact list.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <a href="http://mail.google.com/support/bin/topic.py?hl=en&topic=12867" target="blank"><?php echo ((is_array($_tmp='Learn More')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>
				<?php if (! $this->_tpl_vars['google_contacts_token']): ?>
					<p><a href="<?php echo $this->_tpl_vars['google_contacts_oauth_url']; ?>
"><?php echo ((is_array($_tmp='Authorize Google Contacts access')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></p>
				<?php else: ?>
					<p style="color: green; font-weight: bold;"><?php echo ((is_array($_tmp='Google Contacts access authorized')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 (<a href="desk.php?action=subscriber_import&google_contacts_logout=1#google_contacts"><?php echo ((is_array($_tmp='Revoke')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>)</p>
				<?php endif; ?>
		  </div>
		  <div id="external_config_google_contacts" style="display:none"></div>
		</div>
		
		<div id="external_box_google_spreadsheets" style="display:none">
		  <div id="external_form_google_spreadsheets">
				<div class="external_form_help">
				  <?php echo ((is_array($_tmp="Google Docs are part of each Google Account, and allow you to add and modify documents online.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <a href="http://docs.google.com/" target="blank"><?php echo ((is_array($_tmp='Learn More')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>
				<?php if (! $this->_tpl_vars['google_spreadsheets_token']): ?>
					<p><a href="<?php echo $this->_tpl_vars['google_spreadsheets_oauth_url']; ?>
"><?php echo ((is_array($_tmp='Authorize Google Spreadsheets access')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></p>
				<?php else: ?>
					<p style="color: green; font-weight: bold;"><?php echo ((is_array($_tmp='Google Spreadsheets access authorized')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 (<a href="desk.php?action=subscriber_import&google_spreadsheets_logout=1#google_spreadsheets"><?php echo ((is_array($_tmp='Revoke')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>)</p>
				<?php endif; ?>
		  </div>
		  <div id="external_config_google_spreadsheets" style="display:none"></div>
		</div>

		<div id="external_box_freshbooks" style="display:none">
		  <div id="external_form_freshbooks">
				<div class="external_form_help">
				  <?php echo ((is_array($_tmp="Freshbooks helps simplify invoicing and time tracking services that help you manage your business.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <a href="https://campaign.freshbooks.com/refer/www" target="blank"><?php echo ((is_array($_tmp='Learn More')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>
				<?php if (! $this->_tpl_vars['freshbooks_account'] && ! $this->_tpl_vars['freshbooks_token']): ?>
					<div style="margin: 10px 0;">
						http://<input type="text" name="freshbooks_account" id="freshbooks_account" />.freshbooks.com
						<?php echo ((is_array($_tmp='Include your Freshbooks account name')) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

					</div>
					<input type="button" value="<?php echo ((is_array($_tmp='Authorize')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="import_freshbooks_authorize();" style="margin-bottom: 15px;" />
				<?php else: ?>
					<p style="color: green; font-weight: bold;"><?php echo ((is_array($_tmp='Freshbooks access authorized')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 (<a href="desk.php?action=subscriber_import&freshbooks_logout=1#freshbooks"><?php echo ((is_array($_tmp='Revoke')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>)</p>
				<?php endif; ?>
		  </div>
		  <div id="external_config_freshbooks" style="display:none"></div>
		</div>
	<div id="external_box_zendesk" class="formfieldwrap" style="display: none;">
				  <div id="external_form_zendesk">
						<div class="adesk_help_inline" style="margin-bottom: 15px;"><?php echo ((is_array($_tmp="Powerful yet simple multi-channel customer service software.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <a href="http://www.zendesk.com/" target="blank"><?php echo ((is_array($_tmp='Learn More')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></div>
						<fieldset>
							<label for="zendesk_account"><?php echo ((is_array($_tmp='Account URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
							<div class="campaign_help"><?php echo ((is_array($_tmp="Your account name, as it appears in the URL. (Example: https://myaccount.zendesk.com)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
							<div class="inputwrap">https:// <input type="text" name="zendesk_account" id="zendesk_account" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['zendesk']['form_values']['zendesk_account'] )):  echo $this->_tpl_vars['external_sources']['zendesk']['form_values']['zendesk_account'];  endif; ?>" tabindex="1" />.zendesk.com</div>
						</fieldset>
						<fieldset>
							<label for="zendesk_username"><?php echo ((is_array($_tmp='Username')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
							<input type="text" name="zendesk_username" id="zendesk_username" class="form_input_name" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['zendesk']['form_values']['zendesk_username'] )):  echo $this->_tpl_vars['external_sources']['zendesk']['form_values']['zendesk_username'];  endif; ?>" tabindex="1" />
							<label style="margin-top:10px;" for="zendesk_password"><?php echo ((is_array($_tmp='Password')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
							<input type="password" name="zendesk_password" id="zendesk_password" autocomplete="off" style="width:290px;" class="form_input_name" value="<?php if (isset ( $this->_tpl_vars['external_sources']['zendesk']['form_values']['zendesk_password'] )):  echo $this->_tpl_vars['external_sources']['zendesk']['form_values']['zendesk_password'];  endif; ?>" tabindex="2" />
						</fieldset>
				  </div>
				  <div id="external_config_zendesk" style="display:none"></div>
				</div>
		<div id="external_box_salesforce" style="display:none">
		  <div id="external_form_salesforce">
			<div class="external_form_help">
			  <?php echo ((is_array($_tmp="Salesforce is the leader in customer relationship management (CRM) & cloud computing.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <a href="http://www.salesforce.com/" target="blank"><?php echo ((is_array($_tmp='Learn More')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			<div>
			  <?php echo ((is_array($_tmp='Username')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:<br />
			  <input type="text" name="salesforce_username" id="salesforce_username" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['salesforce']['form_values']['salesforce_username'] )):  echo $this->_tpl_vars['external_sources']['salesforce']['form_values']['salesforce_username'];  endif; ?>" tabindex="1" />
			</div>
			<div>
			  <?php echo ((is_array($_tmp='Password')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:<br />
			  <input type="password" name="salesforce_password" id="salesforce_password" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['salesforce']['form_values']['salesforce_password'] )):  echo $this->_tpl_vars['external_sources']['salesforce']['form_values']['salesforce_password'];  endif; ?>" tabindex="2" />
			</div>
			<div>
			  <?php echo ((is_array($_tmp='Security Token')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
: <?php echo ((is_array($_tmp="You can obtain your security token by logging into Salesforce, and going to YOUR NAME | Setup | Reset your security token (under My Personal Information). You will then be emailed your security token.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
<br />
			  <input type="text" name="salesforce_token" id="salesforce_token" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['salesforce']['form_values']['salesforce_token'] )):  echo $this->_tpl_vars['external_sources']['salesforce']['form_values']['salesforce_token'];  endif; ?>" tabindex="3" />
			</div>
		  </div>
		  <div id="external_config_salesforce" style="display:none"></div>
		</div>

		<div id="external_box_sugarcrm" style="display:none">
		  <div id="external_form_sugarcrm">
			<div class="external_form_help">
			  <?php echo ((is_array($_tmp="SugarCRM helps companies communicate with prospects, share sales information, close deals and keep customers happy.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <a href="http://www.sugarcrm.com/crm/" target="blank"><?php echo ((is_array($_tmp='Learn More')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			<div>
			  <?php echo ((is_array($_tmp='URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
: <?php echo ((is_array($_tmp='Your unique URL to your SugarCRM installation')) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
<br />
			  <input type="text" name="sugarcrm_url" id="sugarcrm_url" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['sugarcrm']['form_values']['sugarcrm_url'] )):  echo $this->_tpl_vars['external_sources']['sugarcrm']['form_values']['sugarcrm_url'];  endif; ?>" tabindex="1" />
			</div>
			<div>
			  <?php echo ((is_array($_tmp='Username')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
: <?php echo ((is_array($_tmp='Any user that can log into the admin section of SugarCRM can authenticate here')) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
<br />
			  <input type="text" name="sugarcrm_username" id="sugarcrm_username" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['sugarcrm']['form_values']['sugarcrm_username'] )):  echo $this->_tpl_vars['external_sources']['sugarcrm']['form_values']['sugarcrm_username'];  endif; ?>" tabindex="2" />
			</div>
			<div>
			  <?php echo ((is_array($_tmp='Password')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:<br />
			  <input type="password" name="sugarcrm_password" id="sugarcrm_password" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['sugarcrm']['form_values']['sugarcrm_password'] )):  echo $this->_tpl_vars['external_sources']['sugarcrm']['form_values']['sugarcrm_password'];  endif; ?>" tabindex="3" />
			</div>
		  </div>
		  <div id="external_config_sugarcrm" style="display:none"></div>
		</div>

		<div id="external_box_zohocrm" style="display:none">
		  <div id="external_form_zohocrm">
			<div class="external_form_help">
			  <?php echo ((is_array($_tmp="Zoho CRM aligns your Sales and Marketing by integrating sales with campaigns, leads, sales pipeline, forecasts, etc.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <a href="http://www.zoho.com/crm" target="blank"><?php echo ((is_array($_tmp='Learn More')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			<div>
			  <?php echo ((is_array($_tmp='Username')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
: <?php echo ((is_array($_tmp='Your Zoho username or email address')) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
<br />
			  <input type="text" name="zohocrm_username" id="zohocrm_username" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['zohocrm']['form_values']['zohocrm_username'] )):  echo $this->_tpl_vars['external_sources']['zohocrm']['form_values']['zohocrm_username'];  endif; ?>" tabindex="1" />
			</div>
			<div>
			  <?php echo ((is_array($_tmp='Password')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:<br />
			  <input type="password" name="zohocrm_password" id="zohocrm_password" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['zohocrm']['form_values']['zohocrm_password'] )):  echo $this->_tpl_vars['external_sources']['zohocrm']['form_values']['zohocrm_password'];  endif; ?>" tabindex="2" />
			</div>
			<div>
			  <?php echo ((is_array($_tmp='API Key')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
: <?php echo ((is_array($_tmp="To generate your API key, log into Zoho CRM, then go to Setup | Crm API Key | Generate Now")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
<br />
			  <input type="text" name="zohocrm_apikey" id="zohocrm_apikey" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['zohocrm']['form_values']['zohocrm_apikey'] )):  echo $this->_tpl_vars['external_sources']['zohocrm']['form_values']['zohocrm_apikey'];  endif; ?>" tabindex="3" />
			</div>
		  </div>
		  <div id="external_config_zohocrm" style="display:none"></div>
		</div>
		<div id="external_box_capsule" style="display:none">
		  <div id="external_form_capsule">
				<div class="external_form_help">
				  <?php echo ((is_array($_tmp="Use Capsule to keep track of the people and companies you do business with, communications with them, opportunities in the pipeline, and what needs to be done when.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <a href="http://capsulecrm.com/" target="blank"><?php echo ((is_array($_tmp='Learn More')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>
				<div>
				  <?php echo ((is_array($_tmp='URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
: <?php echo ((is_array($_tmp="Your Capsule application name, as it appears in the URL that you would access in a browser; IE: https://APP.capsulecrm.com. Just include the APP portion.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
<br />
				  https://<input type="text" name="capsule_app" id="capsule_app" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['capsule']['form_values']['capsule_app'] )):  echo $this->_tpl_vars['external_sources']['capsule']['form_values']['capsule_app'];  endif; ?>" tabindex="1" />.capsulecrm.com
				</div>
				<div>
				  <?php echo ((is_array($_tmp='API Token')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
: <?php echo ((is_array($_tmp="You can find your API token from the More | My Settings page in Capsule")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
<br />
				  <input type="text" name="capsule_token" id="capsule_token" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['capsule']['form_values']['capsule_token'] )):  echo $this->_tpl_vars['external_sources']['capsule']['form_values']['capsule_token'];  endif; ?>" tabindex="2" />
				</div>
		  </div>
		  <div id="external_config_capsule" style="display:none"></div>
		</div>

		<div id="external_box_tactile" style="display:none">
		  <div id="external_form_tactile">
				<div class="external_form_help">
				  <?php echo ((is_array($_tmp="Tactile CRM lets you easily record every email, telephone call, note, activity and meeting, so that you and your colleagues can quickly see every interaction.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <a href="http://tactilecrm.com/" target="blank"><?php echo ((is_array($_tmp='Learn More')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>
				<div>
				  <?php echo ((is_array($_tmp='URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
: <?php echo ((is_array($_tmp="Your Tactile CRM application name, as it appears in the URL that you would access in a browser; IE: https://APP.tactilecrm.com. Just include the APP portion.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
<br />
				  https://<input type="text" name="tactile_app" id="tactile_app" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['tactile']['form_values']['tactile_app'] )):  echo $this->_tpl_vars['external_sources']['tactile']['form_values']['tactile_app'];  endif; ?>" tabindex="1" />.tactilecrm.com
				</div>
				<div>
				  <?php echo ((is_array($_tmp='API Token')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
: <?php echo ((is_array($_tmp='You can generate your API token from the User Preferences page in Tactile CRM')) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
<br />
				  <input type="text" name="tactile_token" id="tactile_token" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['tactile']['form_values']['tactile_token'] )):  echo $this->_tpl_vars['external_sources']['tactile']['form_values']['tactile_token'];  endif; ?>" tabindex="2" />
				</div>
		  </div>
		  <div id="external_config_tactile" style="display:none"></div>
		</div>

		<div id="external_box_batchbook" style="display:none">
		  <div id="external_form_batchbook">
				<div class="external_form_help">
				  <?php echo ((is_array($_tmp="Batchbook allows you to keep track of your business, personal, and social networking contacts and share them with the rest of your team.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <a href="http://www.batchblue.com/" target="blank"><?php echo ((is_array($_tmp='Learn More')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>
				<div>
				  <?php echo ((is_array($_tmp='URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
: <?php echo ((is_array($_tmp="Your Batchbook account name, as it appears in the URL that you would access in a browser; IE: https://ACCOUNT.batchbook.com. Just include the ACCOUNT portion.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
<br />
				  https://<input type="text" name="batchbook_account" id="batchbook_account" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['batchbook']['form_values']['batchbook_account'] )):  echo $this->_tpl_vars['external_sources']['batchbook']['form_values']['batchbook_account'];  endif; ?>" tabindex="1" />.batchbook.com
				</div>
				<div>
				  <?php echo ((is_array($_tmp='API Token')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
: <?php echo ((is_array($_tmp='You can generate your API token from the User Preferences page in batchbook CRM')) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
<br />
				  <input type="text" name="batchbook_token" id="batchbook_token" style="width:290px;" value="<?php if (isset ( $this->_tpl_vars['external_sources']['batchbook']['form_values']['batchbook_token'] )):  echo $this->_tpl_vars['external_sources']['batchbook']['form_values']['batchbook_token'];  endif; ?>" tabindex="2" />
				</div>
		  </div>
		  <div id="external_config_batchbook" style="display:none"></div>
		</div>

	  </div>
	</div>

	<h5><?php echo ((is_array($_tmp='Import Into')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
	<div class="adesk_checkboxlist"  style="margin-left:15px;">
<?php $_from = $this->_tpl_vars['lists']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
		<div>
			<label>
				<input type="checkbox" id="into_<?php echo $this->_tpl_vars['list']['id']; ?>
" name="into[<?php echo $this->_tpl_vars['list']['id']; ?>
]" value="<?php echo $this->_tpl_vars['list']['id']; ?>
" <?php if (count ( $this->_tpl_vars['lists'] ) == 1 || isset ( $this->_tpl_vars['list_checked'][$this->_tpl_vars['list']['id']] )): ?>checked="checked"<?php endif; ?> />
				<?php echo $this->_tpl_vars['list']['name']; ?>

			</label>
		</div>
<?php endforeach; endif; unset($_from); ?>
	</div>

	<div style="font-size: 10px; margin-left:15px;">
		<a href="#" onclick="advanced_options_toggle(); return false;" style=" color:#999999;"><?php echo ((is_array($_tmp='Advanced Importing Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
	</div>
	<div id="advanced" style="display:none; margin-left:30px;">
		<div>
			<select name="status" id="status" size="1" style="width:250px;">
<?php if (! $this->_tpl_vars['__ishosted']): ?>
				<option value="0"<?php if ($this->_tpl_vars['admin']['optinconfirm']): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp='Import As Unconfirmed Subscriber')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <?php echo ((is_array($_tmp="(slower, sends opt-in emails)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
<?php endif; ?>
<?php if (! $this->_tpl_vars['admin']['optinconfirm']): ?>
				<option value="1" selected="selected"><?php echo ((is_array($_tmp='Import As Active Subscriber')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
<?php endif; ?>
				<option value="2"><?php echo ((is_array($_tmp='Import As Unsubscribed Subscriber')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>

				<option value="3"><?php echo ((is_array($_tmp='Import As Excluded Email Address')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>

				</select>
		</div>

		<div class="adesk_help_inline" style="margin-top:10px;"><B><?php echo ((is_array($_tmp='NOTICE')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
: </b><?php echo ((is_array($_tmp="The following options will slow down your import process.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		<div>
			<label>
				<input type="checkbox" name="update" value="1" id="update" />
				<?php echo ((is_array($_tmp='Update existing subscribers while importing')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php echo ((is_array($_tmp="By checking this box, any subscribers in this import process which are already present in the system will be updated with any new subscriber information that is found during the import process. If this box is not checked, those subscribers will be skipped during the import (their subscriber details will not be updated).")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

			</label>
		</div>
<?php if ($this->_tpl_vars['campaigns_sent'] && ! $this->_tpl_vars['__ishosted']): ?>
		<div>
			<label>
				<input type="checkbox" name="sendlast" value="1" id="sendlast" />
				<?php echo ((is_array($_tmp='Send the last sent campaign to each new subscriber when importing')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>


				<?php echo ((is_array($_tmp="When this option is checked the last campaign you sent will be sent to each subscriber as they are imported.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

			</label>
		</div>
<?php endif; ?>
		<div style="display: none;">
			<label>
				<input type="checkbox" name="sendresponders" value="1" id="sendresponders" checked="checked" />
				<?php echo ((is_array($_tmp="Send future auto-responders to these subscribers")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php echo ((is_array($_tmp="If you turn off this option, the subscribers you are importing now will never receive any auto-responders; now nor in the future.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

			</label>
		</div>
<?php if ($this->_tpl_vars['responders']): ?>
		<div style="display: none;">
			<label>
				<input type="checkbox" name="sentresponders" value="1" id="sentresponders" onclick="adesk_dom_toggle_class('responderslist', 'adesk_blockquote', 'adesk_hidden');" />
				<?php echo ((is_array($_tmp="Mark these auto-responders as already sent")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php echo ((is_array($_tmp="The imported subscribers will be marked as already received these auto-responders, so these will not be sent to them.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

			</label>
		</div>
		<div id="responderslist" style="display:none">
			<div class="adesk_checkboxlist">
<?php $_from = $this->_tpl_vars['responders']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['responder']):
?>
				<div>
					<label>
						<input type="checkbox" name="responders[<?php echo $this->_tpl_vars['responder']['id']; ?>
]" value="<?php echo $this->_tpl_vars['responder']['id']; ?>
" />
						<?php echo $this->_tpl_vars['responder']['name']; ?>

					</label>
				</div>
<?php endforeach; endif; unset($_from); ?>
			</div>
		</div>
<?php endif; ?>
	</div>

	<div style="margin-top: 20px;">
		<input type="submit" class="adesk_button_next" value="<?php echo ((is_array($_tmp='Next')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" />
	</div>
	
	<?php if (isset ( $this->_tpl_vars['external_options'] )): ?>
		<input type="hidden" name="external_options" id="external_options" value="" />
		<div id="import_loader_external_options" class="adesk_modal" align="center">
		  <div class="adesk_modal_inner">
			<h3 class="m-b"><?php echo ((is_array($_tmp='Import Filters')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
			<p>
				<b><?php echo ((is_array($_tmp="(Optional)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</b> <?php echo ((is_array($_tmp="Supply filters to narrow down the data imported. Leave empty/blank to request all data from the external source.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</p>
			<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellspacing="4" cellpadding="0" border="0" style="width: 90%">
				<tr>
					<th><?php echo ((is_array($_tmp='Field')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</th>
					<th><?php echo ((is_array($_tmp="Filter value (optional)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</th>
				</tr>
				<?php $_from = $this->_tpl_vars['external_options_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field_name'] => $this->_tpl_vars['field_info']):
?>
					<tr>
						<td><?php if (is_array ( $this->_tpl_vars['field_info'] )):  echo $this->_tpl_vars['field_info']['label'];  else:  echo $this->_tpl_vars['field_info'];  endif; ?>:</td>
						<td>
							<?php if (is_array ( $this->_tpl_vars['field_info'] )): ?>
								<?php if ($this->_tpl_vars['field_info']['type'] == 'textbox'): ?>
									<input type="text" name="external_options_filters[<?php echo $this->_tpl_vars['field_name']; ?>
]" />
								<?php endif; ?>
								<?php if ($this->_tpl_vars['field_info']['type'] == 'select'): ?>
									<select name="external_options_filters[<?php echo $this->_tpl_vars['field_name']; ?>
]" <?php if (isset ( $this->_tpl_vars['field_info']['onchange'] )): ?>onchange="<?php echo $this->_tpl_vars['field_info']['onchange']; ?>
"<?php endif; ?> style="width: 220px;">
										<?php $_from = $this->_tpl_vars['field_info']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value'] => $this->_tpl_vars['option']):
?>
											<option value="<?php echo $this->_tpl_vars['value']; ?>
"><?php echo $this->_tpl_vars['option']; ?>
</option>
										<?php endforeach; endif; unset($_from); ?>
									</select>
								<?php endif; ?>
							<?php else: ?>
								<input type="text" name="external_options_filters[<?php echo $this->_tpl_vars['field_name']; ?>
]" />
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; endif; unset($_from); ?>
			</table></div>
		    <p style="margin-top: 35px;">
		      <input type="button" class="adesk_button_cancel" value="<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="$('import_loader_external_options').hide(); $('external_options').value = 1;" />
		      <input type="submit" class="adesk_button_next" value="<?php echo ((is_array($_tmp='Continue')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="$('import_loader_external_options').hide();" style="margin-left: 150px;" />
		    </p>
		  </div>
		</div>
		<script type="text/javascript">
			$("from_external").checked = "checked";
			import_set_from( $("from_external") );
			set_external('<?php echo $this->_tpl_vars['external']; ?>
');
			<?php if ($this->_tpl_vars['external'] == 'google_spreadsheets'): ?>
				var selects = $("import_loader_external_options").getElementsByTagName("select");
				var options = selects[0].getElementsByTagName("option");
				google_spreadsheets_toggle(options[0].value);
			<?php endif; ?>
		</script>
	<?php else: ?>
		<input type="hidden" name="external_options" value="1" />
	<?php endif; ?>

</form>

<div id="import_loader_filters" class="adesk_modal" align="center" style="display: none">
  <div class="adesk_modal_inner">
    <h3 class="m-b"><?php echo ((is_array($_tmp='Processing Your Import')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

	<p>
		<?php echo ((is_array($_tmp="Please wait while your data is fetched and parsed.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php echo ((is_array($_tmp="On the next step you will be able to supply filters for your import.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	</p>

    <div style="margin: 20px 0;text-align:center;font-weight:bold;">
    	<?php echo ((is_array($_tmp="Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    </div>

    <div style="margin: 20px 0;text-align:center;">
    	<img src="images/import-loading.gif" border="0" />
    </div>

  </div>
</div>

<div id="import_loader" class="adesk_modal" align="center" style="display: none">
  <div class="adesk_modal_inner">
    <h3 class="m-b"><?php echo ((is_array($_tmp='Processing Your Import')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

	<p>
		<?php echo ((is_array($_tmp="Please wait while your data is fetched and parsed.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php echo ((is_array($_tmp="On the next step you will be able to map found fields into this application.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	</p>

    <div style="margin: 20px 0;text-align:center;font-weight:bold;">
    	<?php echo ((is_array($_tmp="Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    </div>

    <div style="margin: 20px 0;text-align:center;">
    	<img src="images/import-loading.gif" border="0" />
    </div>

    <br />

    <div>
      <input type="button" class="adesk_button_close" value="<?php echo ((is_array($_tmp='Restart')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.location.reload(true);" />
    </div>
  </div>
</div>

<script type="text/javascript">
  <?php if (isset ( $this->_tpl_vars['submitResult'] ) && $this->_tpl_vars['submitResult']['section'] != 'generic'): ?>
  $("error_<?php echo $this->_tpl_vars['submitResult']['section']; ?>
").show();
  <?php endif; ?>
</script>