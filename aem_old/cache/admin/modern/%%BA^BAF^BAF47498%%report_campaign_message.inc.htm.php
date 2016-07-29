<?php /* Smarty version 2.6.12, created on 2016-07-08 14:48:21
         compiled from report_campaign_message.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'report_campaign_message.inc.htm', 20, false),)), $this); ?>
<div id="message" class="adesk_hidden">

<?php $_from = $this->_tpl_vars['messages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['msgloop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['msgloop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['m']):
        $this->_foreach['msgloop']['iteration']++;
?>

  <div>

  
  <?php if (count ( $this->_tpl_vars['messages'] ) > 1): ?>
    <h3>Message #<?php echo $this->_foreach['msgloop']['iteration']; ?>
</h3>
  <?php endif; ?>

	<?php if ($this->_tpl_vars['m']['a_hashtml']): ?>
	<div class="startup_box_container">
	  <div class="startup_box_title">
		<span class="startup_selected">
			<span style="float: right;">
			  <a href="#" style="background:none; margin:0px; padding:0px; text-decoration:underline;" id="message_showoverlay_<?php echo $this->_tpl_vars['m']['messageid']; ?>
" onclick="report_campaign_message_overlay(<?php echo $this->_tpl_vars['m']['messageid']; ?>
); return false"><?php echo ((is_array($_tmp='Show Overlay')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			  &middot;
			  <a href="#" style="background:none; margin:0px; margin-left:4px; padding:0px; text-decoration:underline;" id="message_showsource_<?php echo $this->_tpl_vars['m']['messageid']; ?>
" onclick="report_campaign_message_source(<?php echo $this->_tpl_vars['m']['messageid']; ?>
); return false"><?php echo ((is_array($_tmp='Show Source')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php if ($this->_tpl_vars['m']['spamcheck_score'] > -1): ?>
			  &middot;
			  <?php echo ((is_array($_tmp="Spam Score:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			  <a href="#" style="background:none; margin:0px; padding:0px; text-decoration:<?php if ($this->_tpl_vars['m']['spamcheck_score'] > 0): ?>underline<?php else: ?>none<?php endif; ?>;" id="message_showspamcheck_<?php echo $this->_tpl_vars['m']['messageid']; ?>
" onclick="<?php if ($this->_tpl_vars['m']['spamcheck_score'] > 0): ?>report_campaign_message_spamcheck(<?php echo $this->_tpl_vars['m']['messageid']; ?>
);<?php endif; ?> return false"><?php echo $this->_tpl_vars['m']['spamcheck_score']; ?>
 / <?php echo $this->_tpl_vars['m']['spamcheck_max']; ?>
</a>
<?php endif; ?>
			</span>

		  <?php echo ((is_array($_tmp='HTML Version')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		</span>
		<?php if ($this->_tpl_vars['m']['messageid'] == $this->_tpl_vars['campaign']['split_winner_messageid']):  echo ((is_array($_tmp="(WINNER)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp));  endif; ?>
	  </div>
	  <div class="startup_box_container_inner">
		<iframe id="message_htmliframe_<?php echo $this->_tpl_vars['m']['messageid']; ?>
" width="99%" height="400" border="0" style="border:0px;" src="<?php echo $this->_tpl_vars['site']['p_link']; ?>
/awebview.php?c=<?php echo $this->_tpl_vars['campaign']['id']; ?>
&m=<?php echo $this->_tpl_vars['m']['messageid']; ?>
&previewtype=html<?php if ($this->_tpl_vars['subscriberhash']): ?>&s=<?php echo $this->_tpl_vars['subscriberhash'];  endif; ?>&useauth"></iframe>
	  </div>
	</div>
	<?php endif; ?>

	<br />

	<?php if ($this->_tpl_vars['m']['a_hastext']): ?>
	<div class="startup_box_container">
	  <div class="startup_box_title">
		<span class="startup_selected">
<?php if (! $this->_tpl_vars['m']['a_hashtml']): ?>
			<span style="float: right;">
			  <a href="#" style="background:none; margin:0px; padding:0px; text-decoration:underline;" id="message_showoverlay_<?php echo $this->_tpl_vars['m']['messageid']; ?>
" onclick="report_campaign_message_overlay(<?php echo $this->_tpl_vars['m']['messageid']; ?>
); return false"><?php echo ((is_array($_tmp='Show Overlay')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			  &middot;
			  <a href="#" style="background:none; margin:0px; margin-left:4px; padding:0px; text-decoration:underline;" id="message_showsource_<?php echo $this->_tpl_vars['m']['messageid']; ?>
" onclick="report_campaign_message_source(<?php echo $this->_tpl_vars['m']['messageid']; ?>
); return false"><?php echo ((is_array($_tmp='Show Source')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
	<?php if ($this->_tpl_vars['m']['spamcheck_score'] > -1): ?>
			  &middot;
			  <?php echo ((is_array($_tmp="Spam Score:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			  <a href="#" style="background:none; margin:0px; padding:0px; text-decoration:<?php if ($this->_tpl_vars['m']['spamcheck_score'] > 0): ?>underline<?php else: ?>none<?php endif; ?>;" id="message_showspamcheck_<?php echo $this->_tpl_vars['m']['messageid']; ?>
" onclick="<?php if ($this->_tpl_vars['m']['spamcheck_score'] > 0): ?>report_campaign_message_spamcheck(<?php echo $this->_tpl_vars['m']['messageid']; ?>
);<?php endif; ?> return false"><?php echo $this->_tpl_vars['m']['spamcheck_score']; ?>
 / <?php echo $this->_tpl_vars['m']['spamcheck_max']; ?>
</a>
	<?php endif; ?>
			</span>
<?php endif; ?>

		  <?php echo ((is_array($_tmp='Text Version')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		</span>
		<?php if ($this->_tpl_vars['m']['messageid'] == $this->_tpl_vars['campaign']['split_winner_messageid']):  echo ((is_array($_tmp="(WINNER)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp));  endif; ?>
	  </div>
	  <div class="startup_box_container_inner">
		<iframe id="message_textiframe_<?php echo $this->_tpl_vars['m']['messageid']; ?>
" width="99%" height="400" border="0" style="border:0px;" src="<?php echo $this->_tpl_vars['site']['p_link']; ?>
/awebview.php?c=<?php echo $this->_tpl_vars['campaign']['id']; ?>
&m=<?php echo $this->_tpl_vars['m']['messageid']; ?>
&previewtype=text<?php if ($this->_tpl_vars['subscriberhash']): ?>&s=<?php echo $this->_tpl_vars['subscriberhash'];  endif; ?>&useauth"></iframe>
	  </div>
	</div>
	<?php endif; ?>

  </div>

<?php endforeach; endif; unset($_from); ?>

</div>