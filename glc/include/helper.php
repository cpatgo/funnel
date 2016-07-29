<?php 

# Helper functions

function dd() {
    array_map(function($x) { 
    	var_dump($x); 
    }, func_get_args());
    
    die;
}

function matchPasswordFields($pass1, $pass2) {
	if ($pass1 === $pass2) {
		return true;
	}
	return false;
}