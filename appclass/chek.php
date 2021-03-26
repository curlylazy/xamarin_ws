<?php
	function check()
	{
		ob_start();
		system('ipconfig /all'); 
		$mycom = ob_get_contents(); 
		ob_clean(); 
		
		$findme = "Physical";
		$pmac 	= strpos($mycom, $findme); 
		$mac  	= substr($mycom,($pmac+36),17); 
		
		return $mac;
	}
	
	if($_SERVER['HTTP_HOST'] == "127.0.0.1" ||  $_SERVER['HTTP_HOST'] == "localhost")
	{
		if(check() != "F6-D5-3D-C2-77-FF")
		{
			exit();
		}
	}
?>