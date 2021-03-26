<?php

namespace appclas;

class Cupload
{
	
	public static function UploadData($fupload, $vdir_upload, $namalama)
	{
		// Data upload
		$fupload_lokasi  = $_FILES[$fupload]["tmp_name"];
		$fupload_name    = $_FILES[$fupload]["name"];
		$fupload_tipe    = $_FILES[$fupload]["type"];
		
		if(empty($fupload_lokasi))
		{
			return $namalama;
		}
		
		$nama_file = "";
		
		//Simpan gambar dalam ukuran sebenarnya
		if(!empty($fupload_name))
		{
			// hapus yang lama
			if(!empty($namalama))
			{
				$file_old = $vdir_upload.$namalama;
				if(file_exists($file_old))
				{
					unlink($file_old);
				}
			}

			$nama_file = uniqid("pict_", false).str_replace(" ", "", $fupload_name);
			$vfile_upload = $vdir_upload.$nama_file;
			move_uploaded_file($fupload_lokasi,$vfile_upload);
		}
		else
		{
			$nama_file = $namalama;
		}
		
		return $nama_file;
	}

	public static function RemoveFile($vdir_upload, $namalama)
	{
		// hapus yang lama
		if(!empty($namalama))
		{
			$file_old = $vdir_upload.$namalama;
			if(file_exists($file_old))
			{
				unlink($file_old);
			}
		}
	}

}
