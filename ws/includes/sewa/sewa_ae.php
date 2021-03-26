<?php

namespace ws\includes\sewa\sewa_ae;

use appclas\Security;
use appclas\StringFunction;
use appclas\AppConfig;
use appclas\Carray;
use appclas\CSql;
use appclas\Cupload;

try 
{

	$table = "tbl_sewa";
	$ketreturn = "";
	$kodereturn = "";

	$postdata = $_POST['postdata'];
	$rowsjson = json_decode($postdata, true);
    $rowhd = $rowsjson['DataHeader'][0];
    $rowph = $rowsjson['DataPenghuni'][0];
    $rowsave = $rowsjson['DataSave'][0];

    // cek mode
    AppConfig::$APP_MODE = StringFunction::SetString($rowsave['save_mode']);

    if(AppConfig::$APP_MODE == AppConfig::$SAVE_MODE_ADD)
    {
    	$ketreturn = "Tambah Data";

    	AppConfig::$DATABASE->action(function($database) {

    		global $table, $rowhd, $rowph, $kodereturn;

    		$kodesewa = Csql::GetKode("kodesewa", "SEWA", $table, AppConfig::$DATABASE);
		    $kodekamar = StringFunction::SetString($rowhd['kodekamar']);
		    $statussewa = StringFunction::SetString($rowhd['statussewa']);

		    $cekdata = Csql::CekKamarPenghuni($kodekamar, $kodepenghuni, $database);
		    if($cekdata)
		    {
		    	AppConfig::$SUBMIT_SUCCSESS = false;
		    	Carray::setSuccess(false, "[kodepenghuni / kodekamar] sudah ada dalam sistem.");
				$dtjson = Carray::getObject();
				echo $dtjson;
		        return false;
		    }

			$database->insert($table, [
				"kodesewa" => $kodesewa,
				"kodekamar" => $kodekamar,
				"statussewa" => 1,
				"dateaddsewa" => StringFunction::SetString(date("Y-m-d H:i:s")),
				"dateupdsewa" => StringFunction::SetString(date("Y-m-d H:i:s")),
			]);

			$kodereturn = $kodesewa;

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

    		$kodesewa = StringFunction::SetString($rowhd['kodesewa']);
    		$kodepenghuni = StringFunction::SetString($rowhd['kodepenghuni']);
		    $kodekamar = StringFunction::SetString($rowhd['kodekamar']);
		    $statussewa = StringFunction::SetString($rowhd['statussewa']);

		    // cek kamar
		    if(($kodepenghuni != $kodepenghuni_old) OR ($kodekamar != $kodekamar_old))
		    {
		    	$cekdata = Csql::CekKamarPenghuni($kodekamar, $kodepenghuni, $database);
			    if($cekdata)
			    {
			    	AppConfig::$SUBMIT_SUCCSESS = false;
			    	Carray::setSuccess(false, "[kodepenghuni / kodekamar] sudah ada dalam sistem.");
					$dtjson = Carray::getObject();
					echo $dtjson;
			        return false;
			    }
		    }

		    $database->update($table, [
				"kodekamar" => $kodekamar,
				"statussewa" => $statussewa,
				"dateupdsewa" => StringFunction::SetString(date("Y-m-d H:i:s")),
		    ],[
		        "kodesewa[=]" => $kodesewa
		    ]);

			$kodereturn = $kodesewa;

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