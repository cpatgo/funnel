<?php

require_once dirname(__FILE__) . '/prefix.php';
require_once dirname(__FILE__) . '/array.php';      # adesk_array_order_*, adesk_array_limit
require_once dirname(__FILE__) . '/log.php';

#define('adesk_SQL_QUERY_DEBUG', true);

// Uncomment this if you want query debugging.
//define('adesk_SQL_QUERY_DEBUG', 1);

// If adesk_SQL_QUERY_DEBUG is defined, a debugging version of
// adesk_sql_query is then defined that will log failures into the
// querydebug table.  Otherwise, you get the normal version.

if (defined('adesk_SQL_QUERY_DEBUG')) {
    function adesk_sql_query($query, $ignore = false) {
        if (!isset($GLOBALS['dbQueries']))
            $GLOBALS['dbQueries'] = array();

        $query = adesk_prefix_replace($query);

#       echo $query;

        $GLOBALS['dbQueries'][] = $query;
        $result = mysql_query($query, $GLOBALS['db_link']);

        if (!$ignore) {
            if (($result == false) && !preg_match('/INSERT INTO '.adesk_prefix("querydebug").'/', $query)) {
                $ary = array(
                    '=at' => 'NOW()',
                    'query' => $query
                );
                adesk_sql_query("INSERT INTO `#querydebug` ".adesk_sql_insert_str($ary), true);
            }
        }

        return $result;
    }
} else {
    function adesk_sql_query($query, $ignore = false) {

        $query = adesk_prefix_replace($query);

//        if (!isset($GLOBALS['dbLastQuery']))
//          $GLOBALS['dbLastQuery'] = array();
//       $GLOBALS['dbQueries'][] = $query;
        $GLOBALS['dbLastQuery'] = array($query);

        $result = mysql_query($query, $GLOBALS['db_link']);
        return $result;
    }
}

function adesk_sql_fetch_row($result) {
    return mysql_fetch_row($result);
}

function adesk_sql_fetch_assoc($result, $datecols = array()) {
	if ( !$result ) return false;

    $row = mysql_fetch_assoc($result);

	if (count($datecols) > 0 && isset($GLOBALS["site"]["datetimeformat"])) {
		$t_offset = adesk_date_offset_hour();

		foreach ($datecols as $datecol) {
			if (!isset($row[$datecol]))
				continue;

			if (!$row[$datecol])
				continue;

			if ($row[$datecol] == '0000-00-00 00:00:00')
				continue;

			if ($row[$datecol] == '9999-01-01 00:00:00')
				continue;

			$time = strtotime($row[$datecol]);
			if ($time !== false && $time !== -1)
				$row[$datecol] = strftime("%Y-%m-%d %H:%M:%S", $time + ($t_offset * 3600));
		}
	}

	return $row;
}

function adesk_sql_free_result($result) {
    mysql_free_result($result);
}

function adesk_sql_num_rows($result) {
    return mysql_num_rows($result);
}

function adesk_sql_affected_rows($link = null) {
    if (!is_null($link))
		return mysql_affected_rows($link);
	else
		return mysql_affected_rows();
}

function adesk_sql_insert_str($insert = array(), $fields = true) {
    $query = '(';
    if ( $fields ) {
	    foreach ($insert as $k => $v) {
	        if (preg_match("/^=/",$k)) {
	            $k = preg_replace("/^=/",'',$k);
	        }
	        $query .= " `$k`,";
	    }
	    $query = rtrim($query,',');
	    $query .= ') VALUES (';
    }

    foreach ($insert as $k => $v) {
        if (preg_match("/^=/",$k)) {
            $query .= " $v,";
        } else {
            $query .= " '" . adesk_sql_escape($v) . "',";
        }
    }
    $query = rtrim($query,',');
    $query .= ')';
    return $query;
}

function adesk_sql_insert($table, $array, $datecols = array()) {
	if (count($datecols) > 0) {
		$t_offset = adesk_date_offset_hour();

		foreach ($datecols as $datecol) {
			if (!isset($array[$datecol]))
				continue;

			if (!$array[$datecol])
				continue;

			if ($array[$datecol] == '0000-00-00 00:00:00')
				continue;

			if ($array[$datecol] == '9999-01-01 00:00:00')
				continue;

			$time = strtotime($array[$datecol]);
			if ($time !== false && $time !== -1)
				$array[$datecol] = strftime("%Y-%m-%d %H:%M:%S", $time - ($t_offset * 3600));
		}
	}

	if ( is_array($array) and isset($array[0]) ) {

		$first = true;
		foreach ( $array as $k => $v ) {
			$array[$k] = adesk_sql_insert_str($v, $first);
			$first = false;
		}
		$query = implode(', ', $array);
	} else {
		$query = adesk_sql_insert_str($array);
	}

	return adesk_sql_query("INSERT INTO $table $query");
}

