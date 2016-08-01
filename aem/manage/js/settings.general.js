{literal}

function settings_general_load() {
	$("settings_general").className = "adesk_block";
}

function general_maint_check(checked) {
	if (checked)
		$("general_maint_tbody").className = "adesk_table_rowgroup";
	else
		$("general_maint_tbody").className = "adesk_hidden";
}

{/literal}