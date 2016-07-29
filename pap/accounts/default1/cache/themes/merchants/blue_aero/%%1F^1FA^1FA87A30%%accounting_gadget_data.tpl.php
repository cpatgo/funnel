<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:02
         compiled from accounting_gadget_data.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'accounting_gadget_data.tpl', 4, false),)), $this); ?>
<!-- accounting_gadget_data  -->
<table>
	<tr>
	  <td colspan="3" style="color: orange; font-weight: bold;"><?php echo smarty_function_localize(array('str' => 'Accounting stats:'), $this);?>
</td>
	</tr>
	<tr>
	  <td><?php echo smarty_function_localize(array('str' => 'Commissions'), $this);?>
</td><td>&nbsp;</td><td nowrap class="TextAlignRight"><?php echo "<div id=\"commissions\"></div>"; ?></td>
	</tr>
	<tr>  
	  <td><?php echo smarty_function_localize(array('str' => 'Fees'), $this);?>
</td><td>&nbsp;</td><td nowrap class="TextAlignRight"><?php echo "<div id=\"fees\"></div>"; ?></td>
	</tr><tr>
	  <td><?php echo smarty_function_localize(array('str' => 'Payments'), $this);?>
</td><td>&nbsp;</td><td nowrap class="TextAlignRight"><?php echo "<div id=\"payments\"></div>"; ?></td>
	</tr>
	<tr>
	  <td><?php echo smarty_function_localize(array('str' => 'Ballance'), $this);?>
</td><td>&nbsp;</td><td nowrap class="TextAlignRight"><?php echo "<div id=\"ballance\"></div>"; ?></td>
	</tr>
</table>