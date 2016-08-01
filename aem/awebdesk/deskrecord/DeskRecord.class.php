<?php
require_once(dirname(__FILE__) . '/DeskAttribute.class.php');
require_once(dirname(__FILE__) . '/InflectorInterface.class.php');

/**
 * The DeskRecord class is loosely based on the class of the same name in
 * Ruby On Rails.  It is meant to abstract databse information by hiding most
 * of the SQL you would normally use and instead representing individual rows
 * in a database table as objects.  You can then interact with these objects,
 * retrieving and modifying data as you want.  You can also programatically
 * save the data back to the database, without ever having to write SQL
 * statements directly.
 *
 * This allows you code to be cleaner and more descriptive, centralizes database
 * access into one place, and give you the ability to create a rich set of
 * data objects that know how to manipulate themselves instead of having
 * random SELECT and UPDATE statements peppered throughout your code.
 *
 * This class is not meant to be used directly, rather you subclass it in very
 * thin classes, which ofte don't have any methods except the ones they
 * inherit by being a child class.  The important part is the Child class's
 * name, which is used in conjunction with the InflectorInterface to relate
 * the class to a table..
 *
 * DeskRecord also offers lazy loading when you use any of the static
 * "Find" methods (like FindById()).  Lazy loading will load only the Id of the
 * record until you request an attribute by using getAttribute().  This way
 * if you have a record that has large amounts of data (such as a binary blob
 * field or something simliar), you can use lazy loading to avoid loading
 * all of that data if you're not going to use it.
 *
 * @author Chris TenHarmsel
 */
class DeskRecord {
	/**
	 * Attributes of this object
	 * @note INTERNAL, DO NOT ACCESS DIRECTLY
	 */
	var $_attributes;

	/**
	 * ID of this object.
	 * @note INTERNAL, DO NOT ACCESS DIRECTLY
	 */
	var $_id;

	/**
	 * Database connection to use.
	 * @note INTERNAL, DO NOT ACCESS DIRECTLY
	 */
	var $_dbConn;

	/**
	 * Inflector object to use. (see InflectorInterface)
	 * @note INTERNAL, DO NOT ACCESS DIRECTLY
	 */
	var $_inflector;

	/**
	 * The constructor sets up the object based on the database handle and
	 * inflector class passed in, it builds the attribute list and initially
	 * sets the ID to false.  Only use this if you want to create a new
	 * record.
	 *
	 * @param $dbConn Database Connection handle as returned by mysql_connect()
	 * @param $inflectorClass String The Inflector class to use.
	 */
	function DeskRecord($dbConn = null, $inflectorClass = "InflectorInterface") {
		$this->_inflector = new $inflectorClass;
		// Save the database connection
		$this->_dbConn = $dbConn;
		// What will end up being an associative array for the attributes
		$this->_attributes = array();
		// Build attribute array keys
		$tableName = $this->_inflector->Tableize(get_class($this));

		$fields = $this->_inflector->ColumnInfo($tableName, $dbConn);
		// Create an attribute for each field
		foreach($fields as $field) {
			if($field['Field'] != "id"){
				$this->_attributes[$field['Field']] =
					new DeskAttribute($field['Field'], $field['Type']);
			}
		}
		// Set id to false
		$this->_id = false;
	}

