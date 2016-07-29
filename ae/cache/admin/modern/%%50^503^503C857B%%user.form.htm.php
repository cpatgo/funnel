<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:34
         compiled from user.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'adesk_js', 'user.form.htm', 2, false),array('modifier', 'default', 'user.form.htm', 7, false),array('modifier', 'alang', 'user.form.htm', 10, false),array('modifier', 'escape', 'user.form.htm', 29, false),)), $this); ?>
<?php if (isset ( $this->_tpl_vars['user_js_file'] )): ?>
<?php echo smarty_function_adesk_js(array('base' => ".",'src' => $this->_tpl_vars['user_js_file']), $this);?>

<?php endif; ?>

<div id="form" class="adesk_hidden">
  <form method="GET" onsubmit="user_form_save(user_form_id); return false">
	<input type="hidden" name="id" id="form_id" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['user']['id'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
">
	<table border="0" cellspacing="0" cellpadding="5">
	  <tr>
		<td><?php echo ((is_array($_tmp='Username')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input type="text" id="form_username" name="username" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['user']['username'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" autocomplete="off" style="width: 200px"></td>
	  </tr>
	  <tr>
		<td><?php echo ((is_array($_tmp='Password')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input type="password" id="form_password" name="password" autocomplete="off" style="width: 200px"></td>
	  </tr>
	  <tr>
		<td><?php echo ((is_array($_tmp='Repeat Password')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input type="password" id="form_password_r" name="password_r" autocomplete="off" style="width: 200px"></td>
	  </tr>
	  <?php if ($this->_tpl_vars['adesk_admin_ismaingroup']): ?>
	  <tbody id="user_groupbody">
		<tr valign="top" id="group_tr">
		  <td><?php echo ((is_array($_tmp='Group')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td>
			<select id="form_group" name="group"   style="width: 250px"  >
			  <?php $_from = $this->_tpl_vars['groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['group'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['group']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['g']):
        $this->_foreach['group']['iteration']++;
?>
			                 <?php if ($this->_tpl_vars['g']['id'] != 3): ?>
			  <option value="<?php echo $this->_tpl_vars['g']['id']; ?>
" <?php if (($this->_foreach['group']['iteration']-1) == 4): ?>selected<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['g']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
              <?php endif; ?>
			  <?php endforeach; endif; unset($_from); ?>
			</select>
			<a href='javascript:void(0)' onclick='user_group_defaults(); adesk_dom_toggle_display("group", "block")'><?php echo ((is_array($_tmp='Add a new group')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			 
			 
		  </td>
		</tr>
	  </tbody>
      <?php else: ?>
      <input type="hidden" value="3" name="group" />
      
      
	  <?php endif; ?>
	  <tr>
		<td><?php echo ((is_array($_tmp='Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input type="text" id="form_email" name="email" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['user']['email'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" style="width: 200px"></td>
	  </tr>
	  <tr>
		<td><?php echo ((is_array($_tmp='First Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input type="text" id="form_first_name" name="first_name" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['user']['first_name'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" style="width: 200px"></td>
	  </tr>
	  <tr>
		<td><?php echo ((is_array($_tmp='Last Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input type="text" id="form_last_name" name="last_name" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['user']['last_name'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" style="width: 200px"></td>
	  </tr>
  <tr>
		<td>New Login Source ID<br />
        Only for ESP customers. Change accordingly.<br />
        Set  this to 0 as default local AEM DB source<br />
        Hint: To get the login source, click on Login Sources tab above,<br />
         and edit any login source to find its id.
         
        
        </td>
		<td><input type="text" id="form_sourceid" name="sourceid" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['user']['sourceid'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
" style="width: 200px"></td>
	  </tr>


	  <?php if (isset ( $this->_tpl_vars['user_include_file'] )): ?>
	  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['user_include_file'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <?php endif; ?>
	</table>

	<br>
	<div>
	  <?php if (! $this->_tpl_vars['demoMode']): ?>
	  <input type="button" id="user_form_submit" value='<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="user_form_save(user_form_id)">
	  <?php else: ?>
	  <span class="demoDisabled2"><?php echo ((is_array($_tmp='Disabled in demo')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
	  <?php endif; ?>
	  <input type="button" class="adesk_button_back" value='<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="window.history.go(-1)">
	</div>
	<input type="submit" style="display:none"/> <!-- so submit-on-enter works -->
  </form>
</div>