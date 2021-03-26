<?php

namespace ws\includes\extra\extra_penghuni_sewa_list;

use appclas\Security;
use appclas\StringFunction;
use appclas\AppConfig;
use appclas\Carray;
use appclas\CSql;

try 
{
    $strSQL1 = "SELECT * FROM tbl_penghuni WHERE statuspenghuni = 1 ";

	// data list
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL1);
	$query->execute();
	while($row = $query->fetch())
	{
		// cek apakah sudah ada dalam tbl_sewa_penghuni
		$cek = CSql::CekPenghuniSewa($row['kodepenghuni'], AppConfig::$DATABASE);
		if(!$cek)
		{
			$rows_header[] = $row;
		}
	}

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