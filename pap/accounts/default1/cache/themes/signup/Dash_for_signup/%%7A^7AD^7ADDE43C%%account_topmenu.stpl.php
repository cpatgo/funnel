<?php /* Smarty version 2.6.18, created on 2016-07-06 12:44:21
         compiled from account_topmenu.stpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'account_topmenu.stpl', 3, false),)), $this); ?>
			<!-- account_topmenu  -->
			<ul class="c1_nav">
				<li><a href="<?php echo $this->_tpl_vars['baseUrl']; ?>
/affiliates/"><?php echo smarty_function_localize(array('str' => 'Home'), $this);?>
</a></li>
				<li><a href="<?php echo $this->_tpl_vars['baseUrl']; ?>
/affiliates/signup.php#SignupForm"><?php echo smarty_function_localize(array('str' => 'Sign up'), $this);?>
</a></li>
				<li><a href="<?php echo $this->_tpl_vars['baseUrl']; ?>
/affiliates/signup.php#ContactUs"><?php echo smarty_function_localize(array('str' => 'Contact us'), $this);?>
</a></li>
			</ul>