<?php

namespace ws\includes\profile\profile_ae_admin;

use appclas\Security;
use appclas\StringFunction;
use appclas\AppConfig;
use appclas\Carray;
use appclas\CSql;
use appclas\Cupload;

try 
{

	$table = "tbl_admin";
	$ketreturn = "";
	$kodereturn = "";

	$postdata = $_POST['postdata'];
	$rowsjson = json_decode($postdata, true);
    $rowhd = $rowsjson['DataHeader'][0];

    AppConfig::$DATABASE->action(function($database) {

		global $table, $rowhd, $kodereturn;

		$kodeadmin = StringFunction::SetString($rowhd['kodeadmin']);
		$useradmin = StringFunction::SetString($rowhd['useradmin']);
		$password = StringFunction::SetString($rowhd['password']);
		$namaadmin = StringFunction::SetString($rowhd['namaadmin']);

	    $database->update($table, [
	        "useradmin" => $useradmin,
			"password" => $password,
			"namaadmin" => $namaadmin,
			"dateupdadmin" => StringFunction::SetString(date("Y-m-d H:i:s")),
	    ],[
	        "kodeadmin[=]" => $kodeadmin
	    ]);

		$kodereturn = $kodeadmin;

		$error = $database->error();
	    if(!empty($error[2]))
	    {
	    	AppConfig::$SUBMIT_SUCCSESS = false;
	        Carray::setSuccess(false, "Pesan Kesalahan [".AppConfig::$APP_MODE."] :: ". $error[2]);
			$dtjson = Carray::getObject();
			echo $dtjson;
	        return false;
	    }

	    Carray::setSuccess(true, "Berhasil update profile.");
		Carray::setObject('kodereturn', $kodereturn);
		$dtjson = Carray::getObject();
		echo $dtjson;

	});
	
} catch (Exception $e) {

    Carray::setSuccess(false, "Pesan Kesalahan :: ". $e->getMessage());
	$dtjson = Carray::getObject();
	echo $dtjson;
}

?>