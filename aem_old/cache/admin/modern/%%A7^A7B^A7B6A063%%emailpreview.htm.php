<?php /* Smarty version 2.6.12, created on 2016-07-18 11:58:18
         compiled from emailpreview.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'i18n', 'emailpreview.htm', 6, false),array('modifier', 'alang', 'emailpreview.htm', 12, false),array('modifier', 'adesk_isselected', 'emailpreview.htm', 136, false),array('modifier', 'truncate', 'emailpreview.htm', 136, false),array('modifier', 'count', 'emailpreview.htm', 225, false),array('function', 'adesk_js', 'emailpreview.htm', 9, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<?php if ($this->_tpl_vars['public']):  $this->assign('basepath', '');  else:  $this->assign('basepath', '../');  endif; ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo ((is_array($_tmp="utf-8")) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)); ?>
" />
	<meta http-equiv="Content-Language" content="<?php echo ((is_array($_tmp="en-us")) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)); ?>
" />
	<link href="css/default.css" rel="stylesheet" type="text/css" />
	<?php echo smarty_function_adesk_js(array('lib' => "scriptaculous/prototype.js"), $this);?>

	<?php echo smarty_function_adesk_js(array('lib' => "scriptaculous/scriptaculous.js"), $this);?>

	<?php echo smarty_function_adesk_js(array('acglobal' => "ajax,dom,b64,str,array,utf,ui,paginator,loader,tooltip,date,custom_fields,editor,form,progressbar"), $this);?>

	<title><?php echo ((is_array($_tmp='Inbox Preview')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</title>

<style type="text/css">
<?php echo '

	body{
		overflow:hidden;
	}

	h2 {
		margin: 20px 0;
	}

	.client_name {
		font-weight:bold;
	}

	.client_name a, .client_name a:visited {

	}

	ul {

		height: 75px;
		list-style-type: none;
		margin: 0;
		overflow: scroll;
		padding: 0;
		width:100%;
		border:1px solid #EDA7A7;
		margin-top:6px;

	}

	li {
		font-family: "Courier New", Courier;
	}


	.current {
		border-top:1px solid #F1DF0A;
		border-bottom:1px solid #F1DF0A;
		background: #ffc !important;
	}

	.one,
	.two {
		padding:10px;
		paddint-top:5px;
		padding-bottom:5px;
	}

	.one {
		background: #EFEDDE;
	}

	.two {
		background: #F8F7ED;
	}

	.issue_container{
		border-left:1px solid #FF0000;
		padding-left:5px;

	}
	.issue {
		margin-bottom: 3px;
	}

	.issues_some {
		color: red;
		margin-bottom:4px;
	}

	.issues_none {
		color: green;
	}

	.emailpreview_source_display {
		display: none; /* hidden for now */
		font-family: Arial, sans-serif;
		font-weight: bold;
	}

	.header{
		background:#333333;
		padding:10px;
		padding-left:15px;
		color:#FFFFFF;
		font-size:14px;

	}

'; ?>

</style>

<script>
<!--

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "emailpreview.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

-->
</script>
</head>

<body>

	<div id="form">

		<div style="position:absolute; top:11px; right:95px;">
