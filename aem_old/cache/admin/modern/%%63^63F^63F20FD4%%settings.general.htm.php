<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:51
         compiled from settings.general.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'settings.general.htm', 2, false),array('modifier', 'escape', 'settings.general.htm', 13, false),array('modifier', 'help', 'settings.general.htm', 15, false),array('modifier', 'adesk_ischecked', 'settings.general.htm', 87, false),)), $this); ?>
<div id="settings_general">
<h5><?php echo ((is_array($_tmp='General')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
	<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellpadding="5" cellspacing="0" class="adesk_blockquote">
				<tr style="display:none;">
			<td ><?php echo ((is_array($_tmp='Software URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
			<td>
				<input type="text" name="p_link" id="p_link" value="<?php if ($this->_tpl_vars['__ishosted'] && isset ( $this->_tpl_vars['site']['p_link_precname'] )):  echo ((is_array($_tmp=$this->_tpl_vars['site']['p_link_precname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  else:  echo ((is_array($_tmp=$this->_tpl_vars['site']['p_link'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  endif; ?>" style="width: 200px;background-color:#eee;" readonly="readonly" />
<?php if (! $this->_tpl_vars['__ishosted']): ?>
				<?php echo ((is_array($_tmp="The location of this software on the web. To change this, run Updater from a new location.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

<?php endif; ?>
			</td>
		</tr>
	<?php if ($this->_tpl_vars['__ishosted']): ?>
	<?php if ($this->_tpl_vars['__planid'] > 0 && ! adesk_site_hosted_rsid ( )): ?>
	<tr valign="top">
	  <td>
		<div style="float:right;">http://</div>
		<?php echo ((is_array($_tmp='Domain Alias')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </td>
	  <td>
			<?php if ($this->_tpl_vars['cnamefail'] != ""): ?>
			<div class="adesk_help_inline">
				<?php echo ((is_array($_tmp='Your CNAME record update was not successful')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:
				<?php echo $this->_tpl_vars['cnamefail']; ?>

			</div>
			<?php endif; ?>
		<input type="text" id="site_cname" name="site_cname" value="<?php echo $this->_tpl_vars['hosted_cname']; ?>
" style="width: 200px;" />
		<input type="button" id="site_cname_check" value="<?php echo ((is_array($_tmp='Check')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="settings_general_cname();" style="display:none;" />
		<br/>
		<br/>
		<a href="/manage/manage/ssl.php" target="_blank"><?php echo ((is_array($_tmp='Manage your SSL information')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
	  </td>
	</tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>
		<div id="hosted_cname_ok" class="adesk_hidden">
		  <?php echo ((is_array($_tmp="Your domain alias is currently setup and working properly.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		</div>
		<div id="hosted_cname_bad" class="adesk_hidden">
		  <?php echo ((is_array($_tmp="You have a domain alias set however it does not seem to be working properly at this time. If you just changed your DNS it may take time for your changes to go live.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		</div>
		<div class="hosted_cname_help">
		  <p>
		  <?php echo ((is_array($_tmp="To set a domain alias you need to create a CNAME record that points to your account url.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		  </p>

		  <p>
		  <?php echo ((is_array($_tmp="Example:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
		  <?php echo ((is_array($_tmp="If you wanted to create a alias for email.yourdomain.com to point to your account %s you would add a cname to your yourdomain.com domain like:")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['hosted_domain']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['hosted_domain'])); ?>

		  </p>

		  <p>
		  email 14400 IN CNAME <?php echo $this->_tpl_vars['hosted_domain']; ?>
.
		  </p>

		  <p>
		  <b><?php echo ((is_array($_tmp="It may take several hours (or longer) for your DNS changes to go live.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</b>
		  </p>
		</div>
	  </td>
	</tr>
	<?php elseif (! adesk_site_hosted_rsid ( )): ?>
	<tr>
	  <td>
		<?php echo ((is_array($_tmp='Domain Alias')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </td>
	  <td>
		<?php echo ((is_array($_tmp="Upgrade your plan to enable the domain alias feature.  With a domain alias you can set your account to run under your own domain name (such as email.company.com or www.emailcompany.com)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </td>
	</tr>
	<?php endif; ?>
	<?php endif; ?>
		<tr>
		  <td width="110"><?php echo ((is_array($_tmp='Default from')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><input type="text" name="emfrom" id="emfrom" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['site']['emfrom'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width: 200px;" /></td>
	  </tr>
	  <?php if ($this->_tpl_vars['site']['general_public']): ?>
		  <tr>
				<td><?php echo ((is_array($_tmp='Down for maintenance')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td><input type="checkbox" name="general_maint" value="1" onclick="general_maint_check(this.checked)" <?php echo ((is_array($_tmp=$this->_tpl_vars['site']['general_maint'])) ? $this->_run_mod_handler('adesk_ischecked', true, $_tmp) : smarty_modifier_adesk_ischecked($_tmp)); ?>
 /></td>
		  </tr>
	  <?php endif; ?>
		<tbody id="general_maint_tbody" <?php if ($this->_tpl_vars['site']['general_maint'] == 1): ?>class="adesk_table_rowgroup"<?php else: ?>class="adesk_hidden"<?php endif; ?>>
		  <tr valign="top">
			<td><?php echo ((is_array($_tmp='Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
			<td><textarea name="general_maint_message"><?php echo $this->_tpl_vars['site']['general_maint_message']; ?>
</textarea></td>
		  </tr>
		</tbody>
	  		<?php if (! $this->_tpl_vars['__ishosted'] && $this->_tpl_vars['rwCheck']['apache']): ?>
			  <tr>
					<td><?php echo ((is_array($_tmp='SEO friendly URLs')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
					<td>
					  <input type="checkbox" name="public_url_rewrite" value="1" onclick="public_rewrite_check(this.checked)" <?php echo ((is_array($_tmp=$this->_tpl_vars['site']['general_url_rewrite'])) ? $this->_run_mod_handler('adesk_ischecked', true, $_tmp) : smarty_modifier_adesk_ischecked($_tmp)); ?>
 />
					  <?php echo ((is_array($_tmp="This feature will use Apache's mod_rewrite plugin to reformat its public URLs to appear more descriptive, mentioning for example the title of a list or form in the URL.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

					</td>
			  </tr>
			<tbody id="public_rewrite_tbody" <?php if ($this->_tpl_vars['site']['general_url_rewrite'] == 1): ?>class="adesk_table_rowgroup"<?php else: ?>class="adesk_hidden"<?php endif; ?>>
			  <tr valign="top">
					<td>&nbsp;</td>
					<td>
					  <a href="#public" onclick="public_rewrite_htaccess();"><?php echo ((is_array($_tmp="View .htaccess Content")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				      <?php echo ((is_array($_tmp="Upon saving, the system will try to add this content to your .htaccess file if it doesn't have it already. If the file cannot be written to, you will have to put this into file %s")) ? $this->_run_mod_handler('help', true, $_tmp, $this->_tpl_vars['htaccess']) : smarty_modifier_help($_tmp, $this->_tpl_vars['htaccess'])); ?>

					</td>
			  </tr>
			</tbody>
			<tr>
			  <td><?php echo ((is_array($_tmp='Max upload size')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
			  <td>
				<input type="text" name="maxuploadfilesize" value="<?php echo $this->_tpl_vars['site']['maxuploadfilesize']; ?>
" style="width:30px;" /> (MB's)
				<?php echo ((is_array($_tmp="Your server imposes the following limits: maximum file upload size is %s and maximum post size is %s.")) ? $this->_run_mod_handler('help', true, $_tmp, $this->_tpl_vars['uploadLimit'], $this->_tpl_vars['postLimit']) : smarty_modifier_help($_tmp, $this->_tpl_vars['uploadLimit'], $this->_tpl_vars['postLimit'])); ?>

			  </td>
			</tr>

		<?php endif; ?>
	  <tr>
			<td><?php echo ((is_array($_tmp='Disable public section')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
			<td><input type="checkbox" name="general_public" value="1" <?php if ($this->_tpl_vars['site']['general_public'] == 0): ?>checked="checked"<?php endif; ?> /></td>
	  </tr>

	</table></div>
</div>


<div id="htaccess" class="adesk_modal" align="center" style="display: none;">
  <div class="adesk_modal_inner" style="width: 500px; text-align: center;">
     <div>
       <?php echo ((is_array($_tmp="Upon saving, the system will try to add this content to your .htaccess file if it doesn't have it already.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
       <?php echo ((is_array($_tmp="If the file cannot be written to, you will have to put this into file:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
       <?php echo $this->_tpl_vars['htaccess']; ?>

     </div>
     <textarea cols="50" rows="10" readonly onclick="adesk_form_highlight(this);">
# <?php echo ((is_array($_tmp='URL Rewrite Support')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>


Options All -Indexes

ErrorDocument 404 <?php echo $this->_tpl_vars['URI']; ?>
/index.php


&lt;IfModule mod_rewrite.c&gt;
RewriteEngine On
RewriteBase <?php echo $this->_tpl_vars['URI']; ?>
/
<?php echo '
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
#RewriteCond %{REQUEST_URI} !\\.(jpg|jpeg|png|gif|css|js)$
'; ?>

RewriteRule . <?php echo $this->_tpl_vars['URI']; ?>
/index.php [L]
&lt;/IfModule&gt;
    </textarea>
<?php if ($this->_tpl_vars['rwCheck']['iis']): ?>
    <div class="warning"><?php echo ((is_array($_tmp="You will need to have ISAPI_Rewrite filter turned on in your IIS server.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
<?php endif; ?>
    <div align="center"><input type="button" value="<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="$('htaccess').style.display = 'none';" /></div>
  </div>
</div>