<?php /* Smarty version 2.6.12, created on 2016-07-08 13:59:26
         compiled from install.step2.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'install.step2.htm', 6, false),array('modifier', 'adesk_isselected', 'install.step2.htm', 99, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "instup.checks.htm", 'smarty_include_vars' => array('installer' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>



<div id="engine" class="<?php if ($this->_tpl_vars['step'] == 2): ?>adesk_block<?php else: ?>adesk_hidden<?php endif; ?>">
	<h3><?php echo ((is_array($_tmp='Database Information')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

	<div><?php echo ((is_array($_tmp="The MYSQL information requested may typically be requested from your web host or system administrator.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div><br />


	<form action="install.php" method="post" name="reg" id="engineForm" onsubmit="install_next();return false;">

		<div><?php echo ((is_array($_tmp="Database Host **")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		<div><input name="host" type="text" id="engineHost" value="localhost" size="60" tabindex="1" /></div>

		<div><?php echo ((is_array($_tmp='Database Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		<div>
			<input name="name" type="text" id="engineName" size="60" tabindex="2" />
			<label>
				<input name="create" type="checkbox" id="engineCreate" value="1" tabindex="6" />
				<?php echo ((is_array($_tmp="*Create")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</label>
		</div>

		<div><?php echo ((is_array($_tmp='Database Username')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		<div><input name="user" type="text" id="engineUser" size="60" tabindex="3" /></div>

		<div><?php echo ((is_array($_tmp='Database Password')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		<div><input name="pass" type="password" id="enginePass" size="60" tabindex="4" /></div>
		<br />
		<div>
			<input type="button" value="<?php echo ((is_array($_tmp='Next &gt;')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" tabindex="5" class="adesk_button_next" id="engineNext" onclick="install_next();" />
		</div>
		<br />
	    <input type="submit" style="display:none" />
	</form>
	<hr />

	<div><?php echo ((is_array($_tmp="* If checked the installer will attempt to create the database for you. Should not be checked if the database already exists.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
	<div><?php echo ((is_array($_tmp="** This can usually be set to &quot;localhost&quot; by default.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
</div>


<div id="auth" class="<?php if ($this->_tpl_vars['step'] == 3): ?>adesk_block<?php else: ?>adesk_hidden<?php endif; ?>">
	<h3>License Agreement</h3>


	 

	
	<form action="install.php" method="post" name="reg" id="authForm" onsubmit="install_next(); return false;">
	 
		<fieldset>
			 
			<div>
				<label>
				<input type="hidden" name="authtype" id="authtyperemote" value="local" onchange="$('remoteauthinfo').className = ('adesk_block' );" />
					<input type="radio" name="authtype" value="local"    />
						You agree to our terms and conditions and license agreement(find in /docs) by installing this product
				</label>
			</div>

	

		</fieldset>
<br />

		<div>
			<input type="button" value="<?php echo ((is_array($_tmp='Next &gt;')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" class="adesk_button_next" id="authNext" onclick="install_next();" />
		</div>

	    <input type="submit" style="display:none" />
	</form>
</div>


<div id="settings" class="<?php if ($this->_tpl_vars['step'] == 4): ?>adesk_block<?php else: ?>adesk_hidden<?php endif; ?>">
	<h3><?php echo ((is_array($_tmp='Software Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

	<form action="install.php" method="post" name="reg" id="siteForm" onsubmit="install_next(); return false;">

		<div><?php echo ((is_array($_tmp="Specify the main URL of your %s software")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['appname']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['appname'])); ?>
</div>
		<div><input type="text" name="murl" id="murl" value="<?php echo $this->_tpl_vars['siteurl']; ?>
" size="60" tabindex="1" /></div>
		<div><?php echo ((is_array($_tmp="Do not include a trailing slash. Example = &quot;http://www.mysite.com/somepath&quot;")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		<br />

		<div><?php echo ((is_array($_tmp='Site Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		<div><input name="site_name" type="text" id="site_name" value="<?php echo $this->_tpl_vars['sitename']; ?>
" size="60" tabindex="2" /></div>
<br />

		<div><?php echo ((is_array($_tmp='From Email Address')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		<div><input name="emfrom" type="text" id="emfrom" value="<?php echo $this->_tpl_vars['appid']; ?>
@<?php echo $this->_tpl_vars['d_h']; ?>
" size="60" tabindex="3" /></div>
<br />

		<div><?php echo ((is_array($_tmp='Default Time Zone')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		<div>
			<select name="zoneid" size="1" id="zoneid" tabindex="4">
<?php $_from = $this->_tpl_vars['timezones']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['z']):
?>
				<option value="<?php echo $this->_tpl_vars['z']['zoneid']; ?>
" <?php echo ((is_array($_tmp=$this->_tpl_vars['z']['zoneid'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 'America/Chicago') : smarty_modifier_adesk_isselected($_tmp, 'America/Chicago')); ?>
><?php echo $this->_tpl_vars['z']['zoneid']; ?>
 (GMT <?php echo $this->_tpl_vars['z']['offset_format']; ?>
)</option>
<?php endforeach; endif; unset($_from); ?>
			</select>
		</div>

		<div id="newinstalladmin" class="<?php if ($this->_tpl_vars['showAdminBox']): ?>adesk_block<?php else: ?>adesk_hidden<?php endif; ?>">
		<br />

			<div><?php echo ((is_array($_tmp='Your First Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
			<div><input name="firstname" type="text" id="firstname" size="60" tabindex="5" /></div>
<br />

			<div><?php echo ((is_array($_tmp='Your Last Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
			<div><input name="lastname" type="text" id="lastname" size="60" tabindex="6" /></div>
<br />

			<div><?php echo ((is_array($_tmp="Your E-mail Address")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
			<div><input name="email" type="text" id="email" size="60" tabindex="7" /></div>
		</div>

		<div id="oldinstalladmin" class="<?php if (! $this->_tpl_vars['showAdminBox']): ?>adesk_block<?php else: ?>adesk_hidden<?php endif; ?>">
			<br /><?php echo ((is_array($_tmp="Enter your current admin password in order to continue the installation.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		</div>
<br />

		<div><?php echo ((is_array($_tmp='Your Admin Username')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		<div><input name="username" type="text" id="adminusername" size="60" value="admin" tabindex="8" readonly disabled="disabled" /></div>
<br />

		<div><?php echo ((is_array($_tmp='Your Admin Password')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		<div><input name="password" type="password" id="adminpassword" size="60" tabindex="9" /></div>

<br />

		<div>
			<input type="button" value="<?php echo ((is_array($_tmp='Next &gt;')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" class="adesk_button_next" id="settingsNext" tabindex="10" onclick="install_next();" />
		</div>

	    <input type="submit" style="display:none" />
	</form>
</div>

<div id="installer" class="adesk_hidden">
	<h3><?php echo ((is_array($_tmp="Installing %s")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['appname']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['appname'])); ?>
</h3>
	<iframe id="installeriframe" frameborder="0" marginheight="0" marginwidth="0" src="about:blank" width="100%" height="400"></iframe>
<?php if ($this->_tpl_vars['subapps']): ?>
	<iframe id="subinstalleriframe" frameborder="0" marginheight="0" marginwidth="0" src="about:blank" width="100%" height="400" style="display:none;"></iframe>
<?php endif; ?>
</div>