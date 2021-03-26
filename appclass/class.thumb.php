<?php

function UploadData($fupload, $vdir_upload, $namalama)
{
	// Data upload
	$fupload_lokasi  = $_FILES[$fupload]["tmp_name"];
	$fupload_name    = $_FILES[$fupload]["name"];
	$fupload_tipe    = $_FILES[$fupload]["type"];
	
	if(empty($fupload_lokasi))
	{
		return $namalama;
	}
	else
	{
		// unlink($vdir_upload.$namalama);
	}
	
	$nama_file = "";
	
	//Simpan gambar dalam ukuran sebenarnya
	if(!empty($fupload_name))
	{
		$nama_file = uniqid("pict_", false).$fupload_name;
		$vfile_upload = $vdir_upload.$nama_file;
		move_uploaded_file($fupload_lokasi,$vfile_upload);
	}
	else
	{
		$nama_file = $namalama;
	}
	
	return $nama_file;
}

function UploadVideo($fupload, $nama_file, $vdir_upload)
{	
	// Data upload
	$fupload_lokasi  = $_FILES[$fupload]["tmp_name"];
	$fupload_name    = $_FILES[$fupload]["name"];
	$fupload_tipe    = $_FILES[$fupload]["type"];
	
	if(empty($fupload_name))
	{
		return;
	}
	
	//direktori gambar
	$vfile_upload = $vdir_upload.$nama_file;
	
	//Simpan gambar dalam ukuran sebenarnya
	move_uploaded_file($fupload_lokasi,$vfile_upload);
}

function UploadImage($fupload, $nama_file, $vdir_upload)
{	
	// Data upload
	$fupload_lokasi  = $_FILES[$fupload]["tmp_name"];
	$fupload_name    = $_FILES[$fupload]["name"];
	$fupload_tipe    = $_FILES[$fupload]["type"];
	
	if(empty($fupload_name))
	{
		return;
	}
	
	//direktori gambar
	$vfile_upload = $vdir_upload.$nama_file;
	
	//Simpan gambar dalam ukuran sebenarnya
	move_uploaded_file($fupload_lokasi,$vfile_upload);
	
	//identitas file asli
	if($fupload_tipe == "image/jpeg")
	{
	  $im_src = imagecreatefromjpeg($vfile_upload);
	}
	else
	{
	  $im_src = imagecreatefrompng($vfile_upload);
	}
	$src_width = imageSX($im_src);
	$src_height = imageSY($im_src);
	
	//Hapus gambar di memori komputer
	imagedestroy($im_src);
}

function UploadFiles($fupload, $location, $nama){
	
	$fupload_lokasi  = $_FILES[$fupload]["tmp_name"];
	$fupload_name    = $_FILES[$fupload]["name"];
	$fupload_tipe    = $_FILES[$fupload]["type"];
	
	$vdir_upload  = $location;
	$vfile_upload = $vdir_upload.$nama;
	
	move_uploaded_file($fupload_lokasi, $vfile_upload);
}

function CekData($filetipe, $filename)
{
	//$ext = pathinfo($filename, PATHINFO_EXTENSION);
	$allowed = array('application/octet-stream','application/pdf' ,'application/zip', 'image/jpeg');
	if(!in_array($filetipe,$allowed) )
	{
		exit("Penyimpanan Dibatalkan Filename : <b>$filename</b> tidak sesuai format <br />");
	}
}

function UploadFileAuto($fupload, $fupload_name, $type, $vdir_upload){
	//direktori file
	
	if($type == "application/octet-stream")
	{
		$vfile_upload = $vdir_upload."document/".$fupload_name;
		move_uploaded_file($_FILES[$fupload]["tmp_name"], $vfile_upload);
	}
	
	elseif($type == "application/pdf")
	{
		$vfile_upload = $vdir_upload."document/".$fupload_name;
		move_uploaded_file($_FILES[$fupload]["tmp_name"], $vfile_upload);
	}
	
	elseif($type == "application/zip")
	{
		$vfile_upload = $vdir_upload."compressed/".$fupload_name;
		move_uploaded_file($_FILES[$fupload]["tmp_name"], $vfile_upload);
	}
	
	elseif($type == "image/jpeg")
	{
		$vfile_upload = $vdir_upload."gambar/".$fupload_name;
		move_uploaded_file($_FILES[$fupload]["tmp_name"], $vfile_upload);
		$im_src = imagecreatefromjpeg($vfile_upload);
	
		$src_width = imageSX($im_src);
		$src_height = imageSY($im_src);
		
		$dst_width = 600;
		$dst_height = ($dst_width/$src_width)*$src_height;
		
		$im = imagecreatetruecolor($dst_width,$dst_height);
		
		imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
		imagejpeg($im, $vdir_upload."gambar/".$fupload_name);
	}
	else
	{
		echo "Tipe data yang anda upload tidak sesuai format";
		exit();
	}
	//Simpan gambar dalam ukuran sebenarnya
}

function CekLocation($tipe)
{
	if($tipe == "image/jpeg")
	{
		$location = "http://".$_SERVER['HTTP_HOST']."/billing/data/upload/gambar/";
	}
	
	elseif($tipe == "application/zip")
	{
		$location = "http://".$_SERVER['HTTP_HOST']."/billing/data/upload/compressed/";
	}
	
	elseif($tipe == "application/pdf" || $tipe == "application/octet-stream")
	{
		$location = "http://".$_SERVER['HTTP_HOST']."/billing/data/upload/document/";
	}
	
	else
	{
		$location = "";
	}
	
	return $location;
}

function DownloadFile($tipe, $nama)
{
	if($tipe == "image/jpeg")
	{
		$location = "http://".$_SERVER['HTTP_HOST']."/billing/data/upload/gambar/";
	}
	
	elseif($tipe == "application/zip")
	{
		$location = "http://".$_SERVER['HTTP_HOST']."/billing/data/upload/compressed/";
	}
	
	elseif($tipe == "application/pdf" || $tipe == "application/octet-stream")
	{
		$location = "http://".$_SERVER['HTTP_HOST']."/billing/data/upload/document/";
	}
	
	else
	{
		exit("Problem Download");
	}
}

?>
