<?php

namespace ws\includes\route_penghuni;

if(\appclas\AppConfig::$MOD_NAME == "penghuni_list")
	include("includes/penghuni/penghuni_list.php");

elseif(\appclas\AppConfig::$MOD_NAME == "penghuni_ae")
	include("includes/penghuni/penghuni_ae.php");

elseif(\appclas\AppConfig::$MOD_NAME == "penghuni_delete")
	include("includes/penghuni/penghuni_delete.php");

elseif(\appclas\AppConfig::$MOD_NAME == "penghuni_show")
	include("includes/penghuni/penghuni_show.php");

else
{
	\appclas\Carray::setSuccess(false, "Pesan Kesalahan :: Route Sub [".\appclas\AppConfig::$MOD_NAME."] tidak ditemukan");
    $dtjson = \appclas\Carray::getObject();
    echo $dtjson;
    return;
}
	


?>