<?php /* Smarty version 2.6.12, created on 2016-07-08 17:09:38
         compiled from list_field_list.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'list_field_list.inc.htm', 6, false),array('modifier', 'truncate', 'list_field_list.inc.htm', 13, false),)), $this); ?>
<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr class="adesk_table_header_options">
    <td align="left">
<?php if ($this->_tpl_vars['listid'] > 0): ?>
<span style="float: right;">
  <input class="adesk_button_view" type="button" value="<?php echo ((is_array($_tmp='View List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.location = 'desk.php?action=list&id=<?php echo $this->_tpl_vars['relid']; ?>
';" />
</span>
<?php endif; ?>
    <select name="somename" id="JSListManager" size="1" onchange="window.location = 'desk.php?action=list_field&listid=' + this.value + '&relid=' + this.value;">
      <option value="0"<?php if ($this->_tpl_vars['listid'] == 0): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp="List Filter...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
<?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
      <option value="<?php echo $this->_tpl_vars['p']['id']; ?>
"<?php if ($this->_tpl_vars['listid'] == $this->_tpl_vars['p']['id']): ?> selected="selected"<?php endif; ?>>
        <?php echo ((is_array($_tmp=$this->_tpl_vars['p']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>

      </option>
<?php endforeach; endif; unset($_from); ?>
    </select>
<?php if ($this->_tpl_vars['listid'] > 0): ?>
    <input class="adesk_button_clear" type="button" onclick="window.location = 'desk.php?action=list_field';" value="<?php echo ((is_array($_tmp='Clear')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" style="cursor: hand; color: Red;" />
<?php endif; ?>
  </tr>
</table></div>