function adesk_sql_replace($table, $array, $datecols = array()) {
	if (count($datecols) > 0) {
		$t_offset = adesk_date_offset_hour();

		foreach ($datecols as $datecol) {
			if (!isset($array[$datecol]))
				continue;

			if (!$array[$datecol])
				continue;

			if ($array[$datecol] == '0000-00-00 00:00:00')
				continue;

			if ($array[$datecol] == '9999-01-01 00:00:00')
				continue;

			$time = strtotime($array[$datecol]);
			if ($time !== false && $time !== -1)
				$array[$datecol] = strftime("%Y-%m-%d %H:%M:%S", $time - ($t_offset * 3600));
		}
	}

    return adesk_sql_query("REPLACE INTO ".$table." ".adesk_sql_insert_str($array));
}

function adesk_sql_set_str($set = array(), $auth = false) {
    $query = '';
    foreach ($set as $k => $v) {
        if (preg_match("/^=/",$k)) {
            $k = preg_replace("/^=/",'',$k);
            //$v = adesk_prefix_replace($v); // allow subqueries for update
            $query .= " `$k` = $v,";
        } else {
            $query .= " `$k` = '" . ( $auth ? adesk_auth_escape($v) : adesk_sql_escape($v) ) . "',";
        }
    }
    $query = rtrim($query,',');
    return $query;
}

function adesk_sql_update($table, $array, $where = 1, $datecols = array()) {
	if (count($datecols) > 0) {
		$t_offset = adesk_date_offset_hour();

		foreach ($datecols as $datecol) {
			if (!isset($array[$datecol]))
				continue;

			if (!$array[$datecol])
				continue;

			if ($array[$datecol] == '0000-00-00 00:00:00')
				continue;

			if ($array[$datecol] == '9999-01-01 00:00:00')
				continue;

			$time = strtotime($array[$datecol]);
			if ($time !== false && $time !== -1)
				$array[$datecol] = strftime("%Y-%m-%d %H:%M:%S", $time - ($t_offset * 3600));
		}
	}

    return adesk_sql_query("UPDATE ".$table." SET ".adesk_sql_set_str($array)." WHERE ".$where);
}

function adesk_sql_variable($var) {
	$var = adesk_sql_escape($var);
	$rs  = adesk_sql_query("SHOW VARIABLES LIKE '$var'");

	if (!$rs)
		return false;

	$row = adesk_sql_fetch_row($rs);

	if (!isset($row[1]))
		return false;

	return $row[1];
}

// Update only one column.  Instead of creating an array just for one
// value and calling adesk_sql_update, we make a direct call to
// adesk_sql_query.

function adesk_sql_update_one($table, $field, $value, $where = 1) {
    return adesk_sql_query("UPDATE ".$table." SET ".adesk_sql_set_str(array($field => $value))." WHERE ".$where);
}

function adesk_sql_delete($table, $where, $auth = false) {
    if ( $auth ) {
		mysql_query("DELETE FROM ".$table." WHERE ".$where, $GLOBALS["auth_db_link"]);
    } else {
		adesk_sql_query("DELETE FROM ".$table." WHERE ".$where);
    }
    return true;
}

function adesk_sql_insert_id() {
    return mysql_insert_id($GLOBALS['db_link']);
}

function adesk_sql_select_row($query, $datecols = array()) {
    $res = adesk_sql_query($query);

    if (!$res) {
        die(adesk_sql_error() . ": " . adesk_prefix_replace($query));
    }

    if (!@mysql_num_rows($res)) {
        return false;
    }

    $row = adesk_sql_fetch_assoc($res, $datecols);
    adesk_sql_free_result($res);

    return $row;
}

