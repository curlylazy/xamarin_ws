<?php

namespace ws\includes\route_saran;

if(\appclas\AppConfig::$MOD_NAME == "saran_list")
	include("includes/saran/saran_list.php");

elseif(\appclas\AppConfig::$MOD_NAME == "saran_ae")
	include("includes/saran/saran_ae.php");

elseif(\appclas\AppConfig::$MOD_NAME == "saran_delete")
	include("includes/saran/saran_delete.php");

elseif(\appclas\AppConfig::$MOD_NAME == "saran_show")
	include("includes/saran/saran_show.php");

elseif(\appclas\AppConfig::$MOD_NAME == "saran_list_penghuni")
	include("includes/saran/saran_list_penghuni.php");

else
{
	\appclas\Carray::setSuccess(false, "Pesan Kesalahan :: Route Sub [".\appclas\AppConfig::$MOD_NAME."] tidak ditemukan");
    $dtjson = \appclas\Carray::getObject();
    echo $dtjson;
    return;
}
	


?>