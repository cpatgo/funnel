<?php /* Smarty version 2.6.12, created on 2016-07-08 14:15:46
         compiled from side.subscriber.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'side.subscriber.htm', 3, false),)), $this); ?>
 
<?php if ($this->_tpl_vars['canImportSubscriber']): ?>
<p><div class="text-center clearfix"><a    href="desk.php?action=subscriber_import" class="btn btn-info"><i class="fa fa-upload fa-sm"></i>  <?php echo ((is_array($_tmp='Import Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></div></p>
<?php endif; ?>

<?php if ($this->_tpl_vars['canAddSubscriber']): ?>
<p><div class="text-center clearfix"><a   href="desk.php?action=subscriber#form-0" class="btn btn-info"><i class="fa fa-plus-circle fa-sm"></i>  <?php echo ((is_array($_tmp='Add Subscriber')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></div></p>
<?php endif; ?>
 



<header class="panel-heading bg bg-inverse">
       <?php echo ((is_array($_tmp='Other options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </header>
  <div class="list-group list-normal m-b-none">
 

		<a  class="list-group-item first" href="desk.php?action=subscriber#list-01-0-0"><i class="fa fa-eye fa-sm"></i>
 <?php echo ((is_array($_tmp='View Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 </a></li>
		 <a  class="list-group-item " href="desk.php?action=subscriber#search"><i class="fa fa-search fa-sm"></i> <?php echo ((is_array($_tmp='Search Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> 
<?php if ($this->_tpl_vars['admin']['pg_subscriber_export']): ?>
	 <a  class="list-group-item  " href="desk.php?action=subscriber#export"><i class="fa fa-arrow-circle-o-down fa-sm"></i> <?php echo ((is_array($_tmp='Export Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> 
<?php endif; ?>

 </ul>


<?php if ($this->_tpl_vars['admin']['pg_list_edit'] || $this->_tpl_vars['admin']['pg_subscriber_delete'] || $this->_tpl_vars['admin']['pg_subscriber_sync']): ?>


<header class="panel-heading bg bg-inverse">
       <?php echo ((is_array($_tmp='Advanced options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </header>
  <div class="list-group list-normal m-b-none">
 

<?php if ($this->_tpl_vars['admin']['pg_list_edit']): ?>
		 <a  class="list-group-item first" href="desk.php?action=exclusion#list-01-0-0"><i class="fa fa-times-circle fa-sm"></i> <?php echo ((is_array($_tmp='Excluded Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> 
<?php endif; ?>

<?php if ($this->_tpl_vars['admin']['pg_subscriber_delete']): ?>
		 <a  class="list-group-item first" href="desk.php?action=batch"><i class="fa fa-arrows-alt fa-sm"></i> <?php echo ((is_array($_tmp='Batch Actions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
 <?php endif; ?>
<?php if ($this->_tpl_vars['admin']['pg_subscriber_sync']): ?>
		 <a  class="list-group-item first" href="desk.php?action=sync#list-01"><i class="fa fa-bitbucket-square fa-sm"></i> <?php echo ((is_array($_tmp='Database Sync')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
 <?php endif; ?>

 </div>
<?php endif; ?>
 
 