function adesk_sql_select_one($column, $table = '', $where = 1, $auth = false) {
    if ($table == '' && $where == 1) {
        if (preg_match('/^\s*SELECT/', $column))
            $query = $column;
        else
            $query = "SELECT ".$column;
    } elseif ($column[0] == '=')
        $query = "SELECT ".substr($column, 1)." FROM ".$table." WHERE ".$where;
    else
        $query = "SELECT `".$column."` FROM ".$table." WHERE ".$where;

    if ( $auth ) {
		$query = adesk_prefix_replace($query);
		$res = mysql_query($query, $GLOBALS["auth_db_link"]);
    } else {
		$res = adesk_sql_query($query);
    }

    if ($res != false && @mysql_num_rows($res)) {
        $row = adesk_sql_fetch_row($res);
        adesk_sql_free_result($res);
        return $row[0];
    } else {
        return "";
    }
}

function adesk_sql_select_array($query, $datecols = array()) {
    $res = adesk_sql_query($query);

    if (!$res) {
        die(adesk_sql_error() . ": " . adesk_prefix_replace($query));
    }

    $ary = array();

    while ($row = adesk_sql_fetch_assoc($res, $datecols))
        $ary[] = $row;

    adesk_sql_free_result($res);
    return $ary;
}

function adesk_sql_select_list($query) {
    $res = adesk_sql_query($query);

    if (!$res) {
        die(adesk_sql_error() . ": " . adesk_prefix_replace($query));
    }

    $ary = array();

    while ($row = adesk_sql_fetch_row($res))
        $ary[] = $row[0];

    adesk_sql_free_result($res);
    return $ary;
}

function adesk_sql_select_box_array($query) {
    $link = adesk_sql_query($query);
    $result = array();
    if ($link) {
        while ($i = adesk_sql_fetch_row($link)) {
            $result[$i[0]] = $i[1];
        }
    }
    return $result;
}

function adesk_sql_in_list($list) {
    return "'".implode("','", $list)."'";
}

function adesk_sql_in($column, $list) {
    return $column." IN (".adesk_sql_in_list($list).")";
}

function adesk_sql_escape($string, $useInLike = false) {
    if ( is_array($string) ) {
        return adesk_sql_escape_array($string);
    }
	$string = strval($string);
    if (version_compare(phpversion(), "4.3.0") == "-1") {
        $string = mysql_escape_string($string);
    } else {
        $string = mysql_real_escape_string($string, $GLOBALS['db_link']);
    }
    if ( $useInLike ) $string = addcslashes($string, '%_');
    return $string;
}

/* DEPRECATED */
function adesk_sql_unescape($string) {
    return ( get_magic_quotes_runtime() ? stripslashes($string) : $string );
}

function adesk_sql_escape_array(&$ary) {
    foreach ($ary as $key => $val) {
        if (is_array($val))
            adesk_sql_escape_array($ary[$key]);
        else
            $ary[$key] = adesk_sql_escape($val);
    }
    return $ary;
}

/* DEPRECATED */
function adesk_sql_unescape_array(&$ary) {
    foreach ($ary as $key => $val) {
        if (is_array($val))
            adesk_sql_unescape_array($ary[$key]);
        else
            $ary[$key] = adesk_sql_unescape($val);
    }
    return $ary;
}

function adesk_sql_limit($offset, $limit) {
    $offset = intval($offset);
    $limit  = intval($limit);

    if ($limit < 1)
        return "";
    else
        return "LIMIT $offset, $limit";
}


function adesk_sql_error() {
	return mysql_error($GLOBALS['db_link']);
}

function adesk_sql_error_number() {
	return mysql_errno($GLOBALS['db_link']);
}

function adesk_sql_default_row($table, $auth = false) {
	$q = "SHOW COLUMNS FROM $table";
	$sql = ( $auth ? mysql_query($q, $GLOBALS["auth_db_link"]) : adesk_sql_query($q) );
	if ( !$sql ) return false;
	$r = array();
	while ( $row = adesk_sql_fetch_assoc($sql) ) {
		$r[$row['Field']] = ( ( $row['Key'] == 'PRI' and $row['Extra'] == 'auto_increment' ) ? 0 : $row['Default'] );
	}
	return $r;
}



/**
 * Backup function
 *
 * @param string $what false/structure/data switch
 */
