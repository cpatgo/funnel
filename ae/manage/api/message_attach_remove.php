<?php

function message_attach_remove($id) {
	$r = array(
		'succeeded' => false,
		'message' => '',
		'id' => $id
	);
	$r['succeeded'] = adesk_file_upload_remove('#message_file', '#message_file_data', $id);
	if ( $r['succeeded'] ) {
		$r['message'] = _a("File removed.");
	} else {
		$r['message'] = _a("File could not be removed.");
	}
	return $r;
}

?>