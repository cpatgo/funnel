<?php /* Smarty version 2.6.12, created on 2016-07-08 14:15:47
         compiled from autocomplete.inc.htm */ ?>
<?php if (! isset ( $this->_tpl_vars['base'] )):  if (preg_match ( '/\/manage\//' , adesk_http_geturl ( ) )):  $this->assign('base', '../');  else:  $this->assign('base', '');  endif;  endif; ?> <?php if (! isset ( $this->_tpl_vars['fieldPrefix'] )):  $this->assign('fieldPrefix', 'user');  endif; ?>
<?php if (! isset ( $this->_tpl_vars['fieldAction'] )):  $this->assign('fieldAction', $this->_tpl_vars['fieldPrefix']);  endif; ?>
<?php if (! isset ( $this->_tpl_vars['fieldID'] )):  $this->assign('fieldID', 'userField');  endif; ?>
<?php if (! isset ( $this->_tpl_vars['fieldName'] )):  $this->assign('fieldName', 'userid');  endif; ?>
<?php if (! isset ( $this->_tpl_vars['fieldValue'] )):  $this->assign('fieldValue', '');  endif; ?>
<?php if (! isset ( $this->_tpl_vars['fieldClass'] )):  $this->assign('fieldClass', '');  endif; ?>
<?php if (! isset ( $this->_tpl_vars['fieldStyle'] )):  $this->assign('fieldStyle', '');  endif; ?>
<?php if (! isset ( $this->_tpl_vars['fieldOnClick'] )):  $this->assign('fieldOnClick', '');  endif; ?>
<?php if (! isset ( $this->_tpl_vars['acOnUpdate'] )):  $this->assign('acOnUpdate', 'null');  endif; ?>
<?php if (! isset ( $this->_tpl_vars['acInUpdate'] )):  $this->assign('acInUpdate', 'null');  endif; ?>
<?php if (! isset ( $this->_tpl_vars['addlParams'] )):  $this->assign('addlParams', '');  endif; ?>

<input
	type="text"
	id="<?php echo $this->_tpl_vars['fieldID']; ?>
"
	name="<?php echo $this->_tpl_vars['fieldName']; ?>
"
	value="<?php echo $this->_tpl_vars['fieldValue']; ?>
"
	<?php if (isset ( $this->_tpl_vars['fieldSize'] )): ?> size="<?php echo $this->_tpl_vars['fieldSize']; ?>
"<?php endif; ?>
	<?php if ($this->_tpl_vars['fieldClass']): ?> class="<?php echo $this->_tpl_vars['fieldClass']; ?>
"<?php endif; ?>
	<?php if ($this->_tpl_vars['fieldStyle'] != ''): ?> style="<?php echo $this->_tpl_vars['fieldStyle']; ?>
"<?php endif; ?>
	<?php if ($this->_tpl_vars['fieldOnClick'] != ''): ?> onclick="<?php echo $this->_tpl_vars['fieldOnClick']; ?>
"<?php endif; ?>
/>

<div class="adesk_autocomplete" id="<?php echo $this->_tpl_vars['fieldID']; ?>
_autocomplete"></div>

<script type="text/javascript">
<!--
var <?php echo $this->_tpl_vars['fieldPrefix']; ?>
_auto_completer = new Ajax.Autocompleter(
	'<?php echo $this->_tpl_vars['fieldID']; ?>
', // id of input field
	'<?php echo $this->_tpl_vars['fieldID']; ?>
_autocomplete', // id of dropdown list
	'awebdeskapi.php?f=<?php echo $this->_tpl_vars['fieldAction']; ?>
_autocomplete&p[]=<?php echo $this->_tpl_vars['fieldName'];  if ($this->_tpl_vars['addlParams']):  $_from = $this->_tpl_vars['addlParams']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?>&p[]=<?php echo $this->_tpl_vars['value'];  endforeach; endif; unset($_from);  endif; ?>', // ajax url to call
	<?php echo '{'; ?>
 // with additional settings
		minChars : 3, // trigger when
		indicator : null,//'<?php echo $this->_tpl_vars['fieldID']; ?>
_loading', // yes, show loading indicator
		afterUpdateElement : <?php echo $this->_tpl_vars['acOnUpdate']; ?>
, // should we do something with a result (onchange)?
		updateElement : <?php echo $this->_tpl_vars['acInUpdate']; ?>
 // should we do something with a result (onchange)?
	<?php echo '}'; ?>

);
-->
</script>