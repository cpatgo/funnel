<?php /* Smarty version 2.6.12, created on 2016-07-08 14:06:08
         compiled from index.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'i18n', 'index.htm', 8, false),array('modifier', 'alang', 'index.htm', 40, false),array('function', 'adesk_js', 'index.htm', 15, false),array('function', 'jsvar', 'index.htm', 27, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php if ($this->_tpl_vars['ieCompatFix']): ?>
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<?php endif; ?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo ((is_array($_tmp="utf-8")) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)); ?>
" />
<meta http-equiv="Content-Language" content="<?php echo ((is_array($_tmp="en-us")) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)); ?>
" />
<title><?php echo $this->_tpl_vars['site']['site_name']; ?>
</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 

<meta name="robots" content="noindex,nofollow">
<?php echo smarty_function_adesk_js(array('lib' => "scriptaculous/prototype.js"), $this);?>

<?php echo smarty_function_adesk_js(array('lib' => "scriptaculous/scriptaculous.js"), $this);?>

<script language="JavaScript" type="text/javascript" src="js/jsmodules.js"></script><script type="text/javascript">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "strings.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>
<script language="JavaScript" type="text/javascript" src="js/jsv6.js"></script>
<link href="css/default.css" rel="stylesheet" type="text/css" />
<?php echo smarty_function_adesk_js(array('base' => "",'src' => "awebdeskjs.php"), $this);?>

<script>
<!--

<?php echo smarty_function_jsvar(array('name' => 'datetimeformat','var' => $this->_tpl_vars['site']['datetimeformat']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'dateformat','var' => $this->_tpl_vars['site']['dateformat']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'timeformat','var' => $this->_tpl_vars['site']['timeformat']), $this);?>


<?php echo smarty_function_jsvar(array('name' => 'adesk_action','var' => $this->_tpl_vars['action']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'plink','var' => $this->_tpl_vars['plink']), $this);?>


var apipath = "<?php echo $this->_tpl_vars['plink']; ?>
/manage/awebdeskapi.php";

-->
</script>
</head>
<body bgcolor="#EDECE7" style="<?php echo ((is_array($_tmp='direction: ltr')) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)); ?>
">
<div id="adesk_loading_bar" class="adesk_hidden"><span id="adesk_loading_text"><?php echo ((is_array($_tmp="Loading...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></div>
<div id="adesk_result_bar" class="adesk_hidden"><span id="adesk_result_text"><?php echo ((is_array($_tmp="Changes Saved.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></div>
<div id="adesk_error_bar" class="adesk_hidden"><span id="adesk_error_text"><?php echo ((is_array($_tmp="Error Occurred!")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></div>


<div align="center">

	<div class="adesk_login_box_container">


 

	<div class="adesk_login_box_border">
	<div class="adesk_login_box">
		 <img src="<?php if ($this->_tpl_vars['site']['site_logo']):  echo $this->_tpl_vars['site']['site_logo'];  else: ?>../manage/images/logo.gif<?php endif; ?>" border="0" alt="" /> 
	<?php if (isset ( $this->_tpl_vars['browser_alert'] )): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "index.alert.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php else: ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['content_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
	</div>
	</div>


	<div class="adesk_copyright_text" style="margin-top:10px;">
    	<?php if ($this->_tpl_vars['content_template'] == 'index.login.htm'): ?><span style="color:#999; font-size:11px;"><a href="index.php?action=account_lookup" style="color:#999;"><?php echo ((is_array($_tmp="Forgot your login information?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></span><?php endif; ?>
    	<?php if ($this->_tpl_vars['content_template'] == 'index.lookup.htm' || $this->_tpl_vars['content_template'] == 'index.message.htm'): ?><span style="color:#999; font-size:11px;"><a href="index.php" style="color:#999;"><?php echo ((is_array($_tmp='Return to the login page')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></span><?php endif; ?>
    </div>
</div>

</body>
</html>