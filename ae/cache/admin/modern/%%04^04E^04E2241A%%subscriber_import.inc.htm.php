<?php /* Smarty version 2.6.12, created on 2016-07-08 14:18:25
         compiled from subscriber_import.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'subscriber_import.inc.htm', 4, false),array('modifier', 'alang', 'subscriber_import.inc.htm', 126, false),array('modifier', 'help', 'subscriber_import.inc.htm', 154, false),array('modifier', 'escape', 'subscriber_import.inc.htm', 177, false),)), $this); ?>
<script>

<?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['offeroptin'],'name' => 'offerOptIn'), $this);?>

<?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['offeroptout'],'name' => 'offerOptOut'), $this);?>

<?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['offerresponders'],'name' => 'offerResponders'), $this);?>

<?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['offersentresponders'],'name' => 'offerSentResponders'), $this);?>


<?php echo '

function ihook_adesk_sync_destinations_template(data) {
  if (data.sendresponder) {
	$("sendresponderOn").checked = true;
  } else {
	$("sendresponderOff").checked = true;
  }

  $(\'destinationField\').value = data.destination;

  $("instantresponderOn").checked              = data.instantresponder ? true : false;
  $("import_option_field_delete_all").checked  = data.delete_all ? true : false;
  //$("import_option_field_skipbounced").checked = data.skipbounced ? true : false;
  $("import_option_field_updateexisting").checked   = data.updateexisting ? true : false;
  $("import_option_field_lastmessage").checked = data.lastmessage ? true : false;

  if (data.sentresponders != "") {
	var sent = data.sentresponders.toString().split(",");
	$("sentresponders").checked = false;

	for (var i = 0; i < sent.length; i++) {
	  if (!$("sentresponders").checked) {
		$("sentresponders").checked = true;
		$("sentrespondersbox").className = "adesk_block";
	  }

	  $("sentresponder" + sent[i].toString()).selected = true;
	}
  }
}

function ihook_import_relid_change(newval) {
	if ( typeof(newval.join) == \'function\' ) {
		var relval = newval.join(\',\');
	} else {
		var relval = parseInt(newval, 10);
	}
	var destination = $(\'destinationField\').value;
	if ( destination == 2 ) {
		var statusval = \'unsubscribe\';
	} else if ( destination == 1 ) {
		var statusval = \'subscribe\';
	} else {
		var statusval = \'\';
	}
	//if ( relval != \'\' && relval > 0 ) {
		adesk_ajax_call_cb(apipath, "subscriber_import.import_relid_change", ihook_import_relid_change_cb, relval, statusval);
	//} else {
		// if resetting...
	//}
}

function ihook_import_relid_change_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	var value = $(\'destinationField\').value;
	// handle responders
	//responders = ary.responders;
	adesk_dom_remove_children($(\'sentRespondersField\'));
	for ( var i in ary.responders ) {
		var r = ary.responders[i];
		if ( typeof r != \'function\' ) {
			$(\'sentRespondersField\').appendChild(
				Builder.node(
					\'option\',
					{ value: r.id },
					[ Builder._text(r.name) ]
				)
			);
		}
	}
	$(\'sentRespondersField\').selectedIndex = -1;
	// set vars
	offerResponders = ary.offerresponders;
	offerSentResponders = ary.offersentresponders;
	offerOptIn = ary.offeroptin;
	offerOptOut = ary.offeroptout;
	import_destination_setstage(value);
}

function import_destination_change(value) {
	if ( value == 0 ) { // unconfirmed
		//
	} else if ( value == 1 ) { // active
		//
	} else if ( value == 2 ) { // unsubscribed
		//
	} else if ( value == 3 ) { // exclusion
		//
	}
	import_destination_setstage(value);
	ihook_import_relid_change(adesk_form_select_extract($(\'relidField\')));
}

function import_destination_setstage(value) {
	// handle autoresponder options
	$(\'importResponderOptions\').className = ( value != 0 && value != 3 && offerResponders == 1 ) ? \'adesk_block\' : \'adesk_hidden\';
	$(\'responderOptions\').className = ( value != 0 && value != 3 && offerSentResponders > 0 ) ? \'adesk_block\' : \'adesk_hidden\';
	// handle options checkboxes
	if ( $(\'importOption_optin\') ) $(\'importOption_optin\').className = ( value == 0 && offerOptIn == 1 ) ? \'adesk_block\' : \'adesk_hidden\';
	if ( $(\'importOption_optout\') ) $(\'importOption_optout\').className = ( value == 2 && offerOptOut == 1 ) ? \'adesk_block\' : \'adesk_hidden\';
	//if ( $(\'importOption_skipbounced\') ) $(\'importOption_skipbounced\').className = ( value != 3 && value != 2 ) ? \'adesk_block\' : \'adesk_hidden\';
	if ( $(\'importOption_updateexisting\') ) $(\'importOption_updateexisting\').className = ( value != 2 && value != 3 ) ? \'adesk_block\' : \'adesk_hidden\';
	if ( $(\'importOption_lastmessage\') ) $(\'importOption_lastmessage\').className = ( value == 1 ) ? \'adesk_block\' : \'adesk_hidden\';
	// for sync too (this include template is used there too)
	if ( $(\'syncOption_optin\') ) $(\'syncOption_optin\').className = ( value == 0 && offerOptIn == 1 ) ? \'adesk_block\' : \'adesk_hidden\';
	if ( $(\'syncOption_optout\') ) $(\'syncOption_optout\').className = ( value == 2 && offerOptOut == 1 ) ? \'adesk_block\' : \'adesk_hidden\';
}

// now assign defined ihooks
adesk_ihook_define(\'ihook_import_relid_change\', ihook_import_relid_change);
adesk_ihook_define(\'adesk_sync_destinations_template\', ihook_adesk_sync_destinations_template);

'; ?>

</script>

<div id="importDestinations">
	<h3><?php echo ((is_array($_tmp='Import As')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
	<div>
		<select name="destination" id="destinationField" size="1" onchange="import_destination_change(this.value);">
<?php if (! $this->_tpl_vars['__ishosted']): ?>
			<option value="0"<?php if ($this->_tpl_vars['admin']['optinconfirm']): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp='Unconfirmed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
<?php endif; ?>
<?php if (! $this->_tpl_vars['admin']['optinconfirm']): ?>
			<option value="1" selected="selected"><?php echo ((is_array($_tmp='Active')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
<?php endif; ?>
			<option value="2"><?php echo ((is_array($_tmp='Unsubscribed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>

			<option value="3"><?php echo ((is_array($_tmp='Excluded')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>

		</select>
	</div>
</div>

<div id="importResponderOptions" class="<?php if (! $this->_tpl_vars['admin']['optinconfirm'] && $this->_tpl_vars['offerresponders']): ?>adesk_block<?php else: ?>adesk_hidden<?php endif; ?>">
	<h3><?php echo ((is_array($_tmp='Autoresponder Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
	<div id="globalResponderOn">
		<div>
			<label>
				<input type="radio" name="sendresponder" value="1" id="sendresponderOn" checked="checked" onclick="if ( offerSentResponders ) $('responderOptions').className = 'adesk_block';" />
				<?php echo ((is_array($_tmp='Send Autoresponders to these subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</label>
			<?php echo ((is_array($_tmp="Subscribers will be treated the same as any person that subscribed from a public side or a subscription form; all instant and/or delayed AutoResponders will be sent to them in due time.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

		</div>
		<div id="responderOptions" class="<?php if ($this->_tpl_vars['offersentresponders']): ?>adesk_block<?php else: ?>adesk_hidden<?php endif; ?>">
			<div>
				<label>
					<input type="checkbox" name="instantresponder" value="1" id="instantresponderOn" />
					<?php echo ((is_array($_tmp='Send instant autoresponders when importing')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				</label>
				<?php echo ((is_array($_tmp="During importing the subscribers, they will be sent any instant AutoResponders you might have setup.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

			</div>
			<hr size="1" width="100%" noshade />
			<div>
				<label>
					<input id="sentresponders" type="checkbox" value="1" onclick="$('sentrespondersbox').className = ( this.checked ? 'adesk_block' : 'adesk_hidden' );" />
					<?php echo ((is_array($_tmp="Set specific auto-responders as being already sent")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				</label>
				<?php echo ((is_array($_tmp="You can flag certain AutoResponders as already been sent to these subscribers, in case imported subscribers shouldn't receive some of them.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

			</div>
			<div id="sentrespondersbox" class="adesk_hidden">
				<?php echo ((is_array($_tmp="Select the autoresponders that should be marked as 'sent' for these subscribers. Those will not be sent.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
				<?php echo ((is_array($_tmp="If you do not select any autoresponders they will receive all autoresponders that regular subscriber would.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
				<select name="sentresponders[]" size="10" id="sentRespondersField" multiple="multiple">
<?php $_from = $this->_tpl_vars['responders']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['r']):
?>
				  <option id="sentresponder<?php echo $this->_tpl_vars['r']['id']; ?>
" value="<?php echo $this->_tpl_vars['r']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['r']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
<?php endforeach; endif; unset($_from); ?>
				</select>
				<?php echo ((is_array($_tmp="Notice: Hold CTRL to select multiple Autoresponders.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

				<div>
					<?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					<a href="#" onclick="return adesk_form_select_multiple_all($('sentRespondersField'));"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
					&middot;
					<a href="#" onclick="return adesk_form_select_multiple_none($('sentRespondersField'));"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>
			</div>
		</div>
	</div>
	<div id="globalResponderOff">
		<div>
			<label>
				<input type="radio" name="sendresponder" value="0" id="sendresponderOff" onclick="if ( offerSentResponders ) $('responderOptions').className = 'adesk_hidden';" />
				<?php echo ((is_array($_tmp='Never send any Autoresponders to these subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</label>
			<?php echo ((is_array($_tmp="These subscribers will never receive any further AutoResponders setup in the system.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

		</div>
	</div>
</div>