	/**
	 * Saves this object's data back to the database.  If the object is new, it
	 * will insert a new row into the database, otherwise if the data is modified
	 * it will update those fields in the database with the new information.
	 *
	 * If you have created a new record, you must call this method before you can
	 * can get an ID from getId().
	 */
	function save() {
		$dirtyAttributes = array();
		foreach($this->getAttributeNames() as $name){
			if($this->_attributes[$name]->isDirty()){
				$dirtyAttributes[] = $name;
			}
		}

		if(count($dirtyAttributes) > 0) {
			$tableName = $this->_inflector->Tableize(get_class($this));
			if($this->_id == false) {
				$attributeValues = array();
				// This is a new record
				foreach($dirtyAttributes as $name) {
					$attribute = $this->_attributes[$name];
					$value = $this->smart_escape($attribute->getValue(), $this->_dbConn);
					$valueString = "";
					if(preg_match("/[(varchar)|(text)|(date)|(char)|(tinytext)|(mediumtext)|(longtext)]/i", $attribute->getType())){
						$valueString = sprintf("'%s'", $value, true);
					} elseif(preg_match("/int/i", $attribute->getType())) {
						$valueString = sprintf("%d", $value, true);
					} elseif(preg_match("/[(float)|(double)]/i", $attribute->getType())) {
						$valueString = sprintf("%f", $value, true);
					}
					$attributeValues[$name] = $valueString;
				}

				$query = sprintf("INSERT INTO %s (%s) VALUES (%s)",
					$tableName,
					implode(", ", array_keys($attributeValues)),
					implode(", ", $attributeValues));

				if($this->_query($query)){
					if(!is_null($this->_dbConn)){
						$this->_id = mysql_insert_id($this->_dbConn);
					} else {
						$this->_id = mysql_insert_id();
					}
					return true;
				} else {
					return false;
				}
			} else {
				// This is an update
				$query = sprintf("UPDATE %s SET ", $tableName);
				// Go through the fields
				$statements = array();
				foreach($dirtyAttributes as $name) {
					$valueString = "";
					$attribute = $this->_attributes[$name];
					if(preg_match("/[(varchar)|(text)|(date)|(char)|(tinytext)|(mediumtext)|(longtext)]/i", $attribute->getType())){
						$valueString = sprintf("%s = '%s'", $name, $this->smart_escape($attribute->getValue(), $this->_dbConn));
					} elseif(preg_match("/int/i", $attribute->getType())) {
						$valueString = sprintf("%s = %d", $name, $this->smart_escape($attribute->getValue(), $this->_dbConn));
					} elseif(preg_match("/[(float)|(double)]/i", $attribute->getType())) {
						$valueString = sprintf("%s = %f", $name, $this->smart_escape($attribute->getValue(), $this->_dbConn));
					}

					$statements[] = $valueString;
				}

				$query .= implode(", ", $statements);
				$query .= " WHERE id = " . $this->_id;

				return $this->_query($query);
			}
		}

		return true;
	}

	/**
	 * This method deletes this record, which removes it from the database
	 * and resets its ID.  It retains all other information in the object in
	 * case you want to save it again.
	 */
	function delete() {
		$tableName = $this->_inflector->Tableize(get_class($this));
		$query = sprintf("DELETE FROM %s WHERE id = %d", $tableName, $this->_id);
		$success = $this->_query($query);

		if($success) {
			$this->_id = false;
		}

		return $success;
	}

	/**
	 * Accessor for the ID
	 * @returns integer Id.
	 */
	function getId(){
		return $this->_id;
	}

	/**
	 * Setter for the ID, you should probably not use this unless you want to
	 * manually update a row.
	 * @param $newId The new ID to set.
	 */
	function setId($newId) {
		$this->_id = $newId;
	}

	/**
	 * Gets the value of an attribute of the object, which corresponds to a field
	 * in the table row that this object represents.
	 *
	 * @param $name String The name of the attribute you want.
	 * @returns The value of that attribute, or null if it doesn't exist
	 */
	function getAttribute($name) {
		if(!in_array($name, $this->getAttributeNames())){
			return null;
		}

		// Load if needed
		if(!$this->_attributes[$name]->isLoaded()){
			$tableName = $this->_inflector->Tableize(get_class($this));
			$query = sprintf("SELECT %s FROM %s WHERE id = %d",
				$name, $tableName, $this->_id);
			$rs = $this->_query($query);
			$row = mysql_fetch_assoc($rs);
			$this->_attributes[$name]->setValue( DeskRecord::smart_runtime_unescape($row[$name]));
			// Not dirty since we just loaded it
			$this->_attributes[$name]->setDirty(false);
		}

		return $this->_attributes[$name]->getValue();
	}

	/**
	 * This method sets an attribute value.
	 *
	 * @param $name String Name of the attribute.
	 * @param $value New value of the attribute.
	 * @returns boolean indicating success.
	 */
	function setAttribute($name, $value) {
		if(!in_array($name, array_keys($this->_attributes))) {
			return false;
		}
		$this->_attributes[$name]->setValue($value);
		return true;
	}

	/**
	 * Returns the names of the attributes of this class.
	 * @return array of attribute names.
	 */
	function getAttributeNames() {
		return array_keys($this->_attributes);
	}

	/**
	 * Returns the values of the attributes of this class
	 * @returns array of attribute values, the keys of this array
	 * are the attribute names.
	 */
	function getAttributeValues() {
		return array_values($this->_attributes);
	}

