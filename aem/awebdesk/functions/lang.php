<?php

require_once(dirname(__FILE__) . '/i18n.php');
require_once dirname(__FILE__) . '/str.php';
require_once dirname(__FILE__) . '/file.php';

// define constants here
if ( !defined('adesk_LANG_NEW') ) {
	define('adesk_LANG_NEW', 0);
}

/*
function adesk_lang_string($str) {
    return $GLOBALS['__languageArray'][$str];
}
*/

function adesk_lang_file($lang, $type = 'lang') {
	if ( !adesk_LANG_NEW ) $type = 'lang';
	$GLOBALS['__languageName'] = $lang;
	$GLOBALS['__languageType'] = $type;
	if ( $type == 'lang' ) {
    	return adesk_lang($type . '.' . basename($lang) . '.txt');
	} else {
		// if not lang, then manage/public is requested
   		return adesk_lang(basename($lang) . DIRECTORY_SEPARATOR . $type . '.txt');
	}
}

function adesk_lang_trim($str) {
    $len = strlen($str);

    if (substr($str, 0, 1) == '"')
        return substr($str, 1, $len - 1);
    elseif ($str[$len-1] == '"')
        return substr($str, 0, $len - 1);
    return $str;
}

function adesk_lang_compile_line($line, $globalVar = '__languageArray') {
    $line = trim($line);

    if ($line == '' || substr($line, 0, 1) == '#')
        return;

	$tmp = explode('" = "', $line);
	if (count($tmp) == 2) {
		list($lside, $rside) = $tmp;

		$lside = adesk_lang_trim($lside);
		$rside = adesk_lang_trim($rside);
		$GLOBALS[$globalVar][$lside] = $rside;
	}
}

function adesk_lang_default() {
	$GLOBALS['__languageName'] = 'english';
	$GLOBALS['__languageType'] = 'lang';
	return ( !adesk_LANG_NEW ? adesk_lang('lang.english.txt') : adesk_lang('english/language.txt') );
}

function adesk_lang_compile($langfile = null) {
    if (is_null($langfile))
        $langfile = adesk_lang_default();
    else {
        if (!file_exists($langfile))
            $langfile = adesk_lang_default();
    }

    $file  = (string)@adesk_file_get($langfile);
    if ( adesk_LANG_NEW ) {
    	$filename = basename($langfile);
    	$langpath = dirname($langfile);
    	$langname = basename($langpath);
    	if ( $filename != 'language.txt' ) {
    		// prepend global one
    		$file = (string)@adesk_file_get($langpath . DIRECTORY_SEPARATOR . 'language.txt') . $file;
    	}
		// append help and other
		$file .= (string)@adesk_file_get($langpath . DIRECTORY_SEPARATOR . 'other.txt');
		$file .= (string)@adesk_file_get($langpath . DIRECTORY_SEPARATOR . 'help.txt');
		// get application path
		$apppath = dirname(dirname($langpath));
		// check if application supports widgets
		if ( is_dir($apppath . DIRECTORY_SEPARATOR . 'widgets') ) {
			$widgets = adesk_dir_list($apppath . DIRECTORY_SEPARATOR . 'widgets');
			foreach ( $widgets as $widget ) {
				$widgetlangpath = $widget . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $langname;
				if ( is_dir($widgetlangpath) ) {
					if ( file_exists($widgetlangpath . DIRECTORY_SEPARATOR . $filename) ) {
						// append widget's lang file
						$file .= (string)@adesk_file_get($langpath . DIRECTORY_SEPARATOR . $filename);
					}
					if ( file_exists($widgetlangpath . DIRECTORY_SEPARATOR . 'other.txt') ) {
						// append help
						$file .= (string)@adesk_file_get($langpath . DIRECTORY_SEPARATOR . 'other.txt');
					}
					if ( file_exists($widgetlangpath . DIRECTORY_SEPARATOR . 'help.txt') ) {
						// append other
						$file .= (string)@adesk_file_get($langpath . DIRECTORY_SEPARATOR . 'help.txt');
					}
				}
			}
		}
    }

    $lines = explode("\n", $file);

    $GLOBALS['__languageArray'] = array();

    foreach ($lines as $line)
        adesk_lang_compile_line($line);

	// set locale
	$locale = _i18n('en_US');
	if ( trim($locale) ) {
		@setlocale(LC_COLLATE, _i18n('en_US'));
		@setlocale(LC_CTYPE, _i18n('en_US'));
		@setlocale(LC_TIME, _i18n('en_US'));
	}

    return $GLOBALS['__languageArray'];
}

