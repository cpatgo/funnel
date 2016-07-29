<?php /* Smarty version 2.6.18, created on 2016-07-06 14:11:53
         compiled from account_header.stpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'account_header.stpl', 12, false),)), $this); ?>
<!-- account_header -->
<div class="c1_TopBar">
	<div class="c1_TopBarContainer">
		<a href="<?php echo $this->_tpl_vars['baseUrl']; ?>
/affiliates/login.php#login">Affiliate login</a><span>|</span>
        <a href="<?php echo $this->_tpl_vars['baseUrl']; ?>
/merchants/login.php#login">Merchant login</a>
	</div>
</div>
<div class="c1_Header">
	<div class="c1_HeaderContainer">
		<div class="c1_HeaderInfo">
			<strong>
				<a class="c1_Logo" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['programName'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" href="<?php echo $this->_tpl_vars['baseUrl']; ?>
/">
					<img src="<?php echo ((is_array($_tmp=$this->_tpl_vars['programLogo'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="LogoImage" />
				</a>
			</strong>
		</div>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'account_topmenu.stpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><div class="clear"></div>
	</div>
</div>