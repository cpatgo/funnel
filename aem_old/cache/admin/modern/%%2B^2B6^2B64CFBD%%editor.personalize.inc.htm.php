<?php /* Smarty version 2.6.12, created on 2016-07-08 14:47:32
         compiled from editor.personalize.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'editor.personalize.inc.htm', 3, false),)), $this); ?>
<div id="message_personalize" class="adesk_modal" align="center" style="display:none;">
  <div class="adesk_modal_inner" align="left">
	<h3 class="m-b"><?php echo ((is_array($_tmp='Personalize Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>



	<div>
		<ul class="navlist" style="padding-left:0px;">
		<li id="subinfo_tab" class="currenttab"><a href="#" onclick="form_editor_personalization_show('personalize_subinfo'); return false"><?php echo ((is_array($_tmp='Subscriber Info')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
		<li id="message_tab"><a href="#" onclick="form_editor_personalization_show('personalize_message'); return false"><?php echo ((is_array($_tmp="Message Options & Links")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
		<li id="socmedia_tab"><a href="#" onclick="form_editor_personalization_show('personalize_socmedia'); return false"><?php echo ((is_array($_tmp='Social Media')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
		<li id="other_tab"><a href="#" onclick="form_editor_personalization_show('personalize_other'); return false"><?php echo ((is_array($_tmp='Other')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
		</ul>
	</div>
	<br />

	<div id="personalizelist">
	  <div id="personalize_subinfo" class="personalizelistsection">
		<div id="personalize_subinfo_top"></div>
		<div id="personalize_subinfo_field_global"></div>
		<div id="personalize_subinfo_field"></div>
		<div id="personalize_subinfo_bottom"></div>
		<div id="personalize_senderinfo"></div>
	  </div>
	  <div id="personalize_message" class="personalizelistsection" style="display:none"></div>
	  <div id="personalize_socmedia" class="personalizelistsection" style="display:none"></div>
	  <div id="personalize_other" class="personalizelistsection" style="display:none"></div>
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