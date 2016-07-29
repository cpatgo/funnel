<?php /* Smarty version 2.6.12, created on 2016-07-08 16:53:15
         compiled from editor.template.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'editor.template.inc.htm', 3, false),)), $this); ?>
<div id="message_template" class="adesk_modal" align="center" style="display:none;">
  <div class="adesk_modal_inner" align="left">
	<h3 class="m-b"><?php echo ((is_array($_tmp='Insert Template')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

	<div class="adesk_help_inline"><?php echo ((is_array($_tmp="Include a template in your message.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

	<br />

	<div class="adesk_blockquote">
      <strong><?php echo ((is_array($_tmp="Select a Template:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong><br />
      <select id="templateinsert" size="1" onchange="$('editortemplatebutton').disabled=this.value==0;form_editor_template_preview(this.value);" style="width:99%;">
        <option value="0"><?php echo ((is_array($_tmp="Pick a template to insert...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
        <optgroup label="<?php echo ((is_array($_tmp='HTML Templates')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" id="templateinserthtml">
<?php $_from = $this->_tpl_vars['templates']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['t']):
?>
          <option value="<?php echo $this->_tpl_vars['t']['id']; ?>
"><?php echo $this->_tpl_vars['t']['name']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
        </optgroup>
      </select>
      <input type="hidden" id="templateinserttext" name="templateinserttext" />
    </div>

    <br />

    <div id="templatequickpreview" style="text-align:center;display:none;">
      <?php echo ((is_array($_tmp='Preview')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
      <img id="templatequickpreviewimg" src="about:blank" border="1" />
    </div>

    <br />

    <div style="margin-top:10px;">
      <input type="button" value='<?php echo ((is_array($_tmp='Insert')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="form_editor_template_insert($('editortemplate4').value);" id="editortemplatebutton" class="adesk_button_ok" />
      <input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_dom_toggle_display('message_template', 'block');" />
      <input type="hidden" value="" id="editortemplate2" />
      <input type="hidden" value="" id="editortemplate4" />
    </div>
  </div>
</div>