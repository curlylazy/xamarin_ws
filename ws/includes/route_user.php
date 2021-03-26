<?php

namespace ws\includes\route_user;

if(\appclas\AppConfig::$MOD_NAME == "user_list")
	include("includes/user/user_list.php");

elseif(\appclas\AppConfig::$MOD_NAME == "user_ae")
	include("includes/user/user_ae.php");

elseif(\appclas\AppConfig::$MOD_NAME == "user_show")
	include("includes/user/user_show.php");


else
{
	\appclas\Carray::setSuccess(false, "Pesan Kesalahan :: Route Sub [".\appclas\AppConfig::$MOD_NAME."] tidak ditemukan");
    $dtjson = \appclas\Carray::getObject();
    echo $dtjson;
    return;
}
	


?>