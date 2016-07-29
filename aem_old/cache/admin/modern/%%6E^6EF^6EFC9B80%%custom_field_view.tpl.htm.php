<?php /* Smarty version 2.6.12, created on 2016-07-08 17:09:38
         compiled from custom_field_view.tpl.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'adesk_js', 'custom_field_view.tpl.htm', 1, false),array('modifier', 'alang', 'custom_field_view.tpl.htm', 6, false),array('modifier', 'js', 'custom_field_view.tpl.htm', 6, false),array('modifier', 'html', 'custom_field_view.tpl.htm', 31, false),array('modifier', 'default', 'custom_field_view.tpl.htm', 67, false),array('modifier', 'adesk_field_type', 'custom_field_view.tpl.htm', 76, false),)), $this); ?>
<?php echo smarty_function_adesk_js(array('base' => "..",'acglobal' => "ajax,dom,b64,str"), $this);?>

<?php echo smarty_function_adesk_js(array('lib' => "scriptaculous/prototype.js"), $this);?>

<?php echo smarty_function_adesk_js(array('lib' => "scriptaculous/scriptaculous.js"), $this);?>


<script type="text/javascript">
  var really_delete = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to delete this field?  This action cannot be undone!")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
<?php if ($this->_tpl_vars['sorting']): ?>
  var _custom_are_you_sure = '<?php echo ((is_array($_tmp=((is_array($_tmp="You have not saved your order changes.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
  <?php echo '
  window.onbeforeunload = null;
  window.onbeforeunload = function () {
    if ($(\'save_order\') && $(\'save_order\').disabled == false) {
      return _custom_are_you_sure;
    }
  }
  '; ?>

  adesk_ajax_init();
<?php endif; ?>
</script>

<?php if (isset ( $this->_tpl_vars['customfield_usersettings_header'] )): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user-settings.header.inc.htm", 'smarty_include_vars' => array('userpage' => 'user_field')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
	<h3 class="m-b"><?php echo $this->_tpl_vars['pageTitle']; ?>
</h3>
<?php endif; ?>

<div class="inner_content">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message.tpl.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php if (isset ( $this->_tpl_vars['custom_fields_desc'] )): ?>
  <div>
    <?php echo ((is_array($_tmp=$this->_tpl_vars['custom_fields_desc'])) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>

  </div>
  <br />
  <?php endif; ?>

<?php if (isset ( $this->_tpl_vars['custom_content_include'] )): ?>
	<?php if ($this->_tpl_vars['custom_content_include'] != ''): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['custom_content_include'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
<?php endif; ?>
<div class=" table-responsive">
  <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table table-striped m-b-none dataTable">
    <thead>
      <tr class="adesk_table_header">
        <td style="width:100px;"><?php echo ((is_array($_tmp='Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
<?php if ($this->_tpl_vars['sorting']): ?>
        <td style="width:25px;">&nbsp;</td>
<?php endif; ?>
        <td><?php echo ((is_array($_tmp='Label')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        <td style="width:100px;"><?php echo ((is_array($_tmp='Type')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
<?php if ($this->_tpl_vars['perstag']): ?>
        <td><?php echo ((is_array($_tmp='Personalization Tag')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
<?php endif; ?>
<?php if ($this->_tpl_vars['infoTitle']): ?>
        <td><?php echo $this->_tpl_vars['infoTitle']; ?>
</td>
<?php endif; ?>
<?php if ($this->_tpl_vars['inlist']): ?>
        <td><?php echo ((is_array($_tmp='Shown in Lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
<?php endif; ?>
      </tr>
    </thead>
    <tbody id="fieldrows">
	  <?php if (isset ( $this->_tpl_vars['fields'] ) && is_array ( $this->_tpl_vars['fields'] )): ?>
      <?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['f']):
?>
      <tr class="adesk_table_row">
        <td>
          <a href="desk.php?action=<?php echo ((is_array($_tmp=$this->_tpl_vars['get']['action'])) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
&mode=edit&id=<?php echo $this->_tpl_vars['f']['id']; ?>
&relid=<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['relid'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
"><?php echo ((is_array($_tmp='Edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
          &nbsp;
          <a href="desk.php?action=<?php echo ((is_array($_tmp=$this->_tpl_vars['get']['action'])) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
&mode=delete&id=<?php echo $this->_tpl_vars['f']['id']; ?>
&relid=<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['relid'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
" onclick="return confirm(really_delete)"><?php echo ((is_array($_tmp='Delete')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
          <input type="hidden" value="<?php echo $this->_tpl_vars['f']['id']; ?>
">
        </td>
<?php if ($this->_tpl_vars['sorting']): ?>
<td style="text-align: center; cursor:move;"><img src="<?php echo ((is_array($_tmp=@$this->_tpl_vars['__'])) ? $this->_run_mod_handler('default', true, $_tmp, '..') : smarty_modifier_default($_tmp, '..')); ?>
/awebdesk/media/drag_icon.gif" width="11" height="23" /></td>
<?php endif; ?>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['f']['title'])) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
 <?php if ($this->_tpl_vars['f']['req'] == 1): ?><span style="color:#999999;">(<?php echo ((is_array($_tmp='Required')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
)</span><?php endif; ?></td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['f']['type'])) ? $this->_run_mod_handler('adesk_field_type', true, $_tmp) : smarty_modifier_adesk_field_type($_tmp)); ?>
</td>
<?php if ($this->_tpl_vars['perstag']): ?>
        <td>%<?php if (! isset ( $this->_tpl_vars['f']['perstag'] ) || $this->_tpl_vars['f']['perstag'] == ''): ?>PERS_<?php echo $this->_tpl_vars['f']['id'];  else:  echo $this->_tpl_vars['f']['perstag'];  endif; ?>%</td>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['custom_row_info'] )): ?>
	<?php if ($this->_tpl_vars['custom_row_info'] != ''): ?>
        <td>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['custom_row_info'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </td>
	<?php endif; ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['inlist']): ?>
        <td><?php if ($this->_tpl_vars['f']['show_in_list']):  echo ((is_array($_tmp='Yes')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp));  else:  echo ((is_array($_tmp='No')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp));  endif; ?></td>
<?php endif; ?>
      </tr>
      <?php endforeach; endif; unset($_from); ?>
    </tbody>
    <?php if (isset ( $this->_tpl_vars['mirrors'] )): ?>
    <tbody id="mirroredrows">
      <?php $_from = $this->_tpl_vars['mirrors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['m']):
?>
      <tr class="row_normal">
        <td style="width:150px">
          <i><?php echo ((is_array($_tmp='Mirrored from')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['m']['mirror_src'])) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
</i>
          <input type="hidden" value="<?php echo $this->_tpl_vars['m']['id']; ?>
">
        </td>
<?php if ($this->_tpl_vars['sorting']): ?>
<td style="width:25px; cursor:move"><img src="<?php echo ((is_array($_tmp=@$this->_tpl_vars['__'])) ? $this->_run_mod_handler('default', true, $_tmp, '..') : smarty_modifier_default($_tmp, '..')); ?>
/awebdesk/media/drag_icon.gif" width="11" height="23"></td>
<?php endif; ?>
        <td style="width:250px"><?php echo ((is_array($_tmp=$this->_tpl_vars['m']['title'])) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
</td>
        <td style="width:100px"><?php echo ((is_array($_tmp=$this->_tpl_vars['m']['type'])) ? $this->_run_mod_handler('adesk_field_type', true, $_tmp) : smarty_modifier_adesk_field_type($_tmp)); ?>
</td>
      </tr>
      <?php endforeach; endif; unset($_from); ?>
	  <?php endif; ?>
    </tbody>
    <?php endif; ?>
  </table>
</div>
<?php if ($this->_tpl_vars['sorting']): ?>
  <br />
  <input type="button" id="save_order" onclick="save_order()" value='<?php echo ((is_array($_tmp='Save Order')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' class="adesk_button_order" disabled>
<?php endif; ?>

  <br />
  <br />
  <form action="desk.php" method="GET">
    <select name="type">
<?php $_from = $this->_tpl_vars['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
      <option value="<?php echo $this->_tpl_vars['k']; ?>
"><?php echo $this->_tpl_vars['v']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
    </select>
    <input type="submit" value='<?php echo ((is_array($_tmp='Add Field')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
'>
    <input type="hidden" name="action" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['get']['action'])) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
">
    <input type="hidden" name="mode" value="add">
    <input type="hidden" name="relid" value="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['relid'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
">
  </form>
</div>

<script type="text/javascript">
<?php if (isset ( $this->_tpl_vars['custom_update_order'] )): ?>
	<?php if ($this->_tpl_vars['custom_update_order'] != ''): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['custom_update_order'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['sorting']): ?>
<?php echo '
function save_order() {
  var rows = document.getElementById(\'fieldrows\').getElementsByTagName(\'tr\');
  var ary  = new Array(rows.length);

  for (var i = 0; i < rows.length; i++) {
    ary[i] = rows[i].getElementsByTagName(\'input\')[0].value;
  }

  if (update_order)
    update_order(ary);
}

function handle_onUpdate() {
  document.getElementById(\'save_order\').disabled = false;
  // make sure each <tr> is position: relative (scriptaculous was putting \'absolute\', making the row appear in upper corner)
  var fieldrows_trs = $(\'fieldrows\').getElementsByTagName(\'tr\');
  for (var i = 0; i < fieldrows_trs.length; i++) {
	  fieldrows_trs[i].style.position = \'relative\';
  }
}

Sortable.create("fieldrows", {ghosting: true, tag: \'tr\', onUpdate: handle_onUpdate});
'; ?>

<?php endif; ?>
</script>