<?php /* Smarty version 2.6.12, created on 2016-07-08 16:53:15
         compiled from side.list.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'side.list.htm', 2, false),)), $this); ?>
 <?php if ($this->_tpl_vars['admin']['pg_list_add']): ?>
  <p><div class="text-center clearfix"><a href="desk.php?action=list#form-0" class="btn btn-info"><i class="fa fa-plus-circle  fa-sm"></i> <?php echo ((is_array($_tmp='Add New List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></div></p>  
   
<?php endif; ?>
<?php if ($this->_tpl_vars['admin']['pg_list_add'] || $this->_tpl_vars['admin']['pg_list_edit'] || $this->_tpl_vars['admin']['pg_list_delete']): ?>
 <p><div class="text-center clearfix"><a href="desk.php?action=list" class="btn btn-info"><i class="fa fa-list-alt fa-sm"></i> <?php echo ((is_array($_tmp='Manage Lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></div></p>
  
 <?php endif; ?>
 

 
<?php if ($this->_tpl_vars['admin']['pg_subscriber_fields'] || $this->_tpl_vars['admin']['pg_subscriber_filters'] || $this->_tpl_vars['admin']['pg_subscriber_actions'] || $this->_tpl_vars['admin']['pg_list_emailaccount']): ?>
 
 <header class="panel-heading bg bg-inverse">
           <?php echo ((is_array($_tmp='List Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </header>
 <div class="list-group list-normal m-b-none">
		<?php if ($this->_tpl_vars['admin']['pg_subscriber_fields']): ?> <a href="desk.php?action=list_field#list-01-0-0" class="list-group-item first"  > <i class="fa fa-columns fa-sm"></i> <?php echo ((is_array($_tmp='Subscriber Fields')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a><?php endif; ?>
		<?php if ($this->_tpl_vars['admin']['pg_subscriber_filters']): ?>  <a href="desk.php?action=filter#list-01-0-0" class="list-group-item"> <i class="fa fa-th fa-sm"></i> <?php echo ((is_array($_tmp='List Segments')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> <?php endif; ?>  
		<?php if ($this->_tpl_vars['admin']['pg_subscriber_actions']): ?>  <a href="desk.php?action=subscriber_action#list-01-0-0" class="list-group-item"> <i class="fa fa-bolt fa-sm"></i> <?php echo ((is_array($_tmp='Subscriber Actions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> <?php endif; ?>
		<?php if ($this->_tpl_vars['admin']['pg_list_emailaccount']): ?>
		<?php if (! $this->_tpl_vars['__ishosted'] || adesk_admin_ismaingroup ( )): ?>  <a href="desk.php?action=emailaccount#list-01-0-0" class="list-group-item"> <i class="fa fa-envelope-o fa-sm"></i> <?php echo ((is_array($_tmp='Subscriptions by Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> <?php endif; ?>
		<?php endif; ?>
 </div> 
<?php endif; ?>

 
<?php if ($this->_tpl_vars['admin']['pg_list_opt'] || $this->_tpl_vars['admin']['pg_list_headers'] || $this->_tpl_vars['admin']['pg_list_bounce']): ?>
  <header class="panel-heading bg bg-inverse">
        <?php echo ((is_array($_tmp='Other List Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </header>
 
 <div class="list-group list-normal m-b-none">
		<?php if ($this->_tpl_vars['admin']['pg_list_opt']): ?>  <a href="desk.php?action=optinoptout#list-01-0-0" class="list-group-item"> <i class="fa fa-file-text  fa-sm"></i> <?php echo ((is_array($_tmp='Email Confirmation Sets')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> <?php endif; ?>
		<?php if ($this->_tpl_vars['admin']['pg_list_headers']): ?>  <a href="desk.php?action=header#list-01-0-0" class="list-group-item"> <i class="fa fa-qrcode fa-sm"></i> <?php echo ((is_array($_tmp='Custom Email Headers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> <?php endif; ?>
		<?php if ($this->_tpl_vars['admin']['pg_list_bounce'] && ! $this->_tpl_vars['__ishosted']): ?>  <a href="desk.php?action=bounce_management#list-01-0-0" class="list-group-item"> <i class="fa fa-level-up fa-sm"></i> <?php echo ((is_array($_tmp='Bounce Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> <?php endif; ?>
 
<?php endif; ?>
 
</div>