	/**
	 * This function wraps mysql_real_escape_string in a smart way
	 * It first runs smart_gpc_escape on the string and then escapes
	 * it with the proper method for the version of PHP present.
	 *
	 * @param $string The string to escape.
	 * @param $db database handle to use when escaping.
	 * @returns string The escaped string.
	 */
	function smart_escape($string, $db = null) {
		if(version_compare(phpversion(),"4.3.0")=="-1") {
			return mysql_escape_string($string);
		} else {
			if ($db === null)
				return mysql_real_escape_string($string);
			else
				return mysql_real_escape_string($string, $db);
		}
	}

	/**
	 * This function checks the value of magic_quotes_runtime and unescapes strings
	 * if needed.
	 * @param $string String to unescape.
	 * @returns The string stripped of slashes as needed.
	 */
	function smart_runtime_unescape($string) {
		if(get_magic_quotes_runtime() == 1){
			return stripslashes($string);
		} else {
			return $string;
		}
	}

	/**
	 * "Static" helper function for calling mysql_query.
	 * @param $queryString The Query to execute.
	 * @param $db The Database resource to use.
	 * @returns The same thing that mysql_query returns.
	 */
	function StaticQuery($queryString, $db){
		if(!is_null($db)){
			return mysql_query($queryString, $db);
		} else {
			return mysql_query($queryString);
		}
	}

	/**
	 * Helper function to take into account whether we're using a custom DB connector
	 * Basically a non-static version of StaticQuery.
	 * @param $queryString String The Query to execute
	 * @returns the same thing as mysql_query.
	 */
	function _query($queryString){
		if(!is_null($this->_dbConn)){
			return mysql_query($queryString, $this->_dbConn);
		} else {
			return mysql_query($queryString);
		}
	}

	/**
	 * FindAll returns an array of all the Objects of a given type in the database.
	 * @param $className String The name of the class of Object to look up and return.
	 * @param $db Database connection resource to use.
	 * @param $inflectorClass String The name of the InflectorInterface class to use.
	 * @param $lazy boolean Whether or not to do lazy loading. (See class documentation
	 * for more information on this.
	 * @returns Array Of $className objects that represent all the rows in the database
	 * table for that object (determined via the InflectorInterface specified).
	 */
	function FindAll($className, $db = null, $inflectorClass = "InflectorInterface", $lazy = false){
		$inflector = new $inflectorClass;

		if(!class_exists($className)) {
			require_once($className . ".class.php");
		}

		$tableName = $inflector->Tableize($className);
		$query = "";
		if($lazy){
			$query = sprintf("SELECT id FROM %s", DeskRecord::smart_escape($tableName, $db));
		} else {
			$query = sprintf("SELECT * FROM %s", DeskRecord::smart_escape($tableName, $db));
		}

		$rs = DeskRecord::StaticQuery($query, $db);
		$results = array();

		if($rs){
			while($row = mysql_fetch_assoc($rs)){
				$instance = new $className($db, $inflectorClass);
				$instance->_id = intval($row['id']);

				// If we're not lazy loading, load the values
				if(!$lazy){
					$attributes = $instance->getAttributeNames();
					foreach($attributes as $name) {
						$instance->_attributes[$name]->setValue( DeskRecord::smart_runtime_unescape($row[$name]) );
						$instance->_attributes[$name]->setDirty(false);
					}
				}

				// Add it into the array
				$results[] = $instance;
			}
		}

		return $results;
	}

