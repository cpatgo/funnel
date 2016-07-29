<?php
/**
 * This class represents a single attribute of an DeskRecord.  It tracks
 * it's own status (dirty, clean, loaded, not loaded) and some additional
 * information.
 */
class DeskAttribute {
	/** Name */
	var $_name;
	/** Type */
	var $_type;
	/** Value */
	var $_value;
	/** Loaded status */
	var $_loaded;
	/** Dirty status */
	var $_dirty;
	
	/**
	 * Builds an attribute with some default values.
	 *
	 * @param $name This attribute's name
	 * @param $type This attribute's type (String)
	 * @param $value This attribute's value
	 * @param $loaded Boolean indicating whether or not this value has been
	 * loaded from the database
	 */
	function DeskAttribute($name, $type, $value = null, $loaded = false) {
		$this->_name = $name;
		$this->_type = $type;
		$this->_value = $value;
		$this->_loaded = $loaded;
		$this->_dirty = false;
	}
	
	/**
	 * Gets this attribute's current value.
	 * @return The value of this attribute.
	 */
	function getValue() {
		return $this->_value;
	}
	
	/**
	 * Sets this attribute's value, which results in it being marked as both
	 * loaded and dirty.
	 *
	 * @param $newValue the new value to set
	 */
	function setValue($newValue) {
		if($this->_value !== $newValue){
			$this->_value = $newValue;
			$this->_dirty = true;
			$this->_loaded = true;
		}
	}
	
	/**
	 * Gets this attribute's type
	 * @return This attribute's type (String)
	 */
	function getType() {
		return $this->_type;
	}
	
	/**
	 * Gets this attribute's name.
	 * @return This attribute's name
	 */
	function getName() {
		return $this->_name;
	}
	
	/**
	 * ToString method, returns the string value of this attribute
	 * @return String value of this attribute.
	 */
	function __toString() {
		return "" . $this->_value;
	}
	
	/**
	 * Returns whether or not this attribute has been loadeed.
	 * @return boolean
	 */
	function isLoaded() {
		return $this->_loaded;
	}
	
	/**
	 * Sets whether or not this attribute has been loadeed.
	 */
	function setLoaded($newValue){
		$this->_loaded = ($newValue == true);
	}
	
	/**
	 * Returns whether or not this attribute is dirty.
	 * @return boolean
	 */
	function isDirty() {
		return $this->_dirty;
	}
	
	/**
	 * Sets whether or not this attribute is dirty.
	 */
	function setDirty($newValue){
		$this->_dirty = ($newValue == true);
	}
}
?>
