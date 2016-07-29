<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:34
         compiled from user-settings.header.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'user-settings.header.inc.htm', 3, false),)), $this); ?>
<?php if (! isset ( $this->_tpl_vars['userpage'] )):  $this->assign('userpage', 'user');  endif; ?>
<div id="userheader" style="margin-bottom:10px;">
	<h3 class="m-b"><?php echo ((is_array($_tmp='Users')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
	<ul class="navlist">
		<li class="<?php if ($this->_tpl_vars['userpage'] == 'user'): ?>currenttab<?php else: ?>othertab<?php endif; ?>"><a href="desk.php?action=user"><?php echo ((is_array($_tmp='Users')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
		<li class="<?php if ($this->_tpl_vars['userpage'] == 'group'): ?>currenttab<?php else: ?>othertab<?php endif; ?>"><a href="desk.php?action=group"><?php echo ((is_array($_tmp='Admin Groups')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
		<?php if (adesk_admin_ismaingroup ( )): ?>
			<?php if (! $this->_tpl_vars['__ishosted']): ?>
				<li class="<?php if ($this->_tpl_vars['userpage'] == 'loginsource'): ?>currenttab<?php else: ?>othertab<?php endif; ?>"><a href="desk.php?action=loginsource"><?php echo ((is_array($_tmp='Login Sources')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
			<?php endif; ?>
		<?php endif; ?>
	</ul>
</div>