	/**
	 * This method returns an object of the type you specify with the given ID.
	 *
	 * @param $className String The name of the class of Object to look up and return.
	 * @param $id Integer Id of the record to get.
	 * @param $db Database connection resource to use.
	 * @param $inflectorClass String The name of the InflectorInterface class to use.
	 * @param $lazy boolean Whether or not to do lazy loading. (See class documentation
	 * for more information on this.
	 * @returns Object Of type $className for the ID specified
	 */
	function FindById($className, $id, $db = null, $inflectorClass = "InflectorInterface", $lazy = false) {
		$inflector = new $inflectorClass;
		$tableName = $inflector->Tableize($className);
		$query = "";
		if($lazy){
			$query = sprintf("SELECT id FROM %s WHERE id = %d",
				DeskRecord::smart_escape($tableName, $db), $id);
		} else {
			$query = sprintf("SELECT * FROM %s WHERE id = %d",
				DeskRecord::smart_escape($tableName, $db), $id);
		}

		$rs = DeskRecord::StaticQuery($query, $db);
		if($rs == false){
			return null;
		}

		$row = mysql_fetch_assoc($rs);
		if(!$row) {
			return null;
		}

		// Create a new instance
		if(!class_exists($className)){
			require_once($className . ".class.php");
		}
		$instance = new $className($db, $inflectorClass);
		$instance->_id = intval($id);

		if(!$lazy){
			$attributes = $instance->getAttributeNames();
			foreach($attributes as $name) {
				$instance->_attributes[$name]->setValue( DeskRecord::smart_runtime_unescape($row[$name]) );
				$instance->_attributes[$name]->setDirty(false);
			}
		}

		// Set dirty to false, b/c it's not dirty
		return $instance;
	}

	/**
	 * Finds a set of records by attributes.
	 *
	 * @param $className String The name of the class of Object to look up and return.
	 * @param $attributes Array of attribute names
	 * @param $values Array of matching values (matched to the names)
	 * @param $db Database connection resource to use.
	 * @param $inflectorClass String The name of the InflectorInterface class to use.
	 * @param $lazy boolean Whether or not to do lazy loading. (See class documentation
	 * for more information on this.
	 * @returns Array Of $className objects that represent all the rows in the database
	 * table for that object that match the attributes specified.
	 */
	function FindByAttributes($className, $attributes, $values, $db = null, $inflectorClass = "InflectorInterface", $lazy = true) {
		if(!is_array($attributes) || !is_array($values) || (count($attributes) != count($values))){
			return array();
		}

		// Create an instance, just so we can find out if it's a valid attribute
		if(!class_exists($className)){
			require_once($className . ".class.php");
		}
		$testInstance = new $className($db, $inflectorClass);
		foreach($attributes as $attributeName){
			if(!in_array($attributeName, array_keys($testInstance->_attributes))) {
				return array();
			}
		}

		$attributeComparisons = array();
		for($i = 0; $i < count($attributes); $i++){
			$compareString = "";
			$type = $testInstance->_attributes[$attributes[$i]]->getType();

			if(preg_match("/((varchar)|(text)|(date)|(char)|(tinytext)|(mediumtext)|(longtext))/i", $type)){
				$compareString = sprintf("%s = '%s'", $attributes[$i], DeskRecord::smart_escape($values[$i], $db));
			} elseif(preg_match("/int/i", $type)) {
				$compareString = sprintf("%s = %d", $attributes[$i], intval($values[$i]));
			} elseif(preg_match("/((float)|(double))/i", $type)) {
				$compareString = sprintf("%s = %f", $attributes[$i], floatval($values[$i]));
			}

			$attributeComparisons[] = $compareString;
		}


		$results = array();
		$inflector = new $inflectorClass;
		$tableName = $inflector->Tableize($className);
		$query = "";
		if($lazy){
			$query = sprintf("SELECT id FROM %s WHERE %s",
				$tableName,
				join(" AND ", $attributeComparisons));
		} else {
			$query = sprintf("SELECT * FROM %s WHERE %s",
				$tableName,
				join(" AND ", $attributeComparisons));
		}

		$rs = DeskRecord::StaticQuery($query, $db);

		if($rs){
			while($row = mysql_fetch_assoc($rs)){
				$instance = new $className($db, $inflectorClass);
				$instance->_id = intval($row['id']);

				if(!$lazy){
					$attributes = $instance->getAttributeNames();
					foreach($attributes as $name) {
						$instance->_attributes[$name]->setValue( DeskRecord::smart_runtime_unescape($row[$name]) );
						$instance->_attributes[$name]->setDirty(false);
					}
				}

				// Add it into the array
				$results[] = $instance;
			}
		}

		return $results;
	}

