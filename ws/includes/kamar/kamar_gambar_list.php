<?php

namespace ws\includes\kamar\kamar_gambar_list;

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

    $kodekamar = StringFunction::SetString($rowhd['kodekamar']);
    $katakunci = StringFunction::SetString($rowhd['katakunci']);
    $limit = StringFunction::SetInt($rowhd['limit']);

    if(empty($limit) || $limit == 0)
    {
    	$limit = AppConfig::$PAGE_LIMIT;
    }
    
	$page = StringFunction::SetInt($rowhd['page']);
	$offset = ($page -  1) * $limit;

    $strSQL1 = "SELECT * FROM tbl_kamar_gambar WHERE TRUE ";
	$strSQL2 = "SELECT COUNT(*) as totaldata FROM tbl_kamar_gambar WHERE TRUE";

	$strTemp = " ";
	$strTemp .= " AND (kodekamar = :kodekamar) ";
	$strTemp .= " AND (judulgambarkamar LIKE :katakunci) ";

	$strSQL1 .= " LIMIT $limit OFFSET $offset ";

	$rows_header = array();

	// data list
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL1);
	$query->bindValue(":kodekamar", $kodekamar);
	$query->bindValue(":katakunci", "%".$katakunci."%");
	$query->execute();
	while($row = $query->fetch())
	{
	    $rows_header[] = $row;
	}

	// paging
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL2);
	$query->bindValue(":kodekamar", $kodekamar);
	$query->bindValue(":katakunci", "%".$katakunci."%");
	$query->execute();
	$rows_pg = $query->fetch();

	$DataPaging = (object)null;
	$DataPaging->totaldata = $rows_pg['totaldata'];
    $DataPaging->totalpage = ceil($rows_pg['totaldata'] / $limit);

	Carray::setObject('DataKamarGambar', $rows_header);
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