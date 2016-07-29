<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from add_transaction_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'add_transaction_form.tpl', 4, false),)), $this); ?>
<!-- add_transaction_form -->
<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Transaction'), $this);?>
</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  <?php echo "<div id=\"userid\"></div>"; ?>
  <?php echo "<div id=\"campaignid\"></div>"; ?>
  <?php echo "<div id=\"rtype\"></div>"; ?>
  <?php echo "<div id=\"totalcost\"></div>"; ?>
  <?php echo "<div id=\"ordertid\"></div>"; ?>
  <?php echo "<div id=\"productid\"></div>"; ?>
  <?php echo "<div id=\"data1\"></div>"; ?>
  <?php echo "<div id=\"data2\"></div>"; ?>
  <?php echo "<div id=\"data3\"></div>"; ?>
  <?php echo "<div id=\"data4\"></div>"; ?>
  <?php echo "<div id=\"data5\"></div>"; ?>
  <?php echo "<div id=\"commissions\"></div>"; ?>
  <?php echo "<div id=\"commission\"></div>"; ?>
  <?php echo "<div id=\"rstatus\"></div>"; ?>
</div>

<table>
    <tbody>
        <tr>
            <td><?php echo "<div id=\"SaveButton\"></div>"; ?></td>
            <td><?php echo "<div id=\"CancelButton\"></div>"; ?></td>
        </tr>
    </tbody>
</table>