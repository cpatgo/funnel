<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:47
         compiled from news_content.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'news_content.tpl', 4, false),)), $this); ?>
<!-- news_content -->

<div class="NewsBlock">
    <div class="StatsSectionTitle"><?php echo smarty_function_localize(array('str' => 'NEWS'), $this);?>
</div>
    <div class="NewsWrapper">
        <?php echo "<div id=\"news\"></div>"; ?>
    </div>
</div>