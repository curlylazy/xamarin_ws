<?php

namespace appclas;

class StringFunction 
{
	public static function SetInt($tipe)
	{
		$res = filter_var(str_replace(",", "", $tipe), FILTER_SANITIZE_NUMBER_INT);
		return $res;
	}

	public static function SetBool($tipe)
	{
		$tipefile = settype($tipe, "bool");
		return $tipefile;
	}

	public static function SetFloat($tipe)
	{
		$res = filter_var($tipe, FILTER_SANITIZE_NUMBER_FLOAT);
		return $res;
	}

	public static function SetString($tipe)
	{
		$res = trim(filter_var($tipe, FILTER_SANITIZE_STRING));
		return $res;
	}

	public static function FormatNum($num)
	{
		return number_format($num,0,",",".");
	}

	public static function SetSEO($judul, $kode)
    {
        // megubah karakter non huruf dengan strip
        $judul = preg_replace('~[^\\pL\d]+~u', '-', $judul);
        $judul = trim($judul, '-');
        $judul = iconv('utf-8', 'us-ascii//TRANSLIT', $judul);
        $judul = strtolower($judul);
        $judul = preg_replace('~[^-\w]+~', '', $judul);
        if (empty($judul))
        {
            return 'n-a';
        }
        
        return strtolower($judul."-".$kode);
    }
}




?>
