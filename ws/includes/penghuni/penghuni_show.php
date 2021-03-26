<?php

namespace ws\includes\pelanggan\pelanggan_show;

use appclas\Security;
use appclas\StringFunction;
use appclas\AppConfig;
use appclas\Carray;

try 
{

	$postdata = $_POST['postdata'];
	$rowsjson = json_decode($postdata, true);
    $rowhd = $rowsjson['DataHeader'][0];

    $id = StringFunction::SetString($rowhd['id']);

    $strSQL1 = "SELECT * FROM tbl_penghuni WHERE kodepenghuni = :kodepenghuni ";

	// data list
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL1);
	$query->bindValue(":kodepenghuni", $id);
	$query->execute();
	$row = $query->fetch();
	
	$rows_header = array();

	$array_temp = array(
		"kodepenghuni" => $row['kodepenghuni'],
		"username" => $row['username'],
		"namapenghuni" => $row['namapenghuni'],
		"password" => Security::decrypt($row['password']),
		"notelepon" => $row['notelepon'],
		"alamat" => $row['alamat'],
		"email" => $row['email'],
		"pekerjaan" => $row['pekerjaan'],
		"statuspenghuni" => $row['statuspenghuni'],
		"gambarpenghuni" => $row['gambarpenghuni'],
	);

    array_push($rows_header, $array_temp);

	Carray::setObject('DataPenghuni', $rows_header);
	Carray::setSuccess(true);
	$dtjson = Carray::getObject();

	echo $dtjson;

} catch (Exception $e) {

    Carray::setSuccess(false, "Pesan Kesalahan :: ". $e->getMessage());
	$dtjson = Carray::getObject();
	echo $dtjson;
}



// print json_encode($row);


?>