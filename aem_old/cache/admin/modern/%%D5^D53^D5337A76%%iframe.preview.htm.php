<?php /* Smarty version 2.6.12, created on 2016-07-18 14:54:41
         compiled from iframe.preview.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'i18n', 'iframe.preview.htm', 9, false),array('modifier', 'alang', 'iframe.preview.htm', 16, false),array('modifier', 'default', 'iframe.preview.htm', 30, false),array('function', 'adesk_js', 'iframe.preview.htm', 13, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<?php if ($this->_tpl_vars['public']):  $this->assign('basepath', '');  else:  $this->assign('basepath', '../');  endif; ?>
<html>
<head>
<?php if ($this->_tpl_vars['ieCompatFix']): ?>
	<meta http-equiv="X-UA-Compatible" content="IE=8" />
<?php endif; ?>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo ((is_array($_tmp="utf-8")) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)); ?>
" />
	<meta http-equiv="Content-Language" content="<?php echo ((is_array($_tmp="en-us")) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)); ?>
" />
	<link href="<?php echo $this->_tpl_vars['basepath']; ?>
awebdesk/css/default.css" rel="stylesheet" type="text/css" />
	<link href="css/default.css" rel="stylesheet" type="text/css" />
	<?php echo smarty_function_adesk_js(array('lib' => "scriptaculous/prototype.js"), $this);?>

	<?php echo smarty_function_adesk_js(array('lib' => "scriptaculous/scriptaculous.js"), $this);?>

	<?php echo smarty_function_adesk_js(array('acglobal' => "ajax,dom,b64,str,array,utf,ui,paginator,loader,tooltip,date,custom_fields,editor,form,progressbar"), $this);?>

	<title><?php echo ((is_array($_tmp='Preview this Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</title>
<script>
<!--

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "iframe.preview.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

-->
</script>
</head>

<body style="margin: 0; padding: 0;">

	<div class="preview_menu">
		<div style="float: right;">
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'autocomplete.inc.htm', 'smarty_include_vars' => array('fieldPrefix' => 'subscriber','fieldID' => 'preview_email','fieldName' => 'preview_email','fieldSize' => '25','fieldValue' => ((is_array($_tmp=@$this->_tpl_vars['admin']['email'])) ? $this->_run_mod_handler('default', true, $_tmp, 'test@test.com') : smarty_modifier_default($_tmp, 'test@test.com')))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<input type="button" id="preview_button" value="<?php echo ((is_array($_tmp='Preview')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onClick="preview_menu_changed();" />

			<input type="button" value='<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onClick="top.close();" class="adesk_button_ok" />
		</div>
		<div>
			<span id="preview_messageid_box" class="adesk_hidden">
				<?php echo ((is_array($_tmp="Message:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<select id="preview_messageid" name="preview_messageid" size="1" onChange="window.location.href='?c=' + preview_campaignid + '&m=' + this.value + '&s=0';">
								</select>
			</span>
			<span id="preview_format_box" class="adesk_hidden">
				<?php if ($this->_tpl_vars['campaign']['type'] != 'text'): ?>
				<?php echo ((is_array($_tmp="Format:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<select id="preview_format" name="preview_format" size="1" onChange="preview_menu_changed();">
					<?php if ($this->_tpl_vars['campaign_type'] != 'text'): ?><option value="html" selected="selected"><?php echo ((is_array($_tmp='HTML')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option><?php endif; ?>
					<option value="text"><?php echo ((is_array($_tmp='Text')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
				</select>
				<?php endif; ?>
			</span>

		</div>
		<br clear="all" />
	</div>

	<div id="preview_message_loading" class="adesk_block" align="center" style="margin:10px;">
		<img src="images/loader3.gif" />
		<div style="font-size:10px; color:#999999;"><?php echo ((is_array($_tmp='Loading')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
	</div>

	<div id="preview_message_info" class="adesk_hidden">
		<div class="preview_details">
			<div id="preview_images_box" class="adesk_block" style="float:right;">
				<a id="preview_images_link" href="#" onclick="images_toggle();return false;"><?php echo ((is_array($_tmp='Images Enabled')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			<div class="preview_message_from">
				<?php echo ((is_array($_tmp="From:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<span id="preview_message_from"></span>
			</div>
			<div class="preview_message_to">
				<?php echo ((is_array($_tmp="To:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<span id="preview_message_to"></span>
			</div>
			<div class="preview_message_subject">
				<?php echo ((is_array($_tmp="Subject:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<span id="preview_message_subject"></span>
			</div>
			<div id="preview_message_attachments_box" class="adesk_hidden">
				<?php echo ((is_array($_tmp="Attachments:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<span id="preview_message_attachments"></span>
			</div>
		</div>
		<div class="preview_ruler">&nbsp;</div>
	</div>


	<div id="preview_message_text" style="margin-bottom:20px;" class="adesk_hidden"></div>

	<div id="preview_message_html" style="margin-bottom:20px;" class="adesk_hidden"></div>

	<div id="preview_message_source_box" class="adesk_hidden"><textarea id="preview_message_source"></textarea></div>


	<div class="preview_menu_bottom">
		<input type="button" value='<?php echo ((is_array($_tmp='Source')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onClick="adesk_dom_toggle_class('preview_message_source_box', 'adesk_block', 'adesk_hidden');" class="adesk_button_right" />
		<input type="button" value='<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onClick="top.close();" class="adesk_button_ok" />
	</div>

</body>

</html>