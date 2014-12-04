<?php
class Fungsi {
	/*
	* @return string
     */
    public function getGedung(){
		$db = new PdoWrapper();

    	$sql = 'select * from fungsi where tipe=1';
    	$data = $db->pdoQuery($sql)->results();
    	return $data;
    }

    public function getLantai(){
		$db = new PdoWrapper();

    	$sql = 'select * from fungsi where tipe=2';
    	$data = $db->pdoQuery($sql)->results();
    	return $data;
    }
}