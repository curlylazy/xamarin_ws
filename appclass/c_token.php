<?php

namespace appclas;

class Ctoken
{
	public static function createToken($kodeuser, $token)
    {
        DB::table('tbl_token')->where('kodeuser', $kodeuser)->delete();

        DB::table("tbl_token")->insert([[
            'kodeuser' => Cfilter::FilterString($kodeuser),
            'token' => $token,
        ]]);
    }

    public static function deleteToken($kodeuser)
    {
        DB::table('tbl_token')->where('kodeuser', $kodeuser)->delete();
    }

    public static function cekToken($token)
    {
        $row = DB::table("tbl_token")
                ->select("*")
                ->where('token', '=', $token)
                ->first();

        if(empty($row->token))
        {
        	$iRes = false;
        	return $iRes;
        }

		$iRes = true;
    	return $iRes;

    }
}
