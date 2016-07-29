<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from direct_link_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'direct_link_form.tpl', 11, false),)), $this); ?>
<!-- direct_link_form -->
<div class="FormFieldset">
  <?php echo "<div id=\"url\"></div>"; ?>
  <?php echo "<div id=\"userid\"></div>"; ?>
  <?php echo "<div id=\"channelid\"></div>"; ?>
  <?php echo "<div id=\"note\"></div>"; ?>
  <?php echo "<div id=\"rstatus\"></div>"; ?>
</div>
<div class="FormFieldset">
  <?php echo "<div id=\"campaignid\"></div>"; ?>
  <?php echo smarty_function_localize(array('str' => 'or'), $this);?>

  <?php echo "<div id=\"bannerid\"></div>"; ?>
</div>
<?php echo "<div id=\"FormMessage\"></div>"; ?>
<?php echo "<div id=\"SaveButton\"></div>"; ?>