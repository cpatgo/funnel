<?php

$GLOBALS['kbcolumns'] = array();


/**
 * Inflectors are used by DeskRecord to customize how DeskRecord class names
 * are translated into Database table names.  The two main methods are
 * Classify and Tableize.  These methods have default behaviors, but should most
 * likely be reimplemented in child classes specific to applications using
 * DeskRecord.  When you construct an Active Record Object you must
 * pass into it the name of an InflectorInterface child class if you want to
 * override the default behavior of InflectorInterface.
 *
 * @note This class should actually be treated like an Interface.
 * @note This is based on code from the PHP Cake framework.
 * @author Chris TenHarmsel
 */
class InflectorInterface {
	/**
	 * Returns the plural version of a given word.
	 *
	 * @param $word The word to pluralize
	 * @return string Pluralized version of the word.
	 */
	function pluralize($word) {
		$count = 0;
		$newValue = preg_replace(
		array(
		 '/(x|ch|ss|sh)$/',
		 '/([^aeiouy]|qu)ies$/',
		 '/([^aeiouy]|qu)y$/',
		 '/(?:([^f])fe|([lr])f)$/',
		 '/sis$/',
		 '/([ti])um$/',
		 '/person$/',
		 '/man$/',
		 '/child$/',
		 '/s$/'
		),
		array(
		 '\1es',
		 '\1y',
		 '\1ies',
		 '\1\2ves',
		 'ses',
		 '\1a',
		 'people',
		 'men',
		 'children',
		 's'
		),
		$word);

		if($newValue == $word) {
			$newValue .= "s";
		}
		return $newValue;
	}

	/**
	 * Returns the singular version of a given word.
	 *
	 * @param $word The word to pluralize.
	 * @return string The pluralized version of the word.
	 */
	function singularize($word)	{
		return preg_replace(
			array(
			 '/(x|ch|ss)es$/',
			 '/movies$/',
			 '/(^aeiouy|qu)ies$/',
			 '/(lr)ves$/',
			 '/(^f)ves$/',
			 '/(analy|ba|diagno|parenthe|progno|synop|the)ses$/',
			 '/(ti)a$/',
			 '/people$/',
			 '/men$/',
			 '/status$/',
			 '/children$/',
			 '/s$/',
			),
			array(
			 '\1',
			 'movie',
			 '\1y',
			 '\1f',
			 '\1fe',
			 '\1sis',
			 '\1um',
			 'person',
			 'man',
			 'status',
			 'child',
			 '',
			),
			$word
		);
	}

	/**
	 * Transform a table to a Class name.
	 * By default this takes the table name,  singularizes it, and uppercases the
	 * first letter to make the class name (ie. tables, becomes "Table", also
	 * 'table_names becomes Table_Name"
	 *
	 * You should override this method if you want to change how your class names
	 * and table names are related.
	 *
	 * @param $table The table name to classify.
	 * @return string The name of the class that corresponds to the specified table.
	 */
	function Classify($table)	{
		// Bumpy case the name
		$bumpyName = preg_replace_callback(
			"/_(\w)/",
			create_function(
				'$matches',
				'return "_" . strtoupper($matches[1]);'
			),
			$table);
		return InflectorInterface::singularize($bumpyName);
	}

	/**
	 * Transform a class name to a table name.
	 * By default this method takes a class name lowercases the entire thing and pluralizes it.
	 *
	 * You should override this method if you want to change how your class names
	 * and table names are related.
	 *
	 * @param $class The class name to tableize.
	 * @return string The name of the table corresponding to the class specified.
	 */
	function Tableize($class)	{
		return InflectorInterface::pluralize(strtolower($class));
	}

	/**
	 * Builds an array of field names for a given table.
	 * Assumes you are already connected to a mysql database.
	 *
	 * @param $tablename Table name to inspect.
	 * @param $dbConn Database Connection handle to use, defaults to null, which will
	 * use the last connected mysql database.
	 */
	function ColumnInfo($tablename, $dbConn = null){
		if ( isset($GLOBALS['kbcolumns'][$tablename]) ) return $GLOBALS['kbcolumns'][$tablename];
		$query = sprintf("SHOW COLUMNS FROM %s", $tablename);
		if(is_null($dbConn)){
			$resultset = mysql_query( $query );
		} else {
			$resultset = mysql_query($query, $dbConn);
		}

		$fieldNames = array();
		if(!$resultset) return $fieldNames;
		while($row = mysql_fetch_assoc($resultset)) {
			$fieldNames[] = $row;
		}
		$GLOBALS['kbcolumns'][$tablename] = $fieldNames;
		return $fieldNames;
	}
}
?>
