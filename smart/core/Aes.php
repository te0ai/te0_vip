<?php

class Aes{

	public static function enc($str,$pad='zero'){

		//AESkeyは必須
		if(!defined('_AES_KEY_')) exit('_AES_KEY_ is not defined!');

		if( defined('_CRYPT_TYPE_') && _CRYPT_TYPE_ === 'openssl'){

			//openssl

			// Set a random salt
	    $salt = openssl_random_pseudo_bytes(16);

	    $salted = '';
	    $dx = '';
	    // Salt the key(32) and iv(16) = 48
	    while (strlen($salted) < 48) {
	      $dx = hash('sha256', $dx._AES_KEY_.$salt, true);
	      $salted .= $dx;
	    }

	    $key = substr($salted, 0, 32);
	    $iv  = substr($salted, 32,16);

	    $encrypted_data = openssl_encrypt($str, 'AES-256-CBC', $key, 0, $iv);

			$ciphertext_base64 = base64_encode($salt . $encrypted_data);

			//URLにも埋め込めるように変換
			$ciphertext_base64 = str_replace(array('/','+','='),array('-','_','!'),$ciphertext_base64);

		}else{

			//use mcrypt <- php7.1 greater than or equal to error
			$key = pack('H*',_AES_KEY_);

			//初期化ベクター制作
			$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

			//暗号化
			$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,$str, MCRYPT_MODE_CBC, $iv);

			//初期化ベクターと暗号化文字列を合体
			$ciphertext = $iv . $ciphertext;

			//ベース64エンコード
			$ciphertext_base64 = base64_encode($ciphertext);

			//URLにも埋め込めるように変換
			$ciphertext_base64 = str_replace(array('/','+','='),array('-','_','!'),$ciphertext_base64);

		}

		return $ciphertext_base64;

	}

	public static function dec($str,$pad='zero'){

		//AESkeyは必須
		if(!defined('_AES_KEY_')) exit('_AES_KEY_ is not defined!');

		if( defined('_CRYPT_TYPE_') && _CRYPT_TYPE_ === 'openssl'){

			//openssl
			$ciphertext_dec = str_replace(array('-','_','!'),array('/','+','='),$str);

			$data = base64_decode($ciphertext_dec);
	    $salt = substr($data, 0, 16);
	    $ct = substr($data, 16);

	    $rounds = 3; // depends on key length
	    $data00 = _AES_KEY_.$salt;
	    $hash = array();
	    $hash[0] = hash('sha256', $data00, true);
	    $result = $hash[0];
	    for ($i = 1; $i < $rounds; $i++) {
	        $hash[$i] = hash('sha256', $hash[$i - 1].$data00, true);
	        $result .= $hash[$i];
	    }
	    $key = substr($result, 0, 32);
	    $iv  = substr($result, 32,16);

	    $plaintext_dec = openssl_decrypt($ct, 'AES-256-CBC', $key, 0, $iv);

		}else{

			//use mcrypt <- php7.1 greater than or equal to error
			$key = pack('H*',_AES_KEY_);

			$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

			$ciphertext_dec = str_replace(array('-','_','!'),array('/','+','='),$str);

			$ciphertext_dec = base64_decode($ciphertext_dec);

			$iv_dec = substr($ciphertext_dec, 0, $iv_size);

			$ciphertext_dec = substr($ciphertext_dec, $iv_size);

			$plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,$ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

			if($pad === 'zero'){

				//「ZeroBytePadding」なのでヌルパディングを削除
				$plaintext_dec = rtrim($plaintext_dec,"\0");

			}else if($pad === 'pkcs5'){

				//「PKCS#5 Padding」なのでヌルパディングを削除
				$plaintext_dec = self::pkcs5_unpad($plaintext_dec);

			}

		}

		return $plaintext_dec;

	}

	//パディング方式が「PKCS#5 Padding」方式の場合
	private function pkcs5_pad ($text, $blocksize){

		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);

	}

	private function pkcs5_unpad($text){

		$pad = ord($text(strlen($text)-1));
		if ($pad > strlen($text)) return false;
		if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
		return substr($text, 0, -1 * $pad);

	}



}

?>
