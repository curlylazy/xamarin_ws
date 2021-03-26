<?php

namespace ws\includes\penghuni\penghuni_ae;

use appclas\Security;
use appclas\StringFunction;
use appclas\AppConfig;
use appclas\Carray;
use appclas\CSql;
use appclas\Cupload;

try 
{

	$table = "tbl_penghuni";
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

    		$kodepenghuni = Csql::GetKode("kodepenghuni", "USR", $table, AppConfig::$DATABASE);
    		$username = StringFunction::SetString($rowhd['username']);
		    $password = Security::encrypt($rowhd['password']);
		    $namapenghuni = StringFunction::SetString($rowhd['namapenghuni']);
		    $pekerjaan = StringFunction::SetString($rowhd['pekerjaan']);
		    $notelepon = StringFunction::SetString($rowhd['notelepon']);
		    $email = StringFunction::SetString($rowhd['email']);
		    $alamat = StringFunction::SetString($rowhd['alamat']);
		    $statuspenghuni = StringFunction::SetString($rowhd['statuspenghuni']);

		    // cek username
		    $cekdata = Csql::CekData($username, "username", $table, $database);
		    if($cekdata)
		    {
		    	AppConfig::$SUBMIT_SUCCSESS = false;
		    	Carray::setSuccess(false, "[username] sudah ada dalam sistem.");
				$dtjson = Carray::getObject();
				echo $dtjson;
		        return false;
		    }

		    $gambarpenghuni = Cupload::UploadData("gambarpenghuni", "../upload/", "");

			$database->insert($table, [
				"kodepenghuni" => $kodepenghuni,
				"username" => $username,
				"password" => $password,
				"namapenghuni" => $namapenghuni,
				"pekerjaan" => $pekerjaan,
				"notelepon" => $notelepon,
				"email" => $email,
				"alamat" => $alamat,
				"gambarpenghuni" => $gambarpenghuni,
				"statuspenghuni" => $statuspenghuni,
				"dateaddpenghuni" => StringFunction::SetString(date("Y-m-d H:i:s")),
				"dateupdpenghuni" => StringFunction::SetString(date("Y-m-d H:i:s")),
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

    		$kodepenghuni = StringFunction::SetString($rowhd['kodepenghuni']);
    		$username = StringFunction::SetString($rowhd['username']);
    		$username_old = StringFunction::SetString($rowhd['username_old']);
		    $password = Security::encrypt($rowhd['password']);
		    $namapenghuni = StringFunction::SetString($rowhd['namapenghuni']);
		    $pekerjaan = StringFunction::SetString($rowhd['pekerjaan']);
		    $notelepon = StringFunction::SetString($rowhd['notelepon']);
		    $email = StringFunction::SetString($rowhd['email']);
		    $alamat = StringFunction::SetString($rowhd['alamat']);
		    $statuspenghuni = StringFunction::SetString($rowhd['statuspenghuni']);

		    // cek username
		    if($username != $username_old)
		    {
		    	$cekdata = Csql::CekData($username, "username", $table, $database);
			    if($cekdata)
			    {
			    	AppConfig::$SUBMIT_SUCCSESS = false;
			    	Carray::setSuccess(false, "[username] sudah ada dalam sistem.");
					$dtjson = Carray::getObject();
					echo $dtjson;
			        return false;
			    }
		    }
		    
		    $gambarpenghuni_old = CSql::CariData("gambarpenghuni", "kodepenghuni", $kodepenghuni, $table, AppConfig::$DATABASE);
		    $gambarpenghuni = Cupload::UploadData("gambarpenghuni", "../upload/", $gambarpenghuni_old);

		    $database->update($table, [
		        "username" => $username,
				"password" => $password,
				"namapenghuni" => $namapenghuni,
				"pekerjaan" => $pekerjaan,
				"notelepon" => $notelepon,
				"email" => $email,
				"alamat" => $alamat,
				"gambarpenghuni" => $gambarpenghuni,
				"statuspenghuni" => $statuspenghuni,
				"dateupdpenghuni" => StringFunction::SetString(date("Y-m-d H:i:s")),
		    ],[
		        "kodepenghuni[=]" => $kodepenghuni
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