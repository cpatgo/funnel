<?php /* Smarty version 2.6.12, created on 2016-07-11 16:58:35
         compiled from personalization.search.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'personalization.search.htm', 6, false),array('modifier', 'truncate', 'personalization.search.htm', 22, false),array('modifier', 'help', 'personalization.search.htm', 25, false),)), $this); ?>
<div id="search" class="adesk_modal_search" align="center" style="display:none">
  <div class="adesk_modal_inner">
    <form method="GET" onsubmit="personalization_search(); return false">
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
        <tr valign="top">
          <td><?php echo ((is_array($_tmp='In Lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
          <td>
            <select name="listid" id="JSListFilter" size="5" multiple>
<?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
              <option value="<?php echo $this->_tpl_vars['p']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['p']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>
</option>
<?php endforeach; endif; unset($_from); ?>
             </select>
            <?php echo ((is_array($_tmp="Notice: Hold CTRL to select multiple lists.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

            <div>
              <?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

              <a href="#" onclick="return adesk_form_select_multiple_all($('JSListFilter'));"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
              &middot;
              <a href="#" onclick="return adesk_form_select_multiple_none($('JSListFilter'));"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
            </div>
          </td>
        </tr>
      </table></div>
    </form>
    <br/>
    <br/>
    <div>
      <input type="button" value='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="personalization_search()" class="adesk_button_ok" />
      <input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_dom_toggle_display('search', 'block'); adesk_ui_anchor_set(personalization_list_anchor())"/>
    </div>
  </div>
</div>
<script type="text/javascript">
  personalization_search_defaults();
</script>