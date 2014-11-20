<?php
class User {
	/*
	* @return string
     */
	public function getLogIn($code) {
		//init
		//var_dump($_POST);
		$db = new db();
		$resp = 0;
		$id = 0;
		$token = "";
		$nama = "";
		$noktp = "";
		$email = "";
		$notelp = "";
		//cek login
		//$query = "SELECT * FROM Tiket WHERE codex = '$code'";
		$query = "SELECT * FROM Tiket WHERE codex = '$code' AND token = ''";
		//echo $query;
		$res = $db->query($query);
		$resp = (count($res)>=1) ? 1 : 0;
		if ($resp) {
			//store data
			$id = $res[0]['idTiket'];
			$nama = $res[0]['nama'];
			$noktp = $res[0]['noktp'];
			$email = $res[0]['email'];
			$notelp = $res[0]['notelp'];
			$token = uniqid($code, true);
			$token = str_replace(".","",$token);
			//change token
			$query = "UPDATE Tiket SET token = '$token' WHERE idTiket = $id";
			//echo "<br />".$query;
			$res = $db->exec($query);
		}
		
		$ret = array(
			"response" => $resp,
			"id" => $id,
			"token" => $token,
			"nama" => $nama,
			"noktp" => $noktp,
			"email" => $email,
			"notelp" => $notelp,
		);
		
		return json_encode($ret);
	}
	
	public function getLogOut($idtiket,$token) {
		//init
		//var_dump($_POST);
		$db = new db();
		$resp = 0;
		$id = 0;
		//cek login
		$query = "UPDATE Tiket SET token = '' WHERE idTiket = $idtiket AND token = '$token'";
		//echo $query;
		$res = $db->exec($query);
		$resp = $db->rowCount();
		//echo $query;		
		//$resp = (count($res)>=1) ? 1 : 0;
		
		$ret = array(
			"response" => $resp,
			"id" => $idtiket,
		);
		
		return json_encode($ret);
	}
	
	public function getSave($idtiket,$field,$val) {
		//init
		//var_dump($_POST);
		$db = new db();
		$resp = 0;
		$id = 0;
		//cek login
		$query = "UPDATE Tiket SET $field = '$val' WHERE idTiket = $idtiket";
		//echo $query;
		$res = $db->exec($query);
		$resp = $db->rowCount();
		
		$ret = array(
			"response" => $resp,
			"id" => $idtiket,
			"field" => $field,
			"val" => $val,
		);
		
		return json_encode($ret);
	}
	/*
	* @return string
     */
	public function getChecklogin($idtiket,$token) {
		$db = new db();
		$resp = 0;
		//cek login
		$query = "SELECT * FROM Tiket WHERE idTiket = '$idtiket' AND token = '$token'";
		//echo $query;
		$res = $db->query($query);
		$resp = (count($res)>=1) ? 1 : 0;
		
		$ret = array(
			"response" => $resp
		);
		
		return json_encode($ret);
	}
}
