<?php /* Smarty version 2.6.12, created on 2016-07-08 14:19:52
         compiled from campaign_new_message.attach.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'campaign_new_message.attach.htm', 5, false),array('function', 'adesk_upload', 'campaign_new_message.attach.htm', 33, false),)), $this); ?>
<div id="message_attach" class="adesk_modal" align="center" style="display:none;">
	<input type="hidden" id="campaign_embed_images" name="embed_images" value="<?php echo $this->_tpl_vars['campaign']['embed_images']; ?>
">
	<div class="adesk_modal_inner" align="left">
		<div id="message_attach_regular" <?php if ($this->_tpl_vars['campaign']['embed_images']): ?>style="display:none"<?php endif; ?>>
			<h3 class="m-b"><?php echo ((is_array($_tmp='Attach a file')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

			<?php if ($this->_tpl_vars['__ishosted']): ?>
			<div>
				<?php echo ((is_array($_tmp="There are a few limits to attaching files.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>


				<ul>
					<li><?php echo ((is_array($_tmp="You may only attach one file.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
					<li><?php echo ((is_array($_tmp="Attachments cannot be greater than 1 megabyte.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
					<li><?php echo ((is_array($_tmp="The file you attach must either be .DOC, .DOCX, .JPG or .PDF.  No other file types are allowed.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
				</ul>
			</div>
			<?php else: ?>
			<div>
				<?php if ($this->_tpl_vars['admin']['limit_attachment'] == -1): ?>
				<?php echo ((is_array($_tmp="There is no limit to the number of attachments you may have for your message.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php elseif ($this->_tpl_vars['admin']['limit_attachment'] == 0): ?>
				<?php echo ((is_array($_tmp="You may not upload any attachments.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php else: ?>
				<?php echo ((is_array($_tmp="You may only upload %s attachment(s) in all.")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['admin']['limit_attachment']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['admin']['limit_attachment'])); ?>

				<?php endif; ?>
			</div>
			<?php endif; ?>

			<br>

			<div>
				<div id="attachmentsBox">
					<?php echo smarty_function_adesk_upload(array('id' => 'message_attach','name' => 'attach','action' => 'message_attach','files' => $this->_tpl_vars['files'],'limit' => $this->_tpl_vars['admin']['limit_attachment']), $this);?>

				</div>
			</div>

			<?php if ($this->_tpl_vars['campaign']['type'] != 'text'): ?>
			<div>
				<a href="#" onclick="campaign_attach_embed(true); return false"><?php echo ((is_array($_tmp='I want to embed images in my message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			<?php endif; ?>

			<div style="margin-top: 10px">
				<input type="button" onclick="$('message_attach').hide(); campaign_attach_update()" value='<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
'>
			</div>
		</div>

		<div id="message_attach_embed" <?php if (! $this->_tpl_vars['campaign']['embed_images']): ?>style="display:none"<?php endif; ?>>
			<h3 class="m-b"><?php echo ((is_array($_tmp='Embed images')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

			<div>
				<?php echo ((is_array($_tmp="You have chosen to embed images in your message.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php echo ((is_array($_tmp="You are not allowed to add any attachments to your message if you embed images.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</div>

			<div style="margin-top: 10px">
				<a href="#" onclick="campaign_attach_embed(false); return false"><?php echo ((is_array($_tmp="Don't embed images in my message")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>

			<div style="margin-top: 10px">
				<input type="button" onclick="$('message_attach').hide(); campaign_attach_update()" value='<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
'>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	<?php echo '
	function campaign_attach_update() {
		var post = $("campaignform").serialize(true);

		if (typeof post["attach[]"] != "undefined") {
			post.attach = post["attach[]"];
		}

		if ($("campaign_embed_images").value == 1 || ( typeof post.attach != "undefined" && (typeof post.attach == "string" || post.attach.length > 0))) {
			if ($("attachimg"))
				$("attachimg").src = "images/mesg-attach-on.gif";
		} else {
			if ($("attachimg"))
				$("attachimg").src = "images/mesg-attach.gif";
		}
	}

	function campaign_attach_embed(val) {
		if (val) {
			$("message_attach_regular").hide();
			$("message_attach_embed").show();
			$("campaign_embed_images").value = 1;
		} else {
			$("message_attach_regular").show();
			$("message_attach_embed").hide();
			$("campaign_embed_images").value = 0;
		}
	}

	campaign_attach_update();
	'; ?>

</script>