<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:51
         compiled from settings.delivery.abuse.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'settings.delivery.abuse.htm', 3, false),array('function', 'adesk_sortcol', 'settings.delivery.abuse.htm', 27, false),)), $this); ?>


        <div style="background:#F3F3F0; padding:5px; padding-left:10px;"><?php echo ((is_array($_tmp='Automatic Abuse Management')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
	<div  style="padding:10px; border: 1px solid #E0DFDC; margin-bottom:20px;">
          <input type="checkbox" id="mail_abuse" name="mail_abuse" value="1" <?php if ($this->_tpl_vars['site']['mail_abuse']): ?>checked="checked"<?php endif; ?> onclick="adesk_dom_toggle_class('mail_abuse_help', 'adesk_block', 'adesk_hidden');" /> <label for="mail_abuse">
            <?php echo ((is_array($_tmp='Enable Abuse Management')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

          </label>
 
		
        <div id="mail_abuse_help" class="<?php if ($this->_tpl_vars['site']['mail_abuse']): ?>adesk_block<?php else: ?>adesk_hidden<?php endif; ?>">
        <br />

          <?php echo ((is_array($_tmp="The report abuse link can be added to each user groups mandatory footer (thus adding it to all their outgoing emails).")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

          <?php echo ((is_array($_tmp="When clicked it will verify that they wish to report abuse and unsubscribe them from the list if they choose to continue with reporting abuse.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

          <br />
          <br />
          <?php echo ((is_array($_tmp="To include a report abuse link add the following:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

          <br />
          &lt;a href=&quot;%REPORTABUSE%&quot;&gt;<?php echo ((is_array($_tmp='Click here to report this email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
&lt;/a&gt;
		<br /><br />


		  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  width="100%" border="0" cellspacing="0" cellpadding="1">
			<thead id="abuselist_head">
			  <tr class="adesk_table_header">
				<td width="50"><?php echo ((is_array($_tmp='Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td><?php echo smarty_function_adesk_sortcol(array('action' => 'group','id' => '01','label' => ((is_array($_tmp='Group')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
				<td><?php echo ((is_array($_tmp="# Abuses")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td><?php echo ((is_array($_tmp="% Of Abuses")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
			  </tr>
			</thead>
			<tbody id="abuselist_table">
			</tbody>
			<tbody id="abuselist_noresults" style="display:none">
			  <tr>
				<td colspan="4" align="center">
				  <div><?php echo ((is_array($_tmp="Nothing found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				</td>
			  </tr>
			</tbody>
			<tfoot>
			  <td colspan="4" align="left">
				<div id="abuseloadingBar" style="display:none; background: url(../awebdesk/media/loader.gif); background-repeat: no-repeat; padding: 5px; padding-left: 20px; padding-top: 2px; color: #999999; font-size: 10px; margin: 5px">
				  <?php echo ((is_array($_tmp="Loading. Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				</div>
			  </td>
			</tfoot>
		  </table></div>
		  <div style="float:right;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.js.tpl.htm", 'smarty_include_vars' => array('tabelize' => 'group_list_tabelize','paginate' => 'group_list_paginate','paginator' => $this->_tpl_vars['paginator_abuse'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
		  <br clear="all" />
		</div>
		</div>