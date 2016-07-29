<?php /* Smarty version 2.6.12, created on 2016-07-11 16:58:35
         compiled from personalization.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'personalization.form.htm', 7, false),array('modifier', 'help', 'personalization.form.htm', 11, false),array('modifier', 'truncate', 'personalization.form.htm', 28, false),)), $this); ?>
<div id="form" class="adesk_hidden">
  <form method="POST" onsubmit="personalization_form_save(personalization_form_id); return false">
    <input type="hidden" name="id" id="form_id" />
    <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td>
          <label for="nameField"><?php echo ((is_array($_tmp='Personalization Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
        </td>
        <td>
          <input type="text" name="name" id="nameField" value="" size="45" />
          <?php echo ((is_array($_tmp="Brief Description for you to recognize. Does NOT affect your actual personalization tag.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

        </td>
      </tr>
      <tr>
        <td>
          <label for="tagField"><?php echo ((is_array($_tmp='Personalization Tag')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
        </td>
        <td>
          %<input type="text" name="tag" id="tagField" value="" size="45" />%
          <?php echo ((is_array($_tmp="Tag that will be used in mailings.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        </td>
      </tr>
      <tr valign="top">
        <td><?php echo ((is_array($_tmp="Used in Lists:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        <td>
          <select name="p" id="parentsList" tabindex="1" size="10" multiple="multiple" style="width:415px; height:65px;" onchange="customFieldsObj.fetch(0);">
<?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
            <option value="<?php echo $this->_tpl_vars['p']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['p']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>
</option>
<?php endforeach; endif; unset($_from); ?>
          </select>
          <?php echo ((is_array($_tmp="Notice: This personalization will be a member of each selected list! Hold CTRL to select multiple lists.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

          <div>
            <?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            <a href="#" onclick="return parents_list_select(true);"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
              &middot;
            <a href="#" onclick="return parents_list_select(false);"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <label for="formatField"><?php echo ((is_array($_tmp='Format')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
        </td>
        <td>
          <select name="format" id="personalizationformatField" onchange="adesk_editor_mime_prompt('personalization', this.value);">
            <option value="html"><?php echo ((is_array($_tmp='HTML')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
            <option value="text"><?php echo ((is_array($_tmp='Text')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          </select>
        </td>
      </tr>
    </table></div>

    <div id="personalizationhtml" class="adesk_hidden">
        <h3 style="background:#D3E2F1; padding:5px; padding-left:12px; font-size:14px;"><?php echo ((is_array($_tmp='HTML Template')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
			<div style="border-left:1px solid #CCCCCC; border-right:1px solid #CCCCCC; margin:0px; padding:2px; padding-bottom:0px; background:#F0F0EE;">
				<div style="float:right; margin-top:5px; margin-bottom:3px;">

					<div align="right" style="vertical-align: middle;">
						<a href="#" onclick="form_editor_conditional_open('html', 'personalizationEditor');return false;" style="padding:2px; background:url(images/editor_conditional.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; margin-right:10px;"><?php echo ((is_array($_tmp='Insert Conditional Content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
						<a href="#" onclick="form_editor_personalize_open('html', 'personalizationEditor'); return false;" style=" margin-right:8px; padding:2px; background:url(images/editor_personalization.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; font-weight:bold; color:#006600;"><?php echo ((is_array($_tmp='Personalize Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
					</div>
				</div>

				<div>
				<ul class="navlist" style="padding-left:4px; border-bottom:0px;">

					<li id="personalizationEditorLinkOn" class="<?php if ($this->_tpl_vars['admin']['htmleditor']): ?>currenttab<?php else: ?>othertab<?php endif; ?>">
						<a href="#" onclick="return toggleEditor('personalization', true, adesk_editor_init_word_object);" style="border-bottom:0px;"><span><?php echo ((is_array($_tmp='Visual Editor')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a>
					</li>
					<li id="personalizationEditorLinkOff" class="<?php if (! $this->_tpl_vars['admin']['htmleditor']): ?>currenttab<?php else: ?>othertab<?php endif; ?>">
						<a href="#" onclick="return toggleEditor('personalization', false, adesk_editor_init_word_object);" style="border-bottom:0px;"><span><?php echo ((is_array($_tmp='Code Editor')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a>
					</li>

				</ul>
				</div>
				<div id="personalizationEditorLinkDefault" class="adesk_hidden" style="padding:2px; padding-left:4px; font-size:10px; background:none; border-top:1px solid #CCCCCC; background:#FFFFD5; ">
					<a href="#" onclick="return setDefaultEditor('personalization');" style="color:#666666;"><?php echo ((is_array($_tmp='Set as default editor mode')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>
			</div>
<textarea name="html" id="personalizationEditor" style="width: <?php echo $this->_tpl_vars['admin']['editorsize_w']; ?>
; height: <?php echo $this->_tpl_vars['admin']['editorsize_h']; ?>
; padding:0px; border:1px solid #CCCCCC; margin:0px;"></textarea>
<?php if ($this->_tpl_vars['admin']['htmleditor']): ?><script>toggleEditor('personalization', true, adesk_editor_init_word_object);</script><?php endif; ?>
    </div>

    <div id="personalizationtext" class="adesk_hidden">
      <h3 style="background:#D3E2F1; padding:5px; padding-left:12px; font-size:14px;"><?php echo ((is_array($_tmp='Text Template')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
      <div style="border-left:1px solid #CCCCCC; border-right:1px solid #CCCCCC; margin:0px; padding:2px; background:#F0F0EE;">
        <div align="right" style="vertical-align: middle; margin-top:5px; margin-bottom:3px;">
          <a href="#" onclick="form_editor_conditional_open('text', 'personalizationtextField');return false;" style="padding:2px; background:url(images/editor_conditional.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; margin-right:10px;"><?php echo ((is_array($_tmp='Insert Conditional Content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
          <a href="#" onclick="form_editor_personalize_open('text', 'personalizationtextField'); return false;" style=" margin-right:8px; padding:2px; background:url(images/editor_personalization.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; font-weight:bold; color:#006600;"><?php echo ((is_array($_tmp='Personalize Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
        </div>
      </div>
      <div style="margin:0px; padding:0px; border:1px solid #ccc;">
        <textarea name="text" cols="65" rows="10" id="personalizationtextField" style="width: <?php echo $this->_tpl_vars['admin']['editorsize_w']; ?>
; height: <?php echo $this->_tpl_vars['admin']['editorsize_h']; ?>
; border:none;"></textarea>
      </div>





      <!--<div>
        <div style="float: right;">
          <a href="#" onclick="form_editor_conditional_open('text', 'personalizationtextField');return false;"><img src="images/editor_conditional.gif" border="0" alt="<?php echo ((is_array($_tmp='Insert Conditional Content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" title="<?php echo ((is_array($_tmp='Insert Conditional Content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" /></a>
          <button type="button" onclick="form_editor_personalize_open('text', 'personalizationtextField');" title="<?php echo ((is_array($_tmp='Insert a Personalization Tag')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
">
            <img src="images/editor_personalization.gif" border="0" alt="<?php echo ((is_array($_tmp='Insert a Personalization Tag')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" align="absmiddle" />
            <?php echo ((is_array($_tmp='Personalize')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

          </button>
        </div>
        <div><?php echo ((is_array($_tmp='TEXT Content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
      </div>
      <div>
        <textarea name="text" cols="65" rows="10" id="personalizationtextField" style="width:100%"></textarea>
      </div>-->
    </div>

    <br />

      <input type="button" id="form_submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="personalization_form_save(personalization_form_id)" />
      <input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1)" />

    <input type="submit" style="display:none"/>
  </form>
</div>