<?php

namespace ws\includes\transaksi\transaksi_ae_penghuni;

use appclas\Security;
use appclas\StringFunction;
use appclas\AppConfig;
use appclas\Carray;
use appclas\CSql;
use appclas\Cupload;

try 
{

	$table = "tbl_transaksi";
	$ketreturn = "";
	$kodereturn = "";

	$postdata = $_POST['postdata'];
	$rowsjson = json_decode($postdata, true);
    $rowhd = $rowsjson['DataHeader'][0];
    $rowsave = $rowsjson['DataSave'][0];

    // cek mode
    AppConfig::$APP_MODE = StringFunction::SetString($rowsave['save_mode']);

    if (AppConfig::$APP_MODE == AppConfig::$SAVE_MODE_EDIT) 
    {
    	$ketreturn = "Update Data";

    	AppConfig::$DATABASE->action(function($database) {

    		global $table, $rowhd, $kodereturn;

    		$kodetransaksi = StringFunction::SetString($rowhd['kodetransaksi']);
    		$kodesewa = StringFunction::SetString($rowhd['kodesewa']);
    		$biayasewa = StringFunction::SetInt($rowhd['biayasewa']);
    		$biayasampah = StringFunction::SetInt($rowhd['biayasampah']);
    		$biayalistrik = StringFunction::SetInt($rowhd['biayalistrik']);
    		$biayaair = StringFunction::SetInt($rowhd['biayaair']);
    		$biayasecurity = StringFunction::SetInt($rowhd['biayasecurity']);
    		$total = StringFunction::SetInt($rowhd['total']);
    		$statustransaksi = StringFunction::SetInt($rowhd['statustransaksi']);
    		$periodetransaksi = StringFunction::SetString($rowhd['periodetransaksi']);

    		$konfirmasi_an = StringFunction::SetString($rowhd['konfirmasi_an']);
    		$konfirmasi_bank = StringFunction::SetString($rowhd['konfirmasi_bank']);
    		$konfirmasi_norek = StringFunction::SetString($rowhd['konfirmasi_norek']);

    		$konfirmasi_gambar_old = CSql::CariData("konfirmasi_gambar", "kodetransaksi", $kodetransaksi, $table, AppConfig::$DATABASE);
		    $konfirmasi_gambar = Cupload::UploadData("konfirmasi_gambar", "../upload/", $konfirmasi_gambar_old);

		    $database->update($table, [
		        "biayasewa" => $biayasewa,
				"biayasampah" => $biayasampah,
				"biayalistrik" => $biayalistrik,
				"biayaair" => $biayaair,
				"biayasecurity" => $biayasecurity,
				"total" => $total,
				"statustransaksi" => $statustransaksi,
				"konfirmasi_an" => $konfirmasi_an,
				"konfirmasi_bank" => $konfirmasi_bank,
				"konfirmasi_norek" => $konfirmasi_norek,
				"konfirmasi_gambar" => $konfirmasi_gambar,
				"statustransaksi" => 1,
				"dateupdtransaksi" => StringFunction::SetString(date("Y-m-d H:i:s")),
		    ],[
		        "kodetransaksi[=]" => $kodetransaksi
		    ]);

			$kodereturn = $kodetransaksi;

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