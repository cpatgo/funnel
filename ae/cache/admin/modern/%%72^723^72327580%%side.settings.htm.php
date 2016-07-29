<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:34
         compiled from side.settings.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'side.settings.htm', 1, false),)), $this); ?>
<p><div class="text-center clearfix"><a    href="desk.php?action=account" class="btn btn-info"><i class="fa fa-gear"></i> <?php echo ((is_array($_tmp='Your Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></div></p>
 
<?php if ($this->_tpl_vars['admin']['pg_user_add'] || $this->_tpl_vars['admin']['pg_user_edit'] || $this->_tpl_vars['admin']['pg_user_delete']): ?>

<header class="panel-heading bg bg-inverse">
       <?php echo ((is_array($_tmp="Users & Groups")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </header>
  <div class="list-group list-normal m-b-none">
 
	 
<?php if (adesk_admin_ismaingroup ( )): ?>
		<a  class="list-group-item first" href="desk.php?action=settings"> <i class="fa fa-cogs fa-sm"></i> <?php echo ((is_array($_tmp='General Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php endif; ?>

<?php if ($this->_tpl_vars['admin']['pg_user_add'] || $this->_tpl_vars['admin']['pg_user_edit'] || $this->_tpl_vars['admin']['pg_user_delete']): ?>
		<a  class="list-group-item first" href="desk.php?action=user#list-01-0-0"> <i class="fa fa-user fa-sm"></i> <?php echo ((is_array($_tmp='Users')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php endif; ?>
<?php if (adesk_admin_ismaingroup ( )): ?>
		<a  class="list-group-item first" href="desk.php?action=group#list-01-0-0"> <i class="fa fa-users fa-sm"></i> <?php echo ((is_array($_tmp='User Groups')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		<?php if (! adesk_site_hosted_rsid ( )): ?>
		<a  class="list-group-item first" href="desk.php?action=design#list-01-0-0"> <i class="fa fa-edit fa-sm"></i> <?php echo ((is_array($_tmp='Branding')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		<a  class="list-group-item first" href="desk.php?action=service#list-0-0-0"> <i class="fa fa-external-link-square fa-sm"></i> <?php echo ((is_array($_tmp='External Services')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		<?php endif; ?>

<?php if (! $this->_tpl_vars['__ishosted']): ?>
		<a  class="list-group-item first" href="desk.php?action=loginsource#list-01-0-0"> <i class="fa fa-spinner fa-sm"></i> <?php echo ((is_array($_tmp='Login Sources')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php endif; ?>
<?php endif; ?>
 </div>
 
<?php endif; ?>


<?php if (adesk_admin_ismaingroup ( ) && ! $this->_tpl_vars['__ishosted']): ?>
<header class="panel-heading bg bg-inverse">
      <?php echo ((is_array($_tmp='Other Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </header>
  <div class="list-group list-normal m-b-none">

 	<a  class="list-group-item first" href="desk.php?action=settings#settings_mailsending"> <i class="fa fa-random fa-sm"></i> <?php echo ((is_array($_tmp='SMTP Connections')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		<a  class="list-group-item first" href="desk.php?action=database"> <i class="fa fa-expand fa-sm"></i> <?php echo ((is_array($_tmp='Database Utilities')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		<a  class="list-group-item first" href="desk.php?action=cron#list-01-0-0"> <i class="fa fa-tachometer fa-sm"></i> <?php echo ((is_array($_tmp='Scheduled Tasks')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		<a  class="list-group-item first" href="desk.php?action=about"> <i class="fa fa-info fa-lg"></i> <?php echo ((is_array($_tmp='About')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['processesCnt'] || $this->_tpl_vars['pausedProcessesCnt']): ?>
<header class="panel-heading bg bg-inverse">
   <?php echo ((is_array($_tmp='Ongoing Processes')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </header>
  <div class="list-group list-normal m-b-none">
 
 <?php if ($this->_tpl_vars['processesCnt']): ?>
		<a  class="list-group-item first" href="desk.php?action=processes#list-01-0-0"> <i class="fa fa-forward fa-sm"></i> <?php echo ((is_array($_tmp="Current Processes (%s)")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['processesCnt']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['processesCnt'])); ?>
</a><?php endif; ?>
		<?php if ($this->_tpl_vars['pausedProcessesCnt']): ?>
		<a  class="list-group-item first" href="desk.php?action=processes&status=paused#list-01-0-0">  <i class="fa fa-pause fa-sm"></i> <?php echo ((is_array($_tmp="Paused Processes (%s)")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['pausedProcessesCnt']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['pausedProcessesCnt'])); ?>
</a>
		<?php endif; ?>
 </div>
<?php else: ?>
<header class="panel-heading bg bg-inverse">
  <?php echo ((is_array($_tmp='Ongoing Processes')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </header>
  <div class="list-group list-normal m-b-none">
  No Active process Now 
  </div>
  
<?php endif; ?>