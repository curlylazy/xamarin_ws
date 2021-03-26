<?php

namespace ws\includes\pelanggan\pelanggan_delete;

use appclas\Security;
use appclas\StringFunction;
use appclas\AppConfig;
use appclas\Carray;
use appclas\CSql;
use appclas\Cupload;

try 
{

	$table = "tbl_kamar";
	$ketreturn = "";
	$kodereturn = "";

	$postdata = $_POST['postdata'];
	$rowsjson = json_decode($postdata, true);
    $rowhd = $rowsjson['DataHeader'][0];

    AppConfig::$DATABASE->action(function($database) {

		global $table, $rowhd, $kodereturn;

		$kode = StringFunction::SetString($rowhd['kode']);

		$database->delete($table, [
	        "kodekamar" => $kode
	    ]);

		$error = $database->error();
	    if(!empty($error[2]))
	    {
	    	AppConfig::$SUBMIT_SUCCSESS = false;
	        Carray::setSuccess(false, "Pesan Kesalahan :: ". $error[2]);
			$dtjson = Carray::getObject();
			echo $dtjson;
	        return false;
	    }

	});

    if(AppConfig::$SUBMIT_SUCCSESS == true)
    {
    	Carray::setSuccess(true, "Berhasil hapus data");
		$dtjson = Carray::getObject();
		echo $dtjson;
    }
	

} catch (Exception $e) {

    Carray::setSuccess(false, "Pesan Kesalahan :: ". $e->getMessage());
	$dtjson = Carray::getObject();
	echo $dtjson;
}

?>