<?php /* Smarty version 2.6.12, created on 2016-07-08 14:19:54
         compiled from iframe.upload.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'i18n', 'iframe.upload.htm', 4, false),array('modifier', 'alang', 'iframe.upload.htm', 17, false),array('function', 'adesk_js', 'iframe.upload.htm', 9, false),array('function', 'jsvar', 'iframe.upload.htm', 20, false),)), $this); ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo ((is_array($_tmp="utf-8")) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)); ?>
" />
	<meta http-equiv="Content-Language" content="<?php echo ((is_array($_tmp="en-us")) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)); ?>
" />
	<link href="<?php if (isset ( $this->_tpl_vars['site']['p_link2'] )):  echo $this->_tpl_vars['site']['p_link2'];  else:  echo $this->_tpl_vars['site']['p_link'];  endif; ?>/awebdesk/css/default.css" rel="stylesheet" type="text/css" />
	<link href="css/default.css" rel="stylesheet" type="text/css" />
<?php if (isset ( $this->_tpl_vars['site']['p_link2'] )): ?>
	<?php echo smarty_function_adesk_js(array('base' => $this->_tpl_vars['site']['p_link2'],'lib' => "scriptaculous/prototype.js"), $this);?>

	<?php echo smarty_function_adesk_js(array('base' => $this->_tpl_vars['site']['p_link2'],'lib' => "scriptaculous/scriptaculous.js"), $this);?>

	<?php echo smarty_function_adesk_js(array('base' => $this->_tpl_vars['site']['p_link2'],'acglobal' => "ajax,dom,b64,str,array,utf,ui,paginator,loader,tooltip,date,custom_fields,editor,form,progressbar,md5"), $this);?>

<?php else: ?>
	<?php echo smarty_function_adesk_js(array('base' => $this->_tpl_vars['site']['p_link'],'lib' => "scriptaculous/prototype.js"), $this);?>

	<?php echo smarty_function_adesk_js(array('base' => $this->_tpl_vars['site']['p_link'],'lib' => "scriptaculous/scriptaculous.js"), $this);?>

	<?php echo smarty_function_adesk_js(array('base' => $this->_tpl_vars['site']['p_link'],'acglobal' => "ajax,dom,b64,str,array,utf,ui,paginator,loader,tooltip,date,custom_fields,editor,form,progressbar,md5"), $this);?>

<?php endif; ?>
	<title><?php echo ((is_array($_tmp='Upload a File')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</title>
<script>
<!--
<?php echo smarty_function_jsvar(array('name' => 'upload_id','var' => $this->_tpl_vars['id']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'upload_name','var' => $this->_tpl_vars['name']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'upload_action','var' => $this->_tpl_vars['action']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'upload_relid','var' => $this->_tpl_vars['relid']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'upload_limit','var' => $this->_tpl_vars['limit']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'upload_submitted','var' => $this->_tpl_vars['submitted']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'upload_result','var' => $this->_tpl_vars['result']), $this);?>


//if ( self == top ) top.location = 'index.php';
var daddy = window.parent.document;
-->
</script>
</head>
<body style="margin: 0; padding: 0;">
<form action="upload.php?action=<?php echo $this->_tpl_vars['action']; ?>
&id=<?php echo $this->_tpl_vars['id']; ?>
&relid=<?php echo $this->_tpl_vars['relid']; ?>
&limit=<?php echo $this->_tpl_vars['limit']; ?>
&name=<?php echo $this->_tpl_vars['name']; ?>
" enctype="multipart/form-data" method="post" style="margin: 0; padding: 0;">
<input id="<?php echo $this->_tpl_vars['id']; ?>
_file" name="adesk_uploader" type="file" value="" onchange="window.parent.adesk_form_upload_start(this);" />


<script>
<!--

<?php if ($this->_tpl_vars['submitted']): ?>
window.parent.adesk_form_upload_stop(upload_id, upload_name, upload_result, upload_limit);
<?php if (isset ( $this->_tpl_vars['additional'] )):  echo $this->_tpl_vars['additional'];  endif; ?>
<?php endif; ?>

-->
</script>
</form>
</body>
</html>