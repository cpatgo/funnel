<?php /* Smarty version 2.6.12, created on 2016-07-08 14:09:37
         compiled from inc.searchbar.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'plang', 'inc.searchbar.htm', 3, false),)), $this); ?>
<?php if (! $this->_tpl_vars['is_campaign_new']): ?>
 
		<input type="text" name="search" class="navbar-form pull-right shift"   placeholder='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
' data-target=".nav-primary" id="search_box" onkeypress="<?php echo 'adesk_dom_keypress_doif( event, 13, function() { main_search($(\'search_box\').value) } );'; ?>
" style="width:500px; height:35px;" /> 
	 


<?php endif; ?>