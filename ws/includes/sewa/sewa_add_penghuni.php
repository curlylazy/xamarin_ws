<?php

namespace ws\includes\sewa\sewa_add_penghuni;

use appclas\Security;
use appclas\StringFunction;
use appclas\AppConfig;
use appclas\Carray;
use appclas\CSql;
use appclas\Cupload;

try 
{

	$table = "tbl_sewa_penghuni";
	$ketreturn = "";
	$kodereturn = "";

	$postdata = $_POST['postdata'];
	$rowsjson = json_decode($postdata, true);
    $rowhd = $rowsjson['DataHeader'][0];

    AppConfig::$DATABASE->action(function($database) {

		global $table, $rowhd, $rowph, $kodereturn;

		$kodesewapenghuni = Csql::GetKode("kodesewapenghuni", "SEWAPNG", $table, AppConfig::$DATABASE);
	    $kodesewa = StringFunction::SetString($rowhd['kodesewa']);
	    $kodepenghuni = StringFunction::SetString($rowhd['kodepenghuni']);

	    $cek = CSql::CekPenghuniSewa($kodepenghuni, AppConfig::$DATABASE);
	    if($cek)
	    {
	    	AppConfig::$SUBMIT_SUCCSESS = false;
	    	Carray::setSuccess(false, "[kodepenghuni] sudah ada dalam sewa.");
			$dtjson = Carray::getObject();
			echo $dtjson;
	        return false;
	    }

		$database->insert($table, [
			"kodesewapenghuni" => $kodesewapenghuni,
			"kodesewa" => $kodesewa,
			"kodepenghuni" => $kodepenghuni,
			"dateaddsewapenghuni" => StringFunction::SetString(date("Y-m-d H:i:s")),
		]);

		$error = $database->error();
	    if(!empty($error[2]))
	    {
	    	AppConfig::$SUBMIT_SUCCSESS = false;
	        Carray::setSuccess(false, "Pesan Kesalahan [".AppConfig::$APP_MODE."] :: ". $error[2]);
			$dtjson = Carray::getObject();
			echo $dtjson;
	        return false;
	    }
	    else
	    {
	    	Carray::setSuccess(true, "Berhasil tambah penghuni ke dalam sewa.");
			Carray::setObject('kodereturn', "");
			$dtjson = Carray::getObject();
			echo $dtjson;
	    }

	});
	

} catch (Exception $e) {

    Carray::setSuccess(false, "Pesan Kesalahan :: ". $e->getMessage());
	$dtjson = Carray::getObject();
	echo $dtjson;
}

?>