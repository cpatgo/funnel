<?php /* Smarty version 2.6.12, created on 2016-07-08 16:50:20
         compiled from account.settings.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'account.settings.htm', 2, false),array('modifier', 'adesk_isselected', 'account.settings.htm', 5, false),array('modifier', 'help', 'account.settings.htm', 69, false),)), $this); ?>
      <tr valign="top">
      <td><?php echo ((is_array($_tmp='Default Dashboard theme')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
      <td>
		<select name="default_dashboard">
		  <option value="modern" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['default_dashboard'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 'modern') : smarty_modifier_adesk_isselected($_tmp, 'modern')); ?>
>Modern</option>
<option value="mighty" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['default_dashboard'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 'mighty') : smarty_modifier_adesk_isselected($_tmp, 'mighty')); ?>
>Mighty</option>
		  <option value="classic" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['default_dashboard'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 'classic') : smarty_modifier_adesk_isselected($_tmp, 'classic')); ?>
>Classic</option>
		  <option value="arabic" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['default_dashboard'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 'arabic') : smarty_modifier_adesk_isselected($_tmp, 'arabic')); ?>
>Arabic(RTL)</option>
        </select>
	  </td>
      </td>
    </tr> 
            <tr valign="top">
      <td><?php echo ((is_array($_tmp='Default Mobile Dashboard theme')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
      <td>
		<select name="default_mobdashboard">
		  <option value="modern" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['default_mobdashboard'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 'modern') : smarty_modifier_adesk_isselected($_tmp, 'modern')); ?>
>Modern</option> 

		  
		  
 
		</select>
	  </td>
      </td>
    </tr> 
      
    <tr valign="top">
      <td><?php echo ((is_array($_tmp='Lists per Page')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
      <td>
		<select name="lists_per_page">
		  <option value="5" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['lists_per_page'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 5) : smarty_modifier_adesk_isselected($_tmp, 5)); ?>
>5</option>
		  <option value="10" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['lists_per_page'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 10) : smarty_modifier_adesk_isselected($_tmp, 10)); ?>
>10</option>
		  <option value="20" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['lists_per_page'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 20) : smarty_modifier_adesk_isselected($_tmp, 20)); ?>
>20</option>
		  <option value="50" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['lists_per_page'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 50) : smarty_modifier_adesk_isselected($_tmp, 50)); ?>
>50</option>
		  <option value="100" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['lists_per_page'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 100) : smarty_modifier_adesk_isselected($_tmp, 100)); ?>
>100</option>
		</select>
	  </td>
      </td>
    </tr>
    <tr valign="top">
      <td><?php echo ((is_array($_tmp='Messages per Page')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
      <td>
		<select name="messages_per_page">
		  <option value="5" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['messages_per_page'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 5) : smarty_modifier_adesk_isselected($_tmp, 5)); ?>
>5</option>
		  <option value="10" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['messages_per_page'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 10) : smarty_modifier_adesk_isselected($_tmp, 10)); ?>
>10</option>
		  <option value="20" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['messages_per_page'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 20) : smarty_modifier_adesk_isselected($_tmp, 20)); ?>
>20</option>
		  <option value="50" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['messages_per_page'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 50) : smarty_modifier_adesk_isselected($_tmp, 50)); ?>
>50</option>
		  <option value="100" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['messages_per_page'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 100) : smarty_modifier_adesk_isselected($_tmp, 100)); ?>
>100</option>
		</select>
      </td>
    </tr>
    <tr valign="top">
      <td><?php echo ((is_array($_tmp='Subscribers per Page')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
      <td>
		<select name="subscribers_per_page">
		  <option value="5" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['subscribers_per_page'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 5) : smarty_modifier_adesk_isselected($_tmp, 5)); ?>
>5</option>
		  <option value="10" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['subscribers_per_page'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 10) : smarty_modifier_adesk_isselected($_tmp, 10)); ?>
>10</option>
		  <option value="20" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['subscribers_per_page'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 20) : smarty_modifier_adesk_isselected($_tmp, 20)); ?>
>20</option>
		  <option value="50" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['subscribers_per_page'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 50) : smarty_modifier_adesk_isselected($_tmp, 50)); ?>
>50</option>
		  <option value="100" <?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['subscribers_per_page'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 100) : smarty_modifier_adesk_isselected($_tmp, 100)); ?>
>100</option>
		</select>
      </td>
    </tr>
	<tr valign="top">
	  <td><?php echo ((is_array($_tmp='Default message editor size')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
	  <td>
		<input type="text" name="editorsize_w" id="editorsize_w" value="<?php echo $this->_tpl_vars['admin']['editorsize_w']; ?>
"> <?php echo ((is_array($_tmp="(width)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br>
		<input type="text" name="editorsize_h" id="editorsize_h" value="<?php echo $this->_tpl_vars['admin']['editorsize_h']; ?>
"> <?php echo ((is_array($_tmp="(height)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php echo ((is_array($_tmp="Both values should be given as CSS values; 100% would be 100% of the allowed area for the editor, or 600px for 600 pixels.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

	  </td>
	</tr>
    <tr>
      <td valign="top"><?php echo ((is_array($_tmp='Auto Update Pages')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
      <td>
        <select name="autoupdate" id="autoupdate" size="1">
          <option value="30"<?php if ($this->_tpl_vars['admin']['autoupdate'] == 30): ?> selected<?php endif; ?>><?php echo ((is_array($_tmp='Update every 30 seconds')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="60"<?php if ($this->_tpl_vars['admin']['autoupdate'] == 60): ?> selected<?php endif; ?>><?php echo ((is_array($_tmp='Update every minute')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="120"<?php if ($this->_tpl_vars['admin']['autoupdate'] == 120): ?> selected<?php endif; ?>><?php echo ((is_array($_tmp='Update every 2 minutes')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="180"<?php if ($this->_tpl_vars['admin']['autoupdate'] == 180): ?> selected<?php endif; ?>><?php echo ((is_array($_tmp='Update every 3 minutes')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="300"<?php if ($this->_tpl_vars['admin']['autoupdate'] == 300): ?> selected<?php endif; ?>><?php echo ((is_array($_tmp='Update every 5 minutes')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="600"<?php if ($this->_tpl_vars['admin']['autoupdate'] == 600): ?> selected<?php endif; ?>><?php echo ((is_array($_tmp='Update every 10 minutes')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
        </select>
      </td>
    </tr>
    