	/**
	 * This function returns the first record found for given attributes,
	 * it's basically a convenience function when you know you're only going
	 * to get one record back.
	 *
	 * @param $className String The name of the class of Object to look up and return.
	 * @param $attributes Array of attribute names
	 * @param $values Array of matching values (matched to the names)
	 * @param $db Database connection resource to use.
	 * @param $inflectorClass String The name of the InflectorInterface class to use.
	 * @param $lazy boolean Whether or not to do lazy loading. (See class documentation
	 * for more information on this.
	 * @returns Object Of type $className that represents the first row in the database
	 * table for that object that matches the attributes specified.
	 */
	function FindFirstByAttributes($className, $attributes, $values, $db = null, $inflectorClass = "InflectorInterface", $lazy = false) {
		// Call FindByAttributes
		$records = DeskRecord::FindByAttributes($className, $attributes, $values, $db, $inflectorClass, $lazy);
		if(count($records) > 0){
			return $records[0];
		} else {
			return null;
		}
	}

	/**
	 * Find function that allows you to specify the WHERE clause, be careful with this one.
	 * you will be returned an array of DeskRecord objects as usual.
	 *
	 * @param $className String The name of the class of Object to look up and return.
	 * @param $whereClause String The SQL code that follows the "WHERE" in an SQL SELECT statement.
	 * @param $db Resource Database connection resource to use.
	 * @param $inflectorClass String The name of the InflectorInterface class to use.
	 * @param $lazy boolean Whether or not to do lazy loading. (See class documentation
	 * for more information on this.
	 * @returns Array Of $className objects that match the terms specified in the $whereClause parameter.
	 */
	function Find($className, $whereClause, $db = null, $inflectorClass = "InflectorInterface", $lazy = false){
		$inflector = new $inflectorClass;
		$tableName = $inflector->Tableize($className);
		$query = "";
		if($lazy){
			$query = sprintf("SELECT id FROM %s WHERE %s",
				$tableName, $whereClause);
		} else {
			$query = sprintf("SELECT * FROM %s WHERE %s",
				$tableName, $whereClause);
		}

		$rs = DeskRecord::StaticQuery($query, $db);

		$records = array();
		if($rs != false){
			while($row = mysql_fetch_assoc($rs)){
				$instance = new $className($db, $inflectorClass);
				$instance->setId(intval($row['id']));
				if(!$lazy){
					foreach($instance->getAttributeNames() as $name){
						$instance->setAttribute($name, DeskRecord::smart_runtime_unescape($row[$name]));
						$instance->_attributes[$name]->setDirty(false);
					}
				}
				$records[] = $instance;
			}
		}

		return $records;
	}

	/**
	 * This function finds the number of records based on specified attributes.
	 *
	 * @param $className String The name of the class of Object to look up and return.
	 * @param $attributes Array of attribute names
	 * @param $values Array of matching values (matched to the names)
	 * @param $db Database connection resource to use.
	 * @param $inflectorClass String The name of the InflectorInterface class to use.
	 * @returns Integer The count of records matching the attributes specified.
	 */
	function FindCountByAttributes($className, $attributes, $values, $db = null, $inflectorClass = "InflectorInterface"){
		if(!is_array($attributes) || !is_array($values) || (count($attributes) != count($values))){
			return 0;
		}

		// Create an instance, just so we can find out if it's a valid attribute
		if(!class_exists($className)){
			require_once($className . ".class.php");
		}
		$testInstance = new $className($db, $inflectorClass);
		foreach($attributes as $attributeName){
			if(!in_array($attributeName, array_keys($testInstance->_attributes))) {
				return 0;
			}
		}

		$attributeComparisons = array();
		for($i = 0; $i < count($attributes); $i++){
			$compareString = "";
			$type = $testInstance->_attributes[$attributes[$i]]->getType();

			if(preg_match("/((varchar)|(text)|(date)|(char)|(tinytext)|(mediumtext)|(longtext))/i", $type)){
				$compareString = sprintf("%s = '%s'", $attributes[$i], DeskRecord::smart_escape($values[$i], $db));
			} elseif(preg_match("/int/i", $type)) {
				$compareString = sprintf("%s = %d", $attributes[$i], intval($values[$i]));
			} elseif(preg_match("/((float)|(double))/i", $type)) {
				$compareString = sprintf("%s = %f", $attributes[$i], floatval($values[$i]));
			}

			$attributeComparisons[] = $compareString;
		}

		return DeskRecord::FindCount($className, join(" AND ", $attributeComparisons), $db, $inflectorClass);
	}

