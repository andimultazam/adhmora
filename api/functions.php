<?php
function getBasesite(){
	return "http://localhost/adhmora/";
}
function slugging($string) {
	$nstring = preg_replace("/[^a-zA-Z0-9]/","-",strtolower($string));
	return $nstring;
}
