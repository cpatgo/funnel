<?php /* Smarty version 2.6.12, created on 2016-07-08 16:53:15
         compiled from list.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'list.form.htm', 5, false),)), $this); ?>
<div id="form" class="adesk_hidden">
  <form method="POST" onsubmit="list_form_save(list_form_id); return false">
    <input type="hidden" name="id" id="form_id" />

  <h1 id="list_title_add"><?php echo ((is_array($_tmp='Add New List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h1>
  <h1 id="list_title_edit"><?php echo ((is_array($_tmp='Edit This List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h1>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list.form.settings.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list.form.optinoptout.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php if ($this->_tpl_vars['admin']['pg_list_bounce']): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list.form.bounce.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list.form.external.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <br />
    <div>
      <input type="button" id="form_submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="list_form_save(list_form_id)" />
      <input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1)" />
    </div>
    <input type="submit" style="display:none"/>
  </form>
</div>

<div id="added" class="adesk_modal" align="center" style="display: none">
  <div class="adesk_modal_inner">
    <h3 class="m-b"><?php echo ((is_array($_tmp='Your list has been added')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
    <div><?php echo ((is_array($_tmp="Now that your list has been added you can start adding subscribers to your list so that you can send a campaign.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
    <br />
    <div>
      <input type="button" class="adesk_button_ok" value="<?php echo ((is_array($_tmp='Add Subscriber')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.location.href='desk.php?action=subscriber#form-0';" />
      <input type="button" class="adesk_button_ok" value="<?php echo ((is_array($_tmp='Import Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.location.href='desk.php?action=subscriber_import';" />
      <input type="button" class="adesk_button_close" value="<?php echo ((is_array($_tmp='Manage Lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_dom_display_none('added'); adesk_ui_anchor_set(list_list_anchor())" />
    </div>
  </div>
</div>