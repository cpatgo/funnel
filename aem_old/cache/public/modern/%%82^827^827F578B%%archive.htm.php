<?php /* Smarty version 2.6.12, created on 2016-07-20 15:27:10
         compiled from archive.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'adesk_js', 'archive.htm', 1, false),array('function', 'adesk_headercol', 'archive.htm', 42, false),array('modifier', 'plang', 'archive.htm', 8, false),)), $this); ?>
<?php echo smarty_function_adesk_js(array('base' => $this->_tpl_vars['_'],'lib' => "really/simplehistory.js"), $this);?>

<script type="text/javascript">
<!--
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "archive.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
-->
</script>

<h3><?php echo ((is_array($_tmp='Archive')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</h3>


	<div id="list">

		<?php if (! $this->_tpl_vars['listid']): ?>

			<script type="text/javascript">
			<!--
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "archive.list.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			-->
			</script>

			<input type="hidden" name="filterid" id="filterid" value="<?php echo $this->_tpl_vars['filterid']; ?>
" />

			<div style="float: right; padding-top:1px; padding-left:10px;">
				<a href="<?php echo $this->_tpl_vars['_']; ?>
/index.php?action=archive&nl=0&rss=1"><img src="<?php echo $this->_tpl_vars['_']; ?>
/images/feed.gif" border="0" /></a>
			</div>

			<div style="float: right;">
				<input type="text" name="qsearch" id="list_search" onkeypress="adesk_dom_keypress_doif(event, 13, archive_list_search);" />
				<input type="button" value='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
' onclick="archive_list_search()" />
				<input type="button" value='<?php echo ((is_array($_tmp='Clear')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
' id="list_clear" style="display:none" onclick="archive_list_clear()" />
			</div>

			<h4><?php echo ((is_array($_tmp='Select a List')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</h4>

<div class="table-responsive">

			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table table-striped b-t text-small">

				<thead id="list_head" class="adesk_table_header">
					<tr class="adesk_table_row">
						<td width="500"><?php echo smarty_function_adesk_headercol(array('action' => 'archive','id' => '01','label' => ((is_array($_tmp='List Name')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp))), $this);?>
</td>
						<td><?php echo smarty_function_adesk_headercol(array('action' => 'archive','id' => '03','label' => ((is_array($_tmp='Messages')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp))), $this);?>
</td>
					</tr>
				</thead>

				<tbody id="archive_list_list"></tbody>

				<tbody id="archive_list_noresults" class="adesk_hidden">
					<tr>
						<td colspan="2" align="center">No results</td>
					</tr>
				</tbody>

			</table>
</div>
			<div id="archive_list_paginator">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.js.tpl.htm", 'smarty_include_vars' => array('tabelize' => 'archive_list_tabelize','paginate' => 'archive_list_paginate')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			</div>

			<script type="text/javascript">
			<?php echo '
			  /*if (paginators[1] !== undefined){
					paginators[1].paginate(0);
			  }*/
			'; ?>

			</script>

		<?php else: ?>

			<script type="text/javascript">
			<!--
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "archive.campaign.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			-->
			</script>

			<div style="float: right; padding-top:1px; padding-left:10px;">
				<a href="<?php echo $this->_tpl_vars['_']; ?>
/index.php?action=archive&nl=<?php echo $this->_tpl_vars['listid']; ?>
&rss=1"><img src="<?php echo $this->_tpl_vars['_']; ?>
/images/feed.gif" border="0" /></a>
			</div>

			<div style="float: right;">
				<input type="text" name="qsearch" id="list_search" onkeypress="adesk_dom_keypress_doif(event, 13, archive_campaign_search);" />
				<input type="button" value='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
' onclick="archive_campaign_search()" />
				<input type="button" value='<?php echo ((is_array($_tmp='Clear')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
' id="list_clear" style="display:none" onclick="archive_campaign_clear()" />
			</div>

			<div style="float: right; margin-right: 15px;">
				<input type="button" value='<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
' id="list_back" onclick="archive_campaign_back()" />
			</div>

			<h4><?php echo ((is_array($_tmp='Select a Campaign from List ')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
'<?php echo ((is_array($_tmp=$this->_tpl_vars['list']['name'])) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
'</h4>

			<input type="hidden" name="listid" id="listid" value="<?php echo $this->_tpl_vars['listid']; ?>
" />
			<input type="hidden" name="filterid" id="filterid" value="<?php echo $this->_tpl_vars['filterid']; ?>
" />


<div class="table-responsive">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="table table-striped b-t text-small">

				<thead id="list_head">
					<tr class="adesk_table_header">
						<td width="500"><?php echo smarty_function_adesk_headercol(array('action' => 'archive','id' => '07','label' => ((is_array($_tmp='Subject')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp))), $this);?>
</td>
						<td><?php echo smarty_function_adesk_headercol(array('action' => 'archive','id' => '06','label' => ((is_array($_tmp='Create Date')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp))), $this);?>
</td>
					</tr>
				</thead>

				<tbody id="archive_campaign_list"></tbody>

				<tbody id="archive_campaign_noresults" class="adesk_hidden">
					<tr class="adesk_table_row">
						<td colspan="2" align="center">No results</td>
					</tr>
				</tbody>

			</table>
</div>
			<div id="archive_campaign_paginator">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.js.tpl.htm", 'smarty_include_vars' => array('tabelize' => 'archive_campaign_tabelize','paginate' => 'archive_campaign_paginate')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			</div>

			<script type="text/javascript">
			<?php echo '
			  /*if (paginators[1] !== undefined){
					paginators[1].paginate(0);
			  }*/
			'; ?>

			</script>

		<?php endif; ?>

		<script type="text/javascript">
		  adesk_ui_rsh_init(archive_process, true);
		</script>

	</div>
