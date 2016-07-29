<?php
// file.php

// Some standard functions for working with files and discovering files.
// --

// Tail-recursive form of adesk_file_find.  If a file is encountered which
// is a directory, but not the magic . and .. directories, then
// adesk_file_find_r recurses into those directories and looks for files in
// them.  All files returned in the $files array use paths derived from
// $base.  If you use '.' in $base then the paths will be based on
// that; if you use dirname(...), the path will be absolute.

function adesk_file_find_r($base, $patn, &$files) {
		if (!is_dir($base))
        return array();

    $dir = @opendir($base);

    while (($file = @readdir($dir)) !== false) {
        if (substr($file, 0, 1) != ".") {
	        if (is_dir($base . DIRECTORY_SEPARATOR . $file))
	            adesk_file_find_r($base . DIRECTORY_SEPARATOR . $file, $patn, $files);
	        elseif ($patn == '' || preg_match('/'.$patn.'/', $file))
	            $files[] = $base . DIRECTORY_SEPARATOR . $file;
        }
    }

		@closedir($dir);
    return $files;
}

// Given $base, a string path representing where to begin looking for
// files, and $patn, a regular expression describing the filenames you
// are looking for, return an array list of all matching files from
// $base on down.

function adesk_file_find($base, $patn = '') {
	$files = array();
	return adesk_file_find_r($base, $patn, $files);
}

// List the directories in a given directory. $base should point to the directory that you want to look in.
// $base could be something like: adesk_base("templates"), which points us to \knowledgebuilder\templates\
function adesk_dir_list($base) {
	$files = array();
	return adesk_dir_list_r($base, $files);
}

function adesk_dir_list_r($base, &$files) {
	if (!is_dir($base)) return;

    $dir = @opendir($base);

    while (($file = @readdir($dir)) !== false) {
        if (substr($file, 0, 1) != ".") {
	        if (is_dir($base . DIRECTORY_SEPARATOR . $file))
	            $files[] = $base . DIRECTORY_SEPARATOR . $file;
        }
    }

	@closedir($dir);
	return $files;
}

// Alias for file_get_contents().

function adesk_file_get($fname) {
    return file_get_contents($fname);
}

function adesk_file_get_lines($fname) {
    $fd    = @fopen($fname, "r");
    $lines = array();

    if ($fd == false)
        return $lines;

    while (!feof($fd)) {
        $line = fgets($fd);

        if ($line == false)
            return $lines;

        $lines[] = $line;
    }

    return $lines;
}

// Replacement for file_put_contents(), which is not in PHP prior to
// version 5.

function adesk_file_put($fname, $str) {
    $fd = @fopen($fname, "w");

    if ($fd == false)
        return false;

    fwrite($fd, $str);
    fclose($fd);

    return true;
}

function adesk_file_put_lines($fname, &$lines) {
    $fd = @fopen($fname, "w");

    if ($fd == false)
        return false;

    foreach ($lines as $line)
        fwrite($fd, $line."\n");

    fclose($fd);
    return true;
}

function adesk_file_copy_recursive($source, $dest) {
	// Simple copy for a file
	if ( is_file($source) ) {
		return copy($source, $dest);
	}

	// Make destination directory
	if (!is_dir($dest)) {
		mkdir($dest, 0755);
	}

	// Loop through the folder
	$dir = dir($source);
	while ( false !== $entry = $dir->read() ) {
		// Skip pointers
		if ( $entry == '.' || $entry == '..' ) {
			continue;
		}

		// Deep copy directories
		if ( $dest !== $source . '/' . $entry ) {
			adesk_file_copy_recursive($source . '/' . $entry, $dest . '/' . $entry);
		}
	}

	// Clean up
	$dir->close();
	return true;
}

