<?php

namespace ws\includes\route_dashboard;

if(\appclas\AppConfig::$MOD_NAME == "dashboard_list")
	include("includes/dashboard/dashboard_list.php");

else
{
	\appclas\Carray::setSuccess(false, "Pesan Kesalahan :: Route Sub [".\appclas\AppConfig::$MOD_NAME."] tidak ditemukan");
    $dtjson = \appclas\Carray::getObject();
    echo $dtjson;
    return;
}
	


?>