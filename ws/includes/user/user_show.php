<?php

namespace ws\includes\pelanggan\pelanggan_show;

use appclas\Security;
use appclas\StringFunction;
use appclas\AppConfig;
use appclas\Carray;

try 
{

    $id = StringFunction::SetString($_POST['id']);

    $strSQL1 = "SELECT * FROM tbl_user WHERE username = :username ";

	// data list
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL1);
	$query->bindValue(":username", $id);
	$query->execute();
	while($row = $query->fetch())
	{
	    $rows_header[] = $row;
	}

	Carray::setObject('DataUser', $rows_header);
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