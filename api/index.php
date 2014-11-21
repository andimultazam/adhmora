<?php
require_once 'restler.php';
require_once 'class/class.pdowrapper.php';
require_once 'functions.php';

use Luracast\Restler\Restler;

$r = new Restler();
$r->addAPIClass('Gedung');
$r->handle();

//echo "200/OK";
