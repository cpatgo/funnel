<?php

/* CONFIG */

$pathToAssets = array("elements/css", "elements/fonts", "elements/images", "elements/js", "elements/scripts");

$filename = "tmp/wow-builder.zip"; //use the /tmp folder to circumvent any permission issues on the root folder

/* END CONFIG */


$zip = new ZipArchive();

$zip->open($filename, ZipArchive::CREATE);


//add folder structure

foreach ($pathToAssets as $thePath) {

    // Create recursive directory iterator
    $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($thePath), RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {

        if ($file->getFilename() != '.' && $file->getFilename() != '..') {

            // Get real path for current file
            $filePath = $file->getRealPath();

            $temp = explode("/", $name);

            array_shift($temp);

            $newName = implode("/", $temp);

            // Add current file to archive
            $zip->addFile($filePath, $newName);
        }
    }
}



foreach ($_POST['pages'] as $page => $content) {
    $content = preg_replace("/Landing_Builder/", $_POST['title'], $content);
    $content = preg_replace("/xsDesc/", $_POST['desc'], $content);
    $content = preg_replace("/xsKeyw/", $_POST['keyw'], $content);

    $zip->addFromString($page . ".html", $_POST['doctype'] . "\n" . stripslashes($content));
}



$zip->close();


$yourfile = $filename;

$file_name = basename($yourfile);

header("Content-Type: application/zip");
header("Content-Transfer-Encoding: Binary");
header("Content-Disposition: attachment; filename=$file_name");
header("Content-Length: " . filesize($yourfile));

readfile($yourfile);

unlink('wow-builder.zip');

exit;
?>