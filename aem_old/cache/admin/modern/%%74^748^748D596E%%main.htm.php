<?php /* Smarty version 2.6.12, created on 2016-07-08 14:09:37
         compiled from main.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'i18n', 'main.htm', 8, false),array('modifier', 'alang', 'main.htm', 83, false),array('function', 'adesk_js', 'main.htm', 14, false),array('function', 'jsvar', 'main.htm', 48, false),)), $this); ?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<?php if ($this->_tpl_vars['ieCompatFix']): ?>
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<?php endif; ?>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo ((is_array($_tmp="utf-8")) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)); ?>
" />
	<meta http-equiv="Content-Language" content="<?php echo ((is_array($_tmp="en-us")) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)); ?>
" />
 <meta name="robots" content="noindex,nofollow">
		<title><?php echo $this->_tpl_vars['site']['site_name']; ?>
 - <?php echo $this->_tpl_vars['pageTitle']; ?>
</title>
	<script type="text/javascript" src="../awebdesk/js/jquery-1.2.6.min.js"></script>
	<?php echo smarty_function_adesk_js(array('lib' => "scriptaculous/prototype.js"), $this);?>

	<?php echo smarty_function_adesk_js(array('lib' => "scriptaculous/scriptaculous.js"), $this);?>

	<script language="JavaScript" type="text/javascript" src="js/jsmodules.js"></script>
	<script language="JavaScript" type="text/javascript" src="js/jsv6.js"></script>
	<?php $_from = $this->_tpl_vars['header_lines']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['line']):
?>
	<?php echo $this->_tpl_vars['line']; ?>

	<?php endforeach; endif; unset($_from); ?>
		<link href="templates/modern/css/default.css" rel="stylesheet" type="text/css" />
 
	<link rel="stylesheet" href="templates/modern/css/v7/css/bootstrap.css">
  <link rel="stylesheet" href="templates/modern/css/v7/css/font-awesome.min.css">
  <link rel="stylesheet" href="templates/modern/css/v7/css/font.css">
	<link rel="stylesheet" href="templates/modern/css/v7/css/style.css">
 
  <!--[if lt IE 9]>
    <script src="templates/modern/js/v7/ie/respond.min.js"></script>
    <script src="templates/modern/js/v7/ie/html5.js"></script>
    <script src="templates/modern/js/v7/ie/excanvas.js"></script>
  <![endif]-->

<?php if (isset ( $this->_tpl_vars['site']['template_css'] ) && $this->_tpl_vars['site']['template_css']): ?>
<style>
<?php echo $this->_tpl_vars['site']['template_css']; ?>

</style>
<?php endif; ?>
	<?php echo smarty_function_adesk_js(array('base' => "",'src' => "awebdeskjs.php"), $this);?>

    
    
    
	<script>
	  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "strings.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <!--

	  	  <?php echo smarty_function_jsvar(array('name' => 'datetimeformat','var' => $this->_tpl_vars['site']['datetimeformat']), $this);?>

	  <?php echo smarty_function_jsvar(array('name' => 'dateformat','var' => $this->_tpl_vars['site']['dateformat']), $this);?>

	  <?php echo smarty_function_jsvar(array('name' => 'timeformat','var' => $this->_tpl_vars['site']['timeformat']), $this);?>


	  <?php echo smarty_function_jsvar(array('name' => 'adesk_action','var' => $this->_tpl_vars['action']), $this);?>

	  <?php echo smarty_function_jsvar(array('name' => 'plink','var' => $this->_tpl_vars['plink']), $this);?>


	  <?php echo smarty_function_jsvar(array('name' => 'nl','var' => $this->_tpl_vars['nl']), $this);?>


	  var apipath = "<?php echo $this->_tpl_vars['plink']; ?>
/manage/awebdeskapi.php";
	  var acgpath = "<?php echo $this->_tpl_vars['plink']; ?>
/awebdesk";

	  var paginator_b64 = false;

<?php if (! $this->_tpl_vars['__ishosted'] && ! $_SESSION['_adesk_disablespawning']): ?>
	  // stalled processes restarter
	  adesk_ajax_call_url('process.php', null, null);
	  <?php echo '
	  // cron restarter - run every 5 minutes
	  var cronTimer = window.setInterval(
	    function() {
	    	adesk_ajax_call_url(\'cron.php\', null, null);
	    },
	    10 * 60 * 1000 // every 10 minutes
	  );
	  '; ?>

<?php endif; ?>
	  -->
	</script>
  </head>
  <?php  flush();  ?>
<body>
	
 	<script language="JavaScript" type="text/javascript" src="../awebdesk/editor_tiny/tiny_mce.js?_v=3.4.3.2"></script>

<div id="adesk_loading_bar" class="adesk_hidden"><span id="adesk_loading_text"><?php echo ((is_array($_tmp="Loading...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></div>
	<div id="adesk_result_bar" class="adesk_hidden"><span id="adesk_result_text"><?php echo ((is_array($_tmp="Changes Saved.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></div>
	<div id="adesk_error_bar" class="adesk_hidden"><span id="adesk_error_text"><?php echo ((is_array($_tmp="Error Occurred!")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></div>



	<?php if (isset ( $this->_tpl_vars['site']['templates'] )): ?>
	<?php echo $this->_tpl_vars['site']['templates']['precontent']; ?>

	<?php endif; ?>
	
	 <section id="content" class="content-sidebar bg-white">
   <?php if (isset ( $this->_tpl_vars['side_content_template'] ) && $this->_tpl_vars['side_content_template'] != ''): ?>
 
 <aside class="sidebar bg-lighter padder clearfix">
      
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['side_content_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </aside>
 		
 
<?php endif; ?>  
     
     
     
     
    
    
    <section class="main padder"> 

		
	


<div class="row"><div class="col-lg-12">
 
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['content_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 </div>
	</div>
	
	
	
	          </section>
         </section>
     
	   
	
	

 	      
        
 


 

 
 
 </div>
	
	
	
	
	
	

 
	 


		<?php if (isset ( $this->_tpl_vars['site']['templates'] )): ?>
		<?php echo $this->_tpl_vars['site']['templates']['postcontent']; ?>

		<?php endif; ?>

 

<script type="text/javascript">
adesk_tooltip_init();
// set error bar for dropped api calls
var printAPIerrors = adesk_error_show;
</script>

<?php if ($this->_tpl_vars['demoMode'] == 1): ?><h3 style="color:#FF0000"><strong><?php echo ((is_array($_tmp='This is a demo')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong> - <?php echo ((is_array($_tmp="Certain features such as sending email are disabled.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3><?php endif; ?>

	 <script src="templates/modern/js/v7/jquery.min.js"></script>
      
  <!-- Bootstrap -->
  <script src="templates/modern/js/v7/bootstrap.js"></script>
  <script src="templates/modern/js/v7/app.js"></script>
 <script type="text/javascript">
  $.noConflict(true); 
 </script>
</body>
</html>