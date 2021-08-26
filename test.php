<?php
require_once('callCurl.php');

$codes_array = [404, 301, 200, 403, 408];

foreach ($codes_array as $code) {
    echo json_encode(callCurl("https://httpstat.us/$code")) . '<br>';
}