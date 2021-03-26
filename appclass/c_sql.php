<?php

namespace appclas;

use PDO;

class CSql{

	private $str = "";

	public function __construct()
	{

	}

	public static function GetKode($kode, $param ,$table, $database)
	{

		if($kode =="" || $param == "" || $table == "")
		{
			echo "Tidak dapat menggenerate data Kode Otomatis";
			return;
		}

		$autonum = "";
		$value	 = "";

		$query = $database->pdo->prepare("SELECT max($kode) AS nomer FROM $table");
		$query->execute();
		$row = $query->fetch();

		$autonum = $row['nomer'];

		# Cek Parameter
		if($autonum == "")
		{
			$autonum = $param."001";
		}
		else
		{
			$autonum = (int) substr($autonum, strlen($param), 4);
			$autonum++;
			$autonum =  $param.sprintf("%03s", $autonum);
		}

		return $autonum;
	}

	public static function CariData($cari, $column, $value, $tabel, $database)
	{
		$query = $database->pdo->prepare("SELECT * FROM $tabel WHERE $column = :value");
		$query->bindValue(':value', $value, PDO::PARAM_STR);

		$query->execute();
		$row = $query->fetch();

		return $row[$cari];
	}

	public static function CekData($value, $column ,$tabel, $database)
	{
		$query = $database->pdo->prepare("SELECT COUNT($column) as jumlah FROM $tabel WHERE $column = :value");
		$query->bindValue(':value', $value, PDO::PARAM_STR);

		$query->execute();
		$row = $query->fetch();
		$jumlah = $row['jumlah'];

		$iRes = false;

		if($jumlah > 0)
			$iRes = true;
		else
			$iRes = false;

		return $iRes;
	}

	public static function CekKamarPenghuni($kodekamar, $kodepenghuni, $database)
	{
		$sql = "SELECT COUNT(*) as jumlah FROM tbl_sewa 
				WHERE (kodekamar = :kodekamar OR kodepenghuni = :kodepenghuni) AND (statussewa= :statussewa)";

		$query = $database->pdo->prepare($sql);
		$query->bindValue(':kodekamar', $kodekamar, PDO::PARAM_STR);
		$query->bindValue(':kodepenghuni', $kodepenghuni, PDO::PARAM_STR);
		$query->bindValue(':statussewa', 1, PDO::PARAM_INT);

		$query->execute();
		$row = $query->fetch();
		$jumlah = $row['jumlah'];

		$iRes = false;

		if($jumlah > 0)
			$iRes = true;
		else
			$iRes = false;

		return $iRes;
	}

	public static function CekPenghuniSewa($kodepenghuni, $database)
	{
		$sql = "SELECT COUNT(*) as jumlah FROM tbl_sewa_penghuni
				WHERE (kodepenghuni = :kodepenghuni)";

		$query = $database->pdo->prepare($sql);
		$query->bindValue(':kodepenghuni', $kodepenghuni, PDO::PARAM_STR);

		$query->execute();
		$row = $query->fetch();
		$jumlah = $row['jumlah'];

		$iRes = false;

		if($jumlah > 0)
			$iRes = true;
		else
			$iRes = false;

		return $iRes;
	}

	public static function CekSewa($kodesewa, $periodetransaksi, $database)
	{
		$periodetransaksi = date('Y-m', strtotime($periodetransaksi));

		$sql = "SELECT COUNT(*) as jumlah FROM tbl_sewa 
				WHERE (kodesewa = :kodesewa) AND (DATE_FORMAT(periodetransaksi, '%Y-%m') = :periodetransaksi)";

		$query = $database->pdo->prepare($sql);
		$query->bindValue(':kodesewa', $kodesewa, PDO::PARAM_STR);
		$query->bindValue(':periodetransaksi', $periodetransaksi, PDO::PARAM_STR);
		$query->execute();
		$row = $query->fetch();
		$jumlah = $row['jumlah'];

		$iRes = false;

		if($jumlah > 0)
			$iRes = true;
		else
			$iRes = false;

		return $iRes;
	}

	public static function DaftarPenghuni($kodesewa, $database)
	{
		$sql = "SELECT * FROM tbl_sewa_penghuni 
				INNER JOIN tbl_penghuni ON (tbl_penghuni.kodepenghuni = tbl_sewa_penghuni.kodepenghuni)
				WHERE (tbl_sewa_penghuni.kodesewa = :kodesewa)";

		$query = $database->pdo->prepare($sql);
		$query->bindValue(':kodesewa', $kodesewa, PDO::PARAM_STR);
		$query->execute();

		while($row = $query->fetch())
        {
        	$iRes[] = $row['namapenghuni'];
        }

		return join($iRes, ",");
	}

	public static function JumlahPenghuniSewa($kodesewa, $database)
	{
		$Sql = "SELECT COUNT(*) as jumlah FROM tbl_sewa_penghuni
				WHERE kodesewa = :kodesewa";
		$query = $database->pdo->prepare($Sql);
		$query->bindValue(':kodesewa', $kodesewa, PDO::PARAM_STR);
		$query->execute();
		$row = $query->fetch();

		return $row['jumlah'];
	}


















