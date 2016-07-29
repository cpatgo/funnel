<?php /* Smarty version 2.6.12, created on 2016-07-08 16:21:25
         compiled from service.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'service.form.htm', 7, false),array('modifier', 'truncate', 'service.form.htm', 66, false),)), $this); ?>
<div id="form" class="adesk_hidden">
  <form method="POST" onsubmit="service_form_save(service_form_id); return false">
    <input type="hidden" name="id" id="form_id" />
    <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5">
		  <tr>
				<td>
				  <?php echo ((is_array($_tmp='Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:
				</td>
				<td>
					<span id="nameField"></span>
				</td>
			</tr>
		  <tr>
				<td>
				  <?php echo ((is_array($_tmp='Description')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:
				</td>
				<td>
					<span id="descriptionField"></span>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<?php echo ((is_array($_tmp='Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:
				</td>
				<td>
					<div id="service_twitter">
						<div class="adesk_help_inline">
						  <?php echo ((is_array($_tmp="By default, Twitter updates will have a source from @awebdesk. To include your own source, ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

						  <a href="http://twitter.com/apps"><?php echo ((is_array($_tmp='register an application on Twitter')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
						  <?php echo ((is_array($_tmp="and include the consumer keys here. Make sure your Twitter application meets the requirements that we outline (provide a value for the Callback URL). You will not be able to save these settings unless it does. NOTE: You may have to update your list settings if these values are changed.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

						</div>
						<?php echo ((is_array($_tmp='Consumer key')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:
						<br />
						<input type="text" name="service_twitter_key" id="service_twitter_key" size="40" />
						<br />
						<br />
						<?php echo ((is_array($_tmp='Consumer secret')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:
						<br />
						<input type="text" name="service_twitter_secret" id="service_twitter_secret" size="40" />
						 
					</div>
					<div id="service_facebook">
						<div class="adesk_help_inline">
						  <?php echo ((is_array($_tmp="In order to send auto-updates to Facebook, you must first include your own application keys that correspond to your domain.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

						  <a href="http://developers.facebook.com/setup/"><?php echo ((is_array($_tmp='Register an application on Facebook')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>,
						  <?php echo ((is_array($_tmp="then include your application keys here. NOTE: You will have to update your list settings if these values are changed.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

						</div>
						<?php echo ((is_array($_tmp='Application ID')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:
						<br />
						<input type="text" name="service_facebook_id" id="service_facebook_id" size="40" />
						<br />
						<br />
						<?php echo ((is_array($_tmp='Application secret')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:
						<br />
						<input type="text" name="service_facebook_secret" id="service_facebook_secret" size="40" />
					</div>
					<div id="service_unbounce">
						<?php echo ((is_array($_tmp="Choose the List that your Unbounce WebHook will add subscribers to:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

						<br />
						<br />
						<div id="parentsList_div" class="adesk_checkboxlist">
							<?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['counter'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['counter']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['p']):
        $this->_foreach['counter']['iteration']++;
?>
								<div>
									<label>
										<input type="checkbox" id="p_<?php echo $this->_tpl_vars['p']['id']; ?>
" class="parentsList" name="p[]" value="<?php echo $this->_tpl_vars['p']['id']; ?>
" onclick="service_form_reset_lists(<?php echo $this->_tpl_vars['p']['id']; ?>
); service_form_gen_url(<?php echo $this->_tpl_vars['p']['id']; ?>
);" <?php if (count ( $this->_tpl_vars['listsList'] ) == 1 || $this->_foreach['counter']['iteration'] == 1): ?>checked="checked"<?php endif; ?> />
										<?php echo ((is_array($_tmp=$this->_tpl_vars['p']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>

									</label>
								</div>
							<?php endforeach; endif; unset($_from); ?>
						</div>
						<br />
						<br />
						<b><?php echo ((is_array($_tmp='Unbounce WebHook URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</b>
						<br />
						<?php echo ((is_array($_tmp="Paste this URL into your Unbounce WebHook (POST to URL) field. New leads will automatically be added to your chosen list.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

						<br />
						<br />
						<textarea id="service_form_url" onclick="this.select();" style="height: 150px; width: 500px;"></textarea>
					</div>
				</td>
			</tr>
    </table></div>

    <br />
    <div>
      <input type="button" id="form_submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="service_form_save(service_form_id)" />
      <input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1)" />
    </div>
    <input type="submit" style="display:none"/>
  </form>
</div>