function adesk_file_readline($fp) {
	$chunklen = 128;
	$out      = array();
	$fileoff  = @ftell($fp);
	$str      = "";

	while (!feof($fp)) {
		$str   .= @fread($fp, $chunklen);
		$lfoff = strpos($str, "\n");
		$croff = strpos($str, "\r");

		if ($lfoff === false && $croff === false) {
			if (feof($fp)) {
				$out[] = $str;
				break;
			}
			continue;
		}

		if ($lfoff !== false) {
			@fseek($fp, $fileoff + $lfoff + 1);
			$out[] = substr($str, 0, $lfoff);
			break;
		}

		if ($lfoff === false) {		# $croff must not be false at this point.
			@fseek($fp, $fileoff + $croff + 1);
			$out[] = substr($str, 0, $croff);
			break;
		}

		$out[] = $str;
		$str   = "";
	}

	return implode("", $out);
}

function adesk_file_rmdir_recursive($dirname) {
	// Sanity check
	if ( !file_exists($dirname) ) {
		return false;
	}

	// Simple delete for a file
	if ( is_file($dirname) ) {
		return unlink($dirname);
	}

	// Loop through the folder
	$dir = dir($dirname);
	while ( false !== $entry = $dir->read() ) {
		// Skip pointers
		if ( $entry == '.' || $entry == '..' ) {
			continue;
		}

		// Recurse
		adesk_file_rmdir_recursive($dirname . DIRECTORY_SEPARATOR . $entry);
	}

	// Clean up
	$dir->close();
	return rmdir($dirname);
}


function adesk_file_basename($path) {
	$path = basename($path);
	return (strlen($path) > 0 && substr($path, 0, 1) != '.' ? $path : '') ;
}


function adesk_file_humansize($size) {
	$count = 0;
	$format = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	while( ( $size / 1024 ) > 1 and $count < 8 ) {
		$size = $size / 1024;
		$count++;
	}
	$decimals = (int)( $size < 10 );
	return number_format($size, $decimals, '.', ' ') . ' ' . $format[$count];
}

