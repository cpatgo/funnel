<?php /* Smarty version 2.6.18, created on 2016-06-29 14:48:57
         compiled from ../include/Gpf/Lang/Storage/cachefile.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '../include/Gpf/Lang/Storage/cachefile.tpl', 3, false),)), $this); ?>
<?php echo '<?php'; ?>

$l = new Gpf_Lang_Language('<?php echo ((is_array($_tmp=$this->_tpl_vars['l']->getCode())) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
');
$l->setName('<?php echo ((is_array($_tmp=$this->_tpl_vars['l']->getName())) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
');
$l->setEnglishName('<?php echo ((is_array($_tmp=$this->_tpl_vars['l']->getEnglishName())) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
');
$l->setAuthor('<?php echo ((is_array($_tmp=$this->_tpl_vars['l']->getAuthor())) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
');
$l->setVersion('<?php echo ((is_array($_tmp=$this->_tpl_vars['l']->getVersion())) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
');
$l->setDateFormat('<?php echo ((is_array($_tmp=$this->_tpl_vars['l']->getDateFormat())) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
');
$l->setTimeFormat('<?php echo ((is_array($_tmp=$this->_tpl_vars['l']->getTimeFormat())) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
');
$l->setThousandsSeparator('<?php echo ((is_array($_tmp=$this->_tpl_vars['l']->getThousandsSeparator())) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
');
$l->setDecimalSeparator('<?php echo ((is_array($_tmp=$this->_tpl_vars['l']->getDecimalSeparator())) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
');
$l->setTranslationPercentage('<?php echo ((is_array($_tmp=$this->_tpl_vars['l']->getTranslationPercentage())) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
');
$l->setTextDirection('<?php echo ((is_array($_tmp=$this->_tpl_vars['l']->getTextDirection())) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
');
$l->setDictionary(array(
<?php $_from = $this->_tpl_vars['l']->getDictionary(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
'<?php echo ((is_array($_tmp=$this->_tpl_vars['k'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
'=>'<?php echo ((is_array($_tmp=$this->_tpl_vars['v'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
',
<?php endforeach; endif; unset($_from); ?>
));
return $l;