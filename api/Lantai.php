<?php
class Lantai {
	/*
	* @return string
     */
    public function postSimpan($id){
		$db = new PdoWrapper();
        $ret = array();

        return json_encode($ret);
    }

    public function getAll($id){
        $db = new PdoWrapper();

        $sql = "select * from lantai where idgedung=$id";
        $datalantai = $db->pdoQuery($sql)->results();
        $gedung = array();
        //iterasi lantai
        foreach ($datalantai as $keylantai => $valuelantai) {
            $lantai = array();
            $sql = "select * from ruang where idlantai=".$valuelantai['idlantai'];
            $dataruang = $db->pdoQuery($sql)->results();
            $lantai = $datalantai[$keylantai];
            $lantai['ruang'] = array();
            foreach ($dataruang as $keyruang => $valueruang) {
                $ruang = array();
                $sql = "select * from utilitas_pasang where idruang=".$valueruang['idruang'];
                $datautilitas = $db->pdoQuery($sql)->results();
                $ruang = $dataruang[$keyruang];
                $ruang['sensor'] = array();
                $ruang['utilitas'] = array();
                //foreach utilitas
                foreach ($datautilitas as $keyutilitas => $valueutilitas) {
                    if ($valueutilitas['util_tipe']==1)
                        array_push($ruang['sensor'],$valueutilitas);    
                    else
                        array_push($ruang['utilitas'],$valueutilitas);    
                }
                //
                array_push($lantai['ruang'],$ruang);
            }
            array_push($gedung,$lantai);
        }
        //var_dump($gedung);
        return $gedung;
    }
}