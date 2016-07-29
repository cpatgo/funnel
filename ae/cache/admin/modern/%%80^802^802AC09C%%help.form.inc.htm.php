<?php /* Smarty version 2.6.12, created on 2016-07-08 17:09:18
         compiled from help.form.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'help.form.inc.htm', 1, false),array('modifier', 'truncate', 'help.form.inc.htm', 83, false),array('function', 'jsvar', 'help.form.inc.htm', 20, false),array('function', 'adesk_printphp', 'help.form.inc.htm', 188, false),)), $this); ?>
<h1 style="margin-top: 20px;"><?php echo ((is_array($_tmp='Other Integration Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

<ul id="form_list_other_options" class="navlist">

	<?php if ($this->_tpl_vars['site']['general_public']): ?><li id="form_list_other_li_public" class="othertab"><a href="javascript: form_list_other_cycle('public');"><?php echo ((is_array($_tmp='Public Message Archive')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li><?php endif; ?>
	<li id="form_list_other_li_api" class="othertab"><a href="javascript: form_list_other_cycle('api');"><?php echo ((is_array($_tmp='Using The API')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
	<li id="form_list_other_li_advanced" class="othertab"><a href="javascript: form_list_other_cycle('advanced');"><?php echo ((is_array($_tmp='Advanced Subscription Form Integration')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>

</ul>

<div style="padding-top: 10px; margin: 10px 0 0 15px;">

	<div id="form_list_other_public">

		<div style="margin-left: 15px;">

			<script>
			// <!--

			<?php echo smarty_function_jsvar(array('name' => 'links','var' => $this->_tpl_vars['links']), $this);?>


			<?php echo '

			function buildLink(section) {
				var val = $(section + \'Select\').value;
				//alert("Section: " + section + "\\nValue: " + val + "\\nLink: " + links[section] + val);
				$(section + \'URL\').href = links[section] + val;
				$(section + \'URL\').innerHTML = links[section] + val;
				//window.location.href = links[section] + val;
			}

			'; ?>


			// -->
			</script>

			<div class="h2_wrap_static">
				<h3><?php echo ((is_array($_tmp='General Public Archive')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
				<div class="h2_content" style="font-size:12px;">
					<p>
						<?php echo ((is_array($_tmp="This is a general public archive that will show all publicly available mailing lists and campaigns in the software.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					</p>
					<p>
						<a class="publiclink" href="<?php echo $this->_tpl_vars['links']['archive']; ?>
" target="_blank"><?php echo $this->_tpl_vars['links']['archive']; ?>
</a>
					</p>
				</div>
			</div>
			<br />

			<div class="h2_wrap_static" <?php if ($this->_tpl_vars['__ishosted']): ?>style="display:none;"<?php endif; ?>>
				<h3><?php echo ((is_array($_tmp='Your Public Archive')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
				<div class="h2_content" style="font-size:12px;">
					<p>
						<?php echo ((is_array($_tmp="View the message archive that is filtered to lists and campaigns from a particular User Group.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					</p>
					<?php if ($this->_tpl_vars['maingroup']): ?>
						<p>
							<?php echo ((is_array($_tmp="Select A User Group:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
							<select id="groupSelect" size="1" onchange="buildLink('group');">
							<?php $_from = $this->_tpl_vars['groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
								<?php if ($this->_tpl_vars['p']['id'] != 1 && $this->_tpl_vars['p']['id'] != 2): ?><option value="<?php echo $this->_tpl_vars['p']['id']; ?>
" <?php if ($this->_tpl_vars['p']['id'] == $this->_tpl_vars['groupid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['p']['title']; ?>
</option><?php endif; ?>
							<?php endforeach; endif; unset($_from); ?>
							</select>
						</p>
					<?php endif; ?>
					<p>
						<a id="groupURL" class="publiclink" href="<?php echo $this->_tpl_vars['links']['group'];  echo $this->_tpl_vars['groupid']; ?>
" target="_blank"><?php echo $this->_tpl_vars['links']['group'];  echo $this->_tpl_vars['groupid']; ?>
</a>
					</p>
				</div>
			</div>
			<br />

			<div class="h2_wrap_static">
				<h3><?php echo ((is_array($_tmp='List Specific Public Archive')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
				<div class="h2_content" style="font-size:12px;">
					<p>
						<?php echo ((is_array($_tmp="View the message archive that is filtered to campaigns from a particular List.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					</p>
					<p>
						<?php echo ((is_array($_tmp="Select A List:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
						<select id="listSelect" size="1" onchange="buildLink('list');">
							<?php $_from = $this->_tpl_vars['lists']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['lloop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['lloop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['p']):
        $this->_foreach['lloop']['iteration']++;
?>
								<option value="<?php if ($this->_tpl_vars['seo']):  echo $this->_tpl_vars['p']['stringid'];  else:  echo $this->_tpl_vars['p']['id'];  endif; ?>" <?php if (($this->_foreach['lloop']['iteration'] == $this->_foreach['lloop']['total'])): ?>selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['p']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>
</option>
							<?php endforeach; endif; unset($_from); ?>
						</select>
					</p>
					<p>
						<a id="listURL" class="publiclink" href="<?php echo $this->_tpl_vars['links']['list'];  if ($this->_tpl_vars['seo']):  echo $this->_tpl_vars['p']['stringid'];  else:  echo $this->_tpl_vars['p']['id'];  endif; ?>" target="_blank"><?php echo $this->_tpl_vars['links']['list'];  if ($this->_tpl_vars['seo']):  echo $this->_tpl_vars['p']['stringid'];  else:  echo $this->_tpl_vars['p']['id'];  endif; ?></a>
					</p>
				</div>
			</div>

		</div>

	</div>

	<div id="form_list_other_api">

		<div style="margin-left: 15px;">

			<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="0" width="99%">

				<tr>

					<td valign="top" width="325">

						<ul>
							<?php $_from = $this->_tpl_vars['api_example_filenames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['counter'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['counter']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['filename']):
        $this->_foreach['counter']['iteration']++;
?>
								<?php if (! adesk_site_hosted_rsid ( ) || substr ( $this->_tpl_vars['filename'] , 0 , 9 ) != 'branding_'): ?>
								<li><a href="javascript: form_list_other_api_load('<?php echo $this->_tpl_vars['filename']; ?>
');"><?php echo $this->_tpl_vars['filename']; ?>
</a></li>
								<?php endif; ?>
							<?php endforeach; endif; unset($_from); ?>
						</ul>

					</td>

					<td valign="top">

						<h2 id="form_list_other_api_filename" style="background: #ffc; font-weight: bold;"><?php echo $this->_tpl_vars['api_example_filename1']; ?>
</h5><div class="line"></div>

						<div id="form_list_other_api_content_div">
							<textarea id="form_list_other_api_content" class="brush: php" style="height: 600px; width: 100%;" wrap="off"><?php echo $this->_tpl_vars['api_example_content1']; ?>
</textarea>
						</div>

					</td>

				</tr>

			</table></div>

		</div>

	</div>

	<div id="form_list_other_advanced">

		<div style="margin-left: 15px;">

			<div class="h2_wrap">
				<h3 onclick="adesk_dom_toggle_class('formlinkspanel', 'h2_content_invis', 'h2_content');"><?php echo ((is_array($_tmp="Other Forms of Allowing Users To Subscribe - VIA LINK")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
				<div id="formlinkspanel" class="h2_content_invis">

				  <div class="question"><?php echo ((is_array($_tmp='Link to complete subscription form with option to subscribe to multiple lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				  <div class="answer"><?php echo ((is_array($_tmp="To link to the subscription form with giving the user the ability to subscribe to multiple lists, simply create a link pointing to:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				  <div class="explanation"><?php echo $this->_tpl_vars['site']['p_link']; ?>
/index.php?action=subscribe&amp;nl=<?php if (intval ( $this->_tpl_vars['nl'] ) > 0):  echo $this->_tpl_vars['nl'];  else: ?>[LISTID]<?php endif; ?></div>

				  <hr width="100%" size="1" noshade />

				  <div class="question"><?php echo ((is_array($_tmp='Link to complete subscription form without option to subscribe to multiple lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				  <div class="answer"><?php echo ((is_array($_tmp="To link to the subscription form without giving the user the ability to subscribe to multiple lists, simply create a link pointing to:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				  <div class="explanation"><?php echo $this->_tpl_vars['site']['p_link']; ?>
/index.php?action=subscribe&amp;mlt=no&amp;nl=<?php if (intval ( $this->_tpl_vars['nl'] ) > 0):  echo $this->_tpl_vars['nl'];  else: ?>[LISTID]<?php endif; ?></div>

				  <hr width="100%" size="1" noshade />

				  <div class="question"><?php echo ((is_array($_tmp="Link to UN-Subscribe form without option to unsubscribe from multiple lists")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				  <div class="answer"><?php echo ((is_array($_tmp="To link to the UN-subscribe form without giving the user the ability to unsubscribe from multiple lists, simply create a link pointing to:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				  <div class="explanation"><?php echo $this->_tpl_vars['site']['p_link']; ?>
/index.php?action=unsubscribe&amp;mlt=no&amp;nl=<?php if (intval ( $this->_tpl_vars['nl'] ) > 0):  echo $this->_tpl_vars['nl'];  else: ?>[LISTID]<?php endif; ?></div>

				</div>
			</div>

			<div class="h2_wrap">
			  <h3 onclick="adesk_dom_toggle_class('formredirectpanel', 'h2_content_invis', 'h2_content');"><?php echo ((is_array($_tmp="Optional Redirection Pages - Further Information")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
			  <div id="formredirectpanel" class="h2_content_invis">

			    <ul>
			      <li><?php echo ((is_array($_tmp="By entering a url in any of the fields, the user will be redirected to that url when the action takes place.<br />IE: you enter http://www.example.com/thanks.htm in the &quot;Successful Completed Subscription URL&quot; field. Then when a user successfully subscribes to your list he or she would be redirected to http://www.example.com/thanks.htm instantly upon filling out the initial form. The users information will be added to your list in the background while redirection is taking place.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
			      <li><?php echo ((is_array($_tmp="Pre-Confirmed Subscription URL or Pre-Confirmed Un-Subscription URL will only be used when you have Require Opt-In/Opt-Out turned on.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</li>
			      <li>
			      <?php echo ((is_array($_tmp="You may further customize your redirection pages by including the system generated messages within your redirection page.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
			      <?php echo ((is_array($_tmp="Here's how it works:  When processing a subscriber's information, the system will generate two variables containing the necessary information.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br /><br />

			      <?php echo ((is_array($_tmp="The first variable is a comma seperated string containing all the IDs of your lists,")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			      <?php echo ((is_array($_tmp="and the second variable is a comma seperated string containing message codes for each list in the first string.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br /><br />

			      <?php echo ((is_array($_tmp="For example, let's say our lists variable contains \"1,4,2,7\", and the message codes variable contains \"4,8,5,2\".")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br /><br />

			      <?php echo ((is_array($_tmp="This would be interpreted like this:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br /><br />

			      <?php echo ((is_array($_tmp="List 1: Message code 4,")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
			      <?php echo ((is_array($_tmp="List 4: Message code 8,")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
			      <?php echo ((is_array($_tmp="List 2: Message code 5,")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
			      <?php echo ((is_array($_tmp="List 7: Message code 2,")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br /><br />

			      <?php echo ((is_array($_tmp="If you copy and paste the following code sample into your redirection page (assuming it is a .php page),")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			      <?php echo ((is_array($_tmp="it will print out the actual message text from the two variables passed in.  You can modify this code sample to better suit your needs.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br /><br />

			      <textarea style="width:100%;" rows="20" wrap="off"><?php echo smarty_function_adesk_printphp(array('str' => $this->_tpl_vars['assemble_error_codes'],'type' => 'string'), $this);?>
</textarea>
									    </li>
			  </ul>

			  </div>
			</div>

		</div>

	</div>

</div>