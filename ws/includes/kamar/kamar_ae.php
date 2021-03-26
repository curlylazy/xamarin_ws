<?php

namespace ws\includes\kamar\kamar_ae;

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
    $rowsave = $rowsjson['DataSave'][0];

    // cek mode
    AppConfig::$APP_MODE = StringFunction::SetString($rowsave['save_mode']);

    if(AppConfig::$APP_MODE == AppConfig::$SAVE_MODE_ADD)
    {
    	$ketreturn = "Tambah Data";

    	AppConfig::$DATABASE->action(function($database) {

    		global $table, $rowhd, $kodereturn;

    		$kodekamar = Csql::GetKode("kodekamar", "KMR", $table, AppConfig::$DATABASE);
    		$nokamar = StringFunction::SetInt($rowhd['nokamar']);
    		$ukurankamar = StringFunction::SetString($rowhd['ukurankamar']);
    		$hargasewa = StringFunction::SetInt($rowhd['hargasewa']);
    		$keterangankamar = $rowhd['keterangankamar'];

    		$cekdata = Csql::CekData($nokamar, "nokamar", $table, $database);
		    if($cekdata)
		    {
		    	AppConfig::$SUBMIT_SUCCSESS = false;
		    	Carray::setSuccess(false, "[nokamar] sudah ada dalam sistem.");
				$dtjson = Carray::getObject();
				echo $dtjson;
		        return false;
		    }

		    $gambarkamar = Cupload::UploadData("gambarkamar", "../upload/", "");

			$database->insert($table, [
				"kodekamar" => $kodekamar,
				"nokamar" => $nokamar,
				"ukurankamar" => $ukurankamar,
				"hargasewa" => $hargasewa,
				"keterangankamar" => $keterangankamar,
				"gambarkamar" => $gambarkamar,
				"dateaddkamar" => StringFunction::SetString(date("Y-m-d H:i:s")),
				"dateupdkamar" => StringFunction::SetString(date("Y-m-d H:i:s")),
			]);

			$kodereturn = $kodekamar;

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

    		$kodekamar_old = StringFunction::SetString($rowhd['kodekamar_old']);
    		$kodekamar = StringFunction::SetString($rowhd['kodekamar']);
    		$nokamar = StringFunction::SetInt($rowhd['nokamar']);
    		$ukurankamar = StringFunction::SetString($rowhd['ukurankamar']);
    		$hargasewa = StringFunction::SetInt($rowhd['hargasewa']);
    		$keterangankamar = $rowhd['keterangankamar'];

    		// cek
		    if($kodekamar != $kodekamar_old)
		    {
		    	$cekdata = Csql::CekData($kodekamar, "kodekamar", $table, $database);
			    if($cekdata)
			    {
			    	AppConfig::$SUBMIT_SUCCSESS = false;
			    	Carray::setSuccess(false, "[kodekamar] sudah ada dalam sistem.");
					$dtjson = Carray::getObject();
					echo $dtjson;
			        return false;
			    }
		    }

		    $gambarkamar_old = CSql::CariData("gambarkamar", "kodekamar", $kodekamar, $table, AppConfig::$DATABASE);
		    $gambarkamar = Cupload::UploadData("gambarkamar", "../upload/", $gambarkamar_old);

		    $database->update($table, [
		        "nokamar" => $nokamar,
				"ukurankamar" => $ukurankamar,
				"hargasewa" => $hargasewa,
				"keterangankamar" => $keterangankamar,
				"gambarkamar" => $gambarkamar,
				"dateupdkamar" => StringFunction::SetString(date("Y-m-d H:i:s")),
		    ],[
		        "kodekamar[=]" => $kodekamar
		    ]);

			$kodereturn = $kodekamar;

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