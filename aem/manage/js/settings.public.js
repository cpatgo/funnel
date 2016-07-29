{literal}

function settings_public_load() {
	$("settings_public").className = "adesk_block";
}

function public_rewrite_check(checked) {
	if (checked)
		$("public_rewrite_tbody").className = "adesk_table_rowgroup";
	else
		$("public_rewrite_tbody").className = "adesk_hidden";
}

function public_rewrite_htaccess() {
	$('htaccess').style.display = 'block';
}

{/literal}