function adesk_sql_backup_all($includeAuth = false, $what = false, $comments = false, $delim = '', $delete = false) {
	// get all application's tables
	$prefix = adesk_sql_escape(adesk_prefix(), true);
	$sql = adesk_sql_query("SHOW TABLES LIKE '$prefix%'");
	//echo strtoupper($k) . ' = ' . mysql_num_rows($sql) . ' tables.' . $nl . $nl;
	while ( $row = mysql_fetch_row($sql) ) {
		if (
			!(
				isset($_SERVER['REQUEST_URI'])
			and
				strpos($_SERVER['REQUEST_URI'], 'make_install_sql.php') !== false
			and
				(
					substr($row[0], 0, 6 ) == 'tt_faq'
				or
					substr($row[0], 0, 4 ) == 'awebdesk_x'
				or
					substr($row[0], 0, 9 ) == 'awebdesk_mtbl_x'
				or
					substr($row[0], 0, 12) == 'Aawebdesk_mtbl_x'
				)
			)
		)
			adesk_sql_backup($row[0], $what, $comments, $delim, $delete);
	}
	// globalauth table too
	if ( $includeAuth )
		adesk_sql_backup('aweb_globalauth', $what, $comments, $delim, $delete);
}

function adesk_sql_backup_update($table) {
	$sql = adesk_sql_query("SELECT * FROM `$table`");
	if ( mysql_num_rows($sql) > 0 ) {
		while ( $row = mysql_fetch_assoc($sql) ) {
			$keys = array_keys($row);
			$vals = array();
			foreach ( $row as $k => $v ) {
				if ($k == $keys[0])
					continue;
				if ( is_null($v) ) {
					$vals[] = "`$k` = '-=-NULL-=-'";
				} else {
					$vals[] = "`$k` = '" . adesk_sql_escape($v) . "'";
				}
			}
			$vals  = implode(", ", $vals);
			$query =
				"UPDATE `$table` SET $vals WHERE `$keys[0]` = '" . $row[$keys[0]] . "';\r\n";
			$query = str_replace("'-=-NULL-=-'", 'NULL', $query);
			adesk_sql_stdout($query);
		}
	}
	adesk_sql_stdout("\r\n");
}

/**
 * Creates a backup of a single table
 *
 * @param string $table full table name with prefix included
 * @param string $what false/structure/data switch
 */
