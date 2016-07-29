<?php /* Smarty version 2.6.12, created on 2016-07-18 12:02:44
         compiled from header.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'header.form.htm', 7, false),array('modifier', 'help', 'header.form.htm', 16, false),array('modifier', 'truncate', 'header.form.htm', 36, false),)), $this); ?>
<div id="form" class="adesk_hidden">
  <form method="POST" onsubmit="header_form_save(header_form_id); return false">
    <input type="hidden" name="id" id="form_id" />
    <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5">
      <tr valign="top">
        <td colspan="2">
          <?php echo ((is_array($_tmp="Warning: This feature is for advanced users only.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
          <?php echo ((is_array($_tmp="Custom headers are headers that will go in the message source, invisible to the subscriber.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
          <b><?php echo ((is_array($_tmp="Some custom headers might prevent your mailings from being sent.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</b>
        </td>
      </tr>
      <tr valign="top">
        <td><?php echo ((is_array($_tmp="Name: (Not part of header)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        <td>
          <input name="title" id="titleField" type="text" size="50" value="" />
          <?php echo ((is_array($_tmp='This is just a label for your own internal use')) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

        </td>
      </tr>
      <tr valign="top">
        <td><?php echo ((is_array($_tmp="Header Information:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        <td>
          <input name="name" id="nameField" type="text" value="" size="25" maxlength="75" value="" />:
          <input name="value" id="valueField" type="text" value="" size="50" maxlength="75" />
          <select id="headerPersTags" onChange="form_editor_insert($('valueField'), this.value);this.value='';">
            <option value="" selected><?php echo ((is_array($_tmp='Insert Personalization Tag')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
                      </select>
          <?php echo ((is_array($_tmp="Header needs to be in format 'HEADER-NAME: HEADER VALUE'")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

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
          <?php echo ((is_array($_tmp="Notice: This custom header will be a member of each selected list! Hold CTRL to select multiple lists.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

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
      <tr valign="top" id="tstampRow" class="adesk_hidden">
        <td><?php echo ((is_array($_tmp="Date Created:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        <td><span id="tstampSpan"></span></td>
      </tr>
    </table></div>

    <br />
    <div>
      <input type="button" id="form_submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="header_form_save(header_form_id)" />
      <input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1)" />
    </div>
    <input type="submit" style="display:none"/>
  </form>
</div>