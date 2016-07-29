<?php /* Smarty version 2.6.12, created on 2016-07-08 14:48:21
         compiled from report_campaign.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'report_campaign.htm', 1, false),array('modifier', 'alang', 'report_campaign.htm', 12, false),array('modifier', 'truncate', 'report_campaign.htm', 58, false),array('modifier', 'acpdate', 'report_campaign.htm', 185, false),array('function', 'adesk_js', 'report_campaign.htm', 3, false),array('function', 'adesk_amchart', 'report_campaign.htm', 98, false),)), $this); ?>
<?php $this->assign('hash', ((is_array($_tmp=@$_GET['hash'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, ''))); ?>

<?php echo smarty_function_adesk_js(array('lib' => "really/simplehistory.js"), $this);?>

<script type="text/javascript">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "report_campaign.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  var report_campaign_id = '<?php echo ((is_array($_tmp=@$_GET['id'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
';
</script>

<?php if (! isset ( $_GET['print'] ) || $_GET['print'] == 0): ?>
<div style="float:right;">
  <div id="exportbutton" style="float:right; padding:5px; margin-top:-5px; font-weight:bold; text-decoration:underline;">
	<a href="#" onclick="report_campaign_export(); return false"><?php echo ((is_array($_tmp='Export')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
  </div>
  <?php if (! $this->_tpl_vars['isShared'] && $this->_tpl_vars['logFile']): ?>
  <div id="logbutton" style="float:right; padding:5px; margin-top:-5px; font-weight:bold; text-decoration:underline; margin-left:10px;">
	<a href="../cache/campaign-<?php echo $this->_tpl_vars['campaign']['id']; ?>
.log" target="_blank"><?php echo ((is_array($_tmp='Log File')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
  </div>
  <?php endif; ?>
  <?php if (! isset ( $this->_tpl_vars['usesharelink'] ) || $this->_tpl_vars['usesharelink'] == 1): ?>
  <div id="sharebutton" style="float:right; padding:5px; margin-top:-5px; font-weight:bold; text-decoration:underline; margin-left:10px;">
	<a href="#share"><?php echo ((is_array($_tmp='Share Report')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
  </div>
  <?php endif; ?>
  <div id="socialbutton" style="float:right; padding:5px; margin-top:-5px; font-weight:bold; text-decoration:underline; margin-left:10px;">
	<a href="#social"><?php echo ((is_array($_tmp='Social Share')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
  </div>
  <?php if (! isset ( $this->_tpl_vars['useresendlink'] ) || $this->_tpl_vars['useresendlink'] == 1): ?>
  <div id="resendbutton" style="float:right; padding:5px; margin-top:-5px; font-weight:bold; text-decoration:underline; margin-left:10px;">
	<a href="desk.php?action=campaign_new&copyid=<?php echo ((is_array($_tmp=@$this->_tpl_vars['campaign']['id'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
"><?php echo ((is_array($_tmp='Resend')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
  </div>
  <?php endif; ?>
  <div id="printbutton" style="float:right; padding:5px; margin-top:-5px; font-weight:bold; text-decoration:underline; margin-left:10px;">
	<a href="#" onclick="report_campaign_print(); return false"><?php echo ((is_array($_tmp='Print')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
  </div>
  <?php if ($this->_tpl_vars['campaign']['status'] == 1): ?>  <div id="editbutton" style="float:right; padding:5px; margin-top:-5px; font-weight:bold; text-decoration:underline; margin-left:10px;">
	<a href="desk.php?action=campaign_new&campaignid=<?php echo ((is_array($_tmp=@$this->_tpl_vars['campaign']['id'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
"><?php echo ((is_array($_tmp='Edit Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
  </div>
  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['messages'] ) && count ( $this->_tpl_vars['messages'] ) > 1): ?>
  <div align="right" style="margin-left:5px;">
	<select name="messageid" id="messageid" onchange="report_campaign_messagefilter(this.value)" style="font-size:10px;">
	  <option value="0"><?php echo ((is_array($_tmp='Filter Split Test Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
	  <?php $_from = $this->_tpl_vars['messages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['m']):
?>
	  <option value="<?php echo $this->_tpl_vars['m']['messageid']; ?>
"><?php echo $this->_tpl_vars['m']['subject']; ?>
</option>
	  <?php endforeach; endif; unset($_from); ?>
	</select>
  </div>
  <?php else: ?>
  <input type="hidden" name="messageid" id="messageid" value="0"/>
  <?php endif; ?>

</div>
<?php else: ?>
<input type="hidden" name="messageid" id="messageid" value="0"/>
<?php endif; ?>

<h1 style="<?php echo $this->_tpl_vars['h1_style']; ?>
"><?php echo ((is_array($_tmp="Campaign Report:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['campaign']['name'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('truncate', true, $_tmp, 60) : smarty_modifier_truncate($_tmp, 60)); ?>
</h3>
<?php if (! isset ( $_GET['print'] ) || $_GET['print'] == 0): ?>
<div style="margin-bottom:10px; margin-top:-4px; color:#999999;"><?php echo ((is_array($_tmp='Campaign Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 &gt; <?php echo ((is_array($_tmp='Campaign Report')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
<?php endif; ?>

<?php if (isset ( $_GET['print'] ) && $_GET['print'] == 1): ?>
<div style="display:none">
  <?php endif; ?>

  <ul id="tablist" class="navlist">
	<li id="main_tab_general" class="currenttab"><a href="#general-01-0-0-0"><?php echo ((is_array($_tmp='Overview')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<span id="count_tab_general">&nbsp;</span></a></li>
	<li id="main_tab_message" class="othertab"  ><a href="#message-01-0-0-0"><?php echo ((is_array($_tmp='Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<span id="count_tab_message">&nbsp;</span></a></li>
	<li id="main_tab_open" class="othertab"     ><a href="#open-01-0-0-0"   ><?php echo ((is_array($_tmp='Opens')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="count_tab_open">(0)</span></a></li>
	<li id="main_tab_link" class="othertab"     ><a href="#link-01-0-0-0"   ><?php echo ((is_array($_tmp='Links')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="count_tab_link">(0)</span></a></li>
	<li id="main_tab_forward" class="othertab"  ><a href="#forward-01-0-0-0"><?php echo ((is_array($_tmp='Forwards')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="count_tab_forward">(0)</span></a></li>
	<li id="main_tab_bounce" class="othertab"   ><a href="#bounce-01-0-0-0" ><?php echo ((is_array($_tmp='Bounces')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="count_tab_bounce">(0)</span></a></li>
	<li id="main_tab_unsub" class="othertab"    ><a href="#unsub-01-0-0-0"  ><?php echo ((is_array($_tmp='Unsubscriptions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="count_tab_unsub">(0)</span></a></li>
	<li id="main_tab_update" class="othertab"   ><a href="#update-01-0-0-0" ><?php echo ((is_array($_tmp='Updates')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
  <span id="count_tab_update">(0)</span></a></li>
	<li id="main_tab_socialsharing" class="othertab"   ><a href="#socialsharing-01-0-0-0" ><?php echo ((is_array($_tmp='Social Sharing')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
  <span id="count_tab_socialsharing">(0)</span></a></li>
  </ul>
  <?php if (isset ( $_GET['print'] ) && $_GET['print'] == 1): ?>
</div>
<?php endif; ?>

<br />
<div id="general" class="adesk_hidden">

  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
	  <td class="startup_box_container">
		<div>
		  <div class="startup_box_title">
			<span id="general_readlabel_date" class="startup_selected"><a href="#" onclick="report_campaign_showdiv_general('chart_read_bydate', 'general_readlabel_date')"><?php echo ((is_array($_tmp='Daily Read Trend')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></span>
			<span id="general_linklabel_date"><a href="#" onclick="report_campaign_showdiv_general('chart_link_bydate', 'general_linklabel_date')"><?php echo ((is_array($_tmp='Daily Link Trend')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></span>
			<span id="general_readlabel_hour"><a href="#" onclick="report_campaign_showdiv_general('chart_read_byhour', 'general_readlabel_hour')"><?php echo ((is_array($_tmp='Hourly Open Trend')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></span>
			<span id="general_linklabel_hour"><a href="#" onclick="report_campaign_showdiv_general('chart_link_byhour', 'general_linklabel_hour')"><?php echo ((is_array($_tmp='Hourly Link Trend')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></span>
		  </div>

		  <div class="startup_box_container_inner">
			<script type="text/javascript" src="../awebdesk/amline/swfobject.js"></script>
			<?php echo smarty_function_adesk_amchart(array('type' => 'line','divid' => 'chart_read_bydate','location' => 'admin','url' => "graph.php?g=read_bydate&id=".($this->_tpl_vars['campaign']['id'])."&hash=".($this->_tpl_vars['hash']),'width' => "100%",'height' => '175','bgcolor' => "#FFFFFF"), $this);?>

			<?php echo smarty_function_adesk_amchart(array('type' => 'line','divid' => 'chart_read_byhour','location' => 'admin','url' => "graph.php?g=read_byhour&campaignid=".($this->_tpl_vars['campaign']['id'])."&hash=".($this->_tpl_vars['hash']),'width' => "100%",'height' => '175','bgcolor' => "#FFFFFF",'display' => false), $this);?>

			<?php echo smarty_function_adesk_amchart(array('type' => 'line','divid' => 'chart_link_bydate','location' => 'admin','url' => "graph.php?g=link_bydate&id=".($this->_tpl_vars['campaign']['id'])."&hash=".($this->_tpl_vars['hash']),'width' => "100%",'height' => '175','bgcolor' => "#FFFFFF",'display' => false), $this);?>

			<?php echo smarty_function_adesk_amchart(array('type' => 'line','divid' => 'chart_link_byhour','location' => 'admin','url' => "graph.php?g=link_byhour&campaignid=".($this->_tpl_vars['campaign']['id'])."&hash=".($this->_tpl_vars['hash']),'width' => "100%",'height' => '175','bgcolor' => "#FFFFFF",'display' => false), $this);?>

		  </div>
		</div>
	  </td>
	</tr>
  </table></div>

  <br />

  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
	  <td width="59%" valign="top" class="startup_box_container">
		<div class="startup_box_title">
		  <span class="startup_selected"><?php echo ((is_array($_tmp='Emails')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
		</div>
		<?php echo smarty_function_adesk_amchart(array('type' => 'pie','divid' => 'chart_open_pie','location' => 'admin','url' => "graph.php?g=open_pie&id=".($this->_tpl_vars['campaign']['id'])."&hash=".($this->_tpl_vars['hash']),'width' => "100%",'height' => '240','bgcolor' => "#FFFFFF"), $this);?>

	  </td>
	  <td width="2%">&nbsp;

	  </td>
	  <td width="39%" valign="top" class="startup_box_container">
		<div class="startup_box_title">
		  <span class="startup_selected"><?php echo ((is_array($_tmp='Overview')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
		  <span id="campaign_overview_details_link" style="color:#999999;">(<a href="#" onclick="adesk_dom_toggle_class('campaign_overview_details_box', 'adesk_block', 'adesk_hidden');return false;" style="border:none; background:none; padding:0px; margin:0px;"><?php echo ((is_array($_tmp='Show all details')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>)</span>
		</div>
		<div class="startup_box_container_inner" align="center">

		  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="0" cellspacing="0" border="0">
			<tr>
			  <td height="30" align="left" style="font-size:19px;"><span id="general_total_t">0</span></td>
			  <td width="10" align="left">&nbsp;</td>
			  <td align="left"><?php echo ((is_array($_tmp='Recipients')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
			</tr>
			<tr>
			  <td height="30" align="left" style="font-size:19px;"><span id="general_success_t">0</span></td>
			  <td width="10" align="left">&nbsp;</td>
			  <td align="left"><?php echo ((is_array($_tmp='Successfully Sent')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
			</tr>
			<tr>
			  <td height="30" align="left" style="font-size:19px;"><span id="general_open_p">0.00%</span></td>
			  <td width="10" align="left">&nbsp;</td>
			  <td align="left"><a href="#open-01-0-0-0"><?php echo ((is_array($_tmp='Opened This Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> (<span id="general_open_t">0</span>)</td>
			</tr>
			<tr>
			  <td height="30" align="left" style="font-size:19px;"><span id="general_link_p">0.00%</span></td>
			  <td width="10" align="left">&nbsp;</td>
			  <td align="left"><a href="#link-01-0-0-0"><?php echo ((is_array($_tmp='Clicked A Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> (<span id="general_link_t">0</span>)</td>
			</tr>
			<tr>
			  <td height="30" align="left" style="font-size:19px;"><span id="general_unsub_p">0.00%</span></td>
			  <td width="10" align="left">&nbsp;</td>
			  <td align="left"><a href="#unsub-01-0-0-0"><?php echo ((is_array($_tmp='Unsubscribed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> (<span id="general_unsub_t">0</span>)</td>
			</tr>
			<tr>
			  <td height="30" align="left" style="font-size:19px;"><span id="general_forward_p">0.00%</span></td>
			  <td width="10" align="left">&nbsp;</td>
			  <td align="left"><a href="#forward-01-0-0-0"><?php echo ((is_array($_tmp='Forwarded')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> (<span id="general_forward_t">0</span>)</td>
			</tr>
			<tr>
			  <td height="30" align="left" style="font-size:19px;"><span id="general_update_p">0.00%</span></td>
			  <td width="10" align="left">&nbsp;</td>
			  <td align="left"><a href="#update-01-0-0-0"><?php echo ((is_array($_tmp='Updated')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> (<span id="general_update_t">0</span>)</td>
			</tr>
			<tr>
			  <td height="30" align="left" style="font-size:19px;"><span id="general_bounce_p">0.00%</span></td>
			  <td width="10" align="left">&nbsp;</td>
			  <td align="left"><a href="#bounce-01-0-0-0"><?php echo ((is_array($_tmp='Bounced')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> (<span id="general_bounce_t">0</span>)</td>
			</tr>
		  </table></div>
		  <div id="campaign_overview_details_box" class="adesk_hidden" style="border-top:1px solid #CCCCCC; margin-top:5px; padding-top:5px;">
		  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
			  <td>&nbsp;</td>
			  <td width="120" align="left"><?php echo ((is_array($_tmp="Type:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
			  <td align="left"><span id="general_type_t"><?php echo $this->_tpl_vars['type_array'][$this->_tpl_vars['campaign']['type']]; ?>
</span></td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			  <td width="120" align="left"><?php echo ((is_array($_tmp="Status:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
			  <td align="left"><span id="general_status_t"><?php echo $this->_tpl_vars['status_array'][$this->_tpl_vars['campaign']['status']]; ?>
</span></td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			  <td width="120" align="left"><?php echo ((is_array($_tmp="Send Date/Time:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
			  <td align="left"><span id="general_sdate_t"><?php echo ((is_array($_tmp=$this->_tpl_vars['campaign']['sdate'])) ? $this->_run_mod_handler('acpdate', true, $_tmp, $this->_tpl_vars['site']['datetimeformat']) : smarty_modifier_acpdate($_tmp, $this->_tpl_vars['site']['datetimeformat'])); ?>
</span></td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			  <td width="120" align="left"><?php echo ((is_array($_tmp="Completion Date/Time:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
			  <td align="left"><span id="general_ldate_t"><?php echo ((is_array($_tmp=$this->_tpl_vars['campaign']['ldate'])) ? $this->_run_mod_handler('acpdate', true, $_tmp, $this->_tpl_vars['site']['datetimeformat']) : smarty_modifier_acpdate($_tmp, $this->_tpl_vars['site']['datetimeformat'])); ?>
</span></td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			  <td width="120" align="left"><?php echo ((is_array($_tmp="List(s):")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
			  <td align="left"><span id="general_lists_t"><?php $_from = $this->_tpl_vars['campaign']['lists']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['listloop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['listloop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['l']):
        $this->_foreach['listloop']['iteration']++;
 echo $this->_tpl_vars['l']['name'];  if (! ($this->_foreach['listloop']['iteration'] == $this->_foreach['listloop']['total'])): ?>, <?php endif;  endforeach; endif; unset($_from); ?></span></td>
			</tr>
<?php if ($this->_tpl_vars['campaign']['filterid']): ?>
			<tr>
			  <td>&nbsp;</td>
			  <td width="120" align="left"><?php echo ((is_array($_tmp="Segment:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
			  <td align="left"><span id="general_segment_t"><?php echo $this->_tpl_vars['campaign']['filter']['name']; ?>
</span></td>
			</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['senduser']): ?>
			<tr>
			  <td>&nbsp;</td>
			  <td width="120" align="left"><?php echo ((is_array($_tmp="Sender:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
			  <td align="left"><span id="general_sender_t"><?php echo $this->_tpl_vars['senduser']['fullname']; ?>
 (<?php echo $this->_tpl_vars['senduser']['username']; ?>
)</span></td>
			</tr>
<?php endif; ?>
		  </table></div>
		  </div>
		</div>
	  </td>
	</tr>
  </table></div>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "report_campaign_message.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "report_campaign_open.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "report_campaign_forward.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "report_campaign_bounce.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "report_campaign_unsub.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "report_campaign_unopen.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "report_campaign_link.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "report_campaign_linkinfo.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "report_campaign_share.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "report_campaign_social.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "report_campaign_update.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "report_campaign_socialsharing.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "spamcheck.inc.js", 'smarty_include_vars' => array('mode' => 'report')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "spamcheck.inc.htm", 'smarty_include_vars' => array('mode' => 'report')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
  adesk_ui_rsh_init(report_campaign_process, true);
</script>