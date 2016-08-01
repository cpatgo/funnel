<?php

header("Content-Type: text/javascript");

$path = dirname(__FILE__);

readfile($path . "/prototype.js");
readfile($path . "/builder.js");
readfile($path . "/effects.js");
readfile($path . "/dragdrop.js");
readfile($path . "/controls.js");
readfile($path . "/slider.js");
readfile($path . "/sound.js");

?>
