<?php
header("HTTP/1.1 301 Moved Permanently");

header("Location: index.php?action=account_update&".$_SERVER['QUERY_STRING']);
?>