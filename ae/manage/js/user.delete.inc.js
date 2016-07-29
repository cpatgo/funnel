var user_delete_check_lists = '{"Delete all lists created by this user"|alang}';
{literal}
function user_delete_check_extra() {
	$("delete_message").innerHTML += "<br><br><input type='checkbox' id='delete_lists' value='1'> " + user_delete_check_lists;
}

function user_delete_custom(id) {
	adesk_ajax_call_cb("awebdeskapi.php", "user!adesk_user_delete", user_delete_cb, id, ( $("delete_lists") && $("delete_lists").checked ) ? 1 : 0);
}

function user_delete_multi_custom(multi) {
	adesk_ajax_call_cb("awebdeskapi.php", "user!adesk_user_delete_multi", user_delete_multi_cb, user_delete_id_multi, ( $("delete_lists") && $("delete_lists").checked ) ? 1 : 0);
}
{/literal}
