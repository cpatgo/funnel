<?php /* Smarty version 2.6.12, created on 2016-07-08 14:21:03
         compiled from tinymce.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'tinymce.htm', 66, false),array('modifier', 'alang', 'tinymce.htm', 69, false),)), $this); ?>
<?php if (isset ( $this->_tpl_vars['site']['brand_editorw'] )): ?>
<?php $this->assign('editor_w', $this->_tpl_vars['site']['brand_editorw']); ?>
<?php $this->assign('editor_h', $this->_tpl_vars['site']['brand_editorh']); ?>
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['admin']['editorsize_w'] )): ?>
<?php $this->assign('editor_w', $this->_tpl_vars['admin']['editorsize_w']); ?>
<?php $this->assign('editor_h', $this->_tpl_vars['admin']['editorsize_h']); ?>
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['width'] )): ?>
<?php $this->assign('editor_w', $this->_tpl_vars['width']); ?>
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['height'] )): ?>
<?php $this->assign('editor_h', $this->_tpl_vars['height']); ?>
<?php endif; ?>

<?php if (! isset ( $this->_tpl_vars['js'] )): ?>
<?php $this->assign('js', 1); ?>
<?php endif; ?>

<?php if (! isset ( $this->_tpl_vars['setDefaultAction'] )): ?>
<?php $this->assign('setDefaultAction', "users.user_update_value"); ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['js'] == 1): ?>
<script type="text/javascript">
  var tmpEditorContent = '';
  var editorSetDefaultAction = '<?php echo $this->_tpl_vars['setDefaultAction']; ?>
';

  <?php echo '
  function toggleEditor(id, action, flips) {
	if ( action == adesk_editor_is(id + \'Editor\') ) return false;
	adesk_editor_toggle(id + \'Editor\', flips);
	if ($(id + \'EditorLinkOn\'))
		$(id + \'EditorLinkOn\').className  = ( action ? \'currenttab\' : \'othertab\' );

	if ($(id + \'EditorLinkOff\'))
		$(id + \'EditorLinkOff\').className = ( !action ? \'currenttab\' : \'othertab\' );

	if ($(id + \'EditorLinkDefault\'))
		$(id + \'EditorLinkDefault\').className = ( ( action != ( adesk_js_admin.htmleditor == 1 ) ) ? \'adesk_block\' : \'adesk_hidden\' );

	if ( !$(id + \'Editor\') )
	  tmpEditorContent = adesk_form_value_get($(id + \'Editor\'));
	else // heavy hack!!!
	  tmpEditorContent = adesk_form_value_get($(id + \'Editor\'));
	return false;
  }

  function setDefaultEditor(id) {
	  var isEditor = adesk_editor_is(id + \'Editor\');
	  if ( isEditor == ( adesk_js_admin.htmleditor == 1 ) ) return false;
	  // send save command
	  // save new admin limit remotelly
	  adesk_ajax_call_cb(\'awebdeskapi.php\', editorSetDefaultAction, null, \'htmleditor\', ( isEditor ? 1 : 0 ));
	  $(id + \'EditorLinkDefault\').className = \'disabledtab\';
	  adesk_js_admin.htmleditor = ( isEditor ? 1 : 0 );
	  return false;
  }
  '; ?>

</script>
<?php endif; ?>

<?php if (( ((is_array($_tmp=@$this->_tpl_vars['navlist'])) ? $this->_run_mod_handler('default', true, $_tmp, 1) : smarty_modifier_default($_tmp, 1)) )): ?>
<ul class="navlist">
  <li id="<?php echo $this->_tpl_vars['id']; ?>
EditorLinkDefault" class="disabledtab" style="float: right; text-align: right; width: 100px;">
	<a href="#" onclick="return setDefaultEditor('<?php echo $this->_tpl_vars['id']; ?>
');"><?php echo ((is_array($_tmp='Set as Default')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
  </li>
  <li id="<?php echo $this->_tpl_vars['id']; ?>
EditorLinkOn" class="<?php if ($this->_tpl_vars['admin']['htmleditor']): ?>currenttab<?php else: ?>othertab<?php endif; ?>">
	<a href="#" onclick="return toggleEditor('<?php echo $this->_tpl_vars['id']; ?>
', true, <?php echo ((is_array($_tmp=@$this->_tpl_vars['editobject'])) ? $this->_run_mod_handler('default', true, $_tmp, 'adesk_editor_init_normal_object') : smarty_modifier_default($_tmp, 'adesk_editor_init_normal_object')); ?>
);"><span><?php echo ((is_array($_tmp='HTML Editor')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a>
  </li>
  <li id="<?php echo $this->_tpl_vars['id']; ?>
EditorLinkOff" class="<?php if (! $this->_tpl_vars['admin']['htmleditor']): ?>currenttab<?php else: ?>othertab<?php endif; ?>">
	<a href="#" onclick="return toggleEditor('<?php echo $this->_tpl_vars['id']; ?>
', false, <?php echo ((is_array($_tmp=@$this->_tpl_vars['editobject'])) ? $this->_run_mod_handler('default', true, $_tmp, 'adesk_editor_init_normal_object') : smarty_modifier_default($_tmp, 'adesk_editor_init_normal_object')); ?>
);"><span><?php echo ((is_array($_tmp='Text Editor')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a>
  </li>
</ul>
<?php endif; ?>

<textarea name="<?php echo $this->_tpl_vars['name']; ?>
" id="<?php echo $this->_tpl_vars['id']; ?>
Editor" style="width: <?php echo $this->_tpl_vars['editor_w']; ?>
; height: <?php echo $this->_tpl_vars['editor_h']; ?>
;"><?php echo ((is_array($_tmp=@$this->_tpl_vars['content'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
</textarea>
<script type="text/javascript">
  if (<?php echo ((is_array($_tmp=@$this->_tpl_vars['ishtml'])) ? $this->_run_mod_handler('default', true, $_tmp, 'false') : smarty_modifier_default($_tmp, 'false')); ?>
)
	if (<?php echo ((is_array($_tmp=@$this->_tpl_vars['navlist'])) ? $this->_run_mod_handler('default', true, $_tmp, 1) : smarty_modifier_default($_tmp, 1)); ?>
)
	  toggleEditor('<?php echo $this->_tpl_vars['id']; ?>
', true, <?php echo ((is_array($_tmp=@$this->_tpl_vars['editobject'])) ? $this->_run_mod_handler('default', true, $_tmp, 'adesk_editor_init_normal_object') : smarty_modifier_default($_tmp, 'adesk_editor_init_normal_object')); ?>
);
	else
	  adesk_editor_toggle('<?php echo $this->_tpl_vars['id']; ?>
Editor', <?php echo ((is_array($_tmp=@$this->_tpl_vars['editobject'])) ? $this->_run_mod_handler('default', true, $_tmp, 'adesk_editor_init_normal_object') : smarty_modifier_default($_tmp, 'adesk_editor_init_normal_object')); ?>
);
</script>