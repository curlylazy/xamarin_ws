<?php

namespace ws\includes\login\login_penghuni;

use appclas\Security;

try 
{

	$postdata = $_POST['postdata'];
	$rowsjson = json_decode($postdata, true);
    $rowhd = $rowsjson['DataHeader'][0];

    $username = \appclas\StringFunction::SetString($rowhd['username']);
    $password = \appclas\StringFunction::SetString($rowhd['password']);

    $sql = "SELECT * FROM tbl_sewa_penghuni
    		INNER JOIN tbl_sewa ON (tbl_sewa.kodesewa = tbl_sewa_penghuni.kodesewa)
    		INNER JOIN tbl_penghuni ON (tbl_penghuni.kodepenghuni = tbl_sewa_penghuni.kodepenghuni)
    		INNER JOIN tbl_kamar ON (tbl_kamar.kodekamar = tbl_sewa.kodekamar)
			WHERE tbl_penghuni.username = :username
		";

	$query = \appclas\AppConfig::$DATABASE->pdo->prepare($sql);
	$query->bindValue(":username", $username);
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
    if($password != $password_dec)
    {
    	\appclas\Carray::setSuccess(false, "Pesan Kesalahan :: password tidak sesuai");
        $dtjson = \appclas\Carray::getObject();
	    echo $dtjson;
	    return;
    }
    
	$DataToken = (object)null;
	$DataToken->token = Security::encrypt($username);

	\appclas\Carray::setObject('DataUser', $rows);
	\appclas\Carray::setObject('DataToken', $DataToken);
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