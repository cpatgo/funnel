<?php /* Smarty version 2.6.12, created on 2016-07-08 16:53:15
         compiled from list.search.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'list.search.htm', 6, false),)), $this); ?>
<div id="search" class="adesk_modal_search" align="center" style="display:none">
  <div class="adesk_modal_inner">
    <form method="GET" onsubmit="list_search(); return false">
      <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5">
        <tr>
          <td><?php echo ((is_array($_tmp='Search for')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
          <td><input type="text" name="content" id="search_content"/></td>
        </tr>
        <tr valign="top">
          <td><?php echo ((is_array($_tmp='Search in')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
          <td>
            <?php $_from = $this->_tpl_vars['search_sections']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['s']):
?>
            <input type="checkbox" name="section[]" value="<?php echo $this->_tpl_vars['s']['col']; ?>
"> <?php echo $this->_tpl_vars['s']['label']; ?>
<br/>
            <?php endforeach; endif; unset($_from); ?>
          </td>
        </tr>
      </table></div>
    </form>
    <br/>
    <br/>
    <div>
      <input type="button" value='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="list_search()" class="adesk_button_ok" />
      <input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_dom_toggle_display('search', 'block'); adesk_ui_anchor_set(list_list_anchor())"/>
    </div>
  </div>
</div>
<script type="text/javascript">
  list_search_defaults();
</script>