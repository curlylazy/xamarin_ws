<?php

namespace ws\includes\user\user_ae;

use appclas\Security;
use appclas\StringFunction;
use appclas\AppConfig;
use appclas\Carray;
use appclas\CSql;
use appclas\Cupload;

try 
{

	$table = "tbl_user";
	$ketreturn = "";
	$kodereturn = "";

	$act = $_POST['act'];

	$ketreturn = "Add Data";
	
	AppConfig::$DATABASE->action(function($database) {

		global $table, $rowhd, $kodereturn;

		$username = StringFunction::SetString($_POST['username']);
		$password = StringFunction::SetString($_POST['password']);
		$nama = StringFunction::SetString($_POST['nama']);
		$notelepon = StringFunction::SetString($_POST['notelepon']);

		$cekdata = Csql::CekData($username, "username", $table, $database);
	    if($cekdata)
	    {
	    	AppConfig::$SUBMIT_SUCCSESS = false;
	    	Carray::setSuccess(false, "[username] sudah ada dalam sistem.");
			$dtjson = Carray::getObject();
			echo $dtjson;
	        return false;
	    }

		$database->insert($table, [
			"username" => $username,
			"password" => $password,
			"nama" => $nama,
			"notelepon" => $notelepon,
		]);

		$kodereturn = $username;

		$error = $database->error();
	    if(!empty($error[2]))
	    {
	    	AppConfig::$SUBMIT_SUCCSESS = false;
	        Carray::setSuccess(false, "Pesan Kesalahan [".AppConfig::$APP_MODE."] :: ". $error[2]);
			$dtjson = Carray::getObject();
			echo $dtjson;
	        return false;
	    }

	});

	if(AppConfig::$SUBMIT_SUCCSESS == true)
    {
    	Carray::setSuccess(true, "Berhasil $ketreturn");
		Carray::setObject('kodereturn', $kodereturn);
		$dtjson = Carray::getObject();
		echo $dtjson;
    }



} catch (Exception $e) {

    Carray::setSuccess(false, "Pesan Kesalahan :: ". $e->getMessage());
	$dtjson = Carray::getObject();
	echo $dtjson;
}

?>