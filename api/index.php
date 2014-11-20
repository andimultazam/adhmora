<?php
require_once 'restler.php';
require_once 'db.php';
require_once 'functions.php';

use Luracast\Restler\Restler;

$r = new Restler();
//$r->addAPIClass('User');
$r->handle();

//echo "200/OK";
