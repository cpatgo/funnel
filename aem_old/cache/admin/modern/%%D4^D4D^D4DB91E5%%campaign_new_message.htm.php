<?php /* Smarty version 2.6.12, created on 2016-07-08 14:19:52
         compiled from campaign_new_message.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'campaign_new_message.htm', 2, false),array('modifier', 'default', 'campaign_new_message.htm', 64, false),array('modifier', 'adesk_isselected', 'campaign_new_message.htm', 93, false),)), $this); ?>
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
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new.shared.js", 'smarty_include_vars' => array('step' => 'message')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new_message.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>

<form id="campaignform" method="POST" action="desk.php" onsubmit="return false">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new.header.htm", 'smarty_include_vars' => array('step' => 'message','highlight' => 2)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<input type="hidden" name="action" value="campaign_new_message">
	<input type="hidden" name="managetext" id="campaign_managetextid" value="<?php echo $this->_tpl_vars['campaign']['managetext']; ?>
">


	<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="48%" valign="top">
				<h5><?php echo ((is_array($_tmp='From Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
				<div class="campaign_help">
					<?php echo ((is_array($_tmp="Receivers will get email from this name.<br>It is suggested to use your company or personal name here.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				</div>
				<div  class="campaign_input">
					<input type="text" tabindex="1" name="fromname" id="campaign_fromname" onkeyup="campaign_different()" value="" style="width: 99%">
				</div>
			</td>
			<td width="4%" valign="top">&nbsp;</td>
			<td width="48%" valign="top">
				<h5><?php echo ((is_array($_tmp='From Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
				<div class="campaign_help">
					<?php echo ((is_array($_tmp="Receivers will get email from this email id.<br> You can also set a different")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <a href="#" onclick="adesk_dom_toggle_display('replyto', 'block'); return false">reply-to</a>.
				</div>
				<div  class="campaign_input">
					<input type="text" tabindex="2" name="fromemail" id="campaign_fromemail" onkeyup="campaign_different()" value="" style="width: 99%">
				</div>

				<div id="replyto" style="<?php if (! $this->_tpl_vars['message']['reply2'] || $this->_tpl_vars['message']['fromemail'] == $this->_tpl_vars['message']['reply2']): ?>display:none;<?php endif; ?>">
					<h2 style="margin-top:15px;"><?php echo ((is_array($_tmp="Reply-To Email")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
					<div class="campaign_input border_top_5">
						<input type="text" name="reply2" id="campaign_reply2" onkeyup="campaign_different();" value="" style="width: 100%">
					</div>
				</div>
			</td>
		</tr>
	</table></div>



	<h2 style="margin-top:20px;"><?php echo ((is_array($_tmp="Subject & Message")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
	<div class="campaign_help">
		<?php echo ((is_array($_tmp="This is the subject your subscribers will see.  We suggest a brief yet informative sentence.  This is what will engage your subscriber to open your email.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	</div>

	<div  class="campaign_input">
		<?php if ($this->_tpl_vars['admin']['limit_attachment'] != 0): ?>

		<div style="float:right; padding-top:11px;" onclick="$('message_attach').show()"><img id="attachimg" src="images/mesg-attach.gif" style="cursor:pointer;" /></div>
		<div style="margin-right:25px;">
			<?php endif; ?>
			<input tabindex="3" type="text" name="subject" id="campaign_subject" onkeyup="campaign_different()" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['message']['subject'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" style="width: 99%; font-size:18px; padding:5px;">
			<?php if ($this->_tpl_vars['admin']['limit_attachment'] != 0): ?>
		</div>
		<?php endif; ?>
		<div style="margin-top:20px;">
		<div class="adesk_help_inline" id="editorfetch" <?php if ($this->_tpl_vars['fetch'] == 'now'): ?>style="display:none"<?php endif; ?>>
			<div style="font-size: 12px">
				<?php echo ((is_array($_tmp="When your campaign is sent, its HTML content will be fetched from")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<a id="fetchhelplink" target="_blank" href="<?php echo $this->_tpl_vars['fetchurl']; ?>
"><?php echo $this->_tpl_vars['fetchurl']; ?>
</a>.
			</div>

			<div style="margin-top: 15px;">
				<input type="button" value='<?php echo ((is_array($_tmp='Edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="campaign_fetch_open()">
				<input type="button" value='<?php echo ((is_array($_tmp='Do not fetch')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="campaign_fetch_stop()">
			</div>
		</div>

<?php if ($this->_tpl_vars['campaign']['type'] == 'deskrss'): ?>
		<div class="rss_sidebar">
        	<div id="deskrss_add" class="rss_sidebar_highlight" style="<?php if ($this->_tpl_vars['campaign']['deskrss_url']): ?>display:none;<?php endif; ?>">
                <div style="font-size:14px; font-weight:bold;"><?php echo ((is_array($_tmp='Insert your RSS feed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

                <div style="margin-top:10px;"><?php echo ((is_array($_tmp='Your RSS feed URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:</div>
                <div><input type="text" name="deskrss_url" id="deskrss_url" value="<?php if ($this->_tpl_vars['campaign']['deskrss_url']):  echo $this->_tpl_vars['campaign']['deskrss_url'];  else: ?>http://<?php endif; ?>" style="width:99%;" /></div>

                <div style="margin-top:10px;"><?php echo ((is_array($_tmp='Number of feed items to include')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:</div>
                <div>
                	<select name="deskrss_items" id="deskrss_items" size="1">
                	<?php unset($this->_sections['foo']);
$this->_sections['foo']['start'] = (int)1;
$this->_sections['foo']['loop'] = is_array($_loop=21) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['foo']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['foo']['name'] = 'foo';
$this->_sections['foo']['show'] = true;
$this->_sections['foo']['max'] = $this->_sections['foo']['loop'];
if ($this->_sections['foo']['start'] < 0)
    $this->_sections['foo']['start'] = max($this->_sections['foo']['step'] > 0 ? 0 : -1, $this->_sections['foo']['loop'] + $this->_sections['foo']['start']);
else
    $this->_sections['foo']['start'] = min($this->_sections['foo']['start'], $this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] : $this->_sections['foo']['loop']-1);
if ($this->_sections['foo']['show']) {
    $this->_sections['foo']['total'] = min(ceil(($this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] - $this->_sections['foo']['start'] : $this->_sections['foo']['start']+1)/abs($this->_sections['foo']['step'])), $this->_sections['foo']['max']);
    if ($this->_sections['foo']['total'] == 0)
        $this->_sections['foo']['show'] = false;
} else
    $this->_sections['foo']['total'] = 0;
if ($this->_sections['foo']['show']):

            for ($this->_sections['foo']['index'] = $this->_sections['foo']['start'], $this->_sections['foo']['iteration'] = 1;
                 $this->_sections['foo']['iteration'] <= $this->_sections['foo']['total'];
                 $this->_sections['foo']['index'] += $this->_sections['foo']['step'], $this->_sections['foo']['iteration']++):
$this->_sections['foo']['rownum'] = $this->_sections['foo']['iteration'];
$this->_sections['foo']['index_prev'] = $this->_sections['foo']['index'] - $this->_sections['foo']['step'];
$this->_sections['foo']['index_next'] = $this->_sections['foo']['index'] + $this->_sections['foo']['step'];
$this->_sections['foo']['first']      = ($this->_sections['foo']['iteration'] == 1);
$this->_sections['foo']['last']       = ($this->_sections['foo']['iteration'] == $this->_sections['foo']['total']);
?>
                		<option value="<?php echo $this->_sections['foo']['index']; ?>
" <?php echo ((is_array($_tmp=$this->_sections['foo']['index'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, $this->_tpl_vars['campaign']['deskrss_items']) : smarty_modifier_adesk_isselected($_tmp, $this->_tpl_vars['campaign']['deskrss_items'])); ?>
><?php echo $this->_sections['foo']['index']; ?>
</option>
                	<?php endfor; endif; ?>
                	</select>
                </div>

                <div style="margin-top:10px;"><input type="button" value="&lt; <?php echo ((is_array($_tmp='Get Code')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" style="font-weight:bold;" onclick="deskrss_add();" /></div>
                <div id="deskrss_loading" style="margin-top:10px;display:none;"><?php echo ((is_array($_tmp="Please wait while your feed is loaded...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
            </div>
        	<div id="deskrss_use" class="rss_sidebar_highlight" style="<?php if (! $this->_tpl_vars['campaign']['deskrss_url']): ?>display:none;<?php endif; ?>">
                <div style="font-size:14px; font-weight:bold;"><?php echo ((is_array($_tmp='Your RSS feed tags')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

								<div style="margin-top:10px;"><?php echo ((is_array($_tmp="Copy and paste your RSS code into your message.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <?php echo ((is_array($_tmp='The following needs to be included within your email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:</div>
                <div style="margin-top:5px;"><textarea style="width:99%; height:200px; font-size:10px;" id="deskrss_preview" wrap="off" />
%RSS-FEED|URL:<?php echo $this->_tpl_vars['campaign']['deskrss_url']; ?>
|SHOW:ALL%

%RSS:CHANNEL:TITLE%

%RSS-LOOP|LIMIT:10%

%RSS:ITEM:DATE%
%RSS:ITEM:TITLE%
%RSS:ITEM:SUMMARY%
%RSS:ITEM:LINK%

%RSS-LOOP%

%RSS-FEED%</textarea></div>
                <div style="margin-top:10px;"><a href="#" onclick="deskrss_reset();return false;"><?php echo ((is_array($_tmp='Start over with a new feed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></div>
            </div>

            <div class="rss_sidebar_help">
            	<?php echo ((is_array($_tmp="Specify any RSS feed URL (such as your blog's RSS feed) and we will check it daily for new posts.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br /><br />
				<?php echo ((is_array($_tmp="When we find a new post we will send this campaign.  The campaign will include the new posts.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br /><br />
				<?php echo ((is_array($_tmp="You can specify the max number of new items to include in this campaign as well.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </div>
        </div>
<?php endif; ?>
		<div style="<?php if ($this->_tpl_vars['campaign']['type'] == 'deskrss'): ?>margin-right:195px;<?php endif; ?>">
		<div id="editordiv" <?php if ($this->_tpl_vars['fetch'] != 'now'): ?>style="display:none"<?php endif; ?>>
			<div id="editorhtml">
				<div style="margin:0px; padding-bottom:0px;">

					<div style="float:right; margin-bottom:3px;">
						<div align="right" style="vertical-align: middle;">
							<?php if ($this->_tpl_vars['campaign']['type'] != 'deskrss'): ?>
							<a href="#" onclick="campaign_fetch_open(); return false" style="padding:2px; background:url(images/editor_fetch.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; margin-right:10px;"><?php echo ((is_array($_tmp='Fetch From URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
							<a href="#" onclick="campaign_deskrss_open(); return false;" style="padding:2px; background:url(images/editor_deskrss.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; margin-right:10px;"><?php echo ((is_array($_tmp='Insert RSS')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
							<?php endif; ?>
							<a href="#" onclick="campaign_conditional_open(); return false;" style="padding:2px; background:url(images/editor_conditional.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; margin-right:10px;"><?php echo ((is_array($_tmp='Insert Conditional Content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
							<a href="#" onclick="campaign_personalization_open(); return false;" style="  padding:2px; background:url(images/editor_personalization.gif); background-position:left; background-repeat:no-repeat; padding-left:25px; font-weight:bold; color:#006600;"><?php echo ((is_array($_tmp='Personalize Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
						</div>
					</div>

					<div>
						<ul class="navlist" style="padding-left:4px; margin-top: 3px; border-bottom:0px;">

							<li id="messageEditorLinkOn" class="<?php if ($this->_tpl_vars['admin']['htmleditor']): ?>currenttab<?php else: ?>othertab<?php endif; ?>">
							<a href="#" onclick="return campaign_message_toggle_editor('message', true, adesk_editor_init_word_object); return false" style="border-bottom:0px;"><span><?php echo ((is_array($_tmp='Visual Editor')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a>
							</li>
							<li id="messageEditorLinkOff" class="<?php if (! $this->_tpl_vars['admin']['htmleditor']): ?>currenttab<?php else: ?>othertab<?php endif; ?>">
							<a href="#" onclick="return campaign_message_toggle_editor('message', false, adesk_editor_init_word_object); return false" style="border-bottom:0px;"><span><?php echo ((is_array($_tmp='Code Editor')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a>
							</li>

						</ul>
					</div>

					<div id="messageEditorLinkDefault" style="display:none;padding:2px; padding-left:4px; font-size:10px; background:none; border-top:1px solid #CCCCCC; background:#FFFFD5; ">
						<a href="#" onclick="return campaign_message_setdefaulteditor('message');" style="color:#666666;"><?php echo ((is_array($_tmp='Set as default editor mode')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
					</div>
				</div>
			</div>




			<textarea tabindex="4" name="html" id="messageEditor" style="width: <?php echo $this->_tpl_vars['admin']['editorsize_w']; ?>
; height: <?php echo $this->_tpl_vars['admin']['editorsize_h']; ?>
; padding:0px;  margin-right:0px;" onblur="messageChanged=true;"></textarea>

		</div>
		<?php if ($this->_tpl_vars['admin']['htmleditor']): ?><script>campaign_message_toggle_editor('message', true, adesk_editor_init_word_object);</script><?php endif; ?>
        </div>
        </div>
	</div>



	<div class="campaign_input" style=" padding-top:0px; font-size:11px; color:#666;">
		<div id="askmanagetext" style="<?php if ($this->_tpl_vars['campaign']['managetext']): ?>display:none<?php endif; ?>">
			<?php echo ((is_array($_tmp="We will automatically create a nicely formatted text-only version of your message for you.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>


			<div style="float: right">
				<a href="#" onclick="campaign_managetext(1); campaign_different(); return false" style="color:#999;"><?php echo ((is_array($_tmp='Let me manage the text only version')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
		</div>

		<div id="willmanagetext" style="<?php if (! $this->_tpl_vars['campaign']['managetext']): ?>display: none<?php endif; ?>">
			<?php echo ((is_array($_tmp="You have chosen to manage the text version of your message.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>


			<div style="float: right">
				<a href="#" onclick="campaign_managetext(0); campaign_different(); return false" style="color:#999;"><?php echo ((is_array($_tmp="No, I'd rather let it be generated automatically")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
		</div>
	</div>

	<div style="margin-top:20px;">
		<div style="float:right;">
			<input value='<?php echo ((is_array($_tmp="Save & Exit")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' type="button" onclick="campaign_save('exit')" style="font-size:14px;" />
			<input value='<?php echo ((is_array($_tmp='Save')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' type="button" onclick="campaign_save('nothing')" style="font-size:14px;" />
		</div>
		<input value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" type="button" onclick="campaign_save('back')" style="font-size:14px;" />
		<input value="<?php echo ((is_array($_tmp='Next')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" type="button" onclick="campaign_save('next')" style="font-weight:bold; font-size:14px;" />
	</div>

	<script type="text/javascript">
		campaign_save_auto_runagain();

<?php echo '
if (message_obj.html != "") {
	adesk_form_value_set($("messageEditor"), message_obj.html);
}
'; ?>

</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new_message.attach.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new_message.fetch.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</form>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new_message.personalize.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new_message.conditional.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new_message.deskrss.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endif; ?>