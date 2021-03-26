<?php

namespace ws\includes\route_informasi;

if(\appclas\AppConfig::$MOD_NAME == "informasi_list")
	include("includes/informasi/informasi_list.php");

elseif(\appclas\AppConfig::$MOD_NAME == "informasi_ae")
	include("includes/informasi/informasi_ae.php");

elseif(\appclas\AppConfig::$MOD_NAME == "informasi_delete")
	include("includes/informasi/informasi_delete.php");

elseif(\appclas\AppConfig::$MOD_NAME == "informasi_show")
	include("includes/informasi/informasi_show.php");

else
{
	\appclas\Carray::setSuccess(false, "Pesan Kesalahan :: Route Sub [".\appclas\AppConfig::$MOD_NAME."] tidak ditemukan");
    $dtjson = \appclas\Carray::getObject();
    echo $dtjson;
    return;
}
	


?>