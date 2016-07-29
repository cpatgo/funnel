<?php /* Smarty version 2.6.12, created on 2016-07-08 16:53:15
         compiled from form.optinoptout.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'form.optinoptout.inc.htm', 2, false),array('modifier', 'html', 'form.optinoptout.inc.htm', 14, false),array('function', 'adesk_upload', 'form.optinoptout.inc.htm', 124, false),)), $this); ?>
<div class="h2_wrap_static" style="margin-bottom:10px;">
  <h5><?php echo ((is_array($_tmp='Email Confirmation Set Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
  <div id="optinform" class="h2_content">
	<input name="optname" type="text" id="optnameField" value="" size="45" />
  </div>
</div>

<?php if (isset ( $this->_tpl_vars['lists'] )): ?>
<div class="h2_wrap_static" style="margin-bottom:10px;">
  <h5><?php echo ((is_array($_tmp='Lists Which May Access this Confirmation Set')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
  <div id="optinform" class="h2_content">
	<select id="form_lists" name="lists" multiple>
	  <?php $_from = $this->_tpl_vars['lists']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['e']):
?>
	  <option id="form_list<?php echo $this->_tpl_vars['e']['id']; ?>
" value="<?php echo $this->_tpl_vars['e']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['e']['name'])) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
</option>
	  <?php endforeach; endif; unset($_from); ?>
	</select>
  </div>
</div>
<?php endif; ?>

<div class="h2_wrap_static" style="margin-bottom:10px;">
  <h5><?php echo ((is_array($_tmp="Opt-In Confirmations")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>

  <div id="optinform" class="h2_content">

	<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5">
	  <tr>
		<td><?php echo ((is_array($_tmp="Confirm Opt-In")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td id="optinselectors">
		  <label><input type="radio" name="optin_confirm" id="optinconfirmFieldYes" value="1" onclick="adesk_editor_mime_toggle('optin',  this.checked);" /> <?php echo ((is_array($_tmp='Yes')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label> &nbsp;&nbsp;&nbsp;
		  <label><input type="radio" name="optin_confirm" id="optinconfirmFieldNo"  value="0" onclick="adesk_editor_mime_toggle('optin', !this.checked);" /> <?php echo ((is_array($_tmp='No')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
		</td>
	  </tr>
	  <tbody id="optintable" class="adesk_hidden">
		<tr>
		  <td><?php echo ((is_array($_tmp='Format')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td>
			<select name="optin_format" id="optinformatField" onchange="adesk_editor_mime_prompt('optin', this.value);">
              <option value="html"><?php echo ((is_array($_tmp='HTML Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              <option value="text"><?php echo ((is_array($_tmp='Text Only Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              <option value="mime"><?php echo ((is_array($_tmp="HTML & TEXT Email")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			</select>
		  </td>
		</tr>
		<tr>
		  <td><?php echo ((is_array($_tmp='Subject')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><input name="optin_subject" type="text" id="optinsubjectField" value="" size="45" /></td>
		</tr>
		<tr>
		  <td><?php echo ((is_array($_tmp='From Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><input name="optin_from_name" type="text" id="optinfromnameField" value="" size="45" /></td>
		</tr>
		<tr>
		  <td><?php echo ((is_array($_tmp='From Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><input name="optin_from_email" type="text" id="optinfromemailField" value="" size="45" /></td>
		</tr>
	  </tbody>
	</table></div>

	<div id="optinhtml" class="adesk_hidden">

        <h3 style="background:#D3E2F1; padding:5px; padding-left:12px; font-size:14px;"><?php echo ((is_array($_tmp='HTML Version Of Your Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
			<div style="border-left:1px solid #CCCCCC; border-right:1px solid #CCCCCC; border-top:1px solid #CCCCCC; margin:0px; padding:2px; padding-bottom:0px; background:#F0F0EE;">
				<div style="float:right; margin-top:5px; margin-bottom:3px;">

					<div align="right" style="vertical-align: middle;">
<?php if ($this->_tpl_vars['templatesCnt']): ?>
						<a href="#" onclick="form_editor_template_open('html', 'optinEditor');return false;" style="padding:2px; background:url(images/editor_template.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; margin-right:10px;"><?php echo ((is_array($_tmp='Insert Template')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php endif; ?>
						<a href="#" onclick="form_editor_conditional_open('html', 'optinEditor');return false;" style="padding:2px; background:url(images/editor_conditional.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; margin-right:10px;"><?php echo ((is_array($_tmp='Insert Conditional Content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
						<a href="#" onclick="form_editor_personalize_open('html', 'optinEditor'); return false;" style=" margin-right:8px; padding:2px; background:url(images/editor_personalization.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; font-weight:bold; color:#006600;"><?php echo ((is_array($_tmp='Personalize Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
					</div>
				</div>

				<div>
				<ul class="navlist" style="padding-left:4px; border-bottom:0px;">

					<li id="optinEditorLinkOn" class="<?php if ($this->_tpl_vars['admin']['htmleditor']): ?>currenttab<?php else: ?>othertab<?php endif; ?>">
						<a href="#" onclick="return toggleEditor('optin', true, adesk_editor_init_word_object);" style="border-bottom:0px;"><span><?php echo ((is_array($_tmp='Visual Editor')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a>
					</li>
					<li id="optinEditorLinkOff" class="<?php if (! $this->_tpl_vars['admin']['htmleditor']): ?>currenttab<?php else: ?>othertab<?php endif; ?>">
						<a href="#" onclick="return toggleEditor('optin', false, adesk_editor_init_word_object);" style="border-bottom:0px;"><span><?php echo ((is_array($_tmp='Code Editor')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a>
					</li>

				</ul>
				</div>
				<div id="optinEditorLinkDefault" class="adesk_hidden" style="padding:2px; padding-left:4px; font-size:10px; background:none; border-top:1px solid #CCCCCC; background:#FFFFD5; ">
					<a href="#" onclick="return setDefaultEditor('optin');" style="color:#666666;"><?php echo ((is_array($_tmp='Set as default editor mode')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>
			</div>

	  <textarea name="optin_html" id="optinEditor" style="width: <?php echo $this->_tpl_vars['admin']['editorsize_w']; ?>
; height: <?php echo $this->_tpl_vars['admin']['editorsize_h']; ?>
; padding:0px; border:1px solid #CCCCCC; margin:0px;"></textarea>
	  <?php if ($this->_tpl_vars['admin']['htmleditor']): ?><script>toggleEditor('optin', true, adesk_editor_init_word_object);</script><?php endif; ?>

	</div>

	<div id="optintext" class="adesk_hidden">
      <h3 style="background:#D3E2F1; padding:5px; padding-left:12px; font-size:14px;"><?php echo ((is_array($_tmp='Text Version Of Your Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
      <div style="border-left:1px solid #CCCCCC; border-right:1px solid #CCCCCC; margin:0px; padding:2px; background:#F0F0EE;">
        <div align="right" style="vertical-align: middle; margin-top:5px; margin-bottom:3px;">
<?php if ($this->_tpl_vars['templatesCnt']): ?>
          <a href="#" onclick="form_editor_template_open('text', 'optintextField');return false;" style="padding:2px; background:url(images/editor_template.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; margin-right:10px;"><?php echo ((is_array($_tmp='Insert Template')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php endif; ?>
          <a href="#" onclick="form_editor_conditional_open('text', 'optintextField');return false;" style="padding:2px; background:url(images/editor_conditional.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; margin-right:10px;"><?php echo ((is_array($_tmp='Insert Conditional Content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
          <a href="#" onclick="form_editor_personalize_open('text', 'optintextField'); return false;" style=" margin-right:8px; padding:2px; background:url(images/editor_personalization.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; font-weight:bold; color:#006600;"><?php echo ((is_array($_tmp='Personalize Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
        </div>
      </div>
      <div style="margin:0px; padding:0px; border:1px solid #ccc;">
		<textarea name="optin_text" class="text_ruler" cols="65" rows="10" id="optintextField" style="width:<?php echo $this->_tpl_vars['admin']['editorsize_w']; ?>
; height: <?php echo $this->_tpl_vars['admin']['editorsize_h']; ?>
; border:none;"></textarea>
	  </div>
	</div>

	<?php if ($this->_tpl_vars['admin']['limit_attachment']): ?>
	<div id="optinattachments" class="adesk_block">
      <h3 style="background:#D3E2F1; padding:5px; padding-left:12px; font-size:14px;"><?php echo ((is_array($_tmp="Opt-In Message Attachments")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
      <div style="border-left:1px solid #CCCCCC; border-right:1px solid #CCCCCC; margin:0px; padding:2px; background:#F0F0EE;">
        <br />
	    <?php echo smarty_function_adesk_upload(array('id' => 'optin_attach','name' => 'optinattach','action' => 'optinoptout_attach','limit' => $this->_tpl_vars['admin']['limit_attachment']), $this);?>

	  </div>
	</div>
	<?php endif; ?>

  </div>

</div>

<div class="h2_wrap_static">

  <h5><?php echo ((is_array($_tmp="Opt-Out Confirmations")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
  <div id="optoutform" class="h2_content">
	<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5">
	  <tr>
		<td><?php echo ((is_array($_tmp="Confirm Opt-Out")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td id="optoutselectors">
		  <label><input type="radio" name="optout_confirm" id="optoutconfirmFieldYes" value="1" onclick="adesk_editor_mime_toggle('optout',  this.checked);" /> <?php echo ((is_array($_tmp='Yes')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label> &nbsp;&nbsp;&nbsp;
		  <label><input type="radio" name="optout_confirm" id="optoutconfirmFieldNo"  value="0" onclick="adesk_editor_mime_toggle('optout', !this.checked);" /> <?php echo ((is_array($_tmp='No')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
		</td>
	  </tr>
	  <tbody id="optouttable" class="adesk_hidden">
		<tr>
		  <td><?php echo ((is_array($_tmp='Format')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td>
			<select name="optout_format" id="optoutformatField" onchange="adesk_editor_mime_prompt('optout', this.value);">
              <option value="html"><?php echo ((is_array($_tmp='HTML Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              <option value="text"><?php echo ((is_array($_tmp='Text Only Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              <option value="mime"><?php echo ((is_array($_tmp="HTML & TEXT Email")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			</select>
		  </td>
		</tr>
		<tr>
		  <td><?php echo ((is_array($_tmp='Subject')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><input name="optout_subject" type="text" id="optoutsubjectField" value="" size="45" /></td>
		</tr>
		<tr>
		  <td><?php echo ((is_array($_tmp='From Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><input name="optout_from_name" type="text" id="optoutfromnameField" value="" size="45" /></td>
		</tr>
		<tr>
		  <td><?php echo ((is_array($_tmp='From Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><input name="optout_from_email" type="text" id="optoutfromemailField" value="" size="45" /></td>
		</tr>
	  </tbody>
	</table></div>

	<div id="optouthtml" class="adesk_hidden">

        <h3 style="background:#D3E2F1; padding:5px; padding-left:12px; font-size:14px;"><?php echo ((is_array($_tmp='HTML Version Of Your Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
			<div style="border-left:1px solid #CCCCCC; border-right:1px solid #CCCCCC; border-top:1px solid #CCCCCC; margin:0px; padding:2px; padding-bottom:0px; background:#F0F0EE;">
				<div style="float:right; margin-top:5px; margin-bottom:3px;">

					<div align="right" style="vertical-align: middle;">
<?php if ($this->_tpl_vars['templatesCnt']): ?>
						<a href="#" onclick="form_editor_template_open('html', 'optoutEditor');return false;" style="padding:2px; background:url(images/editor_template.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; margin-right:10px;"><?php echo ((is_array($_tmp='Insert Template')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php endif; ?>
						<a href="#" onclick="form_editor_conditional_open('html', 'optoutEditor');return false;" style="padding:2px; background:url(images/editor_conditional.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; margin-right:10px;"><?php echo ((is_array($_tmp='Insert Conditional Content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
						<a href="#" onclick="form_editor_personalize_open('html', 'optoutEditor'); return false;" style=" margin-right:8px; padding:2px; background:url(images/editor_personalization.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; font-weight:bold; color:#006600;"><?php echo ((is_array($_tmp='Personalize Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
					</div>
				</div>

				<div>
				<ul class="navlist" style="padding-left:4px; border-bottom:0px;">

					<li id="optoutEditorLinkOn" class="<?php if ($this->_tpl_vars['admin']['htmleditor']): ?>currenttab<?php else: ?>othertab<?php endif; ?>">
						<a href="#" onclick="return toggleEditor('optout', true, adesk_editor_init_word_object);" style="border-bottom:0px;"><span><?php echo ((is_array($_tmp='Visual Editor')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a>
					</li>
					<li id="optoutEditorLinkOff" class="<?php if (! $this->_tpl_vars['admin']['htmleditor']): ?>currenttab<?php else: ?>othertab<?php endif; ?>">
						<a href="#" onclick="return toggleEditor('optout', false, adesk_editor_init_word_object);" style="border-bottom:0px;"><span><?php echo ((is_array($_tmp='Code Editor')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a>
					</li>

				</ul>
				</div>
				<div id="optoutEditorLinkDefault" class="adesk_hidden" style="padding:2px; padding-left:4px; font-size:10px; background:none; border-top:1px solid #CCCCCC; background:#FFFFD5; ">
					<a href="#" onclick="return setDefaultEditor('optout');" style="color:#666666;"><?php echo ((is_array($_tmp='Set as default editor mode')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>
			</div>

	  <textarea name="optout_html" id="optoutEditor" style="width: <?php echo $this->_tpl_vars['admin']['editorsize_w']; ?>
; height: <?php echo $this->_tpl_vars['admin']['editorsize_h']; ?>
; padding:0px; border:1px solid #CCCCCC; margin:0px;"></textarea>
	  <?php if ($this->_tpl_vars['admin']['htmleditor']): ?><script>toggleEditor('optout', true, adesk_editor_init_word_object);</script><?php endif; ?>

	</div>

	<div id="optouttext" class="adesk_hidden">
      <h3 style="background:#D3E2F1; padding:5px; padding-left:12px; font-size:14px;"><?php echo ((is_array($_tmp='Text Version Of Your Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
      <div style="border-left:1px solid #CCCCCC; border-right:1px solid #CCCCCC; margin:0px; padding:2px; background:#F0F0EE;">
        <div align="right" style="vertical-align: middle; margin-top:5px; margin-bottom:3px;">
<?php if ($this->_tpl_vars['templatesCnt']): ?>
          <a href="#" onclick="form_editor_template_open('text', 'optouttextField');return false;" style="padding:2px; background:url(images/editor_template.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; margin-right:10px;"><?php echo ((is_array($_tmp='Insert Template')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php endif; ?>
          <a href="#" onclick="form_editor_conditional_open('text', 'optouttextField');return false;" style="padding:2px; background:url(images/editor_conditional.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; margin-right:10px;"><?php echo ((is_array($_tmp='Insert Conditional Content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
          <a href="#" onclick="form_editor_personalize_open('text', 'optouttextField'); return false;" style=" margin-right:8px; padding:2px; background:url(images/editor_personalization.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; font-weight:bold; color:#006600;"><?php echo ((is_array($_tmp='Personalize Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
        </div>
      </div>
      <div style="margin:0px; padding:0px; border:1px solid #ccc;">
		<textarea name="optout_text" class="text_ruler" cols="65" rows="10" id="optouttextField" style="width:<?php echo $this->_tpl_vars['admin']['editorsize_w']; ?>
; height: <?php echo $this->_tpl_vars['admin']['editorsize_h']; ?>
; border:none;"></textarea>
	  </div>
	</div>

	<?php if ($this->_tpl_vars['admin']['limit_attachment']): ?>
	<div id="optoutattachments" class="adesk_block">
      <h3 style="background:#D3E2F1; padding:5px; padding-left:12px; font-size:14px;"><?php echo ((is_array($_tmp="Opt-Out Message Attachments")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
      <div style="border-left:1px solid #CCCCCC; border-right:1px solid #CCCCCC; margin:0px; padding:2px; background:#F0F0EE;">
        <br />
	    <?php echo smarty_function_adesk_upload(array('id' => 'optout_attach','name' => 'optoutattach','action' => 'optinoptout_attach','limit' => $this->_tpl_vars['admin']['limit_attachment']), $this);?>

	  </div>
	</div>
	<?php endif; ?>

  </div>

</div>
