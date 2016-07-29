<?php /* Smarty version 2.6.12, created on 2016-07-27 12:32:21
         compiled from filter.form.htm */ ?>
<div id="form" class="adesk_hidden_ie">
  <form method="POST" onsubmit="filter_form_save(filter_form_id); return false">
    <input type="hidden" name="id" id="form_filter_id" />

	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "filter.form.inc.htm", 'smarty_include_vars' => array('included' => 0)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <input type="submit" style="display:none"/>
  </form>
</div>