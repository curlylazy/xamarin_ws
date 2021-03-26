<?php

session_start();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: *');

require '../appclass/appconfig.php';
use appclas\AppConfig;

// Hidupkan Koneksi
require '../meedo/vendor/catfan/medoo/src/Medoo.php';
require '../meedo/vendor/autoload.php';
require '../meedo/koneksi.php';

// import class
require '../appclass/c_stringfunction.php';
require '../appclass/c_security.php';
require '../appclass/c_array.php';
require '../appclass/c_sql.php';
require '../appclass/c_upload.php';

use appclas\StringFunction;
use appclas\Security;
use appclas\Carray;
use appclas\CSql;
use appclas\Cupload;

$mod_name = StringFunction::SetString($_GET['mod_name']);
$mod_route = StringFunction::SetString($_GET['mod_route']);

AppConfig::$MOD_ROUTE = $mod_route;
AppConfig::$MOD_NAME = $mod_name;

// echo Security::encrypt('12345');

// include pages

if(AppConfig::$MOD_ROUTE == "login")
	include("includes/route_login.php");

else if(AppConfig::$MOD_ROUTE == "penghuni")
	include("includes/route_penghuni.php");

else if(AppConfig::$MOD_ROUTE == "kamar")
	include("includes/route_kamar.php");

else if(AppConfig::$MOD_ROUTE == "sewa")
	include("includes/route_sewa.php");

else if(AppConfig::$MOD_ROUTE == "extra")
	include("includes/route_extra.php");

else if(AppConfig::$MOD_ROUTE == "transaksi")
	include("includes/route_transaksi.php");

else if(AppConfig::$MOD_ROUTE == "informasi")
	include("includes/route_informasi.php");

else if(AppConfig::$MOD_ROUTE == "saran")
	include("includes/route_saran.php");

else if(AppConfig::$MOD_ROUTE == "dashboard")
	include("includes/route_dashboard.php");

else if(AppConfig::$MOD_ROUTE == "profile")
	include("includes/route_profile.php");

else if(AppConfig::$MOD_ROUTE == "user")
	include("includes/route_user.php");

else
{
	\appclas\Carray::setSuccess(false, "Pesan Kesalahan :: Route [utama] tidak ditemukan");
    $dtjson = \appclas\Carray::getObject();
    echo $dtjson;
    return;
}
	

?>