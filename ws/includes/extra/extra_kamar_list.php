<?php

namespace ws\includes\extra\extra_kamar_list;

use appclas\Security;
use appclas\StringFunction;
use appclas\AppConfig;
use appclas\Carray;
use appclas\CSql;

try 
{
    $strSQL1 = "SELECT * FROM tbl_kamar ";

	// data list
	$rows_header = array();
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL1);
	$query->execute();
	while($row = $query->fetch())
	{
	    $rows_header[] = $row;
	}

	Carray::setObject('DataKamar', $rows_header);
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