<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:02
         compiled from affiliate_delete.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'affiliate_delete.tpl', 5, false),)), $this); ?>
<!-- affiliate_delete -->
<table border=0 width="100%">
<tr>
  <td colspan="2" valign="top" style="height:30px;color:red;">
  <?php echo smarty_function_localize(array('str' => 'Are you sure you want to delete selected affiliate(s)?'), $this);?>
<br />
  <?php echo smarty_function_localize(array('str' => 'Delete of affiliate causes that all his statistics (clicks, commissions, payouts,...) will be deleted.'), $this);?>

  <?php echo smarty_function_localize(array('str' => 'If you want to keep affiliate\'s stats and details change affiliate status to declined instead of deleting him.'), $this);?>

  </td>
</tr>
<tr>
  <td colspan="2" align="center" valign="top" style="height: 30px;padding-top:5px;">
    <?php echo smarty_function_localize(array('str' => 'What to do with child affiliates'), $this);?>
 
    <?php echo "<div id=\"MoveAffiliatesRadio\"></div>"; ?>
  </td>
</tr><tr>
  <td class="TextAlignRight"><?php echo "<div id=\"OkButton\"></div>"; ?></td>
  <td class="TextAlignLeft"><?php echo "<div id=\"CancelButton\"></div>"; ?></td>
</tr>
</table>