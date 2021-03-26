<?php

namespace ws\includes\saran\saran_ae;

use appclas\Security;
use appclas\StringFunction;
use appclas\AppConfig;
use appclas\Carray;
use appclas\CSql;
use appclas\Cupload;

try 
{

	$table = "tbl_saran";
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

    		$kodesaran = Csql::GetKode("kodesaran", "SARAN", $table, AppConfig::$DATABASE);
    		$kodesewa = StringFunction::SetString($rowhd['kodesewa']);
    		$judulsaran = StringFunction::SetString($rowhd['judulsaran']);
    		$isisaran = StringFunction::SetString($rowhd['isisaran']);

		    $gambarsaran_1 = Cupload::UploadData("gambarsaran_1", "../upload/", "");
		    $gambarsaran_2 = Cupload::UploadData("gambarsaran_2", "../upload/", "");
		    $gambarsaran_3 = Cupload::UploadData("gambarsaran_3", "../upload/", "");

			$database->insert($table, [
				"kodesaran" => $kodesaran,
				"kodesewa" => $kodesewa,
				"judulsaran" => $judulsaran,
				"isisaran" => $isisaran,
				"gambarsaran_1" => $gambarsaran_1,
				"gambarsaran_2" => $gambarsaran_2,
				"gambarsaran_3" => $gambarsaran_3,
				"dateaddsaran" => StringFunction::SetString(date("Y-m-d H:i:s")),
				"dateupdsaran" => StringFunction::SetString(date("Y-m-d H:i:s")),
			]);

			$kodereturn = $kodesaran;

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

    		$kodesaran = StringFunction::SetString($rowhd['kodesaran']);
    		$kodesewa = StringFunction::SetString($rowhd['kodesewa']);
    		$judulsaran = StringFunction::SetString($rowhd['judulsaran']);
    		$isisaran = StringFunction::SetString($rowhd['isisaran']);

		    $gambarsaran_1_old = CSql::CariData("gambarsaran_1", "kodesaran", $kodesaran, $table, AppConfig::$DATABASE);
		    $gambarsaran_1 = Cupload::UploadData("gambarsaran_1", "../upload/", $gambarsaran_1_old);

		    $gambarsaran_2_old = CSql::CariData("gambarsaran_2", "kodesaran", $kodesaran, $table, AppConfig::$DATABASE);
		    $gambarsaran_2 = Cupload::UploadData("gambarsaran_2", "../upload/", $gambarsaran_2_old);

		    $gambarsaran_3_old = CSql::CariData("gambarsaran_3", "kodesaran", $kodesaran, $table, AppConfig::$DATABASE);
		    $gambarsaran_3 = Cupload::UploadData("gambarsaran_3", "../upload/", $gambarsaran_3_old);

		    $database->update($table, [
		        "kodesewa" => $kodesewa,
				"judulsaran" => $judulsaran,
				"isisaran" => $isisaran,
				"gambarsaran_1" => $gambarsaran_1,
				"gambarsaran_2" => $gambarsaran_2,
				"gambarsaran_3" => $gambarsaran_3,
				"dateupdsaran" => StringFunction::SetString(date("Y-m-d H:i:s")),
		    ],[
		        "kodesaran[=]" => $kodesaran
		    ]);

			$kodereturn = $kodesaran;

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