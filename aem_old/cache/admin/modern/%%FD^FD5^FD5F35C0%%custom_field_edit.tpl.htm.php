<?php /* Smarty version 2.6.12, created on 2016-07-14 14:06:34
         compiled from custom_field_edit.tpl.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'adesk_modes', 'custom_field_edit.tpl.htm', 1, false),array('function', 'adesk_js', 'custom_field_edit.tpl.htm', 2, false),array('function', 'adesk_back', 'custom_field_edit.tpl.htm', 337, false),array('modifier', 'alang', 'custom_field_edit.tpl.htm', 13, false),array('modifier', 'js', 'custom_field_edit.tpl.htm', 13, false),array('modifier', 'default', 'custom_field_edit.tpl.htm', 18, false),array('modifier', 'adesk_field_type', 'custom_field_edit.tpl.htm', 170, false),array('modifier', 'html', 'custom_field_edit.tpl.htm', 182, false),array('modifier', 'adesk_isselected', 'custom_field_edit.tpl.htm', 202, false),array('modifier', 'adesk_ischecked_radio', 'custom_field_edit.tpl.htm', 208, false),array('modifier', 'escape', 'custom_field_edit.tpl.htm', 236, false),array('modifier', 'help', 'custom_field_edit.tpl.htm', 266, false),array('modifier', 'adesk_ischecked', 'custom_field_edit.tpl.htm', 275, false),array('modifier', 'adesk_isdisabled', 'custom_field_edit.tpl.htm', 300, false),array('modifier', 'truncate', 'custom_field_edit.tpl.htm', 300, false),)), $this); ?>
<?php echo smarty_function_adesk_modes(array('default' => 'add'), $this);?>

<?php echo smarty_function_adesk_js(array('lib' => "scriptaculous/prototype.js"), $this);?>

<?php echo smarty_function_adesk_js(array('lib' => "scriptaculous/scriptaculous.js"), $this);?>


<?php if ($this->_tpl_vars['ftype'] == 5 || $this->_tpl_vars['ftype'] == 7 || $this->_tpl_vars['ftype'] == 8): ?>
	<?php $this->assign('default_on', 'circle_green'); ?>
	<?php $this->assign('default_off', 'circle_grey'); ?>
<?php else: ?>
	<?php $this->assign('default_on', 'radio_checked'); ?>
	<?php $this->assign('default_off', 'radio_unchecked'); ?>
<?php endif; ?>
<script type="text/javascript">
  var text_default = '<?php echo ((is_array($_tmp=((is_array($_tmp='Default')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
  var text_remove  = '<?php echo ((is_array($_tmp=((is_array($_tmp='Remove')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
  var text_label   = '<?php echo ((is_array($_tmp=((is_array($_tmp='Label')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
  var text_value   = '<?php echo ((is_array($_tmp=((is_array($_tmp='Value')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

  var prefix       = '<?php echo ((is_array($_tmp=@$this->_tpl_vars['__'])) ? $this->_run_mod_handler('default', true, $_tmp, '..') : smarty_modifier_default($_tmp, '..')); ?>
';

  var custom_field_str_blank = '<?php echo ((is_array($_tmp="The custom field title cannot be blank and cannot contain only spaces.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
';

  var ftype        = '<?php echo $this->_tpl_vars['ftype']; ?>
';

  <?php if ($this->_tpl_vars['ftype'] == 5 || $this->_tpl_vars['ftype'] == 7 || $this->_tpl_vars['ftype'] == 8): ?>
	  var default_on   = 'circle_green';
	  var default_off  = 'circle_grey';
  <?php else: ?>
	  var default_on   = 'radio_checked';
	  var default_off  = 'radio_unchecked';
  <?php endif; ?>
  var values_count = 0;

  var default_label = '';
  var default_value = '';

	<?php if (isset ( $this->_tpl_vars['custom_field_form'] )): ?>
		<?php if ($this->_tpl_vars['custom_field_form'] != ''): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['custom_field_form'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>
	<?php endif; ?>

  <?php echo '
  function custom_field_validate() {
		if ($("title").value == "" || $("title").value.match(/^[ ]+$/)) {
		  alert(custom_field_str_blank);
		  return false;
		}

		if (typeof custom_field_validate_ihook == "function") {
			var validate_ihook = custom_field_validate_ihook();
			if (!validate_ihook) return false;
		}

		return true;
  }

  function make_default_label(node, index) {
    node = $("img_" + index.toString());
    if (ftype == 4 || ftype == 5)
      reset_bullets();

    if (ftype == 7 || ftype == 8) {
      if (node.src.match(default_on))
        node.src = prefix + "/awebdesk/media/" + default_off + ".gif";
      else
        node.src = prefix + "/awebdesk/media/" + default_on + ".gif";
    } else {
      node.src = prefix + "/awebdesk/media/" + default_on + ".gif";
    }

    if (ftype == 5)
      set_default(index);
    else
      set_default_all();
  }

  function add_value_fast(lab, val, imgsrc, isdefault) {
    values_count++;

    var img = Builder.node("img", {id: "img_" + values_count.toString(), onclick: "make_default_label(this, \'" + values_count.toString() + "\')"});

    if (imgsrc != "")
      img.src = imgsrc;
    else {
      if (values_count == 1)
        img.src = prefix + "/awebdesk/media/" + default_on + ".gif";
      else
        img.src = prefix + "/awebdesk/media/" + default_off + ".gif";
    }

    var div = Builder.node("div", {style: "position: relative", id: "values_" + values_count.toString()}, [
        img,
        " ",
        Builder.node("input", {onclick: "make_default_label(this, \'" + values_count.toString() + "\')", id: "label_" + values_count.toString(), name: "labels[]", type: "text", value: lab}),
        " ",
        Builder.node("input", {onblur: "if (this.id == default_label) set_default(\'" + values_count.toString() + "\')", title: text_value, id: "value_" + values_count.toString(), name: "values[]", type: "text", value: val}),
        " ",
        Builder.node("input", {onclick: "$(\'value_container\').removeChild($(\'values_" + values_count.toString() + "\'))", value: text_remove, type: "button", className: \'adesk_button_remove\'})
      ]
    );

    if (isdefault) {
      default_label = "label_" + values_count.toString();
      default_value = "value_" + values_count.toString();
    }

    $(\'value_container\').appendChild(div);
  }

  function add_value_slow(lab, val, imgsrc, isdefault) {
    add_value_fast(lab, val, imgsrc, isdefault);
    window.setTimeout(\'clear_inputs("\' + values_count.toString() + \'")\', 1200);
    reset_sorting();
  }

  function set_default(n) {
    document.getElementById("title_default").value = document.getElementById("label_" + n).value;
    document.getElementById("onfocus").value       = document.getElementById("value_" + n).value;
  }

  function set_default_all() {
    $("title_default").value = "";
    $("onfocus").value = "";
    for (var i = 1; i <= values_count; i++) {
      var ll = "label_" + i.toString();
      var vv = "value_" + i.toString();
      var ii = "img_" + i.toString();

      if ($(ii).src.match(default_on)) {
        if ($("title_default").value == "")
          $("title_default").value = $(ll).value;
        else
          $("title_default").value += "," + $(ll).value;

        if ($("onfocus").value == "")
          $("onfocus").value = $(vv).value;
        else
          $("onfocus").value += "||" + $(vv).value;
      }
    }
  }

  function clear_inputs(n) {
    document.getElementById("label_" + n).value = document.getElementById("label_" + n).value.replace(/^Label$/, \'\');
    document.getElementById("value_" + n).value = document.getElementById("value_" + n).value.replace(/^Value$/, \'\');
  }

  function reset_sorting() {
    Sortable.create("value_container", {ghosting: true, tag: \'div\', handle: \'drag\', dropOnEmpty: true, constraint: false});
  }

  function reset_bullets() {
    var elem;
    for (var i = 0; i <= values_count; i++) {
      elem = document.getElementById("img_" + i.toString());

      if (elem !== null) {
        elem.src = prefix + "/awebdesk/media/" + default_off +".gif";
      }
    }
  }
</script>

'; ?>


<?php if (isset ( $this->_tpl_vars['customfield_usersettings_header'] )): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user-settings.header.inc.htm", 'smarty_include_vars' => array('userpage' => 'user_field')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
	<?php if ($this->_tpl_vars['mode'] == 'add'): ?>
	<h3 class="m-b"><?php echo $this->_tpl_vars['pageTitle']; ?>
 > <?php echo ((is_array($_tmp='Add')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['ftype'])) ? $this->_run_mod_handler('adesk_field_type', true, $_tmp) : smarty_modifier_adesk_field_type($_tmp)); ?>
</h3>
	<?php else: ?>
	<h3 class="m-b"><?php echo $this->_tpl_vars['pageTitle']; ?>
 > <?php echo ((is_array($_tmp='Edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['ftype'])) ? $this->_run_mod_handler('adesk_field_type', true, $_tmp) : smarty_modifier_adesk_field_type($_tmp)); ?>
 <?php echo ((is_array($_tmp=@$this->_tpl_vars['field']['title'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
</h3>
	<?php endif; ?>
<?php endif; ?>

<div class="inner_content">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message.tpl.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <form action="desk.php" method="POST" onsubmit="return custom_field_validate()">
    <table border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td width="120"><?php echo ((is_array($_tmp='Field Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        <td width="400"><input type="text" name="title" id="title" value="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['field']['title'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
"></td>
      </tr>
      <?php if ($this->_tpl_vars['ftype'] == 2): ?>
      <tr>
        <td><?php echo ((is_array($_tmp='Columns')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        <td><input type="text" name="cols" value="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['cols'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
"></td>
      </tr>
      <tr>
        <td><?php echo ((is_array($_tmp='Rows')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        <td><input type="text" name="rows" value="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['rows'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
"></td>
      </tr>
      <?php endif; ?>
      <?php if (in_array ( $this->_tpl_vars['ftype'] , array ( 1 , 2 , 3 , 6 , 9 ) )): ?>
      <tr>
        <td><?php echo ((is_array($_tmp='Default Value')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        <td>
	      <?php if (in_array ( $this->_tpl_vars['ftype'] , array ( 1 , 6 ) )): ?>
          <input type="text" name="onfocus" id="onfocus" value="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['field']['onfocus'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
">
          <?php elseif ($this->_tpl_vars['ftype'] == 9): ?>
          <select name="onfocus" id="onfocus" size="1">
            <option value="now" <?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['field']['onfocus'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 'now') : smarty_modifier_adesk_isselected($_tmp, 'now')); ?>
><?php echo ((is_array($_tmp='Current Date')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
            <option value="null" <?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['field']['onfocus'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 'null') : smarty_modifier_adesk_isselected($_tmp, 'null')); ?>
><?php echo ((is_array($_tmp='Blank')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          </select>
          <?php elseif ($this->_tpl_vars['ftype'] == 2): ?>
          <textarea name="expl" id="expl"><?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['field']['expl'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
</textarea>
          <?php elseif ($this->_tpl_vars['ftype'] == 3): ?>
          <input type="checkbox" name="onfocus" id="onfocus" value="checked" <?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['field']['onfocus'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('adesk_ischecked_radio', true, $_tmp, 'checked') : smarty_modifier_adesk_ischecked_radio($_tmp, 'checked')); ?>
>
          <?php endif; ?>
        </td>
      </tr>
      <?php elseif ($this->_tpl_vars['ftype'] >= 4 && ( $this->_tpl_vars['ftype'] <= 5 || $this->_tpl_vars['ftype'] == 7 || $this->_tpl_vars['ftype'] == 8 )): ?>
      <tr valign="top">
        <td><?php echo ((is_array($_tmp='Field Values ')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        <td>
          <?php if ($this->_tpl_vars['ftype'] == 5 || $this->_tpl_vars['ftype'] == 7 || $this->_tpl_vars['ftype'] == 8): ?>
          <div class="field_dropdown_head">
            <input type="text" name="title_default" id="title_default" value="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['field']['onfocus_label'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
" style="width:85%; border:0px;" readonly>
          </div>
          <?php else: ?>
          <input type="hidden" name="title_default" id="title_default" value="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['field']['onfocus_label'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
" >
          <?php endif; ?>
          <input type="hidden" name="onfocus" id="onfocus" value="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['field']['onfocus'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
"/>
          <div id="value_container" class="<?php if ($this->_tpl_vars['ftype'] == 5): ?>field_dropdown_value<?php else: ?>field_radio_value<?php endif; ?>">
            <?php if (isset ( $this->_tpl_vars['field']['values'] )): ?>
            <?php $_from = $this->_tpl_vars['field']['values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['fields'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fields']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['v']):
        $this->_foreach['fields']['iteration']++;
?>
            <div style="position: relative" id="values_<?php echo $this->_foreach['fields']['iteration']; ?>
">
              <?php if (( ( $this->_tpl_vars['ftype'] == 7 || $this->_tpl_vars['ftype'] == 8 ) && in_array ( $this->_tpl_vars['v']['value'] , $this->_tpl_vars['field']['onfocus_array'] ) ) || $this->_tpl_vars['v']['value'] == ((is_array($_tmp=@$this->_tpl_vars['field']['onfocus'])) ? $this->_run_mod_handler('default', true, $_tmp, "") : smarty_modifier_default($_tmp, ""))): ?>
              <?php $this->assign('field_img', $this->_tpl_vars['default_on']); ?>
              <?php $this->assign('field_def', true); ?>
              <?php else: ?>
              <?php $this->assign('field_img', $this->_tpl_vars['default_off']); ?>
              <?php $this->assign('field_def', false); ?>
              <?php endif; ?>
			  <img src="<?php echo ((is_array($_tmp=@$this->_tpl_vars['__'])) ? $this->_run_mod_handler('default', true, $_tmp, '..') : smarty_modifier_default($_tmp, '..')); ?>
/awebdesk/media/<?php echo ((is_array($_tmp=$this->_tpl_vars['field_img'])) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
.gif" id="img_<?php echo $this->_foreach['fields']['iteration']; ?>
" onclick="make_default_label(this, '<?php echo $this->_foreach['fields']['iteration']; ?>
')">
              <input onclick="make_default_label(this, '<?php echo $this->_foreach['fields']['iteration']; ?>
')" id="label_<?php echo $this->_foreach['fields']['iteration']; ?>
" title='<?php echo ((is_array($_tmp='Label')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' value='<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['label'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' name="labels[]" type="text">
              <input onblur="if (this.id == default_label) set_default('<?php echo $this->_foreach['fields']['iteration']; ?>
')" title='<?php echo ((is_array($_tmp='Value')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' id="value_<?php echo $this->_foreach['fields']['iteration']; ?>
" name="values[]" type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
">
              <input onclick="$('value_container').removeChild($('values_<?php echo $this->_foreach['fields']['iteration']; ?>
'))" value='<?php echo ((is_array($_tmp='Remove')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' type="button" class="adesk_button_remove">
              <?php if ($this->_tpl_vars['field_def'] == true): ?>
              <script type="text/javascript">
                default_label = "label_<?php echo $this->_foreach['fields']['iteration']; ?>
";
                default_value = "value_<?php echo $this->_foreach['fields']['iteration']; ?>
";
              </script>
              <?php endif; ?>
              <script type="text/javascript">values_count = '<?php echo $this->_foreach['fields']['total']; ?>
'</script>
            </div>
            <?php endforeach; else: ?>
            <script type="text/javascript">add_value_slow(text_label, text_value, '', false)</script>
            <?php endif; unset($_from); ?>
            <?php else: ?>
            <script type="text/javascript">add_value_slow(text_label, text_value, '', false)</script>
            <?php endif; ?>
          </div>
          <div align="right" style="padding-top:4px;"><input type="button" class="adesk_button_add" onclick="add_value_slow(text_label, text_value, '')" value='<?php echo ((is_array($_tmp='Add Value')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
'></div>
        </td>
      </tr>
      <?php endif; ?>
      <?php if (isset ( $this->_tpl_vars['field']['bubble_content'] ) || isset ( $this->_tpl_vars['isstrio'] )): ?>
      <?php if ($this->_tpl_vars['ftype'] == 6): ?>
      <input type="hidden" name="bubble_content" value="" />
      <?php else: ?>
      <tr>
        <td valign="top"><?php echo ((is_array($_tmp='Bubble content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        <td>
          <textarea rows="4" name="bubble_content"><?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['field']['bubble_content'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
</textarea>
          <?php echo ((is_array($_tmp="The contents of this field will show up as a tooltip when visitor hovers over the field.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

        </td>
      </tr>
      <?php endif; ?>
      <?php endif; ?>
      <?php if ($this->_tpl_vars['ftype'] != 6): ?>
      <tr>
        <td><?php echo ((is_array($_tmp="Required?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        <td>
          <input type="checkbox" name="req" value="1" <?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['field']['req'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)))) ? $this->_run_mod_handler('adesk_ischecked', true, $_tmp) : smarty_modifier_adesk_ischecked($_tmp)); ?>
 />
          <?php echo ((is_array($_tmp="If this option is checked, the person filling the form will not be able to proceed with form submission unless the field has some value entered.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

        </td>
      </tr>
      <?php endif; ?>
      <tr>
        <td><?php echo ((is_array($_tmp='Label  Justification')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        <td>
          <select name="label">
            <option value="1" <?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['field']['label'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)))) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 1) : smarty_modifier_adesk_isselected($_tmp, 1)); ?>
><?php echo ((is_array($_tmp='Top')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
            <option value="0" <?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['field']['label'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)))) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, 0) : smarty_modifier_adesk_isselected($_tmp, 0)); ?>
><?php echo ((is_array($_tmp='Left')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          </select>
          <?php echo ((is_array($_tmp="The label of this field (a title) can be shown both above the field, or on its left side.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

        </td>
      </tr>
      <?php if (isset ( $this->_tpl_vars['mirror_list'] ) && count ( $this->_tpl_vars['mirror_list'] ) > 0): ?>
      <tr>
<?php if ($this->_tpl_vars['mirroring']): ?>
        <td valign="top"><?php echo ((is_array($_tmp='Mirror this in')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
<?php else: ?>
        <td valign="top"><?php echo ((is_array($_tmp='For use in')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
<?php endif; ?>
        <td>
          <select id="mirrorsList" name="mirror[]" multiple>
            <?php $_from = $this->_tpl_vars['mirror_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['m']):
?>
            <option value="<?php echo $this->_tpl_vars['m']['id']; ?>
" <?php echo ((is_array($_tmp=$this->_tpl_vars['m']['selected'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, true) : smarty_modifier_adesk_isselected($_tmp, true)); ?>
 <?php if (isset ( $this->_tpl_vars['m']['disabled'] )):  echo ((is_array($_tmp=$this->_tpl_vars['m']['disabled'])) ? $this->_run_mod_handler('adesk_isdisabled', true, $_tmp, true) : smarty_modifier_adesk_isdisabled($_tmp, true));  endif; ?>><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['m']['name'])) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)))) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>
</option>
            <?php endforeach; endif; unset($_from); ?>
          </select>
          <div>
            <?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            <a href="#" onclick="return adesk_form_select_multiple_all($('mirrorsList'), $('mirrorsList').getElementsByTagName('option')[0].value == '0');"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
            &middot;
            <a href="#" onclick="return adesk_form_select_multiple_none($('mirrorsList'));"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
          </div>
        </td>
      </tr>
      <?php endif; ?>
      <?php if ($this->_tpl_vars['inlist']): ?>
      <tr>
        <td><?php echo ((is_array($_tmp="Show on subscriber listing page?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        <td>
          <input name="show_in_list" type="checkbox" value="1" <?php if (isset ( $this->_tpl_vars['field']['show_in_list'] ) && $this->_tpl_vars['field']['show_in_list']): ?>checked<?php endif; ?> />
        </td>
      </tr>
      <?php endif; ?>
      <?php if ($this->_tpl_vars['perstag']): ?>
      <tr>
        <td><?php echo ((is_array($_tmp='Personalization Tag')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        <td>
          %<input name="perstag" id="perstag" type="text" size="16" value="<?php if (isset ( $this->_tpl_vars['field']['perstag'] ) && $this->_tpl_vars['field']['perstag']):  echo $this->_tpl_vars['field']['perstag'];  endif; ?>" />%
          <?php echo ((is_array($_tmp="This value will be used as a placeholder for this personalization field. If you enter 'MYTAG', then your content should have a placeholder %MYTAG% that would be replaced with a field value. NOTE: spaces will be replaced with a dash, and % characters are not allowed.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

        </td>
      </tr>
      <?php endif; ?>
      <?php if (isset ( $this->_tpl_vars['custom_field_include'] )): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['custom_field_include'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php endif; ?>
    </table>

    <br />
    <div>
      <input type="submit" value="<?php echo $this->_tpl_vars['mode_submit']; ?>
">
      <?php echo smarty_function_adesk_back(array('href' => $this->_tpl_vars['back_href']), $this);?>

      <input type="hidden" name="action" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['get']['action'])) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
">
      <input type="hidden" name="mode" value="<?php echo $this->_tpl_vars['mode_future']; ?>
">
      <input type="hidden" name="id" value="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['get']['id'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
">
      <input type="hidden" name="relid" value="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['get']['relid'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
">
      <input type="hidden" name="type" value="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['ftype'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('html', true, $_tmp) : smarty_modifier_html($_tmp)); ?>
">
    </div>
  </form>
</div>

<script type="text/javascript">
  <?php if ($this->_tpl_vars['ftype'] == 4 || $this->_tpl_vars['ftype'] == 5): ?>
  reset_sorting();
  <?php endif; ?>
</script>