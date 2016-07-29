<?php /* Smarty version 2.6.12, created on 2016-07-08 14:15:47
         compiled from subscriber.search.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'subscriber.search.htm', 3, false),array('modifier', 'truncate', 'subscriber.search.htm', 26, false),array('modifier', 'help', 'subscriber.search.htm', 36, false),)), $this); ?>
<div id="search" class="adesk_modal_search" align="center" style="display:none">
  <div class="adesk_modal_inner">
    <h3 class="m-b"><?php echo ((is_array($_tmp='Search Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
    <form method="GET" onsubmit="subscriber_search(); return false">
      <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5" width="100%">
        <tr>
          <td width="75"><?php echo ((is_array($_tmp='Search for')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
          <td>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'autocomplete.inc.htm', 'smarty_include_vars' => array('fieldPrefix' => 'subscriber','fieldID' => 'search_content','fieldName' => 'content','fieldStyle' => 'width:99%;')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
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
			<input type="checkbox" name="custom" value="1"> <?php echo ((is_array($_tmp='Custom fields')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br/>
          </td>
        </tr>
        <tr valign="top">
          <td><?php echo ((is_array($_tmp='In Lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
          <td>
            <select name="listid" id="JSListFilter" size="4" multiple>
<?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
              <option value="<?php echo $this->_tpl_vars['p']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['p']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>
</option>
<?php endforeach; endif; unset($_from); ?>
             </select>

            <div>

              <?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

              <a href="#" onclick="return adesk_form_select_multiple_all($('JSListFilter'));"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
              &middot;
              <a href="#" onclick="return adesk_form_select_multiple_none($('JSListFilter'));"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			  <?php echo ((is_array($_tmp="Notice: Hold CTRL to select multiple lists.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

            </div>
          </td>
        </tr>
        <tr valign="top">
          <td><?php echo ((is_array($_tmp='With Statuses')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
          <td>
            <select name="status" id="JSStatusFilter" size="4" multiple style="width:99%;"/>
              <option value=""><?php echo ((is_array($_tmp="- All Statuses -")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              <option value="1"><?php echo ((is_array($_tmp='Active')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              <option value="0"><?php echo ((is_array($_tmp='Unconfirmed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              <option value="2"><?php echo ((is_array($_tmp='Unsubscribed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              <option value="3"><?php echo ((is_array($_tmp='Bounced')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
             </select>

            <div>
              <?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

              <a href="#" onclick="return adesk_form_select_multiple_all($('JSStatusFilter'), true);"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
              &middot;
              <a href="#" onclick="return adesk_form_select_multiple_none($('JSStatusFilter'));"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			  <?php echo ((is_array($_tmp="Notice: Hold CTRL to select multiple statuses.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

            </div>
          </td>
        </tr>
      </table></div>
    </form>
    <br/>

    <div>
      <input type="button" value='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="subscriber_search()" class="adesk_button_ok" />
      <input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_dom_toggle_display('search', 'block'); adesk_ui_anchor_set(subscriber_list_anchor())"/>
    </div>
  </div>
</div>
<script type="text/javascript">
  subscriber_search_defaults();
</script>