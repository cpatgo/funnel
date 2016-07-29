<?php
// b64.php

// Functions for base64-encoding which are safe to pass via URL.

function adesk_b64_encode($text) {
    $text = base64_encode($text);
    $text = preg_replace('/\+/', '-', $text);
    $text = preg_replace('/\//', '!', $text);
    $text = preg_replace('/=/',  '_', $text);

    return $text;
}

function adesk_b64_decode($text) {
    $text = preg_replace('/-/', '+', $text);
    $text = preg_replace('/!/', '/', $text);
    $text = preg_replace('/_/', '=', $text);

    return base64_decode($text);
}

?>
