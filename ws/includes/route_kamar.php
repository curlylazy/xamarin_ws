<?php

namespace ws\includes\route_kamar;

if(\appclas\AppConfig::$MOD_NAME == "kamar_list")
	include("includes/kamar/kamar_list.php");

elseif(\appclas\AppConfig::$MOD_NAME == "kamar_ae")
	include("includes/kamar/kamar_ae.php");

elseif(\appclas\AppConfig::$MOD_NAME == "kamar_delete")
	include("includes/kamar/kamar_delete.php");

elseif(\appclas\AppConfig::$MOD_NAME == "kamar_show")
	include("includes/kamar/kamar_show.php");

elseif(\appclas\AppConfig::$MOD_NAME == "kamar_gambar_list")
	include("includes/kamar/kamar_gambar_list.php");

elseif(\appclas\AppConfig::$MOD_NAME == "kamar_gambar_ae")
	include("includes/kamar/kamar_gambar_ae.php");

elseif(\appclas\AppConfig::$MOD_NAME == "kamar_gambar_delete")
	include("includes/kamar/kamar_gambar_delete.php");

else
{
	\appclas\Carray::setSuccess(false, "Pesan Kesalahan :: Route Sub [".\appclas\AppConfig::$MOD_NAME."] tidak ditemukan");
    $dtjson = \appclas\Carray::getObject();
    echo $dtjson;
    return;
}
	


?>