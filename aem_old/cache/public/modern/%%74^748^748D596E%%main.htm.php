<?php /* Smarty version 2.6.12, created on 2016-07-08 15:27:03
         compiled from main.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'i18n', 'main.htm', 8, false),array('modifier', 'plang', 'main.htm', 57, false),array('modifier', 'js', 'main.htm', 74, false),array('function', 'adesk_js', 'main.htm', 13, false),)), $this); ?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<?php if ($this->_tpl_vars['ieCompatFix']): ?>
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<?php endif; ?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo ((is_array($_tmp='utf-8')) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)); ?>
" />
<meta http-equiv="Content-Language" content="<?php echo ((is_array($_tmp="en-us")) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)); ?>
" />
 <meta name="robots" content="noindex,follow">
 
<title><?php if ($this->_tpl_vars['site']['site_name'] && $this->_tpl_vars['site']['site_name'] != ""):  echo $this->_tpl_vars['site']['site_name']; ?>
 - <?php endif;  echo $this->_tpl_vars['pageTitle']; ?>
</title>
<?php echo smarty_function_adesk_js(array('base' => $this->_tpl_vars['_'],'lib' => "scriptaculous/prototype.js"), $this);?>

<?php echo smarty_function_adesk_js(array('base' => $this->_tpl_vars['_'],'lib' => "scriptaculous/scriptaculous.js"), $this);?>

<script language="JavaScript" type="text/javascript" src="<?php echo $this->_tpl_vars['_']; ?>
/js/jsmodules.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo $this->_tpl_vars['_']; ?>
/manage/js/jsv6.js"></script>

<?php echo smarty_function_adesk_js(array('base' => $this->_tpl_vars['_'],'src' => "awebdeskjs.php"), $this);?>


<link href="<?php echo $this->_tpl_vars['_']; ?>
/awebdesk/css/default.css" rel="stylesheet" type="text/css" />
 



	<link rel="stylesheet" href="<?php echo $this->_tpl_vars['_']; ?>
/css/v7/css/bootstrap.css">
  <link rel="stylesheet" href="<?php echo $this->_tpl_vars['_']; ?>
/css/v7/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo $this->_tpl_vars['_']; ?>
/css/v7/css/font.css">
	<link rel="stylesheet" href="<?php echo $this->_tpl_vars['_']; ?>
/css/v7/css/style.css">
  <link rel="stylesheet" href="<?php echo $this->_tpl_vars['_']; ?>
/css/v7/css/plugin.css">
  <!--[if lt IE 9]>
    <script src="<?php echo $this->_tpl_vars['_']; ?>
/js/v7/ie/respond.min.js"></script>
    <script src="<?php echo $this->_tpl_vars['_']; ?>
/js/v7/ie/html5.js"></script>
    <script src="js/v7/ie/excanvas.js"></script>
  <![endif]-->



<?php if ($this->_tpl_vars['site']['template_css']): ?>
<style>
<?php echo $this->_tpl_vars['site']['template_css']; ?>

</style>
<?php endif; ?>

</head>

<body>
<?php if ($this->_tpl_vars['site']['general_maint'] && ! $this->_tpl_vars['is_admin'] && $this->_tpl_vars['action'] != 'abuse'): ?>

			<div align="center">
			  <div align="left" style="padding:25px; font-family:Arial, Helvetica, sans-serif; font-size:11px;	width:250px; background:#FFFDE6; border:1px solid #DF746F; color:#993300;">
				<?php echo $this->_tpl_vars['site']['general_maint_message']; ?>

			  </div>
			</div>

<?php else: ?>
		
	<div id="adesk_loading_bar" class="adesk_hidden"><span id="adesk_loading_text"><?php echo ((is_array($_tmp="Loading...")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</span></div>
			<div id="adesk_result_bar" class="adesk_hidden"><span id="adesk_result_text"><?php echo ((is_array($_tmp="Changes Saved.")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</span></div>
			<div id="adesk_error_bar" class="adesk_hidden"><span id="adesk_error_text"><?php echo ((is_array($_tmp="Error Occurred!")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</span></div>
 <?php echo $this->_tpl_vars['site']['templates']['precontent']; ?>
 
		  <section id="content">
    <section class="main padder"> 

			


<div class="row"><div class="col-lg-12"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['content_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div></div>


</section>
</section>
 <?php echo $this->_tpl_vars['site']['templates']['postcontent']; ?>
 
<?php if (isset ( $_POST['lang_ch'] )): ?>
					<script>adesk_result_show('<?php echo ((is_array($_tmp=((is_array($_tmp='Language changed')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
');</script>
			<?php endif; ?>
 <!-- / footer -->

	 <script src="<?php echo $this->_tpl_vars['_']; ?>
/js/v7/jquery.min.js"></script>
      
  <!-- Bootstrap -->
  <script src="<?php echo $this->_tpl_vars['_']; ?>
/js/v7/bootstrap.js"></script>
  <!-- app -->
  <script src="<?php echo $this->_tpl_vars['_']; ?>
/js/v7/app.js"></script>
 <script type="text/javascript">
  $.noConflict(true); 
 </script>
 

   
<?php endif; ?>



</body>
</html>