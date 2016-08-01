<?php

require_once dirname(__FILE__) . '/base.php';
require_once dirname(__FILE__) . '/sql.php';

# Fill up an array of files with all of the files in $path.  If $prefix is not a blank string,
# then it is used to append before each new file in the array.

function adesk_tplversion_files_array($prefix, $path, &$files) {
	if (!file_exists($path) || !is_dir($path) || !is_readable($path))
		return array();

	if (!($dh = opendir($path)))
		return array();

	for ($file = readdir($dh); $file !== false; $file = readdir($dh)) {
		# Skip any file beginning with "."

		if (substr($file, 0, 1) == ".")
			continue;

		if (is_dir("$path/$file"))
			adesk_tplversion_files_array($prefix . $file . "/", "$path/$file", $files);
		else
			$files[] = array(
				"desk"		=> "",
				"name"		=> $prefix == "" ? $file : $prefix . $file,
				"mtime"		=> "",
				"action"	=> "",
				"restoreid"	=> 0,
			);
	}
}

# Return an array of files in the given location, where location is one of the enum values
# in the tplversion table.

function adesk_tplversion_files($loc, $desk = '') {
	$path = "";

	switch ($loc) {
		case 'admin':
			$path = adesk_admin("templates");
			break;

		case 'desk':
			$path = adesk_base("desk/$desk/templates");
			break;

		case 'lang':
			$path = adesk_base("lang");
			break;

		case 'global':
			$path = awebdesk("templates");
			break;

		case 'public':
			$path = adesk_base("templates");
			break;

		default:
			break;
	}

	$files = array();
	adesk_tplversion_files_array("", $path, $files);

	return $files;
}

# Grab all of the template files by location, but include if they were modified and the
# subsequent information about that modification.

function adesk_tplversion_select_files($loc, $desk = '') {
	require_once awebdesk_classes("select.php");

	$files = adesk_tplversion_files($loc, $desk);
	$lookup = array();
	$select = new adesk_Select();

	$loc    = adesk_sql_escape($loc);
	$desk	= adesk_sql_escape($desk);

	$i = 0;
	foreach ($files as $file) {
		$lookup[$file["name"]] = $i++;
	}

	if ($desk != '')
		$select->push("AND desk = '$desk'");

	# You want this query to use the name key, if possible.  So far, I'm avoiding temp
	# tables and filesorts, but I haven't tested this with a large amount of data.

	$rs = adesk_sql_query($select->query("
		SELECT
			desk,
			name,
			mtime,
			action,
			restoreid
		FROM
			#tplversion
		WHERE
			[...]
		AND location = '$loc'
		GROUP BY
			name
		HAVING
			MAX(mtime)
	"));

	$ary = array();

	while ($row = adesk_sql_fetch_assoc($rs)) {
		$name = $row["name"];
		if (isset($lookup[$name]))
			$files[$lookup[$name]] = $row;
	}

	return $files;
}

# Return a list of versions for a file in a given location.  (Ironically enough, the version
# number is not returned here.)

function adesk_tplversion_select_versions($loc, $file) {
	$loc = adesk_sql_escape($loc);
	$file = adesk_sql_escape($file);

	$ary = adesk_sql_select_array("
		SELECT
			desk,
			name,
			mtime,
			action,
			restoreid
		FROM
			#tplversion
		WHERE
			location = '$loc'
		AND name = '$file'
	");

	return $ary;
}

# Insert a new version record.  The mtime and version columns are taken care of in this function.

function adesk_tplversion_insert(&$ary) {
	if (!isset($ary["location"]) || !isset($ary["name"]))
		return;

	$loc 	= adesk_sql_escape($ary["location"]);
	$name 	= adesk_sql_escape($ary["name"]);
	$count 	= adesk_sql_select_one("
		SELECT
			COUNT(*)
		FROM
			#tplversion
		WHERE
			location = '$loc'
		AND name = '$name'
	");

	$ary["=mtime"] 	= "NOW()";
	$ary["version"] = $count + 1;

	adesk_sql_insert("#tplversion", $ary);
	return adesk_sql_insert_id();
}

?>