function adesk_sql_backup($table, $what = false, $comments = false, $delim = '', $delete = false) {
	$rs = adesk_sql_query("SHOW CREATE TABLE $table");
	$row = adesk_sql_fetch_row($rs);

	$has_utf8 = false;

	if ($row && preg_match('/CHARSET=utf8/', $row[1]))
		$has_utf8 = true;

	if ( $comments ) {
		adesk_sql_stdout("#\n");
		adesk_sql_stdout("# Table: $table\n");
		adesk_sql_stdout("#\n\n");
	}
	/*
		STRUCTURE
	*/
	if ( !$what or $what == 'structure' ) {
		if ( $comments ) {
			adesk_sql_stdout("# Structure\n");
		}

		if ($delete) {
			adesk_sql_stdout("DROP TABLE IF EXISTS `$table`;\n");
		}
		adesk_sql_stdout("CREATE TABLE `$table` ($delim");
		$sqlColumns = adesk_sql_query("SHOW COLUMNS FROM `$table`");
		// listing fields
		while ( $rowColumns = mysql_fetch_assoc($sqlColumns) ) {
			// field name with backticks
			adesk_sql_stdout("`$rowColumns[Field]` ");
			// then uppercase type
			//$type = strtoupper($rowColumns['Type']);
			$type = $rowColumns['Type'];
			$upper = explode("(", $type);
			$upper = strtoupper($upper[0]);
			adesk_sql_stdout($type . ' ');
			// is it null or not
			if (
				( $rowColumns['Null'] and strtoupper($rowColumns['Null']) == 'YES' )
			or
				( strpos($upper, 'TEXT') !== false or strpos($upper, 'BLOB') !== false )
			) {
				adesk_sql_stdout('NULL');
			} else {
				adesk_sql_stdout('NOT NULL ');
				if ( !( $rowColumns['Extra'] and strtolower($rowColumns['Extra']) == 'auto_increment') ) {
					if ( strpos($upper, 'TEXT') === false and strpos($upper, 'BLOB') === false ) {
						adesk_sql_stdout("DEFAULT '$rowColumns[Default]'");
					}
				}
			}
			// auto_increment?
			if ( $rowColumns['Extra'] and strtolower($rowColumns['Extra']) == 'auto_increment' )
				adesk_sql_stdout(" AUTO_INCREMENT");
			adesk_sql_stdout(",$delim");
		}
		// listing keys
		$indexes = array();
		$oneIndex = array(
			'fields' => array(),
			'primary' => false,
			'unique' => false,
			'fulltext' => false,
		);
		// fetching keys
		$sqlIndexes = adesk_sql_query("SHOW INDEX FROM `$table`");
		while ( $rowIndexes = mysql_fetch_assoc($sqlIndexes) ) {
			$key = $rowIndexes['Key_name'];
			// create index array
			if ( !isset($indexes[$key]) )
				$indexes[$key] = $oneIndex;
			// set index props
			$indexes[$key]['primary'] = ( strtolower($key) == 'primary' );
			$indexes[$key]['unique'] = ( $rowIndexes['Non_unique'] == 0 and strtolower($key) != 'primary' );
			$indexes[$key]['fulltext'] = ( strtolower($rowIndexes['Index_type']) == 'fulltext' );
			// deal with this field
			$field = "`$rowIndexes[Column_name]`";
			if ( $rowIndexes['Sub_part'] )
				$field .= " ($rowIndexes[Sub_part])";
			$indexes[$key]['fields'][] = $field;
		}
		$keys = array();
		foreach ( $indexes as $key => $index ) {
			$i = '';
			if ( $index['primary'] ) {
				$i .= "PRIMARY KEY ";
			} elseif ( $index['unique'] ) {
				$i .= "UNIQUE KEY `$key` ";
			} elseif ( $index['fulltext'] ) {
				$i .= "FULLTEXT KEY `$key` ";
			} else {
				$i .= "KEY `$key` ";
			}
			$i .= "(" . implode(", ", $index['fields']) . ")";
			$keys[] = $i;
		}
		adesk_sql_stdout(implode(",$delim", $keys));
		// close this table's create statement
		adesk_sql_stdout(")");
		// listing engine and comments
		$tableEsc = adesk_sql_escape($table, true);
		$sqlStatus = adesk_sql_query("SHOW TABLE STATUS LIKE '$tableEsc'");
		$rowStatus = mysql_fetch_assoc($sqlStatus);
		// engine
		adesk_sql_stdout(" ENGINE=$rowStatus[Engine]");
		// comment
		#if ( $rowStatus['Comment'] )
		#	adesk_sql_stdout(" COMMENT='" . adesk_sql_escape($rowStatus['Comment']) . "'");

		if ($has_utf8)
			adesk_sql_stdout(" DEFAULT CHARSET=utf8 DEFAULT COLLATE = utf8_general_ci");
		// cleanup
		adesk_sql_stdout(";\r\n");
	}
	/*
		DATA
	*/
	if ( !$what or $what == 'data' ) {
		adesk_sql_stdout("# Data\n");
		$sql = adesk_sql_query("SELECT * FROM `$table`");
		if ( mysql_num_rows($sql) > 0 ) {
			//echo $row[0] . ' = ' . mysql_num_rows($sql) . $nl;
			while ( $row = mysql_fetch_assoc($sql) ) {
				$keys = array_keys($row);
				foreach ( $row as $k => $v ) {
					if ( is_null($v) ) {
						$row[$k] = '-=-NULL-=-';
					} else {
						$row[$k] = adesk_sql_escape($v);
					}
				}
				$query =
					'INSERT INTO `' . $table .
					'` (`' . implode('`, `', $keys) .
					"`) VALUES ('" . implode("', '", $row) . "');\r\n"
				;
				$query = str_replace("'-=-NULL-=-'", 'NULL', $query);
				adesk_sql_stdout($query);
			}
		}
		adesk_sql_stdout("\r\n");
	}
	if ( !$what ) adesk_sql_stdout("\r\n");
}

