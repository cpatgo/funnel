<?php /* Smarty version 2.6.12, created on 2016-07-13 16:06:17
         compiled from form.code.xml.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'form.code.xml.htm', 11, false),array('modifier', 'alang', 'form.code.xml.htm', 52, false),array('function', 'math', 'form.code.xml.htm', 38, false),)), $this); ?>
<flashform postForm="<?php echo $this->_tpl_vars['site']['p_link']; ?>
/surround.php" stagecolor="0x<?php echo $this->_tpl_vars['form']['background_color']; ?>
">
    <subfield type="text" name="email" value="test@example.com" display="Email:" xpos="100" ypos="20" width="130" height="20" fontsize="<?php echo $this->_tpl_vars['form']['font_size']; ?>
" fontfamily="<?php echo $this->_tpl_vars['form']['font_family']; ?>
" fontcolor="0x<?php echo $this->_tpl_vars['form']['font_color']; ?>
" color="0x<?php echo $this->_tpl_vars['form']['input_color']; ?>
"></subfield>
<?php if ($this->_tpl_vars['form']['ask4fname']): ?>
    <subfield type="text" name="name" value="Your Name Here" display="Name:" <?php if (! $this->_tpl_vars['form']['require_name']): ?>required="no"<?php endif; ?> xpos="100" ypos="50" width="130" height="20" fontsize="<?php echo $this->_tpl_vars['form']['font_size']; ?>
" fontfamily="<?php echo $this->_tpl_vars['form']['font_family']; ?>
" fontcolor="0x<?php echo $this->_tpl_vars['form']['font_color']; ?>
" color="0x<?php echo $this->_tpl_vars['form']['input_color']; ?>
"></subfield>
<?php endif; ?>
<?php if ($this->_tpl_vars['form']['ask4lname']): ?>
    <subfield type="text" name="name" value="Your Name Here" display="Name:" <?php if (! $this->_tpl_vars['form']['require_name']): ?>required="no"<?php endif; ?> xpos="100" ypos="70" width="130" height="20" fontsize="<?php echo $this->_tpl_vars['form']['font_size']; ?>
" fontfamily="<?php echo $this->_tpl_vars['form']['font_family']; ?>
" fontcolor="0x<?php echo $this->_tpl_vars['form']['font_color']; ?>
" color="0x<?php echo $this->_tpl_vars['form']['input_color']; ?>
"></subfield>
<?php endif; ?>
<?php $_from = $this->_tpl_vars['form']['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
    <?php if ($this->_tpl_vars['field']['type'] == 6): ?>
    <subfield type="hidden" name="field[<?php echo $this->_tpl_vars['field']['id']; ?>
,<?php echo $this->_tpl_vars['field']['dataid']; ?>
]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['field']['val'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"></subfield>
    <?php else: ?>
    <?php if ($this->_tpl_vars['field']['type'] == 1): ?>
    <subfield type="text" name="field[<?php echo $this->_tpl_vars['field']['id']; ?>
,<?php echo $this->_tpl_vars['field']['dataid']; ?>
]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['field']['val'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" <?php if (! $this->_tpl_vars['field']['req']): ?>required="no"<?php endif; ?> display="<?php echo $this->_tpl_vars['field']['title']; ?>
" title="" xpos="100" ypos="<?php echo $this->_tpl_vars['field']['ypos']; ?>
" width="130" height="20" fontsize="<?php echo $this->_tpl_vars['form']['font_size']; ?>
" fontfamily="<?php echo $this->_tpl_vars['form']['font_family']; ?>
" fontcolor="0x<?php echo $this->_tpl_vars['form']['font_color']; ?>
" color="0x<?php echo $this->_tpl_vars['form']['input_color']; ?>
"></subfield>
    <?php elseif ($this->_tpl_vars['field']['type'] == 2): ?>
    <subfield type="textarea" name="field[<?php echo $this->_tpl_vars['field']['id']; ?>
,<?php echo $this->_tpl_vars['field']['dataid']; ?>
]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['field']['val'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" columns="<?php echo $this->_tpl_vars['field']['cols']; ?>
" rows="<?php echo $this->_tpl_vars['field']['rows']; ?>
" <?php if (! $this->_tpl_vars['field']['req']): ?>required="no"<?php endif; ?> display="<?php echo $this->_tpl_vars['field']['title']; ?>
" xpos="40" ypos="<?php echo $this->_tpl_vars['field']['ypos']; ?>
" fontsize="<?php echo $this->_tpl_vars['form']['font_size']; ?>
" fontfamily="<?php echo $this->_tpl_vars['form']['font_family']; ?>
" fontcolor="0x<?php echo $this->_tpl_vars['form']['font_color']; ?>
" color="0x<?php echo $this->_tpl_vars['form']['input_color']; ?>
"></subfield>
    <?php elseif ($this->_tpl_vars['field']['type'] == 3): ?>
    <subfield type="checkbox" name="field[<?php echo $this->_tpl_vars['field']['id']; ?>
,<?php echo $this->_tpl_vars['field']['dataid']; ?>
]" value="<?php echo $this->_tpl_vars['field']['expl']; ?>
" checked="<?php if ($this->_tpl_vars['field']['val']): ?>true<?php else: ?>false<?php endif; ?>" <?php if (! $this->_tpl_vars['field']['req']): ?>required="no"<?php endif; ?> display="<?php echo $this->_tpl_vars['field']['title']; ?>
" title="" xpos="40" ypos="<?php echo $this->_tpl_vars['field']['ypos']; ?>
" fontsize="<?php echo $this->_tpl_vars['form']['font_size']; ?>
" fontfamily="<?php echo $this->_tpl_vars['form']['font_family']; ?>
" fontcolor="0x<?php echo $this->_tpl_vars['form']['font_color']; ?>
" color="0x<?php echo $this->_tpl_vars['form']['input_color']; ?>
"></subfield>
    <?php elseif ($this->_tpl_vars['field']['type'] == 4): ?>
    <?php $_from = $this->_tpl_vars['field']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['radio']):
?>
    <subfield type="radio" name="field[<?php echo $this->_tpl_vars['field']['id']; ?>
,<?php echo $this->_tpl_vars['field']['dataid']; ?>
]" value="<?php echo $this->_tpl_vars['radio']['value']; ?>
" checked="<?php if ($this->_tpl_vars['radio']['checked']): ?>true<?php else: ?>false<?php endif; ?>" display="<?php echo $this->_tpl_vars['radio']['name']; ?>
" title="<?php echo $this->_tpl_vars['radio']['title']; ?>
" xpos="40" ypos="<?php echo $this->_tpl_vars['radio']['ypos']; ?>
" fontsize="<?php echo $this->_tpl_vars['form']['font_size']; ?>
" fontfamily="<?php echo $this->_tpl_vars['form']['font_family']; ?>
" fontcolor="0x<?php echo $this->_tpl_vars['form']['font_color']; ?>
" color="0x<?php echo $this->_tpl_vars['form']['input_color']; ?>
"></subfield>
    <?php endforeach; endif; unset($_from); ?>
    <?php elseif ($this->_tpl_vars['field']['type'] == 5): ?>
    <subfield type="select" name="field[<?php echo $this->_tpl_vars['field']['id']; ?>
,<?php echo $this->_tpl_vars['field']['dataid']; ?>
]" <?php if (! $this->_tpl_vars['field']['req']): ?>required="no"<?php endif; ?> display="<?php echo $this->_tpl_vars['field']['title']; ?>
" title="<?php echo $this->_tpl_vars['field']['title']; ?>
" xpos="40" ypos="<?php echo $this->_tpl_vars['field']['ypos']; ?>
" width="130" height="20" fontsize="<?php echo $this->_tpl_vars['form']['font_size']; ?>
" fontfamily="<?php echo $this->_tpl_vars['form']['font_family']; ?>
" fontcolor="0x<?php echo $this->_tpl_vars['form']['font_color']; ?>
" color="0x<?php echo $this->_tpl_vars['form']['input_color']; ?>
">
      <?php $_from = $this->_tpl_vars['field']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['option_value'] => $this->_tpl_vars['option_name']):
?>
      <subfieldoption value="<?php echo $this->_tpl_vars['option_value']; ?>
" selected="<?php if ($this->_tpl_vars['field']['selected'] == $this->_tpl_vars['option_value']): ?>true<?php else: ?>false<?php endif; ?>" display="<?php echo $this->_tpl_vars['option_name']; ?>
"></subfieldoption>
      <?php endforeach; endif; unset($_from); ?>
    </subfield>
    <?php elseif ($this->_tpl_vars['field']['type'] == 7): ?>
    <subfield type="multiselect" name="field[<?php echo $this->_tpl_vars['field']['id']; ?>
,<?php echo $this->_tpl_vars['field']['dataid']; ?>
]" <?php if (! $this->_tpl_vars['field']['req']): ?>required="no"<?php endif; ?> display="<?php echo $this->_tpl_vars['field']['title']; ?>
" title="<?php echo $this->_tpl_vars['field']['title']; ?>
" xpos="40" ypos="<?php echo $this->_tpl_vars['field']['ypos']; ?>
" width="130" size="4" height="80" fontsize="<?php echo $this->_tpl_vars['form']['font_size']; ?>
" fontfamily="<?php echo $this->_tpl_vars['form']['font_family']; ?>
" fontcolor="0x<?php echo $this->_tpl_vars['form']['font_color']; ?>
" color="0x<?php echo $this->_tpl_vars['form']['input_color']; ?>
">
      <?php $_from = $this->_tpl_vars['field']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['option_value'] => $this->_tpl_vars['option_name']):
?>
      <subfieldoption value="<?php echo $this->_tpl_vars['option_value']; ?>
" selected="<?php echo $this->_tpl_vars['field']['_selected']; ?>
" display="<?php echo $this->_tpl_vars['option_name']; ?>
"></subfieldoption>
      <?php endforeach; endif; unset($_from); ?>
    </subfield>
    <?php elseif ($this->_tpl_vars['field']['type'] == 8): ?>
    <subfield type="multicheckbox" name="field[<?php echo $this->_tpl_vars['field']['id']; ?>
,<?php echo $this->_tpl_vars['field']['dataid']; ?>
]" <?php if (! $this->_tpl_vars['field']['req']): ?>required="no"<?php endif; ?> display="<?php echo $this->_tpl_vars['field']['title']; ?>
" title="<?php echo $this->_tpl_vars['field']['title']; ?>
" xpos="40" ypos="<?php echo $this->_tpl_vars['field']['ypos']; ?>
" width="130" size="<?php echo $this->_tpl_vars['field']['_size']; ?>
" height="<?php echo $this->_tpl_vars['field']['_height']; ?>
" fontsize="<?php echo $this->_tpl_vars['form']['font_size']; ?>
" fontfamily="<?php echo $this->_tpl_vars['form']['font_family']; ?>
" fontcolor="0x<?php echo $this->_tpl_vars['form']['font_color']; ?>
" color="0x<?php echo $this->_tpl_vars['form']['input_color']; ?>
">
      <?php $_from = $this->_tpl_vars['field']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['multicheckbox_foreach'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['multicheckbox_foreach']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['option_value'] => $this->_tpl_vars['option_name']):
        $this->_foreach['multicheckbox_foreach']['iteration']++;
?>
      <subfieldoption value="<?php echo $this->_tpl_vars['option_value']; ?>
" checked="<?php echo $this->_tpl_vars['field']['_selected']; ?>
" display="<?php echo $this->_tpl_vars['option_name']; ?>
" title="" xpos="40" ypos="<?php echo smarty_function_math(array('equation' => "x + (y * 20)",'x' => $this->_tpl_vars['field']['ypos'],'y' => $this->_foreach['multicheckbox_foreach']['iteration']), $this);?>
" fontsize="<?php echo $this->_tpl_vars['form']['font_size']; ?>
" fontfamily="<?php echo $this->_tpl_vars['form']['font_family']; ?>
" fontcolor="0x<?php echo $this->_tpl_vars['form']['font_color']; ?>
" color="0x<?php echo $this->_tpl_vars['form']['input_color']; ?>
"></subfieldoption>
      <?php endforeach; endif; unset($_from); ?>
    </subfield>
    <?php endif; ?>
    <?php endif; ?>
<?php endforeach; endif; unset($_from); ?>

<?php $_from = $this->_tpl_vars['form']['lists']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['listcounter'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['listcounter']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['list']):
        $this->_foreach['listcounter']['iteration']++;
?>
<?php if ($this->_tpl_vars['form']['allowselection']): ?>
    <subfield type="checkbox" name="nlbox[<?php echo $this->_foreach['listcounter']['iteration']; ?>
]" value="<?php echo $this->_tpl_vars['list']['id']; ?>
" checked="true" display="<?php echo $this->_tpl_vars['list']['name']; ?>
" title="" xpos="40" ypos="<?php echo $this->_tpl_vars['list']['ypos']; ?>
" fontsize="<?php echo $this->_tpl_vars['form']['font_size']; ?>
" fontfamily="<?php echo $this->_tpl_vars['form']['font_family']; ?>
" fontcolor="0x<?php echo $this->_tpl_vars['form']['font_color']; ?>
" color="0x<?php echo $this->_tpl_vars['form']['input_color']; ?>
"></subfield>
<?php else: ?>
     <subfield type="hidden" name="nlbox[<?php echo $this->_foreach['listcounter']['iteration']; ?>
]" value="<?php echo $this->_tpl_vars['list']['id']; ?>
"></subfield>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
    <subfield type="radio" name="funcml" value="add" checked="true" display="<?php echo ((is_array($_tmp='Subscribe')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" title="" xpos="40" ypos="<?php echo $this->_tpl_vars['form']['subscribe_ypos']; ?>
" fontsize="<?php echo $this->_tpl_vars['form']['font_size']; ?>
" fontfamily="<?php echo $this->_tpl_vars['form']['font_family']; ?>
" fontcolor="0x<?php echo $this->_tpl_vars['form']['font_color']; ?>
" color="0x<?php echo $this->_tpl_vars['form']['input_color']; ?>
"></subfield>
    <subfield type="radio" name="funcml" value="unsub2" checked="" display="<?php echo ((is_array($_tmp='Unsubscribe')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" title="" xpos="40" ypos="<?php echo $this->_tpl_vars['form']['unsubscribe_ypos']; ?>
" fontsize="<?php echo $this->_tpl_vars['form']['font_size']; ?>
" fontfamily="<?php echo $this->_tpl_vars['form']['font_family']; ?>
" fontcolor="0x<?php echo $this->_tpl_vars['form']['font_color']; ?>
" color="0x<?php echo $this->_tpl_vars['form']['input_color']; ?>
"></subfield>
    <subfield type="hidden" name="p" value="<?php echo $this->_tpl_vars['form']['id']; ?>
"></subfield>
    <subfield type="submit" name="submit" value="submit" display="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" xpos="40" ypos="<?php echo $this->_tpl_vars['form']['submit_ypos']; ?>
" height="10" fontsize="<?php echo $this->_tpl_vars['form']['font_size']; ?>
" fontfamily="<?php echo $this->_tpl_vars['form']['font_family']; ?>
" fontcolor="0x<?php echo $this->_tpl_vars['form']['font_color']; ?>
" buttonheight="20.8" buttonwidth="100" color="0x<?php echo $this->_tpl_vars['form']['input_color']; ?>
"></subfield>
</flashform>