<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from compressed_commission_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'compressed_commission_settings.tpl', 8, false),)), $this); ?>
<!--	compressed_commission_settings		-->


<?php echo "<div id=\"PlacementOverviewGrid\"></div>"; ?>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'General settings'), $this);?>
</div>
	</div>
    <?php echo "<div id=\"processing\"></div>"; ?>
    <?php echo "<div id=\"recurrence\"></div>"; ?>
    <?php echo "<div id=\"recurrenceDay\"></div>"; ?>
    <?php echo "<div id=\"compressedCommissionAddTransactions\"></div>"; ?>
</div>

<?php echo "<div id=\"ruleConditions\"></div>"; ?>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Action with transactions of affiliates, who didn\'t achieve conditions'), $this);?>
</div>
	</div>
	<?php echo "<div id=\"action\"></div>"; ?>
	<?php echo "<div id=\"advancedActionFilterButton\"></div>"; ?>
    <?php echo "<div id=\"actionDataConditionLabel\"></div>"; ?>
    <table>
        <tr>
            <td><?php echo "<div id=\"actionDataField\" class=\"ConditionListBox\"></div>"; ?></td>
            <td><?php echo "<div id=\"actionDataFieldEquation\" class=\"ConditionListBox\"></div>"; ?></td>
            <td><?php echo "<div id=\"actionDataFieldValue\"></div>"; ?></td>
        </tr>
    </table>
</div>
<div class="pad_left pad_top">
	<?php echo "<div id=\"formmessage\"></div>"; ?>
	<?php echo "<div id=\"sendButton\"></div>"; ?>
	<?php echo "<div id=\"cancelButton\"></div>"; ?>
	<?php echo "<div id=\"placementOverviewButton\"></div>"; ?>
</div>