<?php /* Smarty version 2.6.12, created on 2016-07-18 15:48:13
         compiled from about.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'about.htm', 5, false),)), $this); ?>
<h3 class="m-b"><?php echo $this->_tpl_vars['pageTitle']; ?>
</h3>



    <p><?php echo ((is_array($_tmp="Your current version:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <strong><?php echo $this->_tpl_vars['site']['version']; ?>
</strong><?php if ($this->_tpl_vars['build'] > 1): ?> <?php echo ((is_array($_tmp="Build %s")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['build']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['build']));  endif; ?></p> 
    <p>Latest version available : <?php echo $this->_tpl_vars['latestv']; ?>
</p><br />
    <?php if ($this->_tpl_vars['site']['version'] < $this->_tpl_vars['latestv']): ?>
    <h4 style="color:#109309;">An update is available. Please visit vendor website</h4>
    <?php else: ?>
       <h4 style="color:#109309;">Your software version is up to date.</h4>
    <?php endif; ?>