	function CekAbsen($kodekelas, $tanggalabsen, $database)
	{

		$query = $database->pdo->prepare("SELECT COUNT(*) as jumlah FROM tbl_absen
										  WHERE kodekelas = :kodekelas AND tanggalabsen = :tanggalabsen");
		$query->bindValue(':kodekelas', $kodekelas, PDO::PARAM_STR);
		$query->bindValue(':tanggalabsen', $tanggalabsen, PDO::PARAM_STR);

		$query->execute();
		$row = $query->fetch();

		return $row['jumlah'];
	}

	public function CekJenisKerusakan($kodeservice, $kodejeniskerusakan, $database)
	{
		$sql = "SELECT COUNT(*) as jumlah
				FROM tbl_service_detail
				WHERE kodeservice = :kodeservice AND kodejeniskerusakan = :kodejeniskerusakan";

		$query = $database->pdo->prepare($sql);
		$query->bindValue(':kodeservice', $kodeservice, PDO::PARAM_STR);
		$query->bindValue(':kodejeniskerusakan', $kodejeniskerusakan, PDO::PARAM_STR);

		$query->execute();
		$row = $query->fetch();

		return $row['jumlah'];
	}

	public function DropDown($table, $value, $display, $database)
	{
		$iRes = array();
		$Sql = "SELECT * FROM $table";
        $sth = $database->pdo->prepare($Sql);
        $sth->execute();
        while($row = $sth->fetch())
        {
        	$iRes[] = "<option value='$row[$value]'>$row[$display]</option>";
        }

        return join($iRes, "");
	}

	public function DropDownHari()
	{
		$iRes = array();
		$iRes[] = "<option value='SENIN'>SENIN</option>";
		$iRes[] = "<option value='SELASA'>SELASA</option>";
		$iRes[] = "<option value='RABU'>RABU</option>";
		$iRes[] = "<option value='KAMIS'>KAMIS</option>";
		$iRes[] = "<option value='JUMAT'>JUMAT</option>";
		$iRes[] = "<option value='SABTU'>SABTU</option>";
		$iRes[] = "<option value='MINGGu'>MINGGu</option>";
		return join($iRes, "");
	}

	public function DropDownCustom($custom,$database)
	{
		$iRes = array();

		if($custom == "KECAMATAN")
		{
			$Sql = "SELECT * FROM tbl_kecamatan
					INNER JOIN tbl_kabupaten ON (tbl_kabupaten.kodekabupaten = tbl_kecamatan.kodekabupaten)
					WHERE tbl_kecamatan.statuskecamatan = :statuskecamatan ";
	        $sth = $database->pdo->prepare($Sql);
	        $sth->bindValue(':statuskecamatan', 'Y', PDO::PARAM_STR);
	        $sth->execute();
	        while($row = $sth->fetch())
	        {
	        	$iRes[] = "<option value='$row[kodekecamatan]'>KAB. $row[namakabupaten] - KEC. $row[namakecamatan]</option>";
	        }
		}


        return join($iRes, "");
	}

	

	public function HakAkses()
	{
		if(empty($_SESSION['tipe']))
		{
			exit("<center>Maaf Akses DiBlokir</center>");
		}
	}

	public function StatusService($stat)
	{
		$iRes = "";

		if($stat == 0)
		{
			$iRes = "Pending";
		}
		elseif($stat == 1)
		{
			$iRes = "Diterima";
		}
		elseif($stat == 2)
		{
			$iRes = "Selesai";
		}
		elseif($stat == 3)
		{
			$iRes = "Ditolak";
		}
		else
		{
			$iRes = "Tidak Diketahui";
		}


		return $iRes;
	}

	public function GetJk($jk)
	{
		$iRes = "";
		if($jk == "P")
		{
			$iRes = "Perempuan";
		}
		elseif($jk == "L")
		{
			$iRes = "Laki - Laki";
		}

		return $iRes;
	}

	function CekProdukSession($kodeproduk, $username, $database)
	{
		$query = $database->pdo->prepare("SELECT COUNT(*) as jumlah FROM tbl_temp WHERE kodeproduk = :kodeproduk AND username = :username ");
		$query->bindValue(':kodeproduk', $kodeproduk, PDO::PARAM_STR);
		$query->bindValue(':username', $username, PDO::PARAM_STR);
		
		$query->execute();
		$row = $query->fetch();
		
		return $row['jumlah'];
	}

	function cekPesananBaru($database)
	{
		$query = $database->pdo->prepare("SELECT COUNT(*) as jumlah FROM tbl_transaksi WHERE baru = :baru ");
		$query->bindValue(':baru', 'Y', PDO::PARAM_STR);
		
		$query->execute();
		$row = $query->fetch();
		
		return $row['jumlah'];
	}
}

?>
