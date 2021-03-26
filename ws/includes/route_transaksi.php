<?php

namespace ws\includes\route_transaksi;

if(\appclas\AppConfig::$MOD_NAME == "transaksi_list")
	include("includes/transaksi/transaksi_list.php");

elseif(\appclas\AppConfig::$MOD_NAME == "transaksi_add")
	include("includes/transaksi/transaksi_add.php");

elseif(\appclas\AppConfig::$MOD_NAME == "transaksi_ae")
	include("includes/transaksi/transaksi_ae.php");

elseif(\appclas\AppConfig::$MOD_NAME == "transaksi_delete")
	include("includes/transaksi/transaksi_delete.php");

elseif(\appclas\AppConfig::$MOD_NAME == "transaksi_show")
	include("includes/transaksi/transaksi_show.php");

elseif(\appclas\AppConfig::$MOD_NAME == "transaksi_sewapenghuni_list")
	include("includes/transaksi/transaksi_sewapenghuni_list.php");

// khusus penghuni

elseif(\appclas\AppConfig::$MOD_NAME == "transaksi_list_penghuni")
	include("includes/transaksi/transaksi_list_penghuni.php");

elseif(\appclas\AppConfig::$MOD_NAME == "transaksi_ae_penghuni")
	include("includes/transaksi/transaksi_ae_penghuni.php");

else
{
	\appclas\Carray::setSuccess(false, "Pesan Kesalahan :: Route Sub [".\appclas\AppConfig::$MOD_NAME."] tidak ditemukan");
    $dtjson = \appclas\Carray::getObject();
    echo $dtjson;
    return;
}
	


?>