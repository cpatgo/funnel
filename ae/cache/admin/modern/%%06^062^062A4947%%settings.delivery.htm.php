<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:51
         compiled from settings.delivery.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'settings.delivery.htm', 3, false),array('modifier', 'adesk_ischecked', 'settings.delivery.htm', 9, false),array('modifier', 'acpdate', 'settings.delivery.htm', 79, false),)), $this); ?>
<?php if ($this->_tpl_vars['__ishosted']): ?>
<div id="settings_admin" style="margin-top: 10px">
  <h5><?php echo ((is_array($_tmp='Deliverability Tools')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
  <div class="adesk_blockquote">

  <div style="background:#F3F3F0; padding:5px; padding-left:10px;"><?php echo ((is_array($_tmp='Improve Deliverability')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
    <div style="padding:10px; border: 1px solid #E0DFDC; margin-bottom:20px;">
	  <label>
        <input type="checkbox" name="onbehalfof" id="onbehalfof" value="1" <?php echo ((is_array($_tmp=$this->_tpl_vars['site']['onbehalfof'])) ? $this->_run_mod_handler('adesk_ischecked', true, $_tmp) : smarty_modifier_adesk_ischecked($_tmp)); ?>
 onclick="if(!this.checked)$('onbehalfof_notify').show();else $('onbehalfof_notify').hide();" />
        <?php echo ((is_array($_tmp='Authenticate all outgoing emails')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        <span id="onbehalfof_notify" style="font-weight:bold; color:red;<?php if ($this->_tpl_vars['site']['onbehalfof']): ?>display:none;<?php endif; ?>">(Enable this to improve your deliverability)</span>
	  </label>
	  <br />
      <div style="margin-left:23px;"><?php echo ((is_array($_tmp="This will add a \"Sent on behalf of\" header to all outgoing emails.  Most of your subscribers will not see this and will be entirely unaware of this unless they inspect the email source code.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
    </div>
  </div>

  </div>
</div>

<?php else: ?>

<div id="settings_admin" style="margin-top: 10px">
<h5><?php echo ((is_array($_tmp='Deliverability Tools')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
<div class="adesk_blockquote">

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "settings.delivery.abuse.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "settings.delivery.feedbackloop.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "settings.delivery.spf.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

</div>
</div>


<div id="settings_delivery_viewabuse" class="adesk_modal" style="display:none">
  <div class="adesk_modal_inner">
	<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  width="100%" border="0" cellspacing="0" cellpadding="1">
	  <thead id="viewlist_head">
		<tr class="adesk_table_header">
		  <td width="150"><?php echo ((is_array($_tmp='Date')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><?php echo ((is_array($_tmp='Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		</tr>
	  </thead>
	  <tbody id="viewlist_table">
	  </tbody>
	  <tbody id="viewlist_noresults" style="display:none">
		<tr>
		  <td colspan="2" align="center">
			<div><?php echo ((is_array($_tmp="Nothing found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		  </td>
		</tr>
	  </tbody>
	  <tfoot>
		<td colspan="2" align="left">
		  <div id="viewloadingBar" style="display:none" style="background: url(../awebdesk/media/loader.gif); background-repeat: no-repeat; padding: 5px; padding-left: 20px; padding-top: 2px; color: #999999; font-size: 10px; margin: 5px">
			<?php echo ((is_array($_tmp="Loading. Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		  </div>
		</td>
	  </tfoot>
	</table></div>
	<br />
	<input type="button" value='<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="$('settings_delivery_viewabuse').hide()">
  </div>
</div>

<div id="settings_delivery_viewfbl" class="adesk_modal" style="display:none">
  <div class="adesk_modal_inner">
    <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  width="100%" border="0" cellspacing="0" cellpadding="1">
	  <thead id="fbllist_head">
	    <tr class="adesk_table_header">
		  <td><?php echo ((is_array($_tmp='Date')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><?php echo ((is_array($_tmp='Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><?php echo ((is_array($_tmp='Subscriber')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
	    </tr>
	  </thead>
	  <tbody id="fbllist_table">
<?php $_from = $this->_tpl_vars['feedbackloops']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['fbl']):
?>
	    <tr>
	      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['fbl']['tstamp'])) ? $this->_run_mod_handler('acpdate', true, $_tmp, $this->_tpl_vars['site']['datetimeformat']) : smarty_modifier_acpdate($_tmp, $this->_tpl_vars['site']['datetimeformat'])); ?>
</td>
	      <td><a href="desk.php?action=report_campaign&id=<?php echo $this->_tpl_vars['fbl']['campaignid']; ?>
"><?php echo $this->_tpl_vars['fbl']['name']; ?>
</a></td>
	      <td><a href="desk.php?action=subscriber_view&id=<?php echo $this->_tpl_vars['fbl']['subscriberid']; ?>
"><?php echo $this->_tpl_vars['fbl']['email']; ?>
</a></td>
	    </tr>
<?php endforeach; endif; unset($_from); ?>
	  </tbody>
    </table></div>
	<br />
	<input type="button" value='<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="$('settings_delivery_viewfbl').hide()">
  </div>
</div>

<script type="text/javascript">
  paginators[1].paginate(0);
</script>
<?php endif; ?>
