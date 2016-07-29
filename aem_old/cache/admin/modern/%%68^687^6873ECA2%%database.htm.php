<?php /* Smarty version 2.6.12, created on 2016-07-08 16:20:57
         compiled from database.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'database.htm', 5, false),)), $this); ?>
<script type="text/javascript">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "database.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>

<h3 class="m-b"><?php echo ((is_array($_tmp='Database Utilities')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

<div>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message.tpl.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <h3><?php echo ((is_array($_tmp='Backup Database')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

  <?php echo ((is_array($_tmp="Clicking the button below will prepare a backup of your data and let you save it as a file on your machine. The format of the backup is a series of MySQL statements, suitable to be restored (imported) by a utility such as phpMyAdmin.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

  <br>
  <form action="desk.php" method="GET">
	<input type="hidden" name="action" value="database">
	<input type="hidden" name="backup" value="1">
	<input type="submit" value='<?php echo ((is_array($_tmp='Backup database')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
'>
	<label>
	  <input type="checkbox" name="gz" value="1" />
	  <?php echo ((is_array($_tmp='GZIP')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	</label>
  </form>
</div>
<div>
  <h3><?php echo ((is_array($_tmp='Repair Database')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

  <?php echo ((is_array($_tmp="If your application is behaving strangely, or you are seeing errors on certain pages, your database may be in need of repair. Click the button below to begin that process.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

  <br>
  <input type="button" value='<?php echo ((is_array($_tmp='Repair database')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="database_repair()">
</div>

<div>
  <h3><?php echo ((is_array($_tmp='Optimize Database')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

  <?php echo ((is_array($_tmp="If your application is behaving slowly after a long period of usage, your data may need to be optimized. Click the button below to do just that.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

  <br>
  <input type="button" value='<?php echo ((is_array($_tmp='Optimize database')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="database_optimize()">
</div>