function adesk_sql_restore($data, $print = false, $comment = '') {
	$r = array(
		'total' => 0,
		'queries' => 0,
		'comments' => 0,
		'good' => 0,
		'bad' => 0,
		'empties' => 0,
	);
	if ( $print and $comment != '' ) echo "<!-- $comment  -->\n";
	$statements = preg_split("/\r?\n/", $data);
	$i = 0;
	foreach ( $statements as $statement ) {
		$i++;
		$statement = trim($statement);
		if ( $statement != '' and substr($statement, 0, 1) != '#' and substr($statement, 0, 2) != '--' ) {
			$r['queries']++;
			if ( function_exists('adesk_sql_stdin_filter') ) {
				adesk_sql_stdin_filter($statement);
			}
			if ( !adesk_sql_query($statement) ) {
				$r['bad']++;
				$statement = adesk_prefix_replace($statement);
				if ( $print ) {
					echo "<!--\n$i.\nmysql_query(\"$statement\");\n-->\n";
					echo "FAILED!\nReason: " . adesk_sql_error() . "\n-->\n";
				}
			} else {
				$r['good']++;
				//echo "<!--\n$i.\nmysql_query(\"$statement\");\n-->\n";
			}
		} else {
			// blank statement or comment?
			$comment = false;
			if ( $statement != '' ) {
				if ( substr($statement, 0, 1) == '#' ) {
					$statement = trim(substr($statement, 1));
					$comment = $statement != '';
				}
				if ( substr($statement, 0, 2) == '--' ) {
					$statement = trim(substr($statement, 2));
					$comment = true;
				}
				// if comment
				if ( $comment ) {
					$r['comments']++;
					// html comment
					if ( $print ) echo "<!-- $statement -->\n";
				} else {
					$r['empties']++;
				}
			} else {
				$r['empties']++;
			}
		}
		$r['total']++;
	}
	return $r;
}


/*
	- to save in a string: you have to create $GLOBALS['sqlstream'] = ''; variable
		- to encode with gz: just define $GLOBALS['gzip_sql']
	- to save to a file: $GLOBALS['sqlstreamfile'] holds fopen|gzopen resource id
		- to encode with gz: just define $GLOBALS['gzip_sql']
	- to echo: just define $GLOBALS['sqlstreamecho'] = whatever

	- filtering: define adesk_sql_stdout_filter(&$string) function
		example of usage: for demo, change all dates/times to NOW()
*/
function adesk_sql_stdout($string) {
	if ( function_exists('adesk_sql_stdout_filter') ) {
		adesk_sql_stdout_filter($string);
	}
	// should we encode the output?
	$gzip = ( isset($GLOBALS['gzip_sql']) and function_exists('gzopen') );
	// write => write to file
	$write = isset($GLOBALS['sqlstreamfile']) and is_resource($GLOBALS['sqlstreamfile']);
	if ( $write ) {
		// writing to stream
		$res = ( $gzip ? gzwrite($GLOBALS['sqlstreamfile'], $string) : fwrite($GLOBALS['sqlstreamfile'], $string) );
	}
	// print => print out the result
	$print = isset($GLOBALS['sqlstreamecho']);
	if ( $print ) {
		// echoing
		echo( $gzip ? gzcompress($string, 9) : $string );
		flush();
	}
	// store => store in global var
	$store = isset($GLOBALS['sqlstream']);
	if ( $store ) {
		$GLOBALS['sqlstream'] .= $string;
	}
}


function adesk_sql_value_exists($table, $field, $value, $where = '', $auth = false) {
	if ( !$auth ) {
		$value = adesk_sql_escape($value);
    	list($found) = adesk_sql_fetch_row(adesk_sql_query("SELECT COUNT(*) FROM $table WHERE `$field` = '$value' $where"));
	} else {
		if ( !function_exists('adesk_auth_escape') ) require_once dirname(__FILE__) . '/auth.php';
		$value = adesk_auth_escape($value);/* mysql_real_escape_string($value, $GLOBALS['auth_db_link']);*/
    	list($found) = adesk_sql_fetch_row(mysql_query("SELECT COUNT(*) FROM $table WHERE `$field` = '$value' $where", $GLOBALS['auth_db_link']));
	}
    return ( $found > 0 );
}

function adesk_sql_find_next_index($table, $field, $value, $where = '', $auth = false) {
	$i = 0;
	$origValue = $value;
	// as long as the match exists, increment a counter
	while ( adesk_sql_value_exists($table, $field, $value, $where, $auth) ) $value = $origValue . '_' . ++$i;
	return $value;
}

function adesk_sql_lastquery() {
	if ( !isset($GLOBALS['dbLastQuery']) ) return null;
	if ( is_array($GLOBALS['dbLastQuery']) ) {
		return $GLOBALS['dbLastQuery'][count($GLOBALS['dbLastQuery'])-1];
	}
	return $GLOBALS['dbLastQuery'];
}

