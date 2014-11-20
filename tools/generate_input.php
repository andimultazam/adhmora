<?php

/**
 * Generate Float Random Number
 *
 * @param float $min Minimal value
 * @param float $max Maximal value
 * @param int $round The optional number of decimal digits to round to. default 0 means not round
 * @return float Random float value
 */
function float_rand($min, $max, $round=0){
    //validate input
    if ($min > $max) { $min=$max; $max=$min; }
        else { $min=$min; $max=$max; }
    $randomfloat = $min + mt_rand() / mt_getrandmax() * ($max - $min);
    if($round > 0)
        $randomfloat = round($randomfloat,$round);
 
    return $randomfloat;
}

function main() {
	header('Content-Type: text/plain; charset=UTF-8');
	
	$db = new PDO('mysql:host=localhost;dbname=adhmora','babeh','aing');
	$query = "insert into pengukuran_alat (timestamp,id_utilitaspasang,A1,V1,PF1,F) values (:tstamp,1,:a1,:v1,:pf1,:f)";
	$stmt = $db->prepare($query);
	for ($m=1;$m<240;$m++){
		$tstamp = date("Y-m-d H:i:s",strtotime("2014-11-20 14:58:16 +$m minutes"));
		$stmt->bindParam(':tstamp', $tstamp);
		$a1 = float_rand(15.001,22.999,3);
		$stmt->bindParam(':a1', $a1);
		$v1 = float_rand(382.001,385.999,3);
		$stmt->bindParam(':v1', $v1);
		$pf1 = float_rand(0.981,0.999,3);
		$stmt->bindParam(':pf1', $pf1);
		$f = float_rand(49.591,50.999,3);
		$stmt->bindParam(':f', $f);
		$stmt->execute();
	}
	
	echo 'return 212';
}

main();
