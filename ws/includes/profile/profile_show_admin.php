<?php

namespace ws\includes\profile\profile_show_admin;

use appclas\Security;

try 
{

	$postdata = $_POST['postdata'];
	$rowsjson = json_decode($postdata, true);
    $rowhd = $rowsjson['DataHeader'][0];

    $kodeadmin = \appclas\StringFunction::SetString($rowhd['kodeadmin']);

    $sql = "SELECT * FROM tbl_admin
			WHERE kodeadmin = :kodeadmin
		";

	$query = \appclas\AppConfig::$DATABASE->pdo->prepare($sql);
	$query->bindValue(":kodeadmin", $kodeadmin);
	$query->execute();
	$rows = $query->fetch();

	if(empty($rows))
    {
        \appclas\Carray::setSuccess(false, "Pesan Kesalahan :: data tidak ditemukan");
        $dtjson = \appclas\Carray::getObject();
	    echo $dtjson;
	    return;
    }

    $password_dec = Security::decrypt($rows['password']);

    $rows['password_dec'] = $password_dec;
    
	\appclas\Carray::setObject('DataAdmin', $rows);
	\appclas\Carray::setSuccess(true);
	$dtjson = \appclas\Carray::getObject();

	echo $dtjson;

} catch (Exception $e) {

    \appclas\Carray::setSuccess(false, "Pesan Kesalahan :: ". $e->getMessage());
	$dtjson = \appclas\Carray::getObject();
	echo $dtjson;
}

// print json_encode($row);


?>