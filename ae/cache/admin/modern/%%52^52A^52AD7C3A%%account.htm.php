<?php /* Smarty version 2.6.12, created on 2016-07-08 16:50:20
         compiled from account.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'account.htm', 1, false),array('modifier', 'js', 'account.htm', 6, false),array('modifier', 'adesk_isselected', 'account.htm', 64, false),array('function', 'html_options', 'account.htm', 85, false),)), $this); ?>
<h3 class="m-b"><?php echo ((is_array($_tmp='Your Account')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span style="float:right; font-size:12px; color:#999">*<?php echo ((is_array($_tmp="Try refreshing this page, if some texts are scrambled")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></h3>

<?php if ($this->_tpl_vars['formSubmitted']): ?>
<?php if (isset ( $this->_tpl_vars['submitResult']['status'] ) && ! $this->_tpl_vars['submitResult']['status'] && isset ( $this->_tpl_vars['submitResult']['message'] )): ?>
<script>
adesk_error_show('<?php echo ((is_array($_tmp=$this->_tpl_vars['submitResult']['message'])) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
');
</script>
<?php else: ?>
<script>
adesk_result_show('<?php echo ((is_array($_tmp=((is_array($_tmp='Changes Saved')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
');
</script>
<?php endif; ?>
<?php endif; ?>

<form name="form1" method="post" action="<?php echo $this->_tpl_vars['thisURL']; ?>
" enctype="multipart/form-data">

<div class="h2_wrap">
<div id="accountInfo" class="h2_content">
  <table border="0" cellspacing="0" cellpadding="4">
    <tr valign="top">
      <td width="200"><?php echo ((is_array($_tmp='Username')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 </td>
      <td><input name="user00000" type="text" id="user00000" value="<?php echo $this->_tpl_vars['admin']['username']; ?>
"  readonly  style="width:200px; background:#EEECE8" />
      </td>
    </tr>
    <tr valign="top">
      <td><?php echo ((is_array($_tmp="E-mail")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 </td>
      <td><input name="email" type="text" id="email" value="<?php echo $this->_tpl_vars['admin']['email']; ?>
" style="width:200px;" /></td>
    </tr>
    <tr valign="top">
      <td><?php echo ((is_array($_tmp='First Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 </td>
      <td><input name="first_name" type="text" id="first_name" value="<?php echo $this->_tpl_vars['admin']['first_name']; ?>
" style="width:200px;" />
      </td>
    </tr>
    <tr valign="top">
      <td><?php echo ((is_array($_tmp='Last Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 </td>
      <td><input name="last_name" type="text" id="last_name" value="<?php echo $this->_tpl_vars['admin']['last_name']; ?>
" style="width:200px;" /></td>
    </tr>
    <tr valign="top">
      <td><?php echo ((is_array($_tmp='Password')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <br />
      </td>
      <td><input name="pass" type="password" id="pass" autocomplete="off" style="width:200px;" />
        <br />
        <font size="1">(<?php echo ((is_array($_tmp='Leave blank if you do not want to change')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
) </font></td>
    </tr>
    <tr valign="top">
      <td><?php echo ((is_array($_tmp='Repeat Password')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <br />
      </td>
      <td><input name="pass_r" type="password" id="pass_r" autocomplete="off" style="width:200px;" /></td>
    </tr>
</table>
</div>
</div>

<div class="h2_wrap">
<h4 onclick="adesk_dom_toggle_class('accountSettings', 'h2_content', 'h2_content_invis');"><?php echo ((is_array($_tmp='Your Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h4>
<div id="accountSettings" class="h2_content_invis">
  <table border="0" cellspacing="0" cellpadding="4">
<?php if (isset ( $this->_tpl_vars['admin']['local_zoneid'] )): ?>
	<tr>
	  <td width="200"><?php echo ((is_array($_tmp='Default Time Zone')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
	  <td>
		<select name="local_zoneid">
		  <?php $_from = $this->_tpl_vars['zones']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['z']):
?>
		  <option value="<?php echo $this->_tpl_vars['z']['zoneid']; ?>
" <?php echo ((is_array($_tmp=$this->_tpl_vars['z']['zoneid'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, $this->_tpl_vars['admin']['local_zoneid']) : smarty_modifier_adesk_isselected($_tmp, $this->_tpl_vars['admin']['local_zoneid'])); ?>
><?php echo $this->_tpl_vars['z']['zoneid']; ?>
 (GMT <?php echo $this->_tpl_vars['z']['offset_format']; ?>
)</option>
		  <?php endforeach; endif; unset($_from); ?>
		</select>
	  </td>
	</tr>
<?php else: ?>
    <tr valign="top">
      <td><?php echo ((is_array($_tmp="Time Offset (Hours)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
      <td>
        <div style="float: right;"><font size="1"><?php echo $this->_tpl_vars['curDateTime']; ?>
</font></div>
        <select name="t_offset_o" id="t_offset_o" style="width:35px;">
          <option value="+" <?php if ($this->_tpl_vars['admin']['t_offset_o'] == '+'): ?>selected<?php endif; ?>>+</option>
          <option value="-" <?php if ($this->_tpl_vars['admin']['t_offset_o'] == '-'): ?>selected<?php endif; ?>>-</option>
        </select> <input name="t_offset" type="text" id="t_offset" value="<?php echo $this->_tpl_vars['admin']['t_offset']; ?>
" size="2" style="width:20px;" />
      </td>
    </tr>
<?php endif; ?>
    <tr valign="top">
      <td><?php echo ((is_array($_tmp='Default Language')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
      <td>
        <select name="lang_ch" id="lang" style="width:200px;">
          <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['languages'],'selected' => $this->_tpl_vars['admin']['lang']), $this);?>

        </select>
      </td>
    </tr>
<?php if ($this->_tpl_vars['settings_template']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['settings_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
  </table>
</div>
</div>


<?php if ($this->_tpl_vars['additional_template']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['additional_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>


<div class="bottom_nav_options">
<?php if (! $this->_tpl_vars['demoMode']): ?>
	<input class="adesk_button_update" type="submit" name="Update" value="<?php echo ((is_array($_tmp='Update')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" />
<?php else: ?>
	<span class="demoDisabled"><?php echo ((is_array($_tmp='Disabled in demo')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
<?php endif; ?>
	<input class="adesk_button_back" type="button" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1);" />
</div>
</form>