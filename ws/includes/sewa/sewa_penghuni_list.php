<?php

namespace ws\includes\sewa\sewa_penghuni_list;

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

    $katakunci = StringFunction::SetString($rowhd['katakunci']);
    $kodesewa = StringFunction::SetString($rowhd['kodesewa']);

    $strSQL1 = "SELECT * FROM tbl_sewa_penghuni 
    			INNER JOIN tbl_penghuni ON (tbl_penghuni.kodepenghuni = tbl_sewa_penghuni.kodepenghuni) 
    			WHERE TRUE ";

	$strTemp = " ";
	$strTemp .= " AND (tbl_penghuni.namapenghuni LIKE :katakunci) ";
	$strTemp .= " AND (tbl_sewa_penghuni.kodesewa = :kodesewa) ";

	$rows_header = array();

	// data list
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL1);
	$query->bindValue(":katakunci", "%".$katakunci."%");
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