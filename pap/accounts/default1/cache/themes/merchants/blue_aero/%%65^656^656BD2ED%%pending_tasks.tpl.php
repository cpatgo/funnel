<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:58
         compiled from pending_tasks.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'pending_tasks.tpl', 5, false),)), $this); ?>
<!--    pending_tasks   -->

<table width="600" class="StatsSummaries">
  <tr class="light">
    <td class="TextAlignLeft" width="20%" nowrap><?php echo smarty_function_localize(array('str' => 'Affiliates'), $this);?>
</td>
    <td class="TextAlignRight"><?php echo "<div id=\"pendingAffiliates\"></div>"; ?></td>
    <td class="TextAlignLeft" width="20%" nowrap><?php echo smarty_function_localize(array('str' => 'Commissions'), $this);?>
</td>
    <td class="TextAlignRight"><?php echo "<div id=\"pendingCommissions\"></div>"; ?></td>
  </tr>
  <tr class="dark">
    <td class="TextAlignLeft" width="20%" nowrap><?php echo smarty_function_localize(array('str' => 'DirecLink Urls'), $this);?>
</td>
    <td class="TextAlignRight"><?php echo "<div id=\"pendingDirectLinks\"></div>"; ?></td>
    <td class="TextAlignLeft" width="20%" nowrap><?php echo smarty_function_localize(array('str' => 'Unsent emails'), $this);?>
</td>
    <td class="TextAlignRight"><?php echo "<div id=\"unsentEmails\"></div>"; ?></td>
  </tr>  
</table>