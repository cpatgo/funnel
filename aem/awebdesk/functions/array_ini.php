<?php

function adesk_array_ini($str, $parse_sections = false) {
    $lines = explode("\n", $str);
    $sect  = "";
    $ary   = array();

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line == "")
            continue;

        if (substr($line, 0, 1) == ';')
            continue;

        if (substr($line, 0, 1) == '[' && $parse_sections) {
            $sect = "";
            for ($i = 1; $i < strlen($line) - 1; $i++) {
                if (substr($line, $i, 1) == ']')
                    break;
                $sect .= substr($line, $i, 1);
            }
            continue;
        }

        if (preg_match('/(\S+)\s*=\s*(.*)/', $line, $mat)) {
            $mat[1] = str_replace('"', '', $mat[1]);
            $mat[2] = str_replace('"', '', $mat[2]);
            if ($sect != "") {
                $ary[$sect][$mat[1]] = $mat[2];
            } else {
                $ary[$mat[1]] = $mat[2];
            }
        }
    }

    return $ary;
}

function adesk_array_ini_file($file, $parse_sections = false) {
    if (!file_exists($file))
        return array();

    return adesk_array_ini(file_get_contents($file), $parse_sections);
}

?>
