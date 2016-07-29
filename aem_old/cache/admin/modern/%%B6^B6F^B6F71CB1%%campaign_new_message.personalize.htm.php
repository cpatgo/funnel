<?php /* Smarty version 2.6.12, created on 2016-07-08 14:19:52
         compiled from campaign_new_message.personalize.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'campaign_new_message.personalize.htm', 3, false),array('modifier', 'escape', 'campaign_new_message.personalize.htm', 25, false),)), $this); ?>
<div id="message_personalize" class="adesk_modal" align="center" style="display:none;">
  <div class="adesk_modal_inner" align="left">
	<h3 class="m-b"><?php echo ((is_array($_tmp='Personalize Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

	<div>
		<ul class="navlist" style="padding-left:0px;">
			<li id="subinfo_tab" class="currenttab"><a href="#" onclick="campaign_personalization_show('personalize_subinfo'); return false"><?php echo ((is_array($_tmp='Subscriber Info')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
			<li id="message_tab"><a href="#" onclick="campaign_personalization_show('personalize_message'); return false"><?php echo ((is_array($_tmp="Message Options & Links")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
			<li id="socmedia_tab"><a href="#" onclick="campaign_personalization_show('personalize_socmedia'); return false"><?php echo ((is_array($_tmp='Social Media')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
			<li id="other_tab"><a href="#" onclick="campaign_personalization_show('personalize_other'); return false"><?php echo ((is_array($_tmp='Other')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
		</ul>
	</div>
	<br />

	<div id="personalizelist">
	  <div id="personalize_subinfo" class="personalizelistsection">
			<ul>
				<li><a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%EMAIL%'); return false"><?php echo ((is_array($_tmp='Email Address')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
				<li><a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%FIRSTNAME%'); return false"><?php echo ((is_array($_tmp='First Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
				<li><a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%LASTNAME%'); return false"><?php echo ((is_array($_tmp='Last Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
				<li><a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%FULLNAME%'); return false"><?php echo ((is_array($_tmp='Full Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
				<!-- fields -->
				<?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['e']):
?>
				<li>
				<a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('<?php if ($this->_tpl_vars['e']['perstag'] != ""): ?>%<?php echo ((is_array($_tmp=$this->_tpl_vars['e']['perstag'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
%<?php else:  echo $this->_tpl_vars['e']['tag'];  endif; ?>'); return false"><?php echo ((is_array($_tmp=$this->_tpl_vars['e']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
				<?php if ($this->_tpl_vars['e']['bubble_content'] != ""): ?>
				<div><?php echo $this->_tpl_vars['e']['bubble_content']; ?>
</div>
				<?php endif; ?>
				</li>
				<?php endforeach; endif; unset($_from); ?>
				<!-- the rest -->
				<li>
				<a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%SUBDATE%'); return false"><?php echo ((is_array($_tmp='Date Subscribed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				<div><?php echo ((is_array($_tmp="The date that this subscriber subscribed.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				</li>
				<li>
				<a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%SUBTIME%'); return false"><?php echo ((is_array($_tmp='Time Subscribed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				<div><?php echo ((is_array($_tmp="The time of the day the subscriber subscribed.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				</li>
			</ul>
	  </div>
		<div id="personalize_message" style="display:none">
			<ul>
				<li>
				<a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%UNSUBSCRIBELINK%'); return false"><?php echo ((is_array($_tmp='Unsubscribe Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				<div><?php echo ((is_array($_tmp="Unsubscribes the subscriber from the list used to send the email.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				</li>
				<li>
				<a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%WEBCOPY%'); return false"><?php echo ((is_array($_tmp='Web Copy')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				<div><?php echo ((is_array($_tmp="Allow subscribers to view the email in their browser.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				</li>
				<li>
				<a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%UPDATELINK%'); return false"><?php echo ((is_array($_tmp='Update Subscription Account Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				<div><?php echo ((is_array($_tmp="Allow subscribers to update their subscription details.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				</li>
				<li>
				<a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%FORWARD2FRIEND%'); return false"><?php echo ((is_array($_tmp='Send to Friend Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				<div><?php echo ((is_array($_tmp="Your subscribers can forward the email to multiple people.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				</li>
				<li>
				<a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%UNSUBSCRIBELINK%&ALL'); return false"><?php echo ((is_array($_tmp="Unsubscribe Link (All Lists)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				<div><?php echo ((is_array($_tmp="Unsubscribe from all lists. Even lists not included in the email sent.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				</li>
			</ul>
		</div>
		<div id="personalize_socmedia" class="personalizelistsection" style="display:none">
			<ul>
				<li><a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%SOCIALSHARE%'); return false"><?php echo ((is_array($_tmp='Social Submit Links')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
				<li><a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%SOCIAL-FACEBOOK-LIKE%'); return false"><?php echo ((is_array($_tmp='Facebook Like Button')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
				<li><a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%SOCIALSHARE-FACEBOOK%'); return false"><?php echo ((is_array($_tmp='Share on Facebook Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
				<li><a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%SOCIALSHARE-TWITTER%'); return false"><?php echo ((is_array($_tmp='Share on Twitter Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
				<li><a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%SOCIALSHARE-DIGG%'); return false"><?php echo ((is_array($_tmp='Share on Digg Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
				<li><a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%SOCIALSHARE-REDDIT%'); return false"><?php echo ((is_array($_tmp='Share on Reddit Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
				<li><a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%SOCIALSHARE-DELICIOUS%'); return false"><?php echo ((is_array($_tmp="Share on del.icio.us Link")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
				<li><a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%SOCIALSHARE-GREADER%'); return false"><?php echo ((is_array($_tmp='Share on Google Reader Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
				<li><a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%SOCIALSHARE-STUMBLEUPON%'); return false"><?php echo ((is_array($_tmp='Share on StumbleUpon Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
			</ul>
		</div>
		<div id="personalize_other" class="personalizelistsection" style="display:none">
			<ul>
				<li><a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%TODAY%'); return false"><?php echo ((is_array($_tmp="Today's Date")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
				<li><a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%TODAY*%'); return false"><?php echo ((is_array($_tmp="Today's Date +/- X day(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
				<li><a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%SENDER-INFO%'); return false"><?php echo ((is_array($_tmp='List Sender Info')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
				<li>
				<a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%SUBSCRIBERIP%'); return false"><?php echo ((is_array($_tmp="Subscriber's IP Address")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				<div><?php echo ((is_array($_tmp="The IP Address of the subscriber when they subscribed to this list.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				</li>
				<li>
				<a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%LISTNAME%'); return false"><?php echo ((is_array($_tmp="Subscriber's List")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				<div><?php echo ((is_array($_tmp="Show the name of the list that this email was sent to.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				</li>
				<li>
				<a href="#" style="font-weight: bold" onclick="campaign_personalization_insert('%SUBSCRIBERID%'); return false"><?php echo ((is_array($_tmp='Subscriber ID')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				<div><?php echo ((is_array($_tmp="Display the ID # of the subscriber.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				</li>
			</ul>	
		</div>
	</div>

	<br />

	<div style="float:right; font-style:italic; color:#999; padding-top:7px;"><?php echo ((is_array($_tmp="Click the item you would like to insert.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
    <div>
          <input type="button" value='<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_dom_toggle_display('message_personalize', 'block');" />
      <input type="hidden" value="text" id="personalize4" />
      <input type="hidden" value="" id="personalize2" />
    </div>
  </div>
</div>