function adesk_lang_load($langfile, $force = false) {
    if ($force || !isset($GLOBALS['__languageArray']))
        adesk_lang_compile($langfile);
}

function adesk_lang_choices() {
	$languages = array();
	if ( adesk_LANG_NEW ) {
		$langFolders = adesk_dir_list(adesk_lang());
		if (count($langFolders) > 0) {
			foreach( $langFolders as $folder ) {
				if ( file_exists($folder . DIRECTORY_SEPARATOR . 'language.txt') ) {
					$name = basename($folder);
					$languages[$name] = ucwords($name);
				}
			}
		}
	} else {
		// Get the languages by examining all the language files in the templates folder
		$langFiles = adesk_file_find(adesk_lang(), "^lang.\w+.txt$");
		if (count($langFiles) > 0) {
			foreach( $langFiles as $file ) {
				preg_match("/lang\.(.*)\.txt/", $file, $matches);
				if ( isset($matches[1]) ) $languages[$matches[1]] = ucwords($matches[1]);
			}
		}
	}

	// add generic language
	if (count($languages) == 0)
		$languages['english'] = 'English';

	return $languages;
}



function adesk_lang_get($type = 'lang') {
	if ( !adesk_LANG_NEW and $type != 'lang' ) $type = 'lang';
	$site = adesk_site_get();
	$admin = adesk_admin_get();
	$req = adesk_http_param('lang_ch');
	$languages = adesk_lang_choices();
	if ( !isset($languages[$req]) ) $req = false;
	// if he is admin
	if ( isset($admin['lang']) ) {
		// if valid language is manually requested
		if ( $req ) {
			$admin['lang'] = $req;
			if ( $admin['id'] ) {
				$reqEsc = adesk_sql_escape($req);
				$table = ( adesk_site_isknowledgebuilder() || adesk_site_isAEM5() ? '#user' : '#admin' );
				adesk_sql_query("UPDATE $table SET lang = '$reqEsc' WHERE id = '$admin[id]'");
			} else {
				$_COOKIE['adesk_lang'] = $req;
				@setcookie('adesk_lang', $req, time() + 365 * 24 * 60 * 60, '/');
				$admin['lang'] = $req;
			}
		} else {
			// we do this in guest ihook, but doens't hurt to do it here as well
			if ( !isset($admin["id"]) || !$admin['id'] ) {
				$admin['lang'] = ( isset($_COOKIE['adesk_lang']) ? $_COOKIE['adesk_lang'] : $site['lang'] );
			}
		}
		if ( isset($GLOBALS['admin']) ) $GLOBALS['admin']['lang'] = $admin['lang'];
		// load the language strings
		adesk_lang_load(adesk_lang_file($admin['lang'], $type));
	} else {
		if ( $req ) {
			$_COOKIE['adesk_lang'] = $req;
			@setcookie('adesk_lang', $req, time() + 365 * 24 * 60 * 60, '/');
			$lang = $req;
		} else {
			$lang = $site['lang'];
		}
		// load the language strings
		adesk_lang_load(adesk_lang_file($lang, $type));
	}
}

function adesk_lang_compile_init(&$out) {
	if (!isset($out["plang"]))    $out["plang"]    = array();
	if (!isset($out["alang"]))    $out["alang"]    = array();
	if (!isset($out["language"])) $out["language"] = array();
	if (!isset($out["dates"]))    $out["dates"]    = array();
}

