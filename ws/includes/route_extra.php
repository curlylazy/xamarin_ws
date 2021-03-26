<?php

namespace ws\includes\route_extra;

if(\appclas\AppConfig::$MOD_NAME == "extra_penghuni_list")
	include("includes/extra/extra_penghuni_list.php");

elseif(\appclas\AppConfig::$MOD_NAME == "extra_kamar_list")
	include("includes/extra/extra_kamar_list.php");

elseif(\appclas\AppConfig::$MOD_NAME == "extra_penghuni_sewa_list")
	include("includes/extra/extra_penghuni_sewa_list.php");

elseif(\appclas\AppConfig::$MOD_NAME == "extra_sewa_list")
	include("includes/extra/extra_sewa_list.php");

else
{
	\appclas\Carray::setSuccess(false, "Pesan Kesalahan :: Route Sub [".\appclas\AppConfig::$MOD_NAME."] tidak ditemukan");
    $dtjson = \appclas\Carray::getObject();
    echo $dtjson;
    return;
}
	


?>