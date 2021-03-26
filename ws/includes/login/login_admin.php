<?php

namespace ws\includes\login\login_admin;

use appclas\Security;

try 
{

	$postdata = $_POST['postdata'];
	$rowsjson = json_decode($postdata, true);
    $rowhd = $rowsjson['DataHeader'][0];

    $useradmin = \appclas\StringFunction::SetString($rowhd['useradmin']);
    $password = \appclas\StringFunction::SetString($rowhd['password']);

    $sql = "SELECT * FROM tbl_admin
			WHERE useradmin = :useradmin
		";

	$query = \appclas\AppConfig::$DATABASE->pdo->prepare($sql);
	$query->bindValue(":useradmin", $useradmin);
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
	$DataToken->token = Security::encrypt($useradmin);

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