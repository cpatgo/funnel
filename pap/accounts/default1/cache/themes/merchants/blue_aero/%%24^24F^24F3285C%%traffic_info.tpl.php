<?php /* Smarty version 2.6.18, created on 2016-07-06 14:15:21
         compiled from traffic_info.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'traffic_info.tpl', 7, false),)), $this); ?>
<!-- traffic_info -->
<table class="StatsSummaries">
<tbody>
        <tr class="gray">
            <td> </td>
            <td><?php echo "<div id=\"transactionsUsedLabel\"></div>"; ?></td>
            <td><?php echo smarty_function_localize(array('str' => 'Limit'), $this);?>
</td>           
        </tr>
        <tr class="light">
            <td><?php echo smarty_function_localize(array('str' => 'Transactions'), $this);?>
 <?php echo "<div id=\"transactionDates\"></div>"; ?></td>
            <td><?php echo "<div id=\"transactionsUsed\"></div>"; ?></td>
            <td><?php echo "<div id=\"transactionsLimit\"></div>"; ?></td>            
        </tr>        
</tbody>        
</table>