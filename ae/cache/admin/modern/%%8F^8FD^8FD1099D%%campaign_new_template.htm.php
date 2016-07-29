<?php /* Smarty version 2.6.12, created on 2016-07-08 14:19:39
         compiled from campaign_new_template.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'campaign_new_template.htm', 2, false),array('modifier', 'capitalize', 'campaign_new_template.htm', 51, false),)), $this); ?>
<?php if ($this->_tpl_vars['hosted_down4'] != 'nobody'): ?>
<?php echo ((is_array($_tmp="Due to your account status, you are unable to send any campaigns.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

<a href="desk.php"><?php echo ((is_array($_tmp="Return to the Dashboard.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php elseif ($this->_tpl_vars['pastlimit']): ?>
<?php echo ((is_array($_tmp="Sending to this list would put you past your limit of allowed emails.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

<a href="desk.php?action=campaign_new_list&id=<?php echo $this->_tpl_vars['campaignid']; ?>
"><?php echo ((is_array($_tmp="Please choose another list.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php else: ?>

<script type="text/javascript">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new.shared.js", 'smarty_include_vars' => array('step' => 'template')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new_template.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>

<form id="campaignform" method="POST" action="desk.php" onsubmit="return false">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new.header.htm", 'smarty_include_vars' => array('step' => 'template','highlight' => 2)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<input type="hidden" name="action" value="campaign_new_template">
	<input type="hidden" name="basetemplateid" id="campaign_basetemplateid" value="<?php echo $this->_tpl_vars['campaign']['basetemplateid']; ?>
">
	<input type="hidden" name="basemessageid" id="campaign_basemessageid" value="<?php echo $this->_tpl_vars['campaign']['basemessageid']; ?>
">
	<input type="text" name="dummy" style="display:none">

	<div class="h2_wrap_static">
		<h5><?php echo ((is_array($_tmp="Select a template to base your email off of...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
		<div>
        
        
        	<div class="tpl_selector_head">
        
			<div style="float: right">
				<input id="searchkey" type="text" value="<?php echo ((is_array($_tmp="Search...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onkeyup="campaign_typesearch(this.value);" onclick="this.value=''; this.style.color='#000'; campaign_typesearch(this.value);" style="color:#ccc;" />
			</div>

			<div style=" height:17px; padding-top:5px;">
				<div style=" padding-right: 20px; float: right; font-size: 12px"><a href="#" onclick="campaign_template_view('list'); return false"><span id="span_list" class="campaign_template_textnotselected"><?php echo ((is_array($_tmp='List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a></div>
				<div style="padding-right: 20px; float: right; font-size: 12px"><a href="#" onclick="campaign_template_view('images'); return false"><span id="span_images" class="campaign_template_textselected"><?php echo ((is_array($_tmp='Images')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a></div>
			</div>
            </div>

			<div class="tpl_selector" style="height:600px; overflow:hidden;">
				<div class="tpl_selector_nav">
					
						<a href="#" onclick="campaign_template_switch('tdisplay'); return false"><span id="span_tdisplay" class="campaign_template_textselected"><?php echo ((is_array($_tmp='All templates')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a>
						<div style="padding-top:8px;"><a href="#" onclick="campaign_template_switch('cdisplay'); return false"><span id="span_cdisplay" class="campaign_template_textnotselected"><?php echo ((is_array($_tmp='Past campaigns')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a>
                        </div>
					

					<div style="margin-top: 16px; padding-top:10px; border-top:1px dotted #ccc; color:#999; <?php if (count ( $this->_tpl_vars['tags'] ) == 0): ?>display:none<?php endif; ?>">
						<?php echo ((is_array($_tmp="Template categories:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br>
						<ul id="taglist" style=" padding-left:15px; font-size:12px; list-style:none;">
							<?php $_from = $this->_tpl_vars['tags']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['e']):
?>
								<?php if ($this->_tpl_vars['e']['count'] > 0): ?>
									<li style="margin-bottom:8px;"><a href="#" onclick="campaign_template_usetag(<?php echo $this->_tpl_vars['e']['id']; ?>
)" style="color:#999;"><span id="tag_<?php echo $this->_tpl_vars['e']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['e']['tag'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</span></a> <span style="color:#999">(<?php echo $this->_tpl_vars['e']['count']; ?>
)</span></li>
								<?php endif; ?>
							<?php endforeach; endif; unset($_from); ?>
						</ul>
					</div>
				</div>
				<div style="background:#fff; height: 600px; overflow-y: scroll;">
					<div style="   padding:15px; ">
						<div id="choices">
						</div>

						<div id="emptysearch" style="display:none;">
							<?php echo ((is_array($_tmp="There are no results for your search terms.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

						</div>

						<div id="loadmore" onclick="campaign_template_loadmore()" style="background:#f2f1ed; padding:10px; font-size:15px; color:#aba89b; text-align: center; clear: both; cursor: pointer">
							<?php echo ((is_array($_tmp="Load more...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<br clear="all">

	<div>
		<div style="float:right;">
			<input value='<?php echo ((is_array($_tmp="Save & Exit")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' type="button" onclick="campaign_template_save('exit')" style="font-size:14px;" />
			<input value='<?php echo ((is_array($_tmp='Save')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' type="button" onclick="campaign_template_save('nothing')" style="font-size:14px;" />
		</div>
		<input value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" type="button" onclick="campaign_template_save('back')" style="font-size:14px;" />
		<input value="<?php echo ((is_array($_tmp='Next')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" type="button" onclick="campaign_template_save('next')" style="font-weight:bold; font-size:14px;" />
	</div>

	<script type="text/javascript">
		campaign_save_auto_runagain();
		campaign_template_display();
	</script>
</form>

<div id="alreadyselected" class="adesk_modal" align="center" style="display:none;">
	<div class="adesk_modal_inner" align="left">
		<h3 class="m-b"><?php echo ((is_array($_tmp='Selecting a new template')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

		<?php echo ((is_array($_tmp="You already have a template/message that you were working on.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php echo ((is_array($_tmp="By selecting a new template, you will overwrite your existing template/message.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php echo ((is_array($_tmp="Are you sure you to start with this new template?  You will lose your current message!")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>


		<br>
		<br>

		<div>
			<input type="button" value='<?php echo ((is_array($_tmp="Yes - Select This New Template")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="campaign_save(campaign_template_afterstep)" style="font-weight: bold;">
			<input type="button" value='<?php echo ((is_array($_tmp="No - Use Existing")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="campaign_template_useexisting()">
		</div>
	</div>
</div>
<?php endif; ?>