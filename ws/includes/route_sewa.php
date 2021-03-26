<?php

namespace ws\includes\route_sewa;

if(\appclas\AppConfig::$MOD_NAME == "sewa_list")
	include("includes/sewa/sewa_list.php");

elseif(\appclas\AppConfig::$MOD_NAME == "sewa_ae")
	include("includes/sewa/sewa_ae.php");

elseif(\appclas\AppConfig::$MOD_NAME == "sewa_delete")
	include("includes/sewa/sewa_delete.php");

elseif(\appclas\AppConfig::$MOD_NAME == "sewa_show")
	include("includes/sewa/sewa_show.php");

elseif(\appclas\AppConfig::$MOD_NAME == "sewa_add_penghuni")
	include("includes/sewa/sewa_add_penghuni.php");

elseif(\appclas\AppConfig::$MOD_NAME == "sewa_penghuni_list")
	include("includes/sewa/sewa_penghuni_list.php");

elseif(\appclas\AppConfig::$MOD_NAME == "sewa_delete_penghuni")
	include("includes/sewa/sewa_delete_penghuni.php");

else
{
	\appclas\Carray::setSuccess(false, "Pesan Kesalahan :: Route Sub [".\appclas\AppConfig::$MOD_NAME."] tidak ditemukan");
    $dtjson = \appclas\Carray::getObject();
    echo $dtjson;
    return;
}
	


?>