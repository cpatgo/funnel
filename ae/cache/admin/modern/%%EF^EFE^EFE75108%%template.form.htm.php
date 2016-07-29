<?php /* Smarty version 2.6.12, created on 2016-07-08 14:47:32
         compiled from template.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'template.form.htm', 7, false),array('modifier', 'help', 'template.form.htm', 11, false),array('modifier', 'truncate', 'template.form.htm', 55, false),array('function', 'adesk_upload', 'template.form.htm', 122, false),)), $this); ?>
<div id="form" class="adesk_hidden">
  <form method="POST" onsubmit="template_form_save(template_form_id); return false">
	<input type="hidden" name="id" id="form_id" />
	<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5">
	  <tr>
		<td>
		  <label for="nameField"><?php echo ((is_array($_tmp='Template Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:</label>
		</td>
		<td>
		  <input type="text" name="name" id="nameField" value="" style="width: 300px;" />
		  <?php echo ((is_array($_tmp="Brief Description for you to recognize. Does NOT affect your actual template.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

		</td>
	  </tr>
	  <tr>
		<td>
		  <label for="subjectField"><?php echo ((is_array($_tmp='Message Subject')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:</label>
		</td>
		<td>
		  <input type="text" name="subject" id="subjectField" style="width: 300px;" />
		  <?php echo ((is_array($_tmp="The default subject any message begins with when you select this template.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

		</td>
	  </tr>
	  <?php if (adesk_admin_ismaingroup ( )): ?>
	  <tr>
		<td><?php echo ((is_array($_tmp='Visibility')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:</td>
		<td>
		  <input type="radio" name="template_scope" id="template_scope_all" value="all" onclick="template_form_lists_toggle_scope(this.value);" />
		  <label for="template_scope_all"><?php echo ((is_array($_tmp='Available for all lists and users')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
		  <br />
		  <input type="radio" name="template_scope" id="template_scope_specific" value="specific" onclick="template_form_lists_toggle_scope(this.value);" />
		  <label for="template_scope_specific"><?php echo ((is_array($_tmp='Available for specific lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
		</td>
	  </tr>
	  <?php else: ?>
	  <tr>
		<td></td>
		<td>
		  <div style="display: none;">
			<input type="radio" name="template_scope" id="template_scope_all" value="all" />
			<input type="radio" name="template_scope" id="template_scope_specific" value="specific" />
		  </div>
		</td>
	  </tr>
	  <?php endif; ?>
	  <tbody id="template_form_lists">
		<tr valign="top">
		  <td><?php echo ((is_array($_tmp="Used in Lists:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td>

			<div id="parentsList_div" class="adesk_checkboxlist">
			  <?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
			  <div>
				<label>
				  <input type="checkbox" id="p_<?php echo $this->_tpl_vars['p']['id']; ?>
" class="parentsList" name="p[]" value="<?php echo $this->_tpl_vars['p']['id']; ?>
" <?php if (count ( $this->_tpl_vars['listsList'] ) == 1): ?>checked="checked"<?php endif; ?> />
				  <?php echo ((is_array($_tmp=$this->_tpl_vars['p']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>

				</label>
			  </div>
			  <?php endforeach; endif; unset($_from); ?>
			</div>
			<div>
			  <?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			  <a href="#" onclick="parents_box_select(1, 0); return false;"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			  &middot;
			  <a href="#" onclick="parents_box_select(0, 0); return false;"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			
						<input type="hidden" id="templateformatField" />
			<input type="hidden" id="templatetext" />
			<input type="hidden" id="templatetextField" />

		  </td>
		</tr>
	  </tbody>
	</table></div>

	<div id="templatehtml" class="adesk_hidden" style="margin-top:15px;">
	  <div style="border-left:1px solid #CCCCCC; border-right:1px solid #CCCCCC; border-top:1px solid #CCCCCC; margin:0px; padding:2px; padding-bottom:0px; background:#F0F0EE;">
		<div style="float:right; margin-top:5px; margin-bottom:3px;">

		  <div align="right" style="vertical-align: middle;">
						<a href="#" onclick="form_editor_deskrss_open('html', 'templateEditor');return false;" style="padding:2px; background:url(images/editor_deskrss.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; margin-right:10px;"><?php echo ((is_array($_tmp='Insert RSS')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			<a href="#" onclick="form_editor_conditional_open('html', 'templateEditor');return false;" style="padding:2px; background:url(images/editor_conditional.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; margin-right:10px;"><?php echo ((is_array($_tmp='Insert Conditional Content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			<a href="#" onclick="form_editor_personalize_open('html', 'templateEditor'); return false;" style=" margin-right:8px; padding:2px; background:url(images/editor_personalization.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; font-weight:bold; color:#006600;"><?php echo ((is_array($_tmp='Personalize Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		  </div>
		</div>

		<div>
		  <ul class="navlist" style="padding-left:4px; border-bottom:0px;">

			<li id="templateEditorLinkOn" class="<?php if ($this->_tpl_vars['admin']['htmleditor']): ?>currenttab<?php else: ?>othertab<?php endif; ?>">
			<a href="#" onclick="return toggleEditor('template', true, adesk_editor_init_word_object);" style="border-bottom:0px;"><span><?php echo ((is_array($_tmp='Visual Editor')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a>
			</li>
			<li id="templateEditorLinkOff" class="<?php if (! $this->_tpl_vars['admin']['htmleditor']): ?>currenttab<?php else: ?>othertab<?php endif; ?>">
			<a href="#" onclick="return toggleEditor('template', false, adesk_editor_init_word_object);" style="border-bottom:0px;"><span><?php echo ((is_array($_tmp='Code Editor')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a>
			</li>

		  </ul>
		</div>
		<div id="templateEditorLinkDefault" class="adesk_hidden" style="padding:2px; padding-left:4px; font-size:10px; background:none; border-top:1px solid #CCCCCC; background:#FFFFD5; ">
		  <a href="#" onclick="return setDefaultEditor('template');" style="color:#666666;"><?php echo ((is_array($_tmp='Set as default editor mode')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		</div>
	  </div>
	  <textarea name="html" id="templateEditor" style="width: <?php echo $this->_tpl_vars['admin']['editorsize_w']; ?>
; height: <?php echo $this->_tpl_vars['admin']['editorsize_h']; ?>
; padding:0px; border:1px solid #CCCCCC; margin:0px;"></textarea>
	  <?php if ($this->_tpl_vars['admin']['htmleditor']): ?><script>toggleEditor('template', true, adesk_editor_init_word_object);</script><?php endif; ?>
	</div>

	<h3 style="background:#D3E2F1; padding:5px; padding-left:12px; font-size:14px;"><?php echo ((is_array($_tmp='Template Preview')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

	<div id="template_preview_text1">
	  <?php echo ((is_array($_tmp="You can (optionally) add a template preview. Your preview must be a GIF, JPG, or PNG image file. The size of your preview should be at least 200px wide. 200px width by 250px height is suggested.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	</div>

	<div id="template_preview_text2">
	  <?php echo ((is_array($_tmp="Current template preview:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	</div>

	<div id="template_preview_upload_div" style="margin-top: 15px;">
	  <?php echo smarty_function_adesk_upload(array('id' => 'template_preview','name' => 'preview','action' => 'template_preview','limit' => 1), $this);?>

	</div>

	<div id="template_preview_image_div" style="margin: 15px 0;">
	  <img src="" id="template_preview_image" align="top" width="200" height="250" />
	</div>

	<input type="hidden" name="template_preview_cache_filename" id="template_preview_cache_filename" />
	<input type="hidden" name="template_preview_cache_filename_mimetype" id="template_preview_cache_filename_mimetype" />

	<div id="template_preview_upload_extra">
	  <a href="javascript: template_preview_upload_reset();"><?php echo ((is_array($_tmp='Upload a different file')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
	</div>

	<br />
	<div>
	  <input type="button" id="form_submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="template_form_save(template_form_id)" />
	  <input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-2)" />
	</div>
	<input type="submit" style="display:none"/>
  </form>
</div>