<?php /* Smarty version 2.6.12, created on 2016-07-08 13:59:26
         compiled from install.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'i18n', 'install.htm', 5, false),array('modifier', 'alang', 'install.htm', 8, false),array('function', 'adesk_js', 'install.htm', 9, false),array('function', 'jsvar', 'install.htm', 33, false),array('function', 'html_options', 'install.htm', 148, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo ((is_array($_tmp="utf-8")) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)); ?>
" />
<meta http-equiv="Content-Language" content="<?php echo ((is_array($_tmp="en-us")) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)); ?>
" />
<meta name="robots" content="noindex,nofollow" />
<title><?php echo ((is_array($_tmp="%s Setup")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['appname']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['appname'])); ?>
</title>
<?php echo smarty_function_adesk_js(array('lib' => "scriptaculous/prototype.js"), $this);?>

<?php echo smarty_function_adesk_js(array('lib' => "scriptaculous/scriptaculous.js"), $this);?>

<?php echo smarty_function_adesk_js(array('base' => "..",'acglobal' => "ajax,dom,b64,str,array,utf,ui,paginator,loader,tooltip,date,custom_fields,editor,form,class.Table,ihook,progressbar"), $this);?>

<?php echo smarty_function_adesk_js(array('lib' => "templates/install.js"), $this);?>

<link href="../awebdesk/css/default.css" rel="stylesheet" type="text/css" />
<link href="../awebdesk/css/installer_updater.css" rel="stylesheet" type="text/css" />
<link href="../awebdesk/css/awebdeskstyle.css" rel="stylesheet" type="text/css" />
<link href="../awebdesk/css/grids-min.css" rel="stylesheet" type="text/css" />
<script>
<!--

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "adesk_strings.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<?php echo smarty_function_jsvar(array('name' => 'plink','var' => $this->_tpl_vars['siteurl']), $this);?>


<?php echo smarty_function_jsvar(array('name' => 'appid','var' => $this->_tpl_vars['appid']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'appname','var' => $this->_tpl_vars['appname']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'requirements','var' => $this->_tpl_vars['requirements']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'step','var' => $this->_tpl_vars['step']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'subapps','var' => $this->_tpl_vars['subapps']), $this);?>



var apipath = "<?php echo $this->_tpl_vars['siteurl']; ?>
/awebdesk/scripts/instup.php";

// set error bar for dropped api calls
var printAPIerrors = adesk_error_show;

-->
</script>
</head>
<body class="yui-skin-sam" onload="if ( $('dl_s') ) $('dl_s').focus();">

<div id="adesk_loading_bar" class="adesk_hidden"><span id="adesk_loading_text"><?php echo ((is_array($_tmp="Loading...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></div>
<div id="adesk_result_bar" class="adesk_hidden"><span id="adesk_result_text"><?php echo ((is_array($_tmp="Changes Saved.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></div>
<div id="adesk_error_bar" class="adesk_hidden"><span id="adesk_error_text"><?php echo ((is_array($_tmp="Error Occurred!")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></div>



<div class="yui-g" id="top_bar">
    <div class="yui-u first">
      <span class="company"><strong><?php echo $this->_tpl_vars['appname']; ?>
 <?php echo $this->_tpl_vars['appver']; ?>
</strong></span>
    </div>

 

 
  </div>




  <div class="yui-t2" id="doc3">
    <div id="bd">
      <div id="yui-main">
        <div class="yui-b">
          
 <div id="content" style="background:#FFFFFF;">
	<?php if (defined ( @ICONV_DISABLED )): ?>
	<div class="non-critical-warning">
	  <?php echo ((is_array($_tmp="The PHP function, iconv(), is disabled on your system.  Because iconv() is disabled, you may experience persistent language and character set issues.  You should contact your host to have iconv support enabled.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	</div>
	<?php endif; ?>
	<?php if (defined ( @MYSQL_UTF8_DISABLED )): ?>
	<div class="non-critical-warning">
	  <?php echo ((is_array($_tmp="Your MySQL database server does not seem to be configured to use, or allow, the UTF-8 character set.  As a result, some of your text may appear garbled and may appear to be sorted in the wrong order.  We strongly recommend you contact your ISP and ask them to enable UTF-8 support before continuing.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	</div>
	<?php endif; ?>
 
<div class="adesk_install_box">
 
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['content_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 

 </div>
   </div>
        </div>
     
	  </div>
	 
 
 
 
    <div class="yui-b" id="sidebar">
        <div class="yui-g" id="sub_submenu">
		<h4>Installation</h4>
		<ul id="installmenu">
 
		<li class="<?php if (! $this->_tpl_vars['allgood']): ?>nextstep<?php elseif ($this->_tpl_vars['step'] == 1): ?>currentstep<?php else: ?>previousstep<?php endif; ?>"><?php echo ((is_array($_tmp='System Checks')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
		<li class="<?php if (! $this->_tpl_vars['allgood']): ?>nextstep<?php elseif ($this->_tpl_vars['step'] == 2): ?>currentstep<?php elseif ($this->_tpl_vars['step'] < 2): ?>nextstep<?php else: ?>previousstep<?php endif; ?>"><?php echo ((is_array($_tmp='Database Information')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
		<li class="<?php if (! $this->_tpl_vars['allgood']): ?>nextstep<?php elseif ($this->_tpl_vars['step'] == 3): ?>currentstep<?php elseif ($this->_tpl_vars['step'] < 3): ?>nextstep<?php else: ?>previousstep<?php endif; ?>"><?php echo ((is_array($_tmp='License Agreement')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
		<li class="<?php if (! $this->_tpl_vars['allgood']): ?>nextstep<?php elseif ($this->_tpl_vars['step'] == 4): ?>currentstep<?php elseif ($this->_tpl_vars['step'] < 4): ?>nextstep<?php else: ?>previousstep<?php endif; ?>"><?php echo ((is_array($_tmp='Software Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
		<li class="nextstep"><?php echo ((is_array($_tmp='Finished')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
	</ul>
		
			</div></div>
 
 
 
 
 
 
 
 
 
 
 </div>
<div align="center">
  <div class="theme_container" align="left">
    <div class="theme_footer">
  		<?php echo ((is_array($_tmp="&copy; %s AwebDesk Softwares.")) ? $this->_run_mod_handler('i18n', true, $_tmp, $this->_tpl_vars['year']) : smarty_modifier_i18n($_tmp, $this->_tpl_vars['year'])); ?>

		<?php echo ((is_array($_tmp="All rights reserved.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php if (count ( $this->_tpl_vars['languages'] ) > 0): ?>
	<div id="langchangerbox" class="<?php if ($this->_tpl_vars['step'] == 5): ?>adesk_hidden<?php else: ?>adesk_block<?php endif; ?>" style="float: right;">
		<form action="install.php" method="post" name="reg" id="langForm">
			<input name="dl_s" type="hidden" value="<?php echo $this->_tpl_vars['dl_s']; ?>
" />
			<input name="dl_d" type="hidden" value="<?php echo $this->_tpl_vars['d_r']; ?>
" />
			<input name="dl_h" type="hidden" value="<?php echo $this->_tpl_vars['d_h']; ?>
" />
			<input name="rd7" type="hidden" value="<?php echo $this->_tpl_vars['rd7']; ?>
" />
			<input name="rd8" type="hidden" value="<?php echo $this->_tpl_vars['rd8']; ?>
" />
			<input name="rd9" type="hidden" value="<?php echo $this->_tpl_vars['rd9']; ?>
" />
			<input name="t" type="hidden" value="<?php echo $this->_tpl_vars['appid']; ?>
" />
			<span id="installlangpointer" style="color:#080;padding:2px;">
				<span style="color:#800;padding:2px;">
					<?php echo ((is_array($_tmp="If you wish to use a different language as default in your install, please change it now")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				</span>
				&raquo;&raquo;
			</span>
			<select name="lang_ch" size="1" style="font-size:10px;">
				<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['languages'],'selected' => $this->_tpl_vars['lang']), $this);?>

			</select>
			<input type="submit" value="<?php echo ((is_array($_tmp='Change')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
"  style="font-size:10px;"/>
		</form>
	</div>
<?php endif; ?>
 </div>
 
    </div>

 
	
	</div>
    </div>
  </div>
</div>

</body>
<script>
<?php echo '
$(\'installlangpointer\').fade({duration: 10});
'; ?>

</script>
</html>