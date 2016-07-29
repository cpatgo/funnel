<?php
require ('../config.php');
require ('vendor/autoload.php');

$Checkouts = new Dwolla\Checkouts();

$Checkouts->addToCart("Coffee 1", "Mmm, fresh!", 2.25, 1);

$link = sprintf('%s/glc/login.php', GLC_URL);
$test = $Checkouts->create(
    [ 'destinationId' => DWOLLA_id ],
    [ 'redirect' => $link ]
);
print_r($test);
?>