function adesk_sql_query_info($query) {
	$arr = preg_split('/\s+/', trim($query));
	$cmd = strtoupper($arr[0]);
	$table = '';
	if ( $cmd == 'UPDATE' ) {
		$table = trim($arr[1]);
	} elseif ( $cmd == 'CREATE' ) {
		if ( strtoupper($arr[2]) == 'IF' and strtoupper($arr[3]) == 'NOT' and strtoupper($arr[4]) == 'EXISTS' ) {
			$arr[2] = $arr[5];
		}
		$table = $arr[2];
	} elseif ( $cmd == 'DROP' ) {
		if ( strtoupper($arr[2]) == 'IF' and strtoupper($arr[3]) == 'EXISTS' ) {
			$arr[2] = $arr[4];
		}
		list($table) = explode(';', $arr[2]);
	} elseif ( $cmd == 'INSERT' ) {
		if ( strtoupper($arr[2]) == 'IGNORE' ) {
			$arr[2] = $arr[3];
		}
		$table = $arr[2];
	} elseif ( $cmd == 'DELETE' ) {
		list($table) = explode(';', $arr[2]);
	} else { // ALTER, DELETE, TRUNCATE, OPTIMIZE, REPAIR...
		list($table) = explode(';', $arr[2]);
	}
	return array(
		'type' =>  $cmd,
		'table' => ( $cmd == 'UPDATE' ? $arr[1] : $arr[2] ),
		'fatal' => ( in_array($cmd, array('CREATE', 'ALTER', 'DROP')) ),
		'message' => adesk_sql_query_info_message($cmd, $table)
	);
}

function adesk_sql_query_info_message($type, $table) {
	switch ( $type ) {
		case 'CREATE':
			return sprintf(_a('Creating table: %s '), $table);
		case 'ALTER':
			return sprintf(_a('Altering table: %s '), $table);
		case 'UPDATE':
			return sprintf(_a('Updating data in table: %s '), $table);
		case 'INSERT':
			return sprintf(_a('Adding data to table: %s '), $table);
		case 'DELETE':
			return sprintf(_a('Deleting data from table: %s '), $table);
		case 'REPLACE':
			return sprintf(_a('Replace data in table: %s '), $table);
		case 'DROP':
			return sprintf(_a('Dropping table: %s '), $table);
		case 'TRUNCATE':
			return sprintf(_a('Clearing out table: %s '), $table);
		case 'REPAIR':
			return sprintf(_a('Repairing table: %s '), $table);
		case 'OPTIMIZE':
			return sprintf(_a('Optimizing table: %s '), $table);
		default:
			return sprintf(_a('Running queries on table: %s '), $table);
	}
}

function adesk_sql_supports_engine($engine) {
	$rs = adesk_sql_query("SHOW ENGINES");
	while ($row = adesk_sql_fetch_assoc($rs)) {
		if ($row["Engine"] == $engine) {
			if ($row["Support"] == "YES" || $row["Support"] == "DEFAULT")
				return true;
		}
	}

	return false;
}

function adesk_sql_supports_charset($set) {
	$rs = adesk_sql_query("SHOW CHARACTER SET");

	if (!$rs)
		return false;

	while ($row = adesk_sql_fetch_assoc($rs)) {
		if ($row["Charset"] == $set)
			return true;
	}

	return false;
}

function adesk_sql_compare($varkey, $value) {
	# Do a SHOW VARIABLES query; if $varkey is less than $mem, return false, otherwise return
	# true.

	$varkey = adesk_sql_escape($varkey);
	$rs     = adesk_sql_query("SHOW VARIABLES LIKE '$varkey'");

	if ($row = adesk_sql_fetch_assoc($rs))
		return $row["Value"] >= $value;

	# Guess the SHOW VARIABLES didn't work -- or the variable in question isn't present in the
	# system.  What if InnoDB isn't installed, and no InnoDB variables are present?  Well, in
	# that case, we probably ought not to worry about InnoDB's memory.  There's no truly right
	# answer here, but I think true more often than not works.
	return true;
}

function adesk_sql_tablekeys($table, $enable = false) {
	$cmd = ( $enable ? 'ENABLE' : 'DISABLE' );
	return adesk_sql_query("ALTER TABLE `$table` $cmd KEYS;");
}

?>
