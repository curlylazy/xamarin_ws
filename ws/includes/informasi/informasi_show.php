<?php

namespace ws\includes\informasi\informasi_show;

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

    $strSQL1 = "SELECT * FROM tbl_informasi WHERE kodeinformasi = :kodeinformasi ";

	// data list
	$rows_header = array();
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL1);
	$query->bindValue(":kodeinformasi", $id);
	$query->execute();
	while($row = $query->fetch())
	{
	    $rows_header[] = $row;
	}

	Carray::setObject('DataInformasi', $rows_header);
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