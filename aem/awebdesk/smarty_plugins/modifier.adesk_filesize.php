<?php

function smarty_modifier_adesk_filesize($size) {
	return adesk_file_humansize($size);
}

?>
