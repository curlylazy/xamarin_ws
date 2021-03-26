<?php 

namespace appclas;

class Security
{
	public static $seckey = '';
	public static $cipher = '';
	public static $key = ''; 
	public static $ciphering = ''; 

	public static $SESS_CIPHER = 'aes-128-cbc';

	public function __construct()
	{
		self::$key = '2938123jkd(&*^#@20321jdasYWTTW11';
		self::$cipher = 'AES-128-CTR';
		self::$ciphering = 'BF-CBC';
	}

	private function safe_b64encode($string) 
	{
		$data = base64_encode($string);
		$data = str_replace(array('+','/','='),array('-','_',''),$data);
		return $data;
	}
 
	private static function safe_b64decode($string) 
	{
		$data = str_replace(array('-','_'),array('+','/'),$string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}
		
	// public static function encrypt($plaintext)
	// {
	// 	// Store cipher method 
	// 	$ciphering = "BF-CBC"; 
	// 	$iv_length = openssl_cipher_iv_length($ciphering); 
	// 	$options = 0;
	// 	$encryption_iv = random_bytes($iv_length); 
	// 	$encryption_key = openssl_digest(php_uname(), 'MD5', TRUE); 
	//     $res = openssl_encrypt($plaintext, $ciphering, $encryption_key, $options, $encryption_iv); 

	// 	return $res;
	// }

	// public static function decrypt($text)
	// {
	// 	$key = self::$key;   
	// 	//$text = base64_decode($text); 
					
	// 	$text = self::safe_b64decode($text); 

	// 	$IV = substr($text, strrpos($text, "-[--IV-[-") + 9);
	// 	$text = str_replace("-[--IV-[-".$IV, "", $text);
		
	// 	$result = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_CBC, $IV);

	// 	return rtrim($result, "\x00..\x1F");
	// }	


	// new mode in php 7.2+
	public static function encrypt($payload) {
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
		$encrypted = openssl_encrypt($payload, 'aes-256-cbc', self::$key, 0, $iv);
		return base64_encode($encrypted . '::' . $iv);
	}

	public static function decrypt($garble) {
	    list($encrypted_data, $iv) = explode('::', base64_decode($garble), 2);
	    return openssl_decrypt($encrypted_data, 'aes-256-cbc', self::$key, 0, $iv);
	}

}

?>