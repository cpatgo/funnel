{literal}

function custom_field_validate_ihook() {
	var perstag_clean = $("perstag").value;
	perstag_clean = perstag_clean.replace(/\s+/g, "-");
	perstag_clean = perstag_clean.replace(/%/g, "");
	$("perstag").value = perstag_clean;
	return true;	
}

{/literal}