	/**
	 * This function finds the number of records based on a "where clause", which
	 * is the SQL that follows the "WHERE" keyword in a plain SQL select statement.
	 *
	 * @param $className String The name of the class of Object to look up and return.
	 * @param $whereClause String The SQL code that follows the "WHERE" in an SQL SELECT statement.
	 * @param $db Resource Database connection resource to use.
	 * @param $inflectorClass String The name of the InflectorInterface class to use.
	 * @returns Integer The count of records matching the "WhereClause"
	 */
	function FindCount($className, $whereClause, $db = null, $inflectorClass = "InflectorClass"){
		$results = 0;
		$inflector = new $inflectorClass;
		$tableName = $inflector->Tableize($className);
		$query = sprintf("SELECT count(*) AS num FROM %s WHERE %s",
			$tableName, $whereClause);

		$rs = DeskRecord::StaticQuery($query, $db);

		if($rs){
			$row = mysql_fetch_assoc($rs);
			$results = intval($row['num']);
		}

		return $results;
	}

	/**
	 * Find a record letting you specify the WHERE clause.  Also joins in another table based on a matching field.
	 * In your where clause, you can refer to the left hand table as "a" and the right as "b".
	 *
	 * @param $className string Name of the class to get
	 * @param $conditions string conditions to search on
	 * @param $joinClass string Name of the class to join in
	 * @param $leftField string Field on the left object to compare with field on the right object
	 * @param $rightField string Field on the right object to compare with field on the left object
	 * @param $db Database resource to use
	 * @param $inflectorClass The name of the InflectorInterface class to use.
	 * @param $lazy boolean Whether or not DeskRecord should use lazy loading, due to the nature
	 * of this method, this defaults to false, loading all data.
	 */
	function FindJoin($className, $conditions, $joinClass, $leftField, $rightField, $db = null, $inflectorClass = "InflectorClass", $lazy = false) {
		$inflector = new $inflectorClass;
		$leftTableName = $inflector->Tableize($className);
		$rightTableName = $inflector->Tableize($joinClass);

		// Need to get a sample right hand class so we can get all of it's attributes
		// Create an instance, just so we can find out if it's a valid attribute
		if(!class_exists($className)){
			require_once($className . ".class.php");
		}

		if(!class_exists($joinClass)){
			require_once($joinClass . ".class.php");
		}

		$testInstance = new $joinClass($db, $inflectorClass);
		$query = "";
		if($lazy){
			$query = sprintf("SELECT a.id,b.id AS join__id FROM %s AS a LEFT JOIN %s AS b ON a.%s = b.%s WHERE %s",
				$leftTableName, $rightTableName, $leftField, $rightField, $conditions);
		} else {
			$templateOne = "b.%s AS join__%s";
			$joinClassAttributes = array();
			$joinClassAttributes[] = sprintf($templateOne, "id", "id");
			foreach($testInstance->getAttributeNames() as $attr){
				$joinClassAttributes[] = sprintf($templateOne, $attr, $attr);
			}
			// Generate a string to use in the select query, it will be table "b"
			$joinClassParts = implode(",", $joinClassAttributes);

			$query = sprintf("SELECT a.*,%s FROM %s AS a LEFT JOIN %s AS b ON a.%s = b.%s WHERE %s",
				$joinClassParts, $leftTableName, $rightTableName, $leftField, $rightField, $conditions);
		}

		// Do the query
		$rs = DeskRecord::StaticQuery($query, $db);

		$records = array();
		if($rs != false){
			while($row = mysql_fetch_assoc($rs)){
				$leftObject = new $className($db, $inflectorClass);
				$rightObject = new $joinClass($db, $inflectorClass);
				foreach(array_keys($row) as $field){
					$matches = array();
					if(preg_match("/^join__(.*)/", $field, $matches)){
						if($matches[1] == "id"){
							$rightObject->setId(intval($matches[1]));
						} else {
							$rightObject->setAttribute($matches[1], DeskRecord::smart_runtime_unescape($row[$field]));
							$rightObject->_attributes[$matches[1]]->setDirty(false);
						}
					} else {
						if($field == "id") {
							$leftObject->setId(intval($row[$field]));
						} else {
							$leftObject->setAttribute($field, DeskRecord::smart_runtime_unescape($row[$field]));
							$leftObject->_attributes[$field]->setDirty(false);
						}
					}
				}
				$varName = strtolower($joinClass);
				$leftObject->$varName = $rightObject;
				$records[] = $leftObject;
			}
		}

		return $records;

	}
}
?>
