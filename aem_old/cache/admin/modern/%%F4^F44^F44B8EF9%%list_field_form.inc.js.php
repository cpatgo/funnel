<?php /* Smarty version 2.6.12, created on 2016-07-14 14:06:34
         compiled from list_field_form.inc.js */ ?>
<?php echo '

function custom_field_validate_ihook() {
	var perstag_clean = $("perstag").value;
	perstag_clean = perstag_clean.replace(/\\s+/g, "-");
	perstag_clean = perstag_clean.replace(/%/g, "");
	$("perstag").value = perstag_clean;
	return true;	
}

'; ?>
