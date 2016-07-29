<?php /* Smarty version 2.6.18, created on 2016-07-06 14:15:32
         compiled from visitor_affiliates_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'visitor_affiliates_list.tpl', 5, false),)), $this); ?>
<!-- visitor_affiliates_list -->
<div class="FormFieldset">
    <div class="FormFieldsetHeader">
        <div class="FormFieldsetHeaderDescription">
            <?php echo smarty_function_localize(array('str' => 'Link to cookie information in Samples & Tests:'), $this);?>
 <?php echo "<div id=\"cookieInfoLink\"></div>"; ?>
            <br /><?php echo smarty_function_localize(array('str' => 'Show visitor records for non referred clicks:'), $this);?>
 <?php echo "<div id=\"visitorNonReferredClicks\"></div>"; ?>
        </div>
    </div>
  <?php echo "<div id=\"SearchAndFilter\"></div>"; ?>
  <?php echo "<div id=\"VisitorAffiliatesGrid\"></div>"; ?>
</div>