<?php if ($this->_tpl_vars['campaign'] && $this->_tpl_vars['campaign']['type'] == 'split'): ?>
			<a href="#" onclick="adesk_dom_toggle_class('message_select','adesk_block','adesk_hidden'); return false;" style="margin-right: 25px;color:#CCCCCC;"><?php echo ((is_array($_tmp='View Other Messages')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php endif; ?>
			 
			<a href="#" onclick="adesk_dom_toggle_class('email_report','adesk_block','adesk_hidden'); return false;" style="color:#CCCCCC;"><?php echo ((is_array($_tmp='Email This Report')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		</div>

<?php if ($this->_tpl_vars['campaign'] && $this->_tpl_vars['campaign']['type'] == 'split'): ?>
		<div id="message_select" class="adesk_hidden" style="position:absolute; top:32px; right:300px;">
			<div style="position:absolute; margin-top:-5px; margin-left:164px;"><img src="../awebdesk/media/email-preview-send-arrow.gif"></div>
			<div style="border:3px solid #CCCCCC;">
			<div style="border:1px solid #666666; background:#FFFFFF; padding:8px;">
			<select name="emailpreview_message_select" id="emailpreview_message_select" size="1" style="width: 150px;">
			<?php $_from = $this->_tpl_vars['campaign']['messages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['m']):
?>
				<option value="<?php echo $this->_tpl_vars['m']['id']; ?>
" <?php echo ((is_array($_tmp=$this->_tpl_vars['m']['id'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, $this->_tpl_vars['messageid']) : smarty_modifier_adesk_isselected($_tmp, $this->_tpl_vars['messageid'])); ?>
><?php echo ((is_array($_tmp=$this->_tpl_vars['m']['subject'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>
</option>
			<?php endforeach; endif; unset($_from); ?>
			</select>
			<input type="button" value="<?php echo ((is_array($_tmp='Show')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" id="email_button" onclick="emailpreview_message_switch();" />
			</div>
			</div>
		</div>
<?php endif; ?>

		<div id="email_report" class="adesk_hidden" style="position:absolute; top:32px; right:75px;">
			<div style="position:absolute; margin-top:-5px; margin-left:164px;"><img src="../awebdesk/media/email-preview-send-arrow.gif"></div>
			<div style="border:3px solid #CCCCCC;">
			<div style="border:1px solid #666666; background:#FFFFFF; padding:8px;">
			<input type="text" name="emailpreview_message_email" id="emailpreview_message_email" onkeypress="adesk_dom_keypress_doif(event, 13, emailpreview_message_send);" style="width: 150px;" />
			<input type="button" value="<?php echo ((is_array($_tmp='Send')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" id="email_button" onclick="emailpreview_message_send();" />
			</div>
			</div>
		</div>


		<div style="position:absolute; top:8px; right:10px;"><input type="button" value="<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.close();" /></div>
		<div class="header">
			<?php echo ((is_array($_tmp="Inbox Preview:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="client_header" style="color:#FFFFD5; font-weight:bold; border-bottom:2px solid #FFFFD5;"><?php echo $this->_tpl_vars['client_filter_name']; ?>
</span>
		</div>



		<?php if ($this->_tpl_vars['campaignParsed']): ?>

			<input type="hidden" name="client_filter" id="client_filter" value="<?php echo $this->_tpl_vars['client_filter']; ?>
" />
			<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['messageid']; ?>
" />
			<input type="hidden" name="clients2check" value="<?php $_from = $this->_tpl_vars['clients2check']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['counter'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['counter']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['shortname']):
        $this->_foreach['counter']['iteration']++;
 echo $this->_tpl_vars['shortname'];  if (! ($this->_foreach['counter']['iteration'] == $this->_foreach['counter']['total'])): ?>,<?php endif;  endforeach; endif; unset($_from); ?>" />

			<table id="clients" cellspacing="0" border="0" width="100%">

				<tr>

					<td width="650" align="center" valign="top">

						<iframe src="?showhtml=<?php echo $this->_tpl_vars['client_filter']; ?>
" name="iframe_html_modified" id="iframe_html_modified" height="563" width="100%" style="border: 0px;" border="0" frameborder="0"></iframe>
						
					</td>

					<td valign="top">

						<div style="overflow:scroll; height:563px; overflow-x:hidden; border-left:1px solid #999999;">

							<?php $_from = $this->_tpl_vars['clients2check']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['counter'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['counter']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['shortname']):
        $this->_foreach['counter']['iteration']++;
?>

																<?php if ($this->_tpl_vars['clients'][$this->_tpl_vars['shortname']]['html_result']['issuescnt']['style']+$this->_tpl_vars['clients'][$this->_tpl_vars['shortname']]['html_result']['issuescnt']['inline']+$this->_tpl_vars['clients'][$this->_tpl_vars['shortname']]['html_result']['issuescnt']['link'] == 0): ?>
									<?php $this->assign('issues_detected', 'false'); ?>
								<?php else: ?>
									<?php $this->assign('issues_detected', 'true'); ?>
								<?php endif; ?>

								<div id="<?php echo $this->_tpl_vars['shortname']; ?>
_container" class="<?php if ($this->_foreach['counter']['iteration'] % 2 == 0): ?>one<?php else: ?>two<?php endif;  if ($this->_tpl_vars['client_filter'] == $this->_tpl_vars['shortname']): ?> current<?php endif; ?>">

									<div style="float:left; padding-right:10px;"><img src="../awebdesk/media/<?php if ($this->_tpl_vars['issues_detected'] == 'true'): ?>sign_warning.png<?php else: ?>ok.png<?php endif; ?>"></div>

									<div class="client_name">
										<a href="#" onclick="client_show('<?php echo $this->_tpl_vars['shortname']; ?>
');return false;">
											<?php if (isset ( $this->_tpl_vars['clients'][$this->_tpl_vars['shortname']]['quickname'] )): ?>
												<?php echo $this->_tpl_vars['clients'][$this->_tpl_vars['shortname']]['quickname']; ?>

											<?php else: ?>
												<?php echo $this->_tpl_vars['clients'][$this->_tpl_vars['shortname']]['vendor']; ?>
 <?php echo $this->_tpl_vars['clients'][$this->_tpl_vars['shortname']]['software']; ?>
 <?php echo $this->_tpl_vars['clients'][$this->_tpl_vars['shortname']]['version']; ?>

											<?php endif; ?>
										</a>
									</div>

									<?php if ($this->_tpl_vars['issues_detected'] == 'false'): ?>

										<div class="issues_none"><?php echo ((is_array($_tmp="No issues detected!")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

									<?php else: ?>

										<div class="issues_some"><?php echo $this->_tpl_vars['clients'][$this->_tpl_vars['shortname']]['html_result']['issuescnt']['style']+$this->_tpl_vars['clients'][$this->_tpl_vars['shortname']]['html_result']['issuescnt']['inline']+$this->_tpl_vars['clients'][$this->_tpl_vars['shortname']]['html_result']['issuescnt']['link']; ?>
 <?php echo ((is_array($_tmp="possible issue(s):")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

										<div id="<?php echo $this->_tpl_vars['shortname']; ?>
_issue_container" class="<?php if ($this->_tpl_vars['shortname'] == $this->_tpl_vars['client_filter']): ?>issue_container<?php else: ?>adesk_hidden<?php endif; ?>">

											<?php $_from = $this->_tpl_vars['clients'][$this->_tpl_vars['shortname']]['html_result']['issues']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type'] => $this->_tpl_vars['issue']):
?>

												<?php if ($this->_tpl_vars['type'] == 'locations'): ?>

													<?php $_from = $this->_tpl_vars['issue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['location'] => $this->_tpl_vars['issue_']):
?>

														<?php $_from = $this->_tpl_vars['issue_']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['counter_'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['counter_']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['element'] => $this->_tpl_vars['issue__']):
        $this->_foreach['counter_']['iteration']++;
?>

															<div class="issue">
																<b>&lt;<?php echo $this->_tpl_vars['element']; ?>
&gt;</b> <?php echo ((is_array($_tmp='in')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <b>&lt;<?php echo $this->_tpl_vars['location']; ?>
&gt;</b> <?php echo ((is_array($_tmp='present')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp));  if (count($this->_tpl_vars['issue__']['occurrences']) > 1): ?> (<?php echo count($this->_tpl_vars['issue__']['occurrences']); ?>
 <?php echo ((is_array($_tmp='times')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
)<?php endif; ?> (<a href="#" onclick="emailpreview_toggle_issues('<?php echo $this->_tpl_vars['shortname']; ?>
_issues_locations_<?php echo $this->_foreach['counter_']['iteration']; ?>
');return false;"><?php echo ((is_array($_tmp='Details')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>)
															</div>

															<ul id="<?php echo $this->_tpl_vars['shortname']; ?>
_issues_locations_<?php echo $this->_foreach['counter_']['iteration']; ?>
" class="adesk_hidden">
																<div style="position:absolute; margin-top:-5px;"><img src="../awebdesk/media/email-preview-arrow.gif"></div>
																<?php $_from = $this->_tpl_vars['issue__']['occurrences']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['occurrence']):
?>
																	<li><?php echo $this->_tpl_vars['occurrence']['display']; ?>
</li>
																<?php endforeach; endif; unset($_from); ?>
															</ul>

														<?php endforeach; endif; unset($_from); ?>

													<?php endforeach; endif; unset($_from); ?>

												<?php elseif ($this->_tpl_vars['type'] == 'selectors'): ?>

													<?php $_from = $this->_tpl_vars['issue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['counter_'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['counter_']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['selector'] => $this->_tpl_vars['issue_']):
        $this->_foreach['counter_']['iteration']++;
?>

														<?php if ($this->_tpl_vars['selector'] == 'e'): ?>
															<?php $this->assign('selector_display', 'Type'); ?>
														<?php elseif ($this->_tpl_vars['selector'] == "e.className"): ?>
															<?php $this->assign('selector_display', 'Class'); ?>
														<?php elseif ($this->_tpl_vars['selector'] == "e#id"): ?>
															<?php $this->assign('selector_display', 'ID'); ?>
														<?php elseif ($this->_tpl_vars['selector'] == "e:link"): ?>
															<?php $this->assign('selector_display', 'Link'); ?>
														<?php elseif ($this->_tpl_vars['selector'] == "e:active"): ?>
															<?php $this->assign('selector_display', 'Link'); ?>
														<?php elseif ($this->_tpl_vars['selector'] == "e:hover"): ?>
															<?php $this->assign('selector_display', 'Link'); ?>
														<?php elseif ($this->_tpl_vars['selector'] == "e:first-line"): ?>
															<?php $this->assign('selector_display', "first-line"); ?>
														<?php elseif ($this->_tpl_vars['selector'] == "e:first-letter"): ?>
															<?php $this->assign('selector_display', "first-letter"); ?>
														<?php elseif ($this->_tpl_vars['selector'] == "e > f"): ?>
															<?php $this->assign('selector_display', 'Child'); ?>
														<?php elseif ($this->_tpl_vars['selector'] == "e:focus"): ?>
															<?php $this->assign('selector_display', 'focus'); ?>
														<?php elseif ($this->_tpl_vars['selector'] == "e + f"): ?>
															<?php $this->assign('selector_display', 'Adjacent'); ?>
														<?php elseif ($this->_tpl_vars['selector'] == "e[foo]"): ?>
															<?php $this->assign('selector_display', 'Attribute'); ?>
														<?php else: ?>
															<?php $this->assign('selector_display', $this->_tpl_vars['selector']); ?>
														<?php endif; ?>

														<div class="issue">
															<b><?php echo $this->_tpl_vars['selector_display']; ?>
</b> <?php echo ((is_array($_tmp='selector present')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp));  if ($this->_tpl_vars['issue_']['occurrences']['total'] > 1): ?> (<?php echo $this->_tpl_vars['issue_']['occurrences']['total']; ?>
 <?php echo ((is_array($_tmp='times')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
)<?php endif; ?> (<a href="#" onclick="emailpreview_toggle_issues('<?php echo $this->_tpl_vars['shortname']; ?>
_issues_selectors_<?php echo $this->_foreach['counter_']['iteration']; ?>
');return false;"><?php echo ((is_array($_tmp='Details')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>)
														</div>

														<ul id="<?php echo $this->_tpl_vars['shortname']; ?>
_issues_selectors_<?php echo $this->_foreach['counter_']['iteration']; ?>
" class="adesk_hidden">
															<?php $_from = $this->_tpl_vars['issue_']['occurrences']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['source'] => $this->_tpl_vars['source_occurrences']):
?>
																<?php if ($this->_tpl_vars['source'] != 'total'): ?>
																	<?php $_from = $this->_tpl_vars['source_occurrences']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['occurrence']):
?>
																		<?php if ($this->_tpl_vars['source'] == 'style'): ?>
																			<?php $this->assign('source_display', "&lt;style&gt;"); ?>
																		<?php elseif ($this->_tpl_vars['source'] == 'inline'): ?>
																			<?php $this->assign('source_display', 'inline'); ?>
																		<?php else: ?>
																			<?php $this->assign('source_display', "&lt;link&gt;"); ?>
																		<?php endif; ?>
																		<li><?php echo $this->_tpl_vars['occurrence']; ?>
 <span class="emailpreview_source_display">[<?php echo $this->_tpl_vars['source_display']; ?>
]</span></li>
																	<?php endforeach; endif; unset($_from); ?>
																<?php endif; ?>
															<?php endforeach; endif; unset($_from); ?>
														</ul>

													<?php endforeach; endif; unset($_from); ?>

												<?php elseif ($this->_tpl_vars['type'] == 'properties'): ?>

													<?php $_from = $this->_tpl_vars['issue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['counter_'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['counter_']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['property'] => $this->_tpl_vars['issue_']):
        $this->_foreach['counter_']['iteration']++;
?>

														<div class="issue">
															<b><?php echo $this->_tpl_vars['property']; ?>
</b> <?php echo ((is_array($_tmp='property present')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp));  if ($this->_tpl_vars['issue_']['occurrences']['total'] > 1): ?> (<?php echo $this->_tpl_vars['issue_']['occurrences']['total']; ?>
 <?php echo ((is_array($_tmp='times')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
)<?php endif; ?> (<a href="#" onclick="emailpreview_toggle_issues('<?php echo $this->_tpl_vars['shortname']; ?>
_issues_properties_<?php echo $this->_foreach['counter_']['iteration']; ?>
');return false;"><?php echo ((is_array($_tmp='Details')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>)
														</div>

														<ul id="<?php echo $this->_tpl_vars['shortname']; ?>
_issues_properties_<?php echo $this->_foreach['counter_']['iteration']; ?>
" class="adesk_hidden">
															<?php $_from = $this->_tpl_vars['issue_']['occurrences']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['source'] => $this->_tpl_vars['source_occurrences']):
?>
																<?php if ($this->_tpl_vars['source'] != 'total'): ?>
																	<?php $_from = $this->_tpl_vars['source_occurrences']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['occurrence']):
?>
																		<?php if ($this->_tpl_vars['source'] == 'style'): ?>
																			<?php $this->assign('source_display', "&lt;style&gt;"); ?>
																		<?php elseif ($this->_tpl_vars['source'] == 'inline'): ?>
																			<?php $this->assign('source_display', 'inline'); ?>
																																						<?php $this->assign('occurrence', $this->_tpl_vars['occurrence']['content']); ?>
																		<?php else: ?>
																			<?php $this->assign('source_display', "&lt;link&gt;"); ?>
																		<?php endif; ?>
																		<li><?php echo $this->_tpl_vars['occurrence']; ?>
 <span class="emailpreview_source_display">[<?php echo $this->_tpl_vars['source_display']; ?>
]</span></li>
																	<?php endforeach; endif; unset($_from); ?>
																<?php endif; ?>
															<?php endforeach; endif; unset($_from); ?>
														</ul>

													<?php endforeach; endif; unset($_from); ?>

												<?php elseif ($this->_tpl_vars['type'] == 'elements'): ?>

													<?php $_from = $this->_tpl_vars['issue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['counter_'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['counter_']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['element'] => $this->_tpl_vars['requirements']):
        $this->_foreach['counter_']['iteration']++;
?>

														<?php $_from = $this->_tpl_vars['requirements']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['requirement'] => $this->_tpl_vars['info']):
?>

															<div class="issue">
																<b>&lt;<?php echo $this->_tpl_vars['element']; ?>
&gt;</b> <?php echo ((is_array($_tmp="property/value")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <b><?php echo $this->_tpl_vars['requirement']; ?>
</b> <?php echo ((is_array($_tmp='NOT present')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp));  if ($this->_tpl_vars['info']['total'] > 1): ?> (<?php echo $this->_tpl_vars['info']['total']; ?>
 <?php echo ((is_array($_tmp='times')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
)<?php endif; ?> (<a href="#" onclick="emailpreview_toggle_issues('<?php echo $this->_tpl_vars['shortname']; ?>
_issues_elements_<?php echo $this->_foreach['counter_']['iteration']; ?>
');return false;"><?php echo ((is_array($_tmp='Details')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>)
															</div>

															<ul id="<?php echo $this->_tpl_vars['shortname']; ?>
_issues_elements_<?php echo $this->_foreach['counter_']['iteration']; ?>
" class="adesk_hidden">
																<?php $_from = $this->_tpl_vars['info']['occurrences']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['occurrence']):
?>

																	<li style="max-width: 200px;"><?php echo $this->_tpl_vars['occurrence']['element']; ?>
</li>

																<?php endforeach; endif; unset($_from); ?>
															</ul>

														<?php endforeach; endif; unset($_from); ?>

													<?php endforeach; endif; unset($_from); ?>

												<?php endif; ?>

											<?php endforeach; endif; unset($_from); ?>

										</div>

									<?php endif; ?>

								</div>

							<?php endforeach; endif; unset($_from); ?>

						</div>

					</td>

				</tr>

			</table>

		<?php else: ?>

			<div style="margin:40px; background:url(../awebdesk/media/loader.gif); background-repeat:no-repeat; background-position:left; padding-left:20px;"><?php echo ((is_array($_tmp='Please wait while your message is checked')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
...</div>

			<form method="post" id="hiddencampaignform"></form>

			<script type="text/javascript">
			<?php echo '

				if ( typeof window.opener.campaign_obj != \'undefined\' ) {
					//var post = window.opener.campaign_post_prepare();
				} else {
					var post = window.opener.message_emailtest_prepare();
				}
				var rel = $(\'hiddencampaignform\');

				adesk_dom_remove_children(rel);
				addarray2hidden(post, \'\', rel);

				rel.submit();

			'; ?>


			</script>

		<?php endif; ?>

	</div>


<div id="sendfeedback" class="adesk_modal" align="center" style="display:none;">
  <div class="adesk_modal_inner" align="left">
	<h1 style="margin:0px; font-size:16px; margin-bottom:10px;"><?php echo ((is_array($_tmp='Send Us Feedback')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

	<div class="adesk_help_inline" style="font-size:11px; color:#333333;">
 While we strive to make inbox preview as accurate as possible, there may be cases where it is not 100% perfect.  We continually update our inbox preview detection and display based on feedback reports.  
	</div>

	<br />

	<div>
		If you see any inconsistency between what inbox preview shows and what the email client shows please  send us an email to <b>sandeep@awebdesk.com</b> describing the issue(s).
	</div>
	   <input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_dom_display_none('sendfeedback');" />
	 
  </div>
</div>
</body>

</html>