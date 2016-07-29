<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:51
         compiled from settings.mailsending.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'settings.mailsending.htm', 2, false),)), $this); ?>
<div id="settings_mailsending" style="margin-top: 10px; <?php if ($this->_tpl_vars['__ishosted']): ?>display:none<?php endif; ?>">
  <h5><?php echo ((is_array($_tmp='Mail Sending')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
  <div  class="adesk_blockquote">

  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="5" cellspacing="0" width="100%">
    <tr>
      <td width="110"><?php echo ((is_array($_tmp='Sending order')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
      <td>
        <select name="sdord" id="sdord" size="1" onChange="checkSel(this);">
          <option value="rand"<?php if ($this->_tpl_vars['site']['sdord'] == 'rand'): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp='Randomize subscribers with each send')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="asc"<?php if ($this->_tpl_vars['site']['sdord'] == 'asc'): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp='Send from oldest subscriber to newest subscriber')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="desc"<?php if ($this->_tpl_vars['site']['sdord'] == 'desc'): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp='Send from newest subscriber to oldest subscriber')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
        </select>
      </td>
    </tr>
  </table></div>
  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellpadding="5" cellspacing="0" width="100%">
<?php if (! $this->_tpl_vars['__ishosted']): ?>
    <tr>
      <td>
  <div class="adesk_help_inline"><?php echo ((is_array($_tmp="Here you can:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <?php echo ((is_array($_tmp='Set maximum emails to send in  a specific time period per sending method')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
, <?php echo ((is_array($_tmp='Throttle your email sending per mail sending method')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
, & <?php echo ((is_array($_tmp="Setup multiple sending methods &amp; setup rotating options")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
.</div>
  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  width="100%" border="0" cellspacing="0" cellpadding="1">
	<thead id="mailer_list_head">
	  <tr class="adesk_table_header">
		<td width="80"><?php echo ((is_array($_tmp='Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='Mailer')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='Type')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='Info')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='Emails per Cycle')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='Sort Order')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
	  </tr>
	</thead>
	<tbody id="mailer_list_table">
<?php $_from = $this->_tpl_vars['mailconnections']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['mcloop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['mcloop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['mc']):
        $this->_foreach['mcloop']['iteration']++;
?>
	  <tr class="adesk_table_row">
	    <td>
	      <div class="adesk_table_row_options">
	        <a href="#" onclick="mailer_edit(<?php echo $this->_tpl_vars['mc']['id']; ?>
);return false;"><?php echo ((is_array($_tmp='Edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php if ($this->_tpl_vars['mc']['id'] > 1): ?>
	        <a href="#" onclick="mailer_delete(<?php echo $this->_tpl_vars['mc']['id']; ?>
);return false;"><?php echo ((is_array($_tmp='Delete')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php endif; ?>
	        <a href="#" onclick="mailer_test(<?php echo $this->_tpl_vars['mc']['id']; ?>
);return false;"><?php echo ((is_array($_tmp='Test')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
	      </div>
	    </td>
	    <td>
	      <?php if (isset ( $this->_tpl_vars['mc']['name'] )):  echo $this->_tpl_vars['mc']['name'];  else: ?><i><?php echo ((is_array($_tmp="N/A")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</i><?php endif; ?>
	    </td>
	    <td>
<?php if ($this->_tpl_vars['mc']['type'] == 0): ?>
	      <?php echo ((is_array($_tmp="Mail()")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

<?php else: ?>
	      <?php echo ((is_array($_tmp='SMTP')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

<?php endif; ?>
	    </td>
	    <td>
<?php if ($this->_tpl_vars['mc']['type'] == 0): ?>
	      <i><?php echo ((is_array($_tmp="N/A")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</i>
<?php else: ?>
	      <?php echo $this->_tpl_vars['mc']['user']; ?>
@<?php echo $this->_tpl_vars['mc']['host'];  if ($this->_tpl_vars['mc']['port'] != 25): ?>:<?php echo $this->_tpl_vars['mc']['port'];  endif; ?>
<?php endif; ?>
	    </td>
	    <td>
	      <?php echo $this->_tpl_vars['mc']['threshold']; ?>

	    </td>
	    <td>
	      <a href="#" onclick="mailer_down(<?php echo $this->_tpl_vars['mc']['id']; ?>
);return false;"><img src="images/desc.gif" border="0" /></a>
	      <a href="#" onclick="mailer_up(<?php echo $this->_tpl_vars['mc']['id']; ?>
);return false;"><img src="images/asc.gif" border="0" /></a>
	    </td>
	  </tr>
<?php endforeach; endif; unset($_from); ?>
	</tbody>
  </table></div>

  <br />

  <input type="button" value="<?php echo ((is_array($_tmp='Add New Mail Sending Connection')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" style="font-size:10px;" onclick="mailer_add();" />

      </td>
    </tr>
<?php endif; ?>

  </table></div>
  </div>
</div>


<script language="JavaScript" type="text/javascript">
<!--
//calculateSendingSpeed();
-->
</script>