function adesk_file_upload($prefix, $tablef, $tabled, $relid, $relval, $maxsize = null) {
	if ( is_null($maxsize) ) {
		$site = adesk_site_get();
		$maxsize = (int)$site['maxuploadfilesize'];
	}
	$r = array(
		'succeeded' => false,
		'message' => _a('Command not provided.'),
		'id' => 0,
		'filename' => '',
		'filesize' => 0,
		'relid' => $relval
	);
	// require PEAR package for file uploads
	require_once(awebdesk('pear/Upload.php'));
	// if requested upload, upload it
	$upload = new HTTP_Upload('en');
	// Set allowed file extension
	$file = $upload->getFiles('adesk_uploader');

	$r['filename'] = preg_replace('/\.php/i', '', $file->setName('real'));

	if ( isset($GLOBALS["_hosted_account"]) && $GLOBALS['adesk_app_id'] != "ahd" && strpos($prefix, "template_preview") === false && strpos($prefix, "tplimport") === false ) {
		$file->setValidExtensions(array('doc', 'docx', 'pdf'), 'accept');
		if (!preg_match('/\.doc$|\.jpg$|\.docx$|\.pdf$/i', $r['filename'])) {
			$r['message'] = sprintf(_a("Only files ending in %s are allowed as attachments"), ".doc, .docx, .jpg, and .pdf");
			return $r;
		}
	} else {
		$file->setValidExtensions(array('php'), 'deny');
		if (preg_match('/\.php[34s]?$/i', $r['filename'])) {
			$r['message'] = sprintf(_a("Files ending in %s are not allowed as attachments"), ".php");
			return $r;
		}
	}

	if ( PEAR::isError($file) || !$file->isValid() ) {
		$r['message'] = $file->getMessage();
		if ( isset($_FILES['adesk_uploader']['name']) ) $r['filename'] = $_FILES['adesk_uploader']['name'];
		return $r;
	}

	// Get a "safe" file name
	$r['filename'] = preg_replace('/\.php/i', '', $file->setName('real'));
	$file->setName($r['filename']);
	// Check the file size
	$r['filesize'] = $size = $file->getProp('size');

	if (isset($GLOBALS["_hosted_account"])) {
		if ($GLOBALS['adesk_app_id'] == 'ahd') {
			if  ( $r['filesize'] > 6291456 ) { # 6MB.
				$r['message'] = _a("The file is too big! Maximum allowed filesize is 1MB.", $maxsize);
				return $r;
			}
		} else {
			if  ( $r['filesize'] > 1048576 ) { # 1,048,576 = 1MB.
				$r['message'] = _a("The file is too big! Maximum allowed filesize is 1MB.", $maxsize);
				return $r;
			}
		}
	} else {
		if  ( $maxsize > 0 and $r['filesize'] > $maxsize * 1024 * 1024 ) { // this is in megs
			$r['message'] = _a("The file is too big! Maximum allowed filesize is %sMB.", $maxsize);
			return $r;
		}
	}
	// upload a file and return a message
	$realpath = $file->upload['tmp_name'];
	$type = ( strlen($tabled) > 0 && substr($tabled, 0, 1) == '#' ? 'db' : 'fs' ); // if database table provided, save 2 db, otherwise 2 filesystem
	$reltype = ( strlen($tablef) > 0 && substr($tablef, 0, 1) == '#' ? 'db' : 'fs' ); // if database table provided, save 2 db, otherwise 2 filesystem
	// Insert it
	if ( $reltype == 'fs' ) {
		// save to filesystem
		$fn = $prefix . '-' . $r['filename'];
		$file->setName($fn);
		$r['succeeded'] = $file->moveTo($tablef, true);
		$r['id'] = $fileID = $fn;
		if ( !$r['succeeded'] ) {
			$r['message'] = $file->getMessage();
			return $r;
		}
	} else {
		// save file info in database
		$insert = array(
			'id' => 0,
			$relid => $relval,
			'name' => $r['filename'],
			'size' => $r['filesize'],
			'mime_type' => $file->getProp('type'),
			'=tstamp' => 'NOW()'
		);
		if ( adesk_ihook_exists('adesk_file_upload_info') ) {
			$insert = adesk_ihook('adesk_file_upload_info', $file, $insert);
		}
		$sql = adesk_sql_insert($tablef, $insert);
		if ( !$sql ) {
			$r['message'] = adesk_sql_error();
			return $r;
		}
		$r['id'] = $fileID = (int)adesk_sql_insert_id();
	}
	if ( $type == 'fs' ) {
		if ( $reltype != 'fs' ) { // if this is also fs, it's saved already
			// save to filesystem
			$file->setName($prefix . '-' . $fileID);
			$r['succeeded'] = $file->moveTo($tabled, true);
		} else {
			$r['succeeded'] = true;
		}
		if ( !$r['succeeded'] ) {
			$r['message'] = $file->getMessage();
			return $r;
		}
	} else {
		// save file content in database
		// Place holder
		$currentPos = 0;
		// Loop counter
		$count = 1;
		// Chunk size
		$chunkSize = 700000;
		// insert array
		$insert = array(
			'id' => 0,
			'fileid' => $fileID,
			'sequence' => 1,
			'data' => ''
		);
		// Get data
		$data = adesk_file_get($realpath); // we can do fopen/fseek/fread combo for optimization
		$size = strlen($data);
		// Loop
		while ( $currentPos < $size ) {
			// Get a order number
			$insert['sequence'] = $count;
			// Get a chunk of the data
			$insert['data'] = substr($data, $currentPos, $chunkSize);
			// Insert it
			$retval = adesk_sql_insert($tabled, $insert);
			if ( !$retval ) {
				$r['message'] = adesk_sql_error();
				$r['succeeded'] = false;
				// If this is ever false we should remove everything about this file from
				// the database.
				adesk_sql_query("DELETE FROM `$tablef` WHERE `id` = '$fileID'");
				adesk_sql_query("DELETE FROM `$tabled` WHERE `fileid` = '$fileID'");
				return $r;
			}
			// Update the current position
			$currentPos += $chunkSize;
			$count++;
		}
		$r['succeeded'] = true;
	}
	if ( $r['succeeded'] ) {
		$r['message'] = _a('File %s uploaded.', $r['filename']);
	} else {
		$r['message'] = _a('Upload failed for file %s.', $r['filename']);
	}
	return $r;
}

