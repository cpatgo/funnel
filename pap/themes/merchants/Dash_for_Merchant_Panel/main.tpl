<!-- main -->
{include file='header.stpl'}
<table class="Dash_MainTable">
	<tbody>
		<tr>
			<td class="Dash_LeftCell">
				<div class="Dash_LAMenu">
				{widget id="Menu"}
				{widget id="SystemMenu" class="SystemMenu"}
				</div>
			</td>
			<td class="Dash_RightCell">
				<table class="Dash_ContentTable">
					<tbody>
						<tr>
							<td class="Dash_Breadcrumb">
								{widget id="Breadcrumbs"}
							</td>
						</tr>
						<tr>
							<td class="Dash_LAContent">
								{widget id="MainPanelHeader"}
								{widget id="MessageContainer"}
								<div class="Dash_ContentText">
									{widget id="ScreenContainer"}
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
{include file='footer.stpl'}
