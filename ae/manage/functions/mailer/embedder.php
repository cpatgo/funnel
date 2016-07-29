<?php

require_once(SWIFT_ABS_PATH . '/Swift/Plugin/FileEmbedder.php');

// Extend the file embedder class
class SendingEngineEmbedder extends Swift_Plugin_FileEmbedder {

	var $exceptions = array();
	var $go = true;

	/**
	 * Temporary storage so we can restore changes we make.
	 * @var array
	 */
	var $store;



	function SendingEngineEmbedder($extensions = array(), $tags = array()) {
		$this->go = true; // it should be on by default to run
		// set default extensions for tags
		// default swift uses only IMG tags, so table tags can be added here
		foreach ( $tags as $tag => $attrib ) {
			$this->setTagDefinition($tag, $attrib, $extensions);
		}
	}


	/**
	 * Adds an exception
	 * @return true if added, false if overwritten previous
	 */
	function addException($url) {
		$r = isset($this->exceptions[$url]);
		$this->exceptions[$url] = $url;
		return $r;
	}


	/**
	 * Removes an exception
	 * @return true if removed, false if not found
	 */
	function delException($url) {
		$r = isset($this->exceptions[$url]);
		if ( $r ) unset($this->exceptions[$url]);
		return $r;
	}

	function beforeSendPerformed(&$e) {
		// run image embedder only if go switch is on (all checks made)
		if ( $this->go ) {
			campaign_sender_log("Embedding Images...");
			parent::beforeSendPerformed($e);
		}
	}

}


?>