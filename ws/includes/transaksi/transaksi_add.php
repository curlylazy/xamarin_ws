<?php

namespace ws\includes\transaksi\transaksi_add;

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

    AppConfig::$DATABASE->action(function($database) {

		global $table, $rowhd, $kodereturn;

		$bulan = StringFunction::SetString($rowhd['bulan']);
		$tahun = StringFunction::SetString($rowhd['tahun']);
		$periodetransaksi = date('Y-m-d', strtotime("$tahun-$bulan-01"));

		// buka semua data sewa
		$strSQL = " SELECT * FROM tbl_sewa
    				INNER JOIN tbl_kamar ON (tbl_kamar.kodekamar = tbl_sewa.kodekamar)
    				WHERE tbl_sewa.statussewa = :statussewa ";

    	$query = AppConfig::$DATABASE->pdo->prepare($strSQL);
    	$query->bindValue(":statussewa", 1);
    	$query->execute();
	    while($row = $query->fetch())
		{
			$kodetransaksi = Csql::GetKode("kodetransaksi", "TRS", $table, AppConfig::$DATABASE);
			$kodesewa = $row['kodesewa'];
			$biayasewa = $row['hargasewa'];

			// cek apakah data sewa, sudah ada sebelumnya pada tabel transaksi
			$ceksewa = Csql::CekSewa($kodesewa, $periodetransaksi, $database);
			if(!$cekdata)
	    	{
	    		$database->insert($table, [
					"kodetransaksi" => $kodetransaksi,
					"kodesewa" => $kodesewa,
					"biayasewa" => $biayasewa,
					"biayasampah" => 0,
					"biayalistrik" => 0,
					"biayaair" => 0,
					"biayasecurity" => 0,
					"total" => $biayasewa,
					"statustransaksi" => 0,
					"periodetransaksi" => $periodetransaksi,
					"dateaddtransaksi" => StringFunction::SetString(date("Y-m-d H:i:s")),
					"dateupdtransaksi" => StringFunction::SetString(date("Y-m-d H:i:s")),
				]);
	    	}
		}

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
		$dtjson = Carray::getObject();
		echo $dtjson;
    }
	

} catch (Exception $e) {

    Carray::setSuccess(false, "Pesan Kesalahan :: ". $e->getMessage());
	$dtjson = Carray::getObject();
	echo $dtjson;
}

?>