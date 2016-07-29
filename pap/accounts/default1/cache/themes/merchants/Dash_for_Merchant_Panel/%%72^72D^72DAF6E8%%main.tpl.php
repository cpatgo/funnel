<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:31
         compiled from main.tpl */ ?>
<!-- main -->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.stpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table class="Dash_MainTable">
	<tbody>
		<tr>
			<td class="Dash_LeftCell">
				<div class="Dash_LAMenu">
				<?php echo "<div id=\"Menu\"></div>"; ?>
				<?php echo "<div id=\"SystemMenu\" class=\"SystemMenu\"></div>"; ?>
				</div>
			</td>
			<td class="Dash_RightCell">
				<table class="Dash_ContentTable">
					<tbody>
						<tr>
							<td class="Dash_Breadcrumb">
								<?php echo "<div id=\"Breadcrumbs\"></div>"; ?>
							</td>
						</tr>
						<tr>
							<td class="Dash_LAContent">
								<?php echo "<div id=\"MainPanelHeader\"></div>"; ?>
								<?php echo "<div id=\"MessageContainer\"></div>"; ?>
								<div class="Dash_ContentText">
									<?php echo "<div id=\"ScreenContainer\"></div>"; ?>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.stpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>