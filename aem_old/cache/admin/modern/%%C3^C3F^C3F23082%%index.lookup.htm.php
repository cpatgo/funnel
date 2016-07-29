<?php /* Smarty version 2.6.12, created on 2016-07-08 14:06:51
         compiled from index.lookup.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'index.lookup.htm', 2, false),)), $this); ?>
<div>
<?php echo ((is_array($_tmp="To have your password reset, please enter the following fields:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

</div>
<br />

<form action="index.php" method="post" name="log_user" id="log_user">
  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td><div align="left"><?php echo ((is_array($_tmp='Username')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
              <input name="user" type="text" id="user" style="width:97%;" value="" />
              <br />
      </div></td>
    </tr>
    <tr>
      <td><div align="left"><br />
        <?php echo ((is_array($_tmp="E-mail Addresss")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
        <input name="email" type="text" id="email" style="width:97%;" />
      </div></td>
    </tr>
    <tr>
      <td>
      <br />
      <input type="submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" />
      <input type="hidden" name="action" id="f" value="account_lookup" />
      </td>
    </tr>
  </table></div>
</form>
