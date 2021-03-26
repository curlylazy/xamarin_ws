<?php

namespace ws\includes\informasi\informasi_ae;

use appclas\Security;
use appclas\StringFunction;
use appclas\AppConfig;
use appclas\Carray;
use appclas\CSql;
use appclas\Cupload;

try 
{

	$table = "tbl_informasi";
	$ketreturn = "";
	$kodereturn = "";

	$postdata = $_POST['postdata'];
	$rowsjson = json_decode($postdata, true);
    $rowhd = $rowsjson['DataHeader'][0];
    $rowsave = $rowsjson['DataSave'][0];

    // cek mode
    AppConfig::$APP_MODE = StringFunction::SetString($rowsave['save_mode']);

    if(AppConfig::$APP_MODE == AppConfig::$SAVE_MODE_ADD)
    {
    	$ketreturn = "Tambah Data";

    	AppConfig::$DATABASE->action(function($database) {

    		global $table, $rowhd, $kodereturn;

    		$kodeinformasi = Csql::GetKode("kodeinformasi", "INF", $table, AppConfig::$DATABASE);
    		$judulinformasi = StringFunction::SetString($rowhd['judulinformasi']);
    		$isiinformasi = $rowhd['isiinformasi'];

			$database->insert($table, [
				"kodeinformasi" => $kodeinformasi,
				"judulinformasi" => $judulinformasi,
				"isiinformasi" => $isiinformasi,
				"dateaddinformasi" => StringFunction::SetString(date("Y-m-d H:i:s")),
				"dateupdinformasi" => StringFunction::SetString(date("Y-m-d H:i:s")),
			]);

			$kodereturn = $kodepenghuni;

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

    }

    elseif (AppConfig::$APP_MODE == AppConfig::$SAVE_MODE_EDIT) 
    {
    	$ketreturn = "Update Data";

    	AppConfig::$DATABASE->action(function($database) {

    		global $table, $rowhd, $kodereturn;

    		$kodeinformasi = StringFunction::SetString($rowhd['kodeinformasi']);
    		$judulinformasi = StringFunction::SetString($rowhd['judulinformasi']);
    		$isiinformasi = $rowhd['isiinformasi'];

		    $database->update($table, [
		        "judulinformasi" => $judulinformasi,
				"isiinformasi" => $isiinformasi,
				"dateupdinformasi" => StringFunction::SetString(date("Y-m-d H:i:s")),
		    ],[
		        "kodeinformasi[=]" => $kodeinformasi
		    ]);

			$kodereturn = $kodeinformasi;

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
    }

    else
    {
    	Carray::setSuccess(false, "Pesan Kesalahan [route tidak cocok] :: ". $error[2]);
		$dtjson = Carray::getObject();
		echo $dtjson;
        return;
    }

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