<?php
class Gedung {
	/*
	* @return string
     */
    public function getAll(){
		$db = new PdoWrapper();

    	$sql = 'select g.*,f.nama as fungsi_nama from gedung g inner join fungsi f on f.idfungsi=g.fungsi where 1';
    	$data = $db->pdoQuery($sql)->results();
    	return $data;
    }

    public function getId($id){
		$db = new PdoWrapper();

    	$sql = "select g.*,f.nama as fungsi_nama from gedung g inner join fungsi f on f.idfungsi=g.fungsi where g.idgedung=$id";
    	$data = $db->pdoQuery($sql)->results();

    	return $data[0];
    }

    /*public function getTest(){
    	$db = new PdoWrapper();
    	$dataArray = array('klien'=>'labtek indie');
		//idtarif?
		$aWhere = array('idgedung'=>2);
		$data = $db->update('gedung', $dataArray, $aWhere)->affectedRows();
    }*/

    public function postEdit($id){
    	$ret = array();
    	//simpan data
    	$db = new PdoWrapper();
    	$dataArray = array('klien'=>$_POST['klien'],'nama'=>$_POST['nama'],'fungsi'=>$_POST['fungsi_val'],'alamat'=>$_POST['alamat'],'luas'=>$_POST['luas'],'daya'=>$_POST['daya'],'baseline'=>$_POST['baseline']);
		//idtarif?
		$aWhere = array('idgedung'=>$id);
		$data = $db->update('gedung', $dataArray, $aWhere)->affectedRows();
		//
		$ret['succesd'] = $data;
    	//simpan gambar
    	$files = $_FILES['logo'];
    	$uploaddir = "../main/img/logo/";
    	$limitedext = array("jpg","jpeg","png","bmp");
		$ext = strrchr($files['name'],'.');
		$ext = strtolower($ext);
		$getExt = explode('.',$ext);
		$getName = explode('.',$files['name']);
		$getName = slugging($getName[0]);
		$file_ext = $getExt[count($getExt)-1];
		$file_name = $getName.".".$file_ext;
		$uploadfile = $uploaddir.$file_name;
		if (!in_array($file_ext,$limitedext)) {
			//gak ada gambar
			$ret['succesg'] = 403;
		} else {
			if (move_uploaded_file($files['tmp_name'], $uploadfile)) {
				// process for thumbnail image
				//WideImage::load($uploadfile)->resize(1000,1000,'inside')->saveToFile($uploaddir."l".$idb."_".$file_name);	//slide
				//WideImage::load($uploadfile)->resize(500,500,'inside')->saveToFile($uploaddir."m".$idb."_".$file_name);	//slide
				//WideImage::load($uploadfile)->resize(80,80,'inside')->saveToFile($uploaddir."s".$idb."_".$file_name);	//slide
				//unlink($uploadfile);
				$imgurl = getBasesite()."main/img/logo/$file_name";
				$ret['url'] = $imgurl;
				//simpan data
    			$dataArray = array('logo'=>$imgurl);
    			//idtarif?
    			$aWhere = array('idgedung'=>$id);
    			$data = $db->update('gedung', $dataArray, $aWhere)->affectedRows();
				//
				$ret['succesg'] = $data;
			} else {
				switch ($files['error']) {
					case 2:
						//"Error: Ukuran gambar melebihi batas<br />Batas ukuran file adalah 1 MB";
						$ret['succesg'] = 401;
						break;
					case 7:
						//"Error: Tidak dapat disimpan dalam server";
						$ret['succesg'] = 402;
						break;
					default:
						//"Error: Unknown error ".$files['error'];
						$ret['succesg'] = 404;
				}
			}
		}

    	return json_encode($ret);
    }

	/*public function getLogIn($code) {
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
	}*/
}
