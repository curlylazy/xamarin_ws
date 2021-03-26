<?php

namespace ws\includes\saran\saran_list;

use appclas\Security;
use appclas\StringFunction;
use appclas\AppConfig;
use appclas\Carray;
use appclas\CSql;

try 
{

	$postdata = $_POST['postdata'];
	$rowsjson = json_decode($postdata, true);
    $rowhd = $rowsjson['DataHeader'][0];

    $katakunci = StringFunction::SetString($rowhd['katakunci']);
    $kodesewa = StringFunction::SetString($rowhd['kodesewa']);
    $limit = StringFunction::SetInt($rowhd['limit']);

    if(empty($limit) || $limit == 0)
    {
    	$limit = AppConfig::$PAGE_LIMIT;
    }
    
	$page = StringFunction::SetInt($rowhd['page']);
	$offset = ($page -  1) * $limit;

    $strSQL1 = "SELECT * FROM tbl_saran 
    			INNER JOIN tbl_sewa ON (tbl_sewa.kodesewa = tbl_saran.kodesewa)
    			INNER JOIN tbl_penghuni ON (tbl_penghuni.kodepenghuni = tbl_sewa.kodepenghuni)
    			INNER JOIN tbl_kamar ON (tbl_kamar.kodekamar = tbl_sewa.kodekamar)
    			WHERE TRUE ";

	$strSQL2 = "SELECT COUNT(*) as totaldata FROM tbl_saran 
				INNER JOIN tbl_sewa ON (tbl_sewa.kodesewa = tbl_saran.kodesewa)
				INNER JOIN tbl_penghuni ON (tbl_penghuni.kodepenghuni = tbl_sewa.kodepenghuni)
    			INNER JOIN tbl_kamar ON (tbl_kamar.kodekamar = tbl_sewa.kodekamar)
				WHERE TRUE";

	$strTemp = " ";
	$strTemp .= " AND (tbl_saran.judulsaran LIKE :katakunci) ";
	$strTemp .= " AND (tbl_sewa.kodesewa = :kodesewa) ";

	$strSQL1 .= " LIMIT $limit OFFSET $offset ";

	$rows_header = array();

	// data list
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL1);
	$query->bindValue(":katakunci", "%".$katakunci."%");
	$query->bindValue(":kodesewa", $kodesewa);
	$query->execute();
	while($row = $query->fetch())
	{
	    $rows_header[] = $row;
	}

	// paging
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL2);
	$query->bindValue(":katakunci", "%".$katakunci."%");
	$query->bindValue(":kodesewa", $kodesewa);
	$query->execute();
	$rows_pg = $query->fetch();

	$DataPaging = (object)null;
	$DataPaging->totaldata = $rows_pg['totaldata'];
    $DataPaging->totalpage = ceil($rows_pg['totaldata'] / $limit);

	Carray::setObject('DataSaran', $rows_header);
	Carray::setObject('DataPaging', $DataPaging);
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