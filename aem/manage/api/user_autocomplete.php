<?php


function user_autocomplete($fieldName) {
	if ( !isset($_POST[$fieldName]) ) die('<ul></ul>');
	$entered = $_POST[$fieldName];
	$results = user_search($entered, '%s%%');
	echo '<ul>';
	if ( count($results) > 0 ) {
		foreach ( $results as $row ) {
			$username = str_replace($entered, '<strong class="highlight">' . $entered . '</strong>', $row['username']);
			$name =
				str_replace($entered, '<strong class="highlight">' . $entered . '</strong>', $row['first_name']) .
				' ' .
				str_replace($entered, '<strong class="highlight">' . $entered . '</strong>', $row['last_name'])
			;
			$email = str_replace($entered, '<strong class="highlight">' . $entered . '</strong>', $row['email']);
			echo "<li>";
				echo "<span class=\"informal\">[</span>";
					echo "<u>$username</u>";
				echo "<span class=\"informal\">] ";
					echo "$name<br />";
					echo "<em>&lt;$email&gt;</em>";
				echo "</span>";
			echo "</li>";
		}
	}
	echo '</ul>';
	exit;
}


?>