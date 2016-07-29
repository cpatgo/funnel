<?php /* Smarty version 2.6.12, created on 2016-07-21 14:15:31
         compiled from inc.headernav.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'inc.headernav.htm', 15, false),)), $this); ?>

 	</header>
  <!-- / header -->
  
   
  <!-- nav -->
  <nav id="nav" class="nav-primary hidden-xs nav-vertical bg-light">
    <ul class="nav" data-spy="affix" data-offset-top="50">
 <?php if (! isset ( $this->_tpl_vars['usemainmenu'] ) || $this->_tpl_vars['usemainmenu'] == 1): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
 
                     <?php if ($this->_tpl_vars['admin']['pg_form_add'] || $this->_tpl_vars['admin']['pg_form_edit'] || $this->_tpl_vars['admin']['pg_form_delete']): ?>
		<?php if ($this->_tpl_vars['style_integration'] != ''): ?>
	 <li> <a href="desk.php?action=form#list-01-0-0"><i class="fa fa-edit fa-lg"></i><span><?php echo ((is_array($_tmp='Forms')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a> </li>
		<?php else: ?>
			 <li> <a href="desk.php?action=form#list-01-0-0"><i class="fa fa-edit fa-lg"></i><span><?php echo ((is_array($_tmp='Forms')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a> </li>
		<?php endif; ?>
	<?php endif; ?>

        
      <li class="dropdown-submenu">
        <a href="#"><i class="fa fa-gears fa-lg"></i><span><?php echo ((is_array($_tmp='Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a>
        <ul class="dropdown-menu">
         
	 <?php if (adesk_admin_ismaingroup ( )): ?>   <li> <a href="desk.php?action=design#list-01-0-0"><i class="fa fa-edit fa-sm"></i>&nbsp;<?php echo ((is_array($_tmp='Branding')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
 	<?php endif; ?>
	<?php if ($this->_tpl_vars['admin']['pg_user_add'] || $this->_tpl_vars['admin']['pg_user_edit'] || $this->_tpl_vars['admin']['pg_user_delete']): ?>
	  <li><a href="desk.php?action=user#list-01-0-0"><i class="fa fa-users fa-sm"></i>&nbsp;<?php echo ((is_array($_tmp='Users')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> </li>
<?php endif; ?>
<?php if (adesk_admin_ismaingroup ( ) && ! $this->_tpl_vars['__ishosted']): ?>
 
 <li> <a href="desk.php?action=cron#list-01-0-0"><i class="fa fa-cog fa-sm"></i>&nbsp; <?php echo ((is_array($_tmp='Cron Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> </li>
 
<?php endif; ?>
   <?php if (adesk_admin_ismain ( )): ?>
	    
	  <li>  <a href="desk.php?action=settings#settings_mailsending" title="<?php echo ((is_array($_tmp='Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
"><i class="fa fa-cloud-upload fa-sm"></i>&nbsp;<?php echo ((is_array($_tmp='SMTP Connections')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
	   <?php endif; ?>
	  <?php if (adesk_admin_ismain ( )): ?>
	  

					 <li><a href="desk.php?action=settings" title="<?php echo ((is_array($_tmp='Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
"><i class="fa fa-gears fa-sm"></i>&nbsp;<?php echo ((is_array($_tmp='Global Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
  <li><a href="desk.php?action=account" title="<?php echo ((is_array($_tmp='Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
"><i class="fa fa-user fa-sm"></i> &nbsp;<?php echo ((is_array($_tmp='Account Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
    <?php else: ?>
					 <li><a href="desk.php?action=account" title="<?php echo ((is_array($_tmp='Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
"><i class="fa fa-user fa-sm"></i> &nbsp;<?php echo ((is_array($_tmp='Account Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
    <?php endif; ?>
	
        </ul>
      </li>
       <!-- <li ><a class="alain" href=
      "index.php?action=logout"><i class="fa fa-power-off fa-lg"></i><span><?php echo ((is_array($_tmp='Logout')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a></li> -->
      <li ><a href=
      "/glc/logout.php"><i class="fa fa-power-off fa-lg"></i><span><?php echo ((is_array($_tmp='Logout')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a></li>
      
    </ul>
  
  <!-- / nav -->
  