<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from language_translations_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'language_translations_filter.tpl', 4, false),)), $this); ?>
<!-- language_translations_filter -->
<div class="LanguageTranslationsFilter">
    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Source'), $this);?>
</div>
            <?php echo "<div id=\"source\"></div>"; ?>
        <div class="clear"></div>
    </div>
    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Translation'), $this);?>
</div>
            <?php echo "<div id=\"translation\"></div>"; ?>
        <div class="clear"></div>
    </div>
    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Status'), $this);?>
</div>
            <?php echo "<div id=\"status\"></div>"; ?>
        <div class="clear"></div>
    </div>

    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Type'), $this);?>
</div>
            <?php echo "<div id=\"type\"></div>"; ?>
        <div class="clear"></div>
    </div>

    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Module'), $this);?>
</div>
            <?php echo "<div id=\"module\"></div>"; ?>
        <div class="clear"></div>
    </div>

    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Is custom'), $this);?>
</div>
            <?php echo "<div id=\"customer\"></div>"; ?>
        <div class="clear"></div>
    </div>
</div>