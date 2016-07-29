<?php /* Smarty version 2.6.12, created on 2016-07-08 13:59:26
         compiled from instup.checks.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'instup.checks.htm', 2, false),array('modifier', 'help', 'instup.checks.htm', 22, false),array('modifier', 'replace', 'instup.checks.htm', 28, false),)), $this); ?>
<div id="checks" class="<?php if (( ! $this->_tpl_vars['installer'] && $this->_tpl_vars['step'] == 4 ) || ( $this->_tpl_vars['installer'] && $this->_tpl_vars['step'] == 1 )): ?>adesk_block<?php else: ?>adesk_hidden<?php endif; ?>">
	<h3><?php echo ((is_array($_tmp='System Check')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

	<form action="<?php if ($this->_tpl_vars['installer']): ?>install<?php else: ?>updater<?php endif; ?>.php" method="post" name="reg" id="checksForm" onsubmit="<?php if ($this->_tpl_vars['installer']): ?>install<?php else: ?>updater<?php endif; ?>_next(); return false;">
		<table border="0" cellspacing="5">
			<tr>
				<th><?php echo ((is_array($_tmp='Option')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</th>
				<th><?php echo ((is_array($_tmp='Setting')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</th>
				<th><?php echo ((is_array($_tmp='Requirement')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</th>
			</tr>
<?php if ($this->_tpl_vars['phpProb']): ?>
			<tr>
				<td><?php echo ((is_array($_tmp='PHP Version')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td><?php echo $this->_tpl_vars['systeminfo']['phpversion']; ?>
</td>
				<td><?php echo $this->_tpl_vars['requirements']['php']; ?>
</td>
			</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['sessionProb']): ?>
			<tr>
				<td><?php echo ((is_array($_tmp='Sessions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td><?php echo ((is_array($_tmp='Do not work')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td><?php echo ((is_array($_tmp="Sessions need to be enabled in order for this application to work.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
</td>
			</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['disabledFunctions'] != ''): ?>
			<tr>
				<td valign="top"><?php echo ((is_array($_tmp='Disabled Functions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td><?php echo ((is_array($_tmp=$this->_tpl_vars['disabledFunctions'])) ? $this->_run_mod_handler('replace', true, $_tmp, ',', '<br />') : smarty_modifier_replace($_tmp, ',', '<br />')); ?>
</td>
				<td valign="top"><?php echo ((is_array($_tmp="Please check if any vital function is listed here.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
</td>
			</tr>
<?php endif; ?>
<?php if (! $this->_tpl_vars['uploadAllowed']): ?>
			<tr>
				<td><?php echo ((is_array($_tmp='File Uploads')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td><?php echo ((is_array($_tmp='Disabled')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td><?php echo ((is_array($_tmp="Attachments will be disabled.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
</td>
			</tr>
<?php endif; ?>
<?php if (! $this->_tpl_vars['gdLib']): ?>
			<tr>
				<td><?php echo ((is_array($_tmp='GD Library')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td><?php echo ((is_array($_tmp='Not Found')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td><?php echo ((is_array($_tmp="GD Library is needed to generate images from the application. Most common (and useful) use are CAPTCHA images.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
</td>
			</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['safeMode']): ?>
			<tr>
				<td><?php echo ((is_array($_tmp='Safe Mode')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td><?php echo ((is_array($_tmp='ON')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td>
					<?php echo ((is_array($_tmp="Recommended: OFF")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					<?php echo ((is_array($_tmp="With safe mode on, the script will not be able to adjust the server configuration for optimal performance.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

				</td>
			</tr>
<?php if ($this->_tpl_vars['execProb']): ?>
			<tr>
				<td><?php echo ((is_array($_tmp='Maximum Execution Time')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td><?php echo $this->_tpl_vars['executionLimit']; ?>
</td>
				<td>
					<?php echo ((is_array($_tmp="Needed: at least 30")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					<?php echo ((is_array($_tmp="Some scripts might not finish the execution within the allowed timeframe, which is set to a value lower than a default PHP value (30 seconds).")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

				</td>
			</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['memProb']): ?>
			<tr>
				<td><?php echo ((is_array($_tmp='Memory Limit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td><?php echo $this->_tpl_vars['memoryLimit']; ?>
</td>
				<td>
					<?php echo ((is_array($_tmp="Needed: at least 64MB")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					<?php echo ((is_array($_tmp="Your server is set to allow less memory than this script requires.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

				</td>
			</tr>
<?php endif; ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['postProb']): ?>
			<tr>
				<td><?php echo ((is_array($_tmp='Maximum POST Size')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td><?php echo $this->_tpl_vars['postLimit']; ?>
</td>
				<td><?php echo ((is_array($_tmp="This value is too low, and this server imposed limit cannot be changed.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
</td>
			</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['uploadProb']): ?>
			<tr>
				<td><?php echo ((is_array($_tmp='Maximum Upload File Size')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td><?php echo $this->_tpl_vars['uploadLimit']; ?>
</td>
				<td><?php echo ((is_array($_tmp="This value is too low, and this server imposed limit cannot be changed.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
</td>
			</tr>
<?php endif; ?>
		</table>

<?php if ($this->_tpl_vars['requirementsMet']): ?>
		<hr />

		<div>
			<input type="button" value="<?php echo ((is_array($_tmp='Next &gt;')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" class="adesk_button_next" id="checksNext" onclick="<?php if ($this->_tpl_vars['installer']): ?>install<?php else: ?>updater<?php endif; ?>_next();" />
		</div>
<?php else: ?>
		<div class="adesk_error_fatal">
<?php if ($this->_tpl_vars['phpProb']): ?>
			<div><?php echo ((is_array($_tmp="Your PHP version does not meet the minimum requirements.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
<?php endif; ?>
<?php if ($this->_tpl_vars['sessionProb']): ?>
			<div><?php echo ((is_array($_tmp="Your server does not appear to be handling sessions properly.Try by refreshing this page two times.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
<?php endif; ?>
<?php if ($this->_tpl_vars['execProb']): ?>
			<div><?php echo ((is_array($_tmp="Your server does not appear to be handling sessions properly.Try by refreshing this page two times.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
<?php endif; ?>
		</div>
<?php endif; ?>
	    <input type="submit" style="display:none" />
	</form>
</div>