function adesk_file_save($prefix, $tablef, $tabled, $relid, $relval, $maxsize = null, $file) {
	if ( is_null($maxsize) ) {
		$site = adesk_site_get();
		$maxsize = (int)$site['maxuploadfilesize'];
	}
	$r = array(
		'succeeded' => false,
		'message' => _a('File not provided.'),
		'id' => 0,
		'filename' => '',
		'filesize' => '',
		'relid' => $relval
	);
	if ( !isset($file['name']) or !isset($file['size']) or !isset($file['data']) ) {
		return $r;
	}
	// Get a "safe" file name
	$r['filename'] = $file['name'] = adesk_str_urlsafe($file['name']);
	// Check the file size
	$r['filesize'] = $size = $file['size'];
	if  ( $maxsize > 0 and $r['filesize'] > $maxsize * 1024 * 1024 ) { // this is in megs
		$r['message'] = _a("The file is too big! Maximum allowed filesize is %sMB.", $maxsize);
		return $r;
	}
	// save a file and return a message
	$type = ( strlen($tabled) > 0 && substr($tabled, 0, 1) == '#' ? 'db' : 'fs' ); // if database table provided, save 2 db, otherwise 2 filesystem
	$reltype = ( strlen($tablef) > 0 && substr($tablef, 0, 1) == '#' ? 'db' : 'fs' ); // if database table provided, save 2 db, otherwise 2 filesystem
	// Insert it
	if ( $reltype == 'fs' ) {
		// save to filesystem
		$fn = $prefix . '-' . $r['filename'];
		$r['succeeded'] = adesk_file_put($tablef, $file['data']);
		$r['id'] = $fileID = $fn;
		if ( !$r['succeeded'] ) {
			$r['message'] = _a('File info could not be saved');
			return $r;
		}
	} else {
		// save file info in database
		$insert = array(
			'id' => 0,
			$relid => $relval,
			'name' => $r['filename'],
			'size' => $r['filesize'],
			'mime_type' => $file['mimetype'],
			'=tstamp' => 'NOW()'
		);
		$sql = adesk_sql_insert($tablef, $insert);
		if ( !$sql ) {
			$r['message'] = adesk_sql_error();
			return $r;
		}
		$r['id'] = $fileID = (int)adesk_sql_insert_id();
	}
	if ( $type == 'fs' ) {
		if ( $reltype != 'fs' ) { // if this is also fs, it's saved already
			// save to filesystem
			$fn = $prefix . '-' . $fileID;
			$r['succeeded'] = adesk_file_put($tabled, $file['data']);
		} else {
			$r['succeeded'] = true;
		}
		if ( !$r['succeeded'] ) {
			$r['message'] = _a('File data could not be saved');
			return $r;
		}
	} else {
		// save file content in database
		// Place holder
		$currentPos = 0;
		// Loop counter
		$count = 1;
		// Chunk size
		$chunkSize = 700000;
		// insert array
		$insert = array(
			'id' => 0,
			'fileid' => $fileID,
			'sequence' => 1,
			'data' => ''
		);
		// Get data
		$size = strlen($file['data']);
		// Loop
		while ( $currentPos < $size ) {
			// Get a order number
			$insert['sequence'] = $count;
			// Get a chunk of the data
			$insert['data'] = substr($file['data'], $currentPos, $chunkSize);
			// Insert it
			$retval = adesk_sql_insert($tabled, $insert);
			if ( !$retval ) {
				$r['message'] = adesk_sql_error();
				$r['succeeded'] = false;
				// If this is ever false we should remove everything about this file from
				// the database.
				adesk_sql_query("DELETE FROM `$tablef` WHERE `id` = '$fileID'");
				adesk_sql_query("DELETE FROM `$tabled` WHERE `fileid` = '$fileID'");
				return $r;
			}
			// Update the current position
			$currentPos += $chunkSize;
			$count++;
		}
		$r['succeeded'] = true;
	}
	if ( $r['succeeded'] ) {
		$r['message'] = _a('File %s uploaded.', $r['filename']);
	} else {
		$r['message'] = _a('Upload failed for file %s.', $r['filename']);
	}
	return $r;
}

