<?php

namespace ws\includes\transaksi\transaksi_list;

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
    $bulan = StringFunction::SetString($rowhd['bulan']);
    $tahun = StringFunction::SetString($rowhd['tahun']);
    $limit = StringFunction::SetInt($rowhd['limit']);

    $periodetransaksi = date("Y-m", strtotime("$tahun-$bulan-01"));

    if(empty($limit) || $limit == 0)
    {
    	$limit = AppConfig::$PAGE_LIMIT;
    }
    
	$page = StringFunction::SetInt($rowhd['page']);
	$offset = ($page -  1) * $limit;

    $strSQL1 = "SELECT * FROM tbl_transaksi 
    			INNER JOIN tbl_sewa ON (tbl_sewa.kodesewa = tbl_transaksi.kodesewa)
    			INNER JOIN tbl_kamar ON (tbl_kamar.kodekamar = tbl_sewa.kodekamar)
    			WHERE TRUE ";

	$strSQL2 = "SELECT COUNT(*) as totaldata FROM tbl_transaksi 
				INNER JOIN tbl_sewa ON (tbl_sewa.kodesewa = tbl_transaksi.kodesewa)
    			INNER JOIN tbl_kamar ON (tbl_kamar.kodekamar = tbl_sewa.kodekamar)
				WHERE TRUE";

	$strTemp = " ";
	$strTemp .= " AND (tbl_sewa.kodesewa LIKE :katakunci) ";

	if(!empty($periodetransaksi))
	{
		$strTemp .= " AND (DATE_FORMAT(tbl_transaksi.periodetransaksi, '%Y-%m') = :periodetransaksi) ";
	}

	$strSQL1 .= $strTemp;
	$strSQL2 .= $strTemp;

	$strSQL1 .= " LIMIT $limit OFFSET $offset ";

	$rows_header = array();

	// data list
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL1);
	$query->bindValue(":katakunci", "%".$katakunci."%");
	if(!empty($periodetransaksi))
	{
		$query->bindValue(":periodetransaksi", $periodetransaksi);
	}

	$query->execute();
	while($row = $query->fetch())
	{
		$row['jmlpenghuni'] = CSql::JumlahPenghuniSewa($row['kodesewa'], AppConfig::$DATABASE);
		$row['daftarpenghuni'] = Csql::DaftarPenghuni($row['kodesewa'], AppConfig::$DATABASE);
	    $rows_header[] = $row;
	}

	// paging
	$query = AppConfig::$DATABASE->pdo->prepare($strSQL2);
	$query->bindValue(":katakunci", "%".$katakunci."%");
	if(!empty($periodetransaksi))
	{
		$query->bindValue(":periodetransaksi", $periodetransaksi);
	}
	$query->execute();
	$rows_pg = $query->fetch();

	$DataPaging = (object)null;
	$DataPaging->totaldata = $rows_pg['totaldata'];
    $DataPaging->totalpage = ceil($rows_pg['totaldata'] / $limit);

	Carray::setObject('DataTransaksi', $rows_header);
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