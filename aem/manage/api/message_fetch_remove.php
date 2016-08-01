<?php

function message_fetch_remove($id) {
	$r = array(
		'succeeded' => false,
		'message' => '',
		'id' => $id
	);
	$r['succeeded'] = adesk_file_upload_remove(adesk_cache_dir(), '', $id);
	if ( $r['succeeded'] ) {
		$r['message'] = sprintf(_a("File '%s' removed."), substr($id, strlen('msgimport-')));
	} else {
		$r['message'] = sprintf(_a("File '%s' could not be removed."), substr($id, strlen('msgimport-')));
	}
	return $r;
}

?>
