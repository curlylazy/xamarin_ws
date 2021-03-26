<?php
class FungsiSql{

	private $str = "";

	public function __construct()
	{

	}

	function SetFlashMsg($pesan, $redirect)
	{
		$_SESSION['pesanflash'] = $pesan;

		if(!empty($redirect))
		{
			echo "<script language='JavaScript'>document.location='$redirect'</script>";
		}
	}

	function ShowFlashMsg()
	{
		$pesan =
		"
		<div class='alert alert-dismissible alert-success'>
			<button type='button' class='close' data-dismiss='alert'>&times;</button>
			$_SESSION[pesanflash]
		</div>
		";

		if(empty($_SESSION['pesanflash']))
		{
			$pesan = "";
		}

		unset($_SESSION['pesanflash']);

		return $pesan;
	}

	function SetPesan($page, $act)
	{
		if($act == "tambah")
		{
			$pesan = "Tambah $page berhasil.";
		}
		elseif($act == "update")
		{
			$pesan = "Update $page berhasil.";
		}
		elseif($act == "verifikasi")
		{
			$pesan = "Verifikasi $page berhasil.";
		}
		else
		{
			$pesan = "Hapus $page berhasil.";
		}

		echo "<script language='JavaScript'>alert('$pesan');document.location='../../index.php?page=$page'</script>";
	}

	function Pesan($pesan)
	{
		echo "<script language='JavaScript'>alert('$pesan');window.history.back(-1)</script>";
	}

	function SetPesanCustom($pesan, $link)
	{
		echo "<script language='JavaScript'>alert('$pesan');document.location='$link'</script>";
	}

	function GetKode($kode, $param ,$table, $database)
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

	public function CariData($cari, $column, $value, $tabel, $database)
	{
		$query = $database->pdo->prepare("SELECT * FROM $tabel WHERE $column = :value");
		$query->bindValue(':value', $value, PDO::PARAM_STR);

		$query->execute();
		$row = $query->fetch();

		return $row[$cari];
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

	public function CekData($value, $column ,$tabel, $database)
	{
		$query = $database->pdo->prepare("SELECT COUNT($column) as jumlah FROM $tabel WHERE $column = :value");
		$query->bindValue(':value', $value, PDO::PARAM_STR);

		$query->execute();
		$row = $query->fetch();
		$jumlah = $row['jumlah'];

		return $jumlah;
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

	public function JumlahAnggotaKursus($kodekelas, $database)
	{
		$Sql = "SELECT COUNT(*) as jumlah FROM tbl_kursus
				WHERE kodekelas = :kodekelas";
		$query = $database->pdo->prepare($Sql);
		$query->bindValue(':kodekelas', $kodekelas, PDO::PARAM_STR);
		$query->execute();
		$row = $query->fetch();

		return $row['jumlah'];
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
