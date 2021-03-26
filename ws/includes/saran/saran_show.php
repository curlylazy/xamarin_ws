<?php

namespace ws\includes\saran\saran_show;

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

    $strSQL1 = "SELECT * FROM tbl_saran 
    			INNER JOIN tbl_sewa ON (tbl_sewa.kodesewa = tbl_saran.kodesewa)
    			INNER JOIN tbl_penghuni ON (tbl_penghuni.kodepenghuni = tbl_sewa.kodepenghuni)
    			INNER JOIN tbl_kamar ON (tbl_kamar.kodekamar = tbl_sewa.kodekamar)
    			WHERE tbl_saran.kodesaran = :kodesaran ";

	// data list
   	$rows_header = array();
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL1);
	$query->bindValue(":kodesaran", $id);
	$query->execute();
	while($row = $query->fetch())
	{
	    $rows_header[] = $row;
	}

	Carray::setObject('DataSaran', $rows_header);
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