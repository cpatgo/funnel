<?php /* Smarty version 2.6.12, created on 2016-07-08 16:50:20
         compiled from account.additional.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'account.additional.htm', 4, false),)), $this); ?>


<div class="h2_wrap">
<h2 onclick="adesk_dom_toggle_class('accountBio', 'h2_content', 'h2_content_invis');"><?php echo ((is_array($_tmp='Stats')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
<div id="accountBio" class="h2_content_invis">
  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="4">
	<?php if ($this->_tpl_vars['admin']['limit_user']): ?>
		<tr>
		  <td><?php echo ((is_array($_tmp='Users Limit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><?php echo $this->_tpl_vars['admin']['limit_user']; ?>
</td>
		</tr>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['admin']['limit_list']): ?>
		<tr>
		  <td><?php echo ((is_array($_tmp='Lists Limit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><?php echo $this->_tpl_vars['admin']['limit_list']; ?>
</td>
		</tr>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['admin']['limit_subscriber'] > 0): ?>
		<tr>
		  <td><?php echo ((is_array($_tmp='Subscribers Limit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><?php echo $this->_tpl_vars['admin']['limit_subscriber']; ?>
</td>
		</tr>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['admin']['limit_mail'] > 0): ?>
		<tr>
		  <td><?php echo ((is_array($_tmp='Mail Limit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td>
			<?php if ($this->_tpl_vars['admin']['limit_mail_type'] == 'month1st'): ?>
				<?php echo ((is_array($_tmp="%s per calendar month (counting from the 1st)")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['admin']['limit_mail']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['admin']['limit_mail'])); ?>

			<?php elseif ($this->_tpl_vars['admin']['limit_mail_type'] == 'monthcdate'): ?>
				<?php echo ((is_array($_tmp="%s per calendar month (counting from the user's creation date)")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['admin']['limit_mail']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['admin']['limit_mail'])); ?>

			<?php else: ?>
				<?php echo $this->_tpl_vars['admin']['limit_mail']; ?>

				<?php if ($this->_tpl_vars['admin']['limit_mail_type'] != 'ever'): ?>
					<?php echo ((is_array($_tmp='per')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					<?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['limit_mail_type'])) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php endif; ?>
			<?php endif; ?>
		  </td>
		</tr>
	<?php endif; ?>
<?php if ($this->_tpl_vars['admin']['limit_mail'] && $this->_tpl_vars['admin']['limit_mail_type'] != 'ever'): ?>
    <tr>
      <td><?php echo ((is_array($_tmp='Messages Sent In This Cycle')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:</td>
      <td><?php echo $this->_tpl_vars['admin']['emails_sent']; ?>
</td>
    </tr>
<?php endif; ?>
    <tr>
      <td><?php echo ((is_array($_tmp='Total Messages Sent')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:</td>
      <td><?php echo $this->_tpl_vars['admin']['emails_sent_total_formatted']; ?>
</td>
    </tr>
	<?php if ($this->_tpl_vars['admin']['limit_attachment'] != -1): ?>
		<tr>
		  <td><?php echo ((is_array($_tmp='Attachments Limit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><?php echo $this->_tpl_vars['admin']['limit_attachment']; ?>
 <?php echo ((is_array($_tmp='per message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		</tr>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['admin']['limit_campaign']): ?>
		<tr>
		  <td><?php echo ((is_array($_tmp='Campaign Limit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td>
			<?php if ($this->_tpl_vars['admin']['limit_campaign_type'] == 'month1st'): ?>
				<?php echo ((is_array($_tmp="%s per calendar month (counting from the 1st)")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['admin']['limit_campaign']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['admin']['limit_campaign'])); ?>

			<?php elseif ($this->_tpl_vars['admin']['limit_campaign_type'] == 'monthcdate'): ?>
				<?php echo ((is_array($_tmp="%s per calendar month (counting from the user's creation date)")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['admin']['limit_campaign']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['admin']['limit_campaign'])); ?>

			<?php else: ?>
				<?php echo $this->_tpl_vars['admin']['limit_campaign']; ?>

				<?php if ($this->_tpl_vars['admin']['limit_campaign_type'] != 'ever'): ?>
					<?php echo ((is_array($_tmp='per')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					<?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['limit_campaign_type'])) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php endif; ?>
			<?php endif; ?>
		  </td>
		</tr>
	<?php endif; ?>
<?php if ($this->_tpl_vars['admin']['limit_campaign'] && $this->_tpl_vars['admin']['limit_campaign_type'] != 'ever'): ?>
    <tr>
      <td><?php echo ((is_array($_tmp='Campaigns Sent In This Cycle')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:</td>
      <td><?php echo $this->_tpl_vars['admin']['campaigns_sent']; ?>
</td>
    </tr>
<?php endif; ?>
    <tr>
      <td><?php echo ((is_array($_tmp='Total Campaigns Sent')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:</td>
      <td><?php echo $this->_tpl_vars['admin']['campaigns_sent_total']; ?>
</td>
    </tr>
  </table></div>
</div>
</div>
