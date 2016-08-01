<?php
# gantt.php

class adesk_Gantt {
    var $rows;
    var $rows_text;
    var $width;         # The width (in units) of this gantt chart; completely arbitrary
    var $defbg;
    var $width_visible; # The width (in PIXELS, not units) that is visible

    function adesk_Gantt($width, $vis) {
        $this->rows     = array();
        $this->rows_text = array();
        $this->width    = $width;
        $this->defbg    = '#ffffff';
        $this->width_visible = $vis;
    }

    function add_row($ary, $text = '') {
        $this->rows[] = $ary;
        $this->rows_text[] = $text;
    }

    function create_row() {
        return array_fill(0, $this->width, $this->defbg);
    }

    function render_row(&$row, $text) {
        $html = "<tr>";
        $first = true;

        foreach ($row as $cell) {
            if ($first) {
                $html .= "<td bgcolor='$cell'><span>$text</span></td>";
                $first = false;
            } else {
                $html .= "<td bgcolor='$cell'>&nbsp;</td>";
            }
        }

        return $html . "</tr>";
    }

    function render() {
        $html = "<div style='width:{$this->width_visible}px; overflow-x: scroll'><table border='0' cellspacing='0' cellpadding='0' width='100%'>";

        for ($i = 0; $i < count($this->rows); $i++)
            $html .= $this->render_row($this->rows[$i], $this->rows_text[$i]);

        return $html . "</table></div>";
    }
}

?>
