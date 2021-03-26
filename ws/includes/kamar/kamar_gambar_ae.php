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

	$table = "tbl_kamar_gambar";
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

    		$kodegambarkamar = Csql::GetKode("kodegambarkamar", "KMGBR", $table, AppConfig::$DATABASE);
    		$kodekamar = StringFunction::SetString($rowhd['kodekamar']);
    		$judulgambarkamar = StringFunction::SetString($rowhd['judulgambarkamar']);

		    $gambarkamardt = Cupload::UploadData("gambarkamardt", "../upload/", "");

			$database->insert($table, [
				"kodegambarkamar" => $kodegambarkamar,
				"kodekamar" => $kodekamar,
				"judulgambarkamar" => $judulgambarkamar,
				"gambarkamardt" => $gambarkamardt,
				"dateaddgambarkamar" => StringFunction::SetString(date("Y-m-d H:i:s")),
				"dateupdgambarkamar" => StringFunction::SetString(date("Y-m-d H:i:s")),
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

		});

    }

    elseif (AppConfig::$APP_MODE == AppConfig::$SAVE_MODE_EDIT) 
    {
    	$ketreturn = "Update Data";

    	AppConfig::$DATABASE->action(function($database) {

    		global $table, $rowhd, $kodereturn;

    		$kodegambarkamar = StringFunction::SetString($rowhd['kodegambarkamar']);
    		$kodekamar = StringFunction::SetString($rowhd['kodekamar']);
    		$judulgambarkamar = StringFunction::SetString($rowhd['judulgambarkamar']);

		    $gambarkamardt_old = CSql::CariData("gambarkamardt", "kodegambarkamar", $kodegambarkamar, $table, AppConfig::$DATABASE);
		    $gambarkamardt = Cupload::UploadData("gambarkamardt", "../upload/", $gambarkamardt_old);

		    $database->update($table, [
				"judulgambarkamar" => $judulgambarkamar,
				"gambarkamardt" => $gambarkamardt,
				"dateupdgambarkamar" => StringFunction::SetString(date("Y-m-d H:i:s")),
		    ],[
		        "kodegambarkamar[=]" => $kodegambarkamar
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
		$dtjson = Carray::getObject();
		echo $dtjson;
    }
	

} catch (Exception $e) {

    Carray::setSuccess(false, "Pesan Kesalahan :: ". $e->getMessage());
	$dtjson = Carray::getObject();
	echo $dtjson;
}

?>