<?php

namespace ws\includes\route_profile;

if(\appclas\AppConfig::$MOD_NAME == "profile_show_admin")
	include("includes/profile/profile_show_admin.php");

elseif(\appclas\AppConfig::$MOD_NAME == "profile_ae_admin")
	include("includes/profile/profile_ae_admin.php");

elseif(\appclas\AppConfig::$MOD_NAME == "profile_penghuni")
	include("includes/profile/profile_penghuni.php");

else
	echo "tidak ada";


?>