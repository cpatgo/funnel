<?php /* Smarty version 2.6.12, created on 2016-07-08 16:22:26
         compiled from loginsource.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'loginsource.form.htm', 10, false),array('modifier', 'escape', 'loginsource.form.htm', 22, false),array('modifier', 'help', 'loginsource.form.htm', 85, false),)), $this); ?>
<div id="form" class="adesk_hidden">
  <form method="POST" onsubmit="loginsource_form_save(loginsource_form_id); return false">
    <input type="hidden" name="id" id="form_id" />
    <table border="0" cellspacing="0" cellpadding="5">
      <tr>
		<td>Login <br />Source Id</td>
		<td width="200"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<h4><span id="loginsourceid"></span ></h4></td>
	  </tr>
	  <tr>
		<td><?php echo ((is_array($_tmp='Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><span id="form_name"></span></td>
	  </tr>
	  <tr>
		<td><?php echo ((is_array($_tmp='Enabled')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input type="checkbox" name="enabled" id="form_enabled" value="1" /></td>
	  </tr>
	  <tr valign="top">
		<td><?php echo ((is_array($_tmp='Groups')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td>
		  <select name="groupset[]" id="form_groupset" multiple size="5" onchange="if (typeof loginsource_groups_onchange_extended == 'function') loginsource_groups_onchange_extended(this.value)">
			<?php $_from = $this->_tpl_vars['groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['g']):
?>
			<option value="<?php echo $this->_tpl_vars['g']['id']; ?>
" id="form_groupset_<?php echo $this->_tpl_vars['g']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['g']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
			<?php endforeach; endif; unset($_from); ?>
		  </select>
		</td>
	  </tr>
	  <?php if (isset ( $this->_tpl_vars['loginsource_include_aftergroups'] )): ?>
	  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['loginsource_include_aftergroups'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <?php endif; ?>
	  <tbody id="form_tbody_host" class="adesk_hidden">
		<tr>
		  <td><?php echo ((is_array($_tmp='Host')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><input type="text" name="host" id="form_host" /></td>
		</tr>
	  </tbody>
	  <tbody id="form_tbody_port" class="adesk_hidden">
		<tr>
		  <td><?php echo ((is_array($_tmp='Port')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><input type="text" name="port" id="form_port" /></td>
		</tr>
	  </tbody>
	  <tbody id="form_tbody_user" class="adesk_hidden">
		<tr>
		  <td><?php echo ((is_array($_tmp='User')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><input type="text" name="user" id="form_user" /></td>
		</tr>
	  </tbody>
	  <tbody id="form_tbody_pass" class="adesk_hidden">
		<tr>
		  <td><?php echo ((is_array($_tmp='Password')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><input type="password" name="pass" id="form_pass" /></td>
		</tr>
	  </tbody>
	  <tbody id="form_tbody_dbname" class="adesk_hidden">
		<tr>
		  <td><?php echo ((is_array($_tmp='Database')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><input type="text" name="dbname" id="form_dbname" /></td>
		</tr>
	  </tbody>
	  <tbody id="form_tbody_tableprefix" class="adesk_hidden">
		<tr>
		  <td><?php echo ((is_array($_tmp='Table prefix')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><input type="text" name="tableprefix" id="form_tableprefix" /></td>
		</tr>
	  </tbody>
	  <tbody id="form_tbody_amsproductid" class="adesk_hidden">
		<tr>
		  <td>AMS Product Id</td>
		  <td><input type="text" name="amsproductid" id="form_amsproductid" />
		 
		  </td>
		</tr>
		<tr>
		  <td> </td>
		  <td> 
  <b><font style="color:#FF0000">You can view AMS Product Id in AwebDesk Membership Software by clicking on Manage Products menu and under the column "Product #".</font></b>
		  </td>
		</tr>
	  </tbody>
	  <tbody id="form_tbody_ad" class="adesk_hidden">
		<tr>
		  <td><?php echo ((is_array($_tmp='Base DN')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td>
			<input type="text" name="ad_basedn" id="form_ad_basedn"/>
			<?php echo ((is_array($_tmp="The base of the distinguished name that users would log in with; e.g., ou=Users,dc=domain,dc=com")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

		  </td>
		</tr>
		<tr>
		  <td><?php echo ((is_array($_tmp='Administrator DN')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td>
			<input type="text" name="ad_admin_dn" id="form_ad_admin_dn"/>
			<?php echo ((is_array($_tmp="The full distinguished name of an administrator account, necessary for Active Directory's login process.  Example: cn=Administrator,ou=Users,dc=domain,dc=com")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

		  </td>
		</tr>
		<tr>
		  <td><?php echo ((is_array($_tmp='Administrator Password')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td>
			<input type="text" name="ad_admin_pw" id="form_ad_admin_pw"/>
			<?php echo ((is_array($_tmp="The password for the administrator account, mentioned above.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

		  </td>
		</tr>
	  </tbody>
	  <tbody id="form_tbody_basedn" class="adesk_hidden">
		<tr>
		  <td><?php echo ((is_array($_tmp='Base DN')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td>
			<input type="text" name="basedn" id="form_basedn" />
			<?php echo ((is_array($_tmp="The base of the distinguished name that users would log in with; e.g., ou=Users,dc=domain,dc=com")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

		  </td>
		</tr>
		<tr>
		  <td><?php echo ((is_array($_tmp='Use base DN when logging in')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td>
			<input type="checkbox" name="loginusesdn" id="form_loginusesdn" />
			<?php echo ((is_array($_tmp="With this checked, we will log in with a fully-formed distinguished name (cn=user,ou=Users,dc=domain,dc=com); without it, we'll log in with 'user' and let the server sort things out.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

		  </td>
		</tr>
		<tr>
		  <td><?php echo ((is_array($_tmp='Login attribute')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td>
			<input type="text" name="loginattr" id="form_loginattr" />
			<?php echo ((is_array($_tmp="The attribute that the LDAP server expects us to log in with.  In the vast majority of cases, this field should be 'cn' (short for 'common name').")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

		  </td>
		</tr>
		<tr>
		  <td><?php echo ((is_array($_tmp='Bind DN')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td>
			<input type="text" name="binddn" id="form_binddn" />
			<?php echo ((is_array($_tmp="In some cases, particularly with Active Directory, we must log in with an administrative account before we can truly log in with a user.  In that case, you would place here the full distinguished name of the LDAP server's administrative user (e.g. cn=Administrator,ou=Users,dc=domain,dc=com).")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

		  </td>
		</tr>
		<tr>
		  <td><?php echo ((is_array($_tmp='Bind Password')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td>
			<input type="text" name="bindpw" id="form_bindpw" />
			<?php echo ((is_array($_tmp="The password of the administrative user mentioned above.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

		  </td>
		</tr>
		<tr>
		  <td><?php echo ((is_array($_tmp='User attribute')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td>
			<input type="text" name="userattr" id="form_userattr" />
			<?php echo ((is_array($_tmp="In some cases, particularly Active Directory, the user name you normally log in with is not actually what the server expects through its normal LDAP interface.  This field is the name of the attribute that you want to use to log in with.  In most of those cases, the value which must go here would be 'samAccountName'.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

		  </td>
		</tr>
	  </tbody>
    </table>

    <br />
    <div>
      <input type="button" id="form_submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="loginsource_form_save(loginsource_form_id)" />
      <input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1)" />
    </div>
    <input type="submit" class="adesk_hidden"/>
  </form>
</div>