<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from mail_template_test_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'mail_template_test_form.tpl', 6, false),)), $this); ?>
<!-- mail_template_test_form -->

<?php echo "<div id=\"recipient\"></div>"; ?>
<div class="FormFieldset">
    <div class="FormFieldsetHeader">
        <div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Template Variables'), $this);?>
</div>
        <div class="FormFieldsetHeaderDescription"><?php echo smarty_function_localize(array('str' => 'Template variable values entered below will be used only in your mail template test.'), $this);?>
</div>
    </div>
    <?php echo "<div id=\"fieldsPanel\" class=\"EmailTestFieldsPanel\"></div>"; ?>
</div>
<?php echo "<div id=\"FormMessage\"></div>"; ?>
<?php echo "<div id=\"sendButton\"></div>"; ?>
<?php echo "<div id=\"closeButton\"></div>"; ?>