function adesk_file_upload_remove($tablef, $tabled, $id) {
	$type = ( strlen($tabled) > 0 && substr($tabled, 0, 1) == '#' ? 'db' : 'fs' ); // if database table provided, save 2 db, otherwise 2 filesystem
	$reltype = ( strlen($tablef) > 0 && substr($tablef, 0, 1) == '#' ? 'db' : 'fs' ); // if database table provided, save 2 db, otherwise 2 filesystem
	// do actual deleting
	if ( $reltype == 'fs' ) {
		return @unlink($tablef . DIRECTORY_SEPARATOR . $id);
	} else {
		return (
			adesk_sql_query("DELETE FROM $tablef WHERE `id` = '$id'")
		and
			( $type == 'fs' ? @unlink($tabled . DIRECTORY_SEPARATOR . $id) : adesk_sql_query("DELETE FROM $tabled WHERE `fileid` = '$id'") )
		);
	}
}

function adesk_file_upload_list($tablef, $relid, $relval, $relop = '=') {
	$site = adesk_site_get();
	$r = array();
	$query = "
		SELECT
			*
		FROM
			$tablef f
		WHERE
			`$relid` $relop $relval
		ORDER BY
			`tstamp` ASC
	";
	$sql = adesk_sql_query($query);
	if ( !$sql or mysql_num_rows($sql) == 0 ) return $r;
	while ( $row = adesk_sql_fetch_assoc($sql, array("tstamp"))) {
		$row['datetime'] = adesk_date_format($row['tstamp'], $site['datetimeformat']);
		$row['humansize'] = adesk_file_humansize($row['size']);
		$row['hash'] = md5($row[$relid] . '*|*' . $row['name']);
		$row['filetype'] = adesk_file_upload_type($row);
		$row['viewable'] = adesk_file_upload_viewable($row['filetype']);
		$r[$row['id']] = $row;
	}
	return $r;
}

function adesk_file_upload_get($tablef, $tabled, $relid, $id) {
//function adesk_file_upload_get($id) {
	$site = adesk_site_get();
	$r = false;
	$query = "
		SELECT
			*
		FROM
			`$tablef` f
		WHERE
			`id` = '$id'
	";
	$sql = adesk_sql_query($query);
	if ( !$sql or mysql_num_rows($sql) == 0 ) return $r;
	$r = adesk_sql_fetch_assoc($sql, array("tstamp"));
	$r['datetime'] = adesk_date_format($r['tstamp'], $site['datetimeformat']);
	$r['humansize'] = adesk_file_humansize($r['size']);
	$r['hash'] = md5($r[$relid] . '*|*' . $r['name']);
	// get data
	$r['data'] = adesk_file_upload_get_data($tabled, $id);
	return $r;
}

function adesk_file_upload_get_data($tabled, $id) {
	// if filesystem is used?
	if ( substr($tabled, 0, 1) != '#' ) {
		return ( file_exists($tabled . $id) ? adesk_file_get($tabled . $id) : '' );
	} else {
		return implode('', adesk_sql_select_list("SELECT `data` FROM `$tabled` WHERE `fileid` = '$id' ORDER BY `sequence`"));
	}
	return '';
}

function adesk_file_upload_type($file) {
	// check mime type
	$mime = strtolower($file['mime_type']);
	$type = explode('/', $mime);
	if ( in_array($type[0], array('image', 'text')) ) {
		if ( $type[0] == 'text' and $type[1] == 'html' ) return 'html';
		return $type[0];
	}
	// check extension
	$ext = ( adesk_str_instr('.', $file['name']) ? strtolower(end(explode('.', $file['name']))) : '' );
	if ( adesk_str_instr('htm', $ext) ) {
		return 'html';
	}
	if ( $ext == 'pdf' or $mime == 'application/pdf' ) return 'pdf';
	return '';
}

function adesk_file_upload_viewable($filetype) {
	return in_array($filetype, array('html', 'text', 'image', 'pdf', /*'document'*/));
}

