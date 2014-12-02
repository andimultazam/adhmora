<?php
require_once 'restler.php';
require_once 'class/class.pdowrapper.php';
require_once 'functions.php';

use Luracast\Restler\Restler;

//
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, PATCH, DELETE');
header('Access-Control-Max-Age: 1000');
if(array_key_exists('HTTP_ACCESS_CONTROL_REQUEST_HEADERS', $_SERVER)) {
    header('Access-Control-Allow-Headers: '
           . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
} else {
    header('Access-Control-Allow-Headers: *');
}
//

$r = new Restler();
$r->setSupportedFormats('JsonFormat', 'UploadFormat');
$r->addAPIClass('Gedung');
$r->addAPIClass('Fungsi');
$r->handle();

//echo "200/OK";
