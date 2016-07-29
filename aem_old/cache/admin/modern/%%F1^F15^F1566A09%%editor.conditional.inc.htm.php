<?php /* Smarty version 2.6.12, created on 2016-07-08 14:47:32
         compiled from editor.conditional.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'editor.conditional.inc.htm', 3, false),)), $this); ?>
<div id="message_conditional" class="adesk_modal" align="center" style="display:none;">
  <div class="adesk_modal_inner" align="left">
	<h3 class="m-b"><?php echo ((is_array($_tmp='Insert Conditional Content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

	<div class="adesk_help_inline"><?php echo ((is_array($_tmp="Insert conditional content in your message by creating a section of content that will only show if a certain condition is matched.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

	<br />

	<div style="font-size:12px; font-weight:bold; margin-bottom:10px;"><?php echo ((is_array($_tmp="Show a block of content to subscriber if...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

	<select id="conditionalfield" name="field" style="width:369px;"></select>
	<select id="conditionalcond" name="cond" style="width:369px;">
		<option value="=="><?php echo ((is_array($_tmp="Equals (Is)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="!="><?php echo ((is_array($_tmp="Does Not Equal (Is Not)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="&gt;="><?php echo ((is_array($_tmp='Is Greater Than Or Equal To')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="&lt;="><?php echo ((is_array($_tmp='Is Less Than Or Equal To')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="&gt;"><?php echo ((is_array($_tmp='Is Greater Than')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="&lt;"><?php echo ((is_array($_tmp='Is Less Than')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="CONTAINS"><?php echo ((is_array($_tmp='Contains')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="DCONTAINS"><?php echo ((is_array($_tmp='Does NOT Contain')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
	</select>
	<div>
		<input id="conditionalvalue" name="value" type="text" style="width:365px;" />
	</div>

	<br />

    <div>
      <input type="button" value='<?php echo ((is_array($_tmp='Insert')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="form_editor_conditional_insert();" class="adesk_button_ok" />
      <input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_dom_toggle_display('message_conditional', 'block');" />
      <input type="hidden" value="text" id="conditional4" />
      <input type="hidden" value="" id="conditional2" />
    </div>
  </div>
</div>