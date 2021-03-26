<?php

namespace ws\includes\route_login;

if(\appclas\AppConfig::$MOD_NAME == "login_admin")
	include("includes/login/login_admin.php");

elseif(\appclas\AppConfig::$MOD_NAME == "login_penghuni")
	include("includes/login/login_penghuni.php");

else
	echo "tidak ada";


?>