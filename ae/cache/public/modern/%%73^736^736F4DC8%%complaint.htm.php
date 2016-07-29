<?php /* Smarty version 2.6.12, created on 2016-07-18 15:47:30
         compiled from complaint.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'plang', 'complaint.htm', 7, false),)), $this); ?>
<script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'complaint.js', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>

<div style="padding:30px;">

  <h1><?php echo ((is_array($_tmp='Abuse Complaint')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</h1>

  <h2><?php echo ((is_array($_tmp="Group:")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
 <?php echo $this->_tpl_vars['group']['title']; ?>
</h2>

  <div id="infobox" class="adesk_block">

<?php if ($this->_tpl_vars['group']['descript']): ?>
    <div style="padding:10px 0;"><?php echo $this->_tpl_vars['group']['descript']; ?>
</div>
<?php endif; ?>


    <strong><?php echo ((is_array($_tmp="Current Abuse Ratio:")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>

  	<span id="current_label"><?php echo $this->_tpl_vars['group']['abuseratio_current']; ?>
</span>%</strong>   <br />
	<?php echo ((is_array($_tmp='With a current limit of')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
 <?php echo $this->_tpl_vars['group']['abuseratio']; ?>
% <?php echo ((is_array($_tmp="before being suspended.")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>

  	<br />
  	<br />
    <?php echo ((is_array($_tmp="E-mails Sent:")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>

  	<span id="sent_label"><?php echo $this->_tpl_vars['group']['emails_sent']; ?>
</span>
  	<br />
    <?php echo ((is_array($_tmp="Abuse Complaints Reported:")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>

  	<span id="abuses_label"><?php echo $this->_tpl_vars['group']['abuses_reported']; ?>
</span>

  	<div style="padding:20px 0;">
  	  <input type="button" value="<?php echo ((is_array($_tmp='Change Abuse Ratio')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" onclick="abuse_change();" />
<?php if (count ( $this->_tpl_vars['group']['users'] )): ?>
  	  &nbsp;
  	  <input type="button" value="<?php echo ((is_array($_tmp='Notify Senders')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" onclick="abuse_notify();" />
<?php endif; ?>
<?php if ($this->_tpl_vars['group']['abuses_reported']): ?>
  	  &nbsp;
  	  <input type="button" value="<?php echo ((is_array($_tmp='View Abuses')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" onclick="abuse_view();" id="abuse_button_view" />
  	  &nbsp;
  	  <input type="button" value="<?php echo ((is_array($_tmp='Reset Abuse Complaints')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" onclick="abuse_reset();" id="abuse_button_reset" />
<?php endif; ?>
  	</div>
  </div>


  <div id="notifybox" class="adesk_hidden">
    <div style="font-weight:bold;"><?php echo ((is_array($_tmp="Notify user(s) in this group:")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</div>
    <div>
<?php $_from = $this->_tpl_vars['group']['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['u']):
?>
        <div><label><input type="checkbox" name="to[]" value="<?php echo $this->_tpl_vars['u']['id']; ?>
" checked="checked" />&quot;<?php echo $this->_tpl_vars['u']['first_name']; ?>
 <?php echo $this->_tpl_vars['u']['last_name']; ?>
&quot; &lt;<?php echo $this->_tpl_vars['u']['email']; ?>
&gt;</label></div>
<?php endforeach; endif; unset($_from); ?>
    </div>
<br />

    <div style="font-weight:bold;"><?php echo ((is_array($_tmp="Send From:")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</div>
    <div><?php echo ((is_array($_tmp="Name:")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
 <br /><input type="text" style="width:200px;" name="from_name" value="<?php echo $this->_tpl_vars['admin']['first_name'];  if ($this->_tpl_vars['admin']['first_name'] != '' && $this->_tpl_vars['admin']['last_name'] != ''): ?> <?php endif;  echo $this->_tpl_vars['admin']['last_name']; ?>
" /></div>
    <div><?php echo ((is_array($_tmp="E-mail:")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
<br /><input type="text" style="width:200px;" name="from_mail" value="<?php echo $this->_tpl_vars['admin']['email']; ?>
" /></div>
<br />

    <div style="font-weight:bold;"><?php echo ((is_array($_tmp="Subject:")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</div>
    <div><input type="text" name="subject" style="width:400px;" value="<?php echo ((is_array($_tmp='You have abused the mailing system %s')) ? $this->_run_mod_handler('plang', true, $_tmp, $this->_tpl_vars['site']['site_name']) : smarty_modifier_plang($_tmp, $this->_tpl_vars['site']['site_name'])); ?>
" /></div>
<br />

    <div style="font-weight:bold;"><?php echo ((is_array($_tmp="Message:")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</div>
    <div><textarea name="message" style="width:400px;"></textarea></div>
<br />

    <div>
      <input type="button" value="<?php echo ((is_array($_tmp='Send')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" onclick="abuse_notify_send();" />
      <input type="button" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" onclick="abuse_notify();" />
    </div>

  </div>


  <div id="viewbox" class="adesk_hidden">
    <div id="abusesbox">
    </div>

    <div>
      <input type="button" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" onclick="abuse_view();" />
    </div>

  </div>


  <div id="changebox" class="adesk_hidden">
    <div>
      <?php echo ((is_array($_tmp="New Abuse Ratio:")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>

      <input type="text" name="abuseratio" id="group_abuseratio" value="<?php echo $this->_tpl_vars['group']['abuseratio']; ?>
" size="2" />%
    </div><br />


    <div>
      <input type="button" value="<?php echo ((is_array($_tmp='Update')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" onclick="abuse_update();" />
      <input type="button" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" onclick="abuse_change();" />
    </div>

  </div>


  <div id="resetbox" class="adesk_hidden">
    <div><?php echo ((is_array($_tmp="Abuse Complaints have been removed.")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</div>
  </div>


  <div id="updatebox" class="adesk_hidden">
    <div><?php echo ((is_array($_tmp="Abuse Ratio has been updated.")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</div>
  </div>

</div>