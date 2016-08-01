<?php


function subscriber_autocomplete($fieldName) {
	if ( !isset($_POST[$fieldName]) ) die('<ul></ul>');
	$entered = $_POST[$fieldName];
	$results = subscriber_search($entered, '%s%%');
	echo '<ul>';
	if ( count($results) > 0 ) {
		foreach ( $results as $row ) {
			$name =
				str_replace($entered, '<strong class="highlight">' . $entered . '</strong>', $row['first_name']) .
				' ' .
				str_replace($entered, '<strong class="highlight">' . $entered . '</strong>', $row['last_name'])
			;
			$email = str_replace($entered, '<strong class="highlight">' . $entered . '</strong>', $row['email']);
			echo '<li>';
			echo '<span class="informal">&quot;' . $name . '&quot; &lt;</span>' . $email . '<span class="informal">&gt;</span>';
			echo '</li>';
		}
	}
	echo '</ul>';
	exit;
}


?>