<?php /* Smarty version 2.6.12, created on 2016-07-08 14:48:21
         compiled from report_campaign_socialsharing.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'report_campaign_socialsharing.inc.htm', 5, false),array('function', 'adesk_headercol', 'report_campaign_socialsharing.inc.htm', 57, false),)), $this); ?>
<div id="socialsharing" class="adesk_hidden">

	<div id="socialsharing_notenabled" style="display: none; border:1px solid #F1DF0A; margin-bottom:10px; font-size:13px; padding:10px; background-color:#FFFDE6;">
		<div style="background:url(../awebdesk/media/sign_warning.png); background-position:left; background-repeat:no-repeat; padding: 5px 0 5px 42px;">
			<?php echo ((is_array($_tmp="Your server does not support cURL. In order to ensure the most accurate Social Sharing reporting, please enable cURL.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		</div>
	</div>

	<div id="socialsharing_webcopy" style="display: none; font-size: 14px; margin-bottom: 10px;"><?php echo $this->_tpl_vars['webcopy_seo']; ?>
</div>

	<div class="startup_box_container">
		<div class="startup_box_container_inner">

		  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td style="font-size:19px;"><span id="socialsharing_twitter_total_t">0</span></td>
				  <td width="10">&nbsp;</td>
				  <td><?php echo ((is_array($_tmp='Twitter Mentions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="socialsharing_twitter_total_p" style="color:#999999; display: none;">(0.00%)</span></td>
				  <td width="15">&nbsp;</td>
				  <td style="font-size:19px;"><span id="socialsharing_facebook_total_t">0</span></td>
				  <td width="10">&nbsp;</td>
				  <td><?php echo ((is_array($_tmp='Facebook Shares')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="socialsharing_facebook_total_p" style="color:#999999; display: none;">(0.00%)</span></td>
				</tr>
		  </table></div>

		</div>
	</div>

	<br />

	<form action="desk.php?action=report_campaign" method="GET" onsubmit="socialsharing_list_search(); return false">
		<?php if (isset ( $_GET['print'] ) && $_GET['print'] == 1): ?> <div style="display:none"> <?php endif; ?>
		<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellspacing="0" cellpadding="0" width="100%">
			<tr class="adesk_table_header_options">
			  <td style="border-right: none;">
					<select name="socialsharing_filter_source" id="socialsharing_filter_source" size="1" onchange="socialsharing_toggle(this.value);">
					  <option value="all" selected="selected"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
					  <option value="facebook"><?php echo ((is_array($_tmp='Facebook')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
					  <option value="twitter"><?php echo ((is_array($_tmp='Twitter')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
					</select>
			  </td>
				<td align="right" style="border-left: none;">
					<div>
					<input type="text" name="qsearch" id="socialsharing_search" />
					<input type="button" value='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="socialsharing_list_search()" />
					<input type="button" value='<?php echo ((is_array($_tmp='Clear')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' id="socialsharing_clear" style="display:none" onclick="socialsharing_list_clear()" />
					</div>
				</td>
			</tr>
		</table></div>
		<?php if (isset ( $_GET['print'] ) && $_GET['print'] == 1): ?> </div> <?php endif; ?>
		<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  width="100%" border="0" cellspacing="0" cellpadding="1">
			<thead id="socialsharing_head">
				<tr class="adesk_table_header">
					<td width="55">&nbsp;</td>
					<td><?php echo ((is_array($_tmp='Content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
					<td width="100"><?php echo smarty_function_adesk_headercol(array('action' => 'socialsharing','idprefix' => 'socialsharing_list_sorter','id' => '01','label' => ((is_array($_tmp='Published')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
				</tr>
			</thead>
			<tbody id="socialsharing_table">
			</tbody>
			<tbody id="socialsharing_table_facebook_external">
				<tr class="adesk_table_row">
					<td height="40"></td>
					<td style="font-size:12px;">
						<span id="facebook_external_total">0</span>
						<span id="facebook_external_total_people"><?php echo ((is_array($_tmp='people')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
						<span id="facebook_external_total_person"><?php echo ((is_array($_tmp='person')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
						<?php echo ((is_array($_tmp='that')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

						<span id="facebook_external_total_are"><?php echo ((is_array($_tmp='are')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
						<span id="facebook_external_total_is"><?php echo ((is_array($_tmp='is')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
						<?php echo ((is_array($_tmp='not subscribed to your list shared this campaign on Facebook')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
.
					</td>
					<td></td>
				</tr>
			</tbody>
		</table></div>
		<div id="socialsharing_noresults" class="adesk_hidden">
		<div align="center"><?php echo ((is_array($_tmp="Nothing found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		</div>
		<div style="float:right">
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.js.tpl.htm", 'smarty_include_vars' => array('paginator' => $this->_tpl_vars['paginator_socialsharing'],'tabelize' => 'socialsharing_tabelize','paginate' => 'socialsharing_paginate')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</div>
		<div id="socialsharing_loadingBar" class="adesk_hidden" style="background: url(../awebdesk/media/loader.gif); background-repeat: no-repeat; padding: 5px; padding-left: 20px; padding-top: 2px; color: #999999; font-size: 10px; margin: 5px">
			<?php echo ((is_array($_tmp="Loading. Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		</div>
	</form>

</div>