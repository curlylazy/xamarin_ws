<?php

namespace ws\includes\dashboard\dashboard_list;

use appclas\Security;
use appclas\StringFunction;
use appclas\AppConfig;
use appclas\Carray;
use appclas\CSql;

try 
{
    // total penghuni
    $strSQL = "SELECT COUNT(*) as total FROM tbl_penghuni
    		   WHERE statuspenghuni = :statuspenghuni";
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL);
	$query->bindValue(":statuspenghuni", 1);
	$query->execute();
	$rows = $query->fetch();
	$total_penghuni = $rows['total'];

	// total kamar
    $strSQL = "SELECT COUNT(*) as total FROM tbl_kamar";
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL);
	$query->execute();
	$rows = $query->fetch();
	$total_kamar = $rows['total'];

	// total informasi
    $strSQL = "SELECT COUNT(*) as total FROM tbl_informasi";
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL);
	$query->execute();
	$rows = $query->fetch();
	$total_informasi = $rows['total'];

	// total saran
    $strSQL = "SELECT COUNT(*) as total FROM tbl_saran";
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL);
	$query->execute();
	$rows = $query->fetch();
	$total_saran = $rows['total'];

	$DataDashboard = (object)null;
	$DataDashboard->total_penghuni = $total_penghuni;
	$DataDashboard->total_kamar = $total_kamar;
	$DataDashboard->total_informasi = $total_informasi;
	$DataDashboard->total_saran = $total_saran;

	Carray::setObject('DataDashboard', $DataDashboard);
	Carray::setSuccess(true);
	$dtjson = Carray::getObject();

	echo $dtjson;

} catch (Exception $e) {

    Carray::setSuccess(false, "Pesan Kesalahan :: ". $e->getMessage());
	$dtjson = Carray::getObject();
	echo $dtjson;
}



// print json_encode($row);


?>