function adesk_file_zip_read_xml($filename, $ext, $return_format = "text") {

	// reads the necessary XML file contained within a compressed filetype, such as docx, pptx, odt
	// taken and modified from: http://www.webcheatsheet.com/PHP/reading_the_clean_text_from_docx_odt.php

	if ( class_exists("ZipArchive") ) {

		$zip = new ZipArchive;
		$file_content = "";

		// Open received archive file
		if ($zip -> open($filename) === true) {

			switch ($ext) {

				case "odt" :
					$data_location = "content.xml";
				case "docx" :
					if ( !isset($data_location) ) $data_location = "word/document.xml";
				case "xlsx" :
					if ( !isset($data_location) ) $data_location = "xl/sharedStrings.xml";

					if ( ($index = $zip -> locateName($data_location)) !== false ) {
						$data = $zip -> getFromIndex($index);
						$xml = DOMDocument::loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
						// return xml by default
						$file_content = $xml -> saveXML();
						if ($return_format == "text") {
							$file_content = strip_tags($file_content);
						}
						elseif ($return_format == "csv") {
							// just process sheet1 for now - later we can add support for every sheet
							$sheet1 = $zip -> locateName("xl/worksheets/sheet1.xml");
							$sheet1 = $zip -> getFromIndex($sheet1);
							$sheet1_xml = DOMDocument::loadXML($sheet1, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
							// information about the file, particularly row and column representation in XML
							$file_info = $sheet1_xml -> saveXML();
							if ( function_exists('simplexml_load_string') ) {
								$file_content = simplexml_load_string($file_content);
								$file_content = get_object_vars($file_content);
								//dbg($file_content);
								$file_info = simplexml_load_string($file_info);
								//dbg($file_info);
								$rows = get_object_vars($file_info -> sheetData);
								$rows = $rows['row'];
								$rows_total = count($rows);
								$result = array();
								foreach ($rows as $row) {
									$row = get_object_vars($row);
									//dbg($row,1);
									$row_columns_total = count($row['c']);
									$row_data = array();
									//dbg($row['c'],1);
									if (!is_array($row['c']))
										$row['c'] = array($row['c']);

									foreach ($row['c'] as $cell) {
										$cell = get_object_vars($cell);
										//$cell = get_object_vars($cell);
										//dbg($cell,1);
										// check if the array key is available in the main data array,
										// where we obtain the actual data: $file_content['si']
										// I've seen cases where there is data in the cell, but the value for $cell['v'] IS the actual data,
										// and NOT the corresponding ID for $file_content['si'].
										// also, I noticed if $cell['@attributes']['t'] is there, then the actual data is in $file_content['si'],
										// otherwise the data is $cell['v']
										if ( isset( $cell['@attributes']['t'] ) && isset( $file_content['si'][ (int)$cell['v'] ] ) ) {
											$cell_data = get_object_vars( $file_content['si'][ (int)$cell['v'] ] );
											if ( isset($cell_data['t']) ) {
											  $cell_data = $cell_data['t'];
											}
											else {
											  $cell_data = "";
											}
										}
										else {
											// if we get here, the value of $cell['v'] should be the actual data,
											// and NOT the corresponding ID for $file_content['si']
											if ( isset($cell['v']) ) {
											  $cell_data = $cell['v'];
											}
											else {
											  $cell_data = "";
											}
										}
										$row_data[] = $cell_data;
									}
									$result[] = $row_data;
								}
								//dbg($result);
								if (!$result) {
									return array( "error" => _a("No data found") );
								}
								$header = current($result);
								$file_content = adesk_array_csv($result, $header, $output = array());
							}
							else {
								return array( "error" => _a("PHP SimpleXML extension required") );
							}
						}
					}

				break;

				case "pptx" :

					// loop through all slide#.xml files
					$slide = 1;

					while ( ($index = $zip -> locateName("ppt/slides/slide" . $slide . ".xml")) !== false ) {

						$data = $zip -> getFromIndex($index);
						$xml = DOMDocument::loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
						$file_content .= strip_tags($xml -> saveXML());

						$slide++;
					}

				break;
			}
		}

		$zip -> close();

		return $file_content;
	}
	else {
		return array( "error" => _a("PHP Zip extension required") );
	}
}

// grab just the text content from the file
function adesk_file_upload_read($mimetype, $filename, $content, $ext, $return_format = "text") {

	$whitelist = array("htm", "html", "txt", "pdf", "doc", "odt", "docx", "xlsx", "pptx");
	if ( !in_array($ext, $whitelist) ) return "";

	if ($mimetype == "text/html") {

		// html

		$content = adesk_str_strip_tags($content);
	}
	elseif ($mimetype == "application/pdf") {

		// pdf

		require_once awebdesk_functions("pdf.php");

	  $searchstart = 'stream';
	  $searchend = 'endstream';
	  $pdfText = '';
	  $pos = 0;
	  $pos2 = 0;
	  $startpos = 0;

	  while ($pos !== false && $pos2 !== false) {

	    $pos = strpos($content, $searchstart, $startpos);
	    $pos2 = strpos($content, $searchend, $startpos + 1);

	    if ($pos !== false && $pos2 !== false){

	      if ($content[$pos] == 0x0d && $content[$pos + 1] == 0x0a) {
	      	$pos += 2;
	      }
	      else if ($content[$pos] == 0x0a) {
	      	$pos++;
	      }

	      if ($content[$pos2 - 2] == 0x0d && $content[$pos2 - 1] == 0x0a) {
	      	$pos2 -= 2;
	      }
	      else if ($content[$pos2 - 1] == 0x0a) {
	      	$pos2--;
	      }

	      $textsection = substr(
	        $content,
	        $pos + strlen($searchstart) + 2,
	        $pos2 - $pos - strlen($searchstart) - 1
	      );
	      $data = @gzuncompress($textsection);
	      $pdfText .= adesk_pdf_extract_text($data);
	      $startpos = $pos2 + strlen($searchend) - 1;
	    }
	  }

	  $content = preg_replace('/(\s)+/', ' ', $pdfText);
	  $content = preg_replace('/\x91|\x92|\x96/', '', $content);
	}
	elseif ($mimetype == "application/msword") {

		// doc

		// script taken from: http://coding.derkeiler.com/Archive/PHP/php.general/2008-12/msg00213.html
		// only works with ".doc" files Word 97-03

		//$headers = substr($content, 0, 0xA00);
		$headers = substr($content, 0, 2560);

		// 1 = (ord(n)*1) ; Document has from 0 to 255 characters
		$n1 = ( ord($headers[0x21C]) - 1 );

		// 1 = ((ord(n)-8)*256) ; Document has from 256 to 63743 characters
		$n2 = ( ( ord($headers[0x21D]) - 8 ) * 256 );

		// 1 = ((ord(n)*256)*256) ; Document has from 63744 to 16775423 characters
		$n3 = ( ( ord($headers[0x21E]) * 256 ) * 256 );

		// (((ord(n)*256)*256)*256) ; Document has from 16775424 to 4294965504 characters
		$n4 = ( ( ( ord($headers[0x21F]) * 256 ) * 256 ) * 256 );

		// total length of text in the document
		$textLength = ($n1 + $n2 + $n3 + $n4);

		$content = substr($content, 2560, $textLength);
	}
	elseif ($mimetype == "application/octet-stream") {

		switch ($ext) {

			case "odt" :
			case "docx" :
			case "xlsx" :
			case "pptx" :

				$content = adesk_file_zip_read_xml($filename, $ext, $return_format);

			break;

			/*
			case "xls" :
			case "ppt" :
			case "wps" :
			*/
		}
	}

	// we either receive the content back as a string, or an array that has a key for "error"
	if ( !is_array($content) ) {
		if ( strlen($content) > 65000 ) $content = substr($content, 0, 65000);
	}

	return $content;
}

// delete all files in folder X older than Z days (mask is a preg pattern for files)
function adesk_file_delete_old($dir, $days, $mask = '') {
	$seconds = $days * 24 * 60 * 60;

	$files = adesk_file_find($dir, $mask);
	foreach ( $files as $fname ) {
		$path = "$dir/$fname";
		//if ( $mask and !preg_match($mask, $path) ) continue;
		$mod_time = @filemtime($path);
		if ( !$mod_time ) continue;
		if ( file_exists($path) && time() - $mod_time > $seconds ) {
			@unlink($path);
		}
	}
}

?>
