<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:47
         compiled from news_form_edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'news_form_edit.tpl', 5, false),)), $this); ?>
<!-- news_form_edit -->

<div class="NewsEditPanel">
    <fieldset>
        <legend><?php echo smarty_function_localize(array('str' => 'News'), $this);?>
</legend>
        <?php echo "<div id=\"rtype\" class=\"StatusListBox\"></div>"; ?>
        <?php echo "<div id=\"dateinserted\"></div>"; ?>
        <?php echo "<div id=\"title\"></div>"; ?>
        <?php echo "<div id=\"content\"></div>"; ?>
    </fieldset>
</div>