function adesk_lang_compile_htm($str, &$out) {
	$len = strlen($str);
	$mat = "";				# What we'll report in our language string.
	$insmarty = false;
	$literal = false;

	adesk_lang_compile_init($out);

	for ($i = 0; $i < $len; $i++) {
		if (substr($str, $i, 9) == "{literal}") {
			$literal = true;
			$i += 9;
			if ($i >= $len)
				break;
		}
		if (substr($str, $i, 10) == "{/literal}") {
			$literal = false;
			$i += 10;
			if ($i >= $len)
				break;
		}

		if (!$literal && substr($str, $i, 1) == "{") {
			$insmarty = true;
		}
		if (!$literal && substr($str, $i, 1) == "}") {
			$insmarty = false;
		}

		if ($insmarty && (substr($str, $i, 1) == '"' || substr($str, $i, 1) == "'") && (substr($str, $i-1, 1) == '{' || substr($str, $i-1, 1) == '=' || substr($str, $i-1, 1) == ':')) {
			$end = substr($str, $i++, 1);

			while ($i < $len && substr($str, $i, 1) != $end) {
				# Loop through the characters until we reach the end, where the end is
				# demarcated by $end.

				# If it's a backslash, we include the character in our output but we don't
				# want to consider the following character as a match for $end.  By appending
				# substr($str, $i, 1) and incrementing $i, we guarantee that the following character
				# will be appended without considering the loop condition.
				if (substr($str, $i, 1) == '\\')
					$mat .= substr($str, $i++, 1);

				# Sanity check -- we could have hit the limit with the backslash
				if ($i >= $len)
					break;

				$mat .= substr($str, $i++, 1);
			}

			# Advance i one past the quote mark, then check if we hit the end or not.
			if ($i + 1 >= $len)
				break;

			# We're looking to see what kind of modifier this is.  We aren't using an exact
			# match, which would be a bit messier.
			$sub5 = substr($str, $i + 1, 5);

			if ($sub5 == "|plan")		# We assume this is |plang
				$out["plang"][] = $mat;
			elseif ($sub5 == "|alan")	# Same here for |alang
				$out["alang"][] = $mat;
			elseif ($sub5 == "|i18n")
				$out["language"][] = $mat;
			elseif ($sub5 == "|help")
				$out["help"][] = $mat;
			elseif ($sub5 == "|acpd")
				$out["dates"][] = $mat;

			$mat = "";
		}
	}

	return $out;
}

function adesk_lang_compile_php($str, &$out) {
	$len = strlen($str);
	$mat = "";				# What we'll report in our language string.

	adesk_lang_compile_init($out);

	for ($i = 0; $i < $len; $i++) {
		if (substr($str, $i, 1) == '_') {
			$i++;

			$sub2 = substr($str, $i, 2);

			if ($sub2 == "a(") {
				$target = "alang";
				$i += 2;
			} elseif ($sub2 == "p(") {
				$target = "plang";
				$i += 2;
			} elseif ($sub2 == "d(") {
				$target = "dates";
				$i += 2;
			} elseif ($sub2 == "h(") {
				$target = "help";
				$i += 2;
			} elseif (substr($str, $i, 5) == "i18n(") {
				$target = "language";
				$i += 5;
			} else {
				continue;
			}

			if (substr($str, $i, 1) == '"' || substr($str, $i, 1) == "'") {
				$end = substr($str, $i++, 1);

				while ($i < $len && substr($str, $i, 1) != $end) {
					# Loop through the characters until we reach the end, where the end is
					# demarcated by $end.

					# If it's a backslash, we include the character in our output but we don't
					# want to consider the following character as a match for $end.  By appending
					# substr($str, $i, 1) and incrementing $i, we guarantee that the following character
					# will be appended without considering the loop condition.
					if (substr($str, $i, 1) == '\\')
						$mat .= substr($str, $i++, 1);

					# Sanity check -- we could have hit the limit with the backslash
					if ($i >= $len)
						break;

					$mat .= substr($str, $i++, 1);
				}

				# Advance i one past the quote mark, then check if we hit the end or not.
				if (++$i >= $len)
					break;

				$out[$target][] = $mat;
				$mat = "";
			}
		}
	}

	return $out;
}

/*
	DEPRECATED?
*/


function loadSmartyLangFile($langfile = null) {
    if (is_null($langfile)) $langfile = adesk_lang('lang.english.txt');
    if (!file_exists($langfile)) $langfile = adesk_lang('lang.english.txt');
    $file = @file_get_contents($langfile);
    $lines = preg_split('/\r?\n/', $file);
    $langarray = array();
    foreach($lines as $line){
        if($line != ""){
            if(substr($line, 0, 1) != "#"){
				preg_match('/^"(.*)" = "(.*)"\s*$/', $line, $splitLine);
                if (!isset($splitLine[1])) {
                    die("Error with language file on line: <b>".$line."</b><br />Each phrase must be in this format and all on one line: \"The phrase\" = \"Your translation\"<br /><br />");
                }
                $key = $splitLine[1];
                if ( !isset($splitLine[2])) $splitLine[2] = $splitLine[1];
                $value = $splitLine[2];
                // Get rid of leading and trailing "'s
//                $key = preg_replace('/^"/', '', $key);
//                $key = preg_replace('/"$/', '', $key);
//                $value = preg_replace('/^"/', '', $value);
//                $value = preg_replace('/"$/', '', $value);
                $langarray[$key] = $value;
            }
        }
    }
    return $langarray;
}

// Convenience method for setting the languagearray
function setLanguageArray($langArray) {
    $GLOBALS['__languageArray'] = $langArray;
}

?>
