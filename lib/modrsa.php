<?php
/*RSA对象*/
class Modrsa
{
	private $public='';
	private $private='';
	private $type=0;


	public function __construct() {

	}

   function __destruct() {
      openssl_free_key($this->public);
      openssl_free_key($this->private);
   }

   //设置字节集类型
   	/*
	0.字节集
	1.十六进制
	2.base64
	*/
   function setcharset($type){
   	switch ($type) {
   		case 0:
   			$this->type=0;
   			break;
   		case 1:
   			$this->type=1;
   			break;
   		case 2:
   			$this->type=2;
   			break;
   		case '0':
   			$this->type=0;
   			break;
   		case '1':
   			$this->type=1;
   			break;
   		case '2':
   			$this->type=2;
   			break;
   		case 'raw':
   			$this->type=0;
   			break;
   		case 'hex':
   			$this->type=1;
   			break;
   		case 'base64':
   			$this->type=2;
   			break;			
   		default:
   			$this->type=0;
   			break;
   	}

   }

	//初始化公钥或者公钥证书地址
	public function setup_public($public){		
		$this->public= file_exists($public) ? openssl_pkey_get_public(file_get_contents($public)) : $public;
		if($this->public){
			return true;
		}
		return false;
	}

	//初始化私钥或者私钥证书地址
	public function setup_private($private){
		$this->private=file_exists($private) ? openssl_pkey_get_private(file_get_contents($private)) : $private;
		if($this->private){
			return true;
		}
		return false;
	}
	//初始化公钥或者公钥证书地址
	public function setup_public_file($public){		
		$this->public= file_exists($public) ? openssl_pkey_get_public(file_get_contents($public)) : $public;
		if($this->public){
			return true;
		}
		return false;
	}

	//初始化私钥或者私钥证书地址
	public function setup_private_file($private){
		$this->private=file_exists($private) ? openssl_pkey_get_private(file_get_contents($private)) : $private;
		if($this->private){
			return true;
		}
		return false;
	}

	//公钥加密
	public function public_encrypt($data){
		$temp='';
		openssl_public_encrypt($data, $temp, $this->public);
		switch ($this->type) {
			case 1:
				return bin2hex($temp);
				break;
			case 2:
				return base64_encode($temp);
				break;			
		}
		
		return $temp;
	}

	//公钥解密
	public function public_decrypt($data){
		$temp='';
		switch ($this->type) {
			case 1:
				$data = hex2bin($data);
				break;
			case 2:
				$data = base64_decode($data);
				break;			
		}
		openssl_public_decrypt($data, $temp, $this->public);
		return $temp;
	}

	//私钥加密
	public function private_encrypt($data){
		$temp='';
		openssl_private_encrypt($data, $temp, $this->private);
		switch ($this->type) {
			case 1:
				return bin2hex($temp);
				break;
			case 2:
				return base64_encode($temp);
				break;			
		}
		return $temp;
	}
	
	//私钥解密
	public function private_decrypt($data){
		$temp='';
		switch ($this->type) {
			case 1:
				$data = hex2bin($data);
				break;
			case 2:
				$data = base64_decode($data);
				break;			
		}
		openssl_private_decrypt($data, $temp, $this->private);
		return $temp;
	}

	//签名
	public function sign($data){
		$temp='';
		openssl_sign($data, $temp, $this->private);
		switch ($this->type) {
			case 1:
				return bin2hex($temp);
				break;
			case 2:
				return base64_encode($temp);
				break;			
		}
		return $temp;
	}

	//验签
	public function verify($data,$sign){
		switch ($this->type) {
			case 1:
				$sign = hex2bin($sign);
				break;
			case 2:
				$sign = base64_decode($sign);
				break;			
		}
		return openssl_verify($data, $sign, $this->public) ? true : false;	
	}

}
?>