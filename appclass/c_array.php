<?php

namespace appclas;

class Carray
{
	public static $retdata;

	public function __construct()
    {
		$this->retdata[] = "";
    }

	public static function setObject($name, $value)
	{
		self::$retdata[$name] = $value;
	}

	public static function setSuccess($status, $pesan = null)
	{
		self::$retdata['status'] = $status;
		self::$retdata['pesan'] = $pesan;
	}

	public static function getObject()
	{
		$retdata = self::$retdata;
		self::$retdata[] = "";
		return json_encode($retdata);
	}

}
