<?php

namespace ws\includes\transaksi\transaksi_sewapenghuni_list;

use appclas\Security;
use appclas\StringFunction;
use appclas\AppConfig;
use appclas\Carray;
use appclas\CSql;

try
{
	$postdata = $_POST['postdata'];
	$rowsjson = json_decode($postdata, true);
    $rowhd = $rowsjson['DataHeader'][0];

    $kodesewa = StringFunction::SetString($rowhd['kodesewa']);

    $strSQL1 = "SELECT * FROM tbl_sewa_penghuni 
    			INNER JOIN tbl_sewa ON (tbl_sewa.kodesewa = tbl_sewa_penghuni.kodesewa)
    			INNER JOIN tbl_penghuni ON (tbl_penghuni.kodepenghuni = tbl_sewa_penghuni.kodepenghuni)
    			INNER JOIN tbl_kamar ON (tbl_kamar.kodekamar = tbl_sewa.kodekamar)
    			WHERE TRUE ";

	$strTemp = " ";
	$strTemp .= " AND (tbl_sewa_penghuni.kodesewa = :kodesewa) ";

	$strSQL1 .= $strTemp;

	$rows_header = array();

	// data list
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL1);
	$query->bindValue(":kodesewa", $kodesewa);
	$query->execute();
	while($row = $query->fetch())
	{
	    $rows_header[] = $row;
	}

	
	Carray::setObject('DataSewaPenghuni', $rows_header);
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