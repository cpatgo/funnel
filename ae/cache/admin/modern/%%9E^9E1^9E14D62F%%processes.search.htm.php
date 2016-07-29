<?php /* Smarty version 2.6.12, created on 2016-07-08 14:41:08
         compiled from processes.search.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'processes.search.htm', 6, false),array('modifier', 'help', 'processes.search.htm', 24, false),array('function', 'html_options', 'processes.search.htm', 22, false),)), $this); ?>
<div id="search" class="adesk_modal_search" align="center" style="display:none">
  <div class="adesk_modal_inner">
    <form method="GET" onsubmit="processes_search(); return false">
      <table border="0" cellspacing="0" cellpadding="5">
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
          <td><?php echo ((is_array($_tmp='In Processes')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
          <td>
            <select name="listid" id="JSActionFilter" size="5" multiple>
              <option value="0"><?php echo ((is_array($_tmp="- All Processes -")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['actions']), $this);?>

             </select>
            <?php echo ((is_array($_tmp="Notice: Hold CTRL to select multiple items.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

            <div>
              <?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

              <a href="#" onclick="return adesk_form_select_multiple_all($('JSActionFilter'), true);"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
              &middot;
              <a href="#" onclick="return adesk_form_select_multiple_none($('JSActionFilter'));"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
            </div>
          </td>
        </tr>
        <tr valign="top">
          <td><?php echo ((is_array($_tmp='With Statuses')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
          <td>
            <select name="status" id="JSStatusFilter" size="5" multiple>
              <option value=""><?php echo ((is_array($_tmp="- All Statuses -")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              <option value="active"><?php echo ((is_array($_tmp='Running')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              <option value="stall"><?php echo ((is_array($_tmp='Stalled')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              <option value="done"><?php echo ((is_array($_tmp='Completed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
             </select>
            <?php echo ((is_array($_tmp="Notice: Hold CTRL to select multiple items.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

            <div>
              <?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

              <a href="#" onclick="return adesk_form_select_multiple_all($('JSStatusFilter'), true);"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
              &middot;
              <a href="#" onclick="return adesk_form_select_multiple_none($('JSStatusFilter'));"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
            </div>
          </td>
        </tr>
      </table>
    </form>
    <br/>
    <br/>
    <div>
      <input type="button" value='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="processes_search()"/>
      <input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_dom_toggle_display('search', 'block'); adesk_ui_anchor_set(processes_list_anchor())"/>
    </div>
  </div>
</div>
<script type="text/javascript">
  processes_search_defaults();
</script>