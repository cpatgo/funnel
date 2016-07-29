<?php /* Smarty version 2.6.18, created on 2016-07-06 12:44:21
         compiled from static_page_includes.stpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'static_page_includes.stpl', 7, false),)), $this); ?>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <meta http-Equiv="Cache-Control" Content="no-cache"/>
        <meta http-Equiv="Pragma" Content="no-cache"/>
        <meta http-Equiv="Expires" Content="0"/>
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo ((is_array($_tmp=$this->_tpl_vars['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</title>
        <link type="image/png" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['faviconUrl'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" rel="shortcut icon"/>
        <link type="image/png" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['faviconUrl'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" rel="icon"/>
        <style type="text/css" media="all">
            <?php $_from = $this->_tpl_vars['stylesheets']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['stylesheet']):
?>
            @import "<?php echo $this->_tpl_vars['stylesheet']; ?>
";
            <?php endforeach; endif; unset($_from); ?>
        </style>
        <?php $_from = $this->_tpl_vars['jsResources']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['jsResource']):
?>
            <script src="<?php echo $this->_tpl_vars['jsResource']['resource']; ?>
" type="text/javascript" <?php if ($this->_tpl_vars['jsResource']['id'] != null): ?>id="<?php echo $this->_tpl_vars['jsResource']['id']; ?>
"<?php endif; ?>></script>       
        <?